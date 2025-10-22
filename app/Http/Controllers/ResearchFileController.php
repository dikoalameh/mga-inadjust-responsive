<?php

namespace App\Http\Controllers;

use App\Models\ResearchInformation;
use Illuminate\Http\Request;
use App\Models\FormsTable;
use App\Models\ResearchFiles;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FileUploaded;
use App\Notifications\DocumentDeletedNotification;
use App\Mail\DocumentDeletedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ResearchFileController extends Controller
{
    public function showForm($formId)
    {
        $student = auth()->user();

        // find the form assigned to this student
        $form = $student->forms()
            ->where('tbl_forms.form_type', 'Submission')
            ->where('tbl_forms.form_id', $formId)
            ->firstOrFail();

        // check if the current student already submitted
        $submitted = ResearchFiles::where('user_ID', $student->user_ID)
            ->where('form_id', $formId)
            ->exists();

        return view('student.submit-form-layout', compact('form', 'submitted'));
    }

    public function storeSubmission(Request $request, $formId)
    {
        $request->validate([
            'uploadForms.*' => 'required|mimes:doc,docx,pdf|max:2048',
        ]);

        $user = auth()->user();
        $folderPath = "researchFolder/{$user->user_ID}";

        if ($request->hasFile('uploadForms')) {
            foreach ($request->file('uploadForms') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();

                // Save file into storage/app/public/researchFolder/{user_ID}
                $filePath = $file->storeAs($folderPath, $filename, 'public');

                // Insert record
                ResearchFiles::create([
                    'user_ID'      => $user->user_ID,
                    'form_id'      => $formId,
                    'file_name'    => $filename,
                    'file_path'    => $filePath,
                    'submitted_at' => now(),
                ]);

                // Send notification to all ERB Admins
                $adminUsers = User::where('user_Access', 'ERB Admin')->get();
                
                if ($adminUsers->isNotEmpty()) {
                    Notification::send($adminUsers, new FileUploaded($user, $formId, $filename));
                }
            }
        }

        return redirect()->back()->with('success', 'Files submitted successfully!');
    }

    public function submittedDocuments($userId)
    {
        $piFiles = User::with(['researchFiles' => function($query) {
            $query->where('status', 'active'); // Only show active files
        }])->findOrFail($userId);

        return view('erb.submitted-documents', compact('piFiles'));
    }

    // Add this method to handle soft deletion
    public function softDeleteResearchFile($fileId, Request $request)
    {
        try {
            $researchFile = ResearchFiles::findOrFail($fileId);
            $deleteReason = $request->input('delete_reason', 'No reason provided');
            
            // Update file status
            $researchFile->update(['status' => 'inactive']);
            
            $user = $researchFile->user;
            
            $notificationData = [
                'message' => 'Your document has been deleted by an administrator.',
                'document_name' => $researchFile->file_name,
                'form_name' => $researchFile->form?->form_name ?? 'Unknown Form',
                'deleted_at' => now()->format('Y-m-d H:i:s'),
                'delete_reason' => $deleteReason,
                'action_url' => '#',
                'type' => 'document_deleted'
            ];
            
            // Send email and notification if user exists AND has email
            if ($user && !empty($user->user_Email)) {
                Mail::to($user->user_Email)->queue(new DocumentDeletedMail($user, $notificationData));
                
                // Send system notification to the user
                $user->notify(new DocumentDeletedNotification($notificationData));
            }
            
            return redirect()->back()->with('success', 'Document deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Error deleting document: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting document');
        }
    }
    public function researchRecords()
    {
        $researchRecords = ResearchInformation::with([
            // Load the P.I. user and their related data
            'user' => function ($query) {
                $query->with([
                    // Load all submitted files
                    'researchFiles',
                    // Load all initial reviews and reviewers
                    'initialReviews' => function ($q) {
                        $q->with([
                            'protocol',        // Load protocol info
                            'reviewer1',       // Load reviewer 1 details
                            'reviewer2',       // Load reviewer 2 details
                        ]);
                    },
                    // Load approved decisions
                    'approved'
                ]);
            },
        ])->get();

        return view('erb.research-records', compact('researchRecords'));
    }
}
