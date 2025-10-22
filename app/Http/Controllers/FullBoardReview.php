<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FullBoardModel;
use Illuminate\Support\Facades\DB;

class FullBoardReview extends Controller
{
    public function index()
    {
        $protocols = Protocol::where('review_type', 'Full Board')
        ->with([
            'user', 
            'researchInformation',
            'fullBoardAssignments.reviewer'
        ])
        ->get();

        // Fetch users with ERB Reviewers role
        $reviewers = User::where('user_Access', 'ERB Reviewer')
            ->get(['user_ID', 'user_Fname', 'user_Lname', 'user_MI']);

        return view('erb.full-board-review', compact('protocols', 'reviewers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'protocols' => 'required|array',
            'protocols.*' => 'exists:tbl_protocol,protocol_ID',
            'reviewers' => 'required|array',
            'reviewers.*' => 'exists:tbl_users,user_ID',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->protocols as $protocolId) {
                    foreach ($request->reviewers as $reviewerId) {
                        // Generate unique assignment ID
                        $assignmentId = 'FBA-' . uniqid();
                        
                        FullBoardModel::create([
                            'assignment_ID' => $assignmentId,
                            'protocol_ID' => $protocolId,
                            'reviewer_ID' => $reviewerId,
                            'assigned_by' => auth()->id(), // Currently logged in user
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Reviewers assigned successfully for full board review.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign reviewers: ' . $e->getMessage()
            ], 500);
        }
    }
}
