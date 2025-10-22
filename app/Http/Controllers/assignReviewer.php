<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Protocol;
use App\Models\InitialReview;
use App\Models\FormsTable;
use App\Models\EvaluatedReviews;
use Illuminate\Validation\Rule;
use App\Notifications\NewProtocolAssigned;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Notifications\ResearchUnderReview;

class assignReviewer extends Controller
{
    public function index()
    {
        $erbReviewer = User::where('user_Access','ERB Reviewer')
            ->with('reviewerInformation')
            ->get();

        $piWithForms = User::with([
            'researchInformation', 
            'forms',
            'assignReviewer'
        ])
        ->where('user_Access', 'Principal Investigator')
        ->whereHas('forms')
        ->get()
        ->sortByDesc(function($user) {
            // Get the latest assignment date, or use a very old date if not assigned
            $latestAssignment = $user->assignReviewer->sortByDesc('created_at')->first();
            return $latestAssignment ? $latestAssignment->created_at : '1900-01-01';
        })
        ->values();
        
        $forms = FormsTable::whereIn('form_code', [
            'Form 2(E)',
            'Form 2(J)',
            'Form 2(E) Soft Copy',
            'Form 2(J) Soft Copy'
        ])->get();
        
        return view('erb.assign-reviewer', compact('piWithForms','erbReviewer','forms'));
    }

    public function ERBstore(Request $request)
    {
        // ✅ Validate request
        $request->validate([
            'pis' => 'required|array',
            'review_type' => 'required|string',
            'reviewer1_ID' => 'required|string',
            'reviewer2_ID' => 'required|string',
            'assigned_forms' => [
                'array',
                Rule::requiredIf(function () use ($request) {
                    return ($request->reviewer1_ID !== 'N/A' || $request->reviewer2_ID !== 'N/A') 
                        && $request->review_type !== 'Exempted';
                }),
            ],
        ]);

        $protocolCodes = [];
        $isExempted = $request->review_type === 'Exempted';

        foreach ($request->pis as $piID) {
            // 🔹 Generate Incremental Protocol Code
            $year = date('Y');
            $latestProtocol = Protocol::where('protocol_ID', 'like', "ERB-$year-%")
                ->orderBy('protocol_ID', 'desc')
                ->first();

            $nextNumber = $latestProtocol
                ? intval(substr($latestProtocol->protocol_ID, strrpos($latestProtocol->protocol_ID, '-') + 1)) + 1
                : 1;

            $protocolCode = sprintf("ERB-%s-%03d", $year, $nextNumber);
            $protocolCodes[] = $protocolCode;

            // 🔹 Save to tbl_protocol
            $protocol = Protocol::create([
                'protocol_ID' => $protocolCode,
                'user_ID' => $piID,
                'review_type' => $request->review_type,
            ]);

            // 🔹 Get PI (Student) details
            $piUser = User::find($piID);
            $piName = $piUser ? $piUser->user_Fname . ' ' . $piUser->user_Lname : 'Unknown';

            // 🔹 Determine valid reviewers
            $reviewers = [
                'reviewer1' => $request->reviewer1_ID !== 'N/A' ? $request->reviewer1_ID : null,
                'reviewer2' => $request->reviewer2_ID !== 'N/A' ? $request->reviewer2_ID : null,
            ];

            // 🔹 Assign forms to initial review if at least one reviewer is valid AND review type is not exempted
            if (($reviewers['reviewer1'] || $reviewers['reviewer2']) && !$isExempted) {
                foreach ($request->assigned_forms as $formID) {
                    InitialReview::create([
                        'protocol_ID' => $protocol->protocol_ID,
                        'user_ID' => $piID,
                        'reviewer1_ID' => $reviewers['reviewer1'],
                        'reviewer2_ID' => $reviewers['reviewer2'],
                        'form_ID' => $formID,
                    ]);
                }
            }

            // 🔹 Create evaluated reviews for each valid reviewer
            foreach ($reviewers as $key => $reviewerID) {
                if ($reviewerID) {
                    EvaluatedReviews::create([
                        'protocol_ID' => $protocol->protocol_ID,
                        'reviewer_ID' => null,
                        'status' => 'Pending',
                        'completed_at' => now(),
                    ]);

                    // 🔹 Notify Reviewer
                    $reviewer = User::find($reviewerID);
                    if ($reviewer) {
                        $reviewer->notify(new NewProtocolAssigned($protocolCode, $piName, $request->review_type));
                    }
                }
            }

            // 🔹 If both reviewers are "N/A", create a single evaluated review with null reviewer_ID
            if (!$reviewers['reviewer1'] && !$reviewers['reviewer2']) {
                EvaluatedReviews::create([
                    'protocol_ID' => $protocol->protocol_ID,
                    'reviewer_ID' => null,
                    'status' => 'Completed',
                    'completed_at' => now(),
                ]);
            }

            // 🔹 Notify Student (PI) that their research is under review
            if ($piUser) {
                $piUser->notify(new ResearchUnderReview($protocolCode, $request->review_type));
            }
        }

        // 🔹 If exempted, generate PDF directly instead of redirecting
        if ($isExempted) {
            $protocol = Protocol::with('user', 'user.researchInformation')->where('protocol_ID', $protocolCodes[0])->first();
            
            if (!$protocol) {
                return response()->json(['error' => 'Protocol not found'], 404);
            }

            $data = [
                'date' => now()->format('F d, Y'),
                'protocol' => $protocol,
                'pi' => $protocol->user,
                'research' => $protocol->user->researchInformation,
            ];

            return Pdf::view('erb.forms.form2iPdf', $data)
                ->format('Letter')
                ->margins(15, 15, 15, 15)
                ->download("Exempted_Certificate_{$protocolCodes[0]}.pdf");
        }

        return response()->json(['message' => 'Reviewers successfully assigned!']);
    }
}
