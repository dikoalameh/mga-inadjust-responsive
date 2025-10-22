<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Amendments;
use App\Models\Approved;
use App\Models\FormUser;
use App\Models\FormsTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AmendmentsERB extends Controller
{
    public function assignedAmendments()
    {
        // Get only approved protocols with their related data
        $approvedProtocols = Approved::with([
            'user', 
            'protocol.researchInformation'
        ])
        ->where('Decision', 'Approved')
        ->get();

        return view('erb.assign-amendments', compact('approvedProtocols'));
    }

    public function assignAmendments(Request $request)
    {
        try {
            $selectedProtocols = $request->input('protocols', []);

            Log::info('Assign amendments request:', $selectedProtocols);

            if (empty($selectedProtocols)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No protocols selected for assignment.'
                ], 400);
            }

            DB::transaction(function () use ($selectedProtocols) {
                // Get form IDs by form_code only
                $forms = FormsTable::whereIn('form_code', ['FORM 3(D)', 'FORM 3(E)'])
                    ->pluck('form_id', 'form_code')
                    ->toArray();

                Log::info('Found forms:', $forms);

                if (count($forms) !== 2) {
                    throw new \Exception('Form 3d or Form 3e not found in the system. Please check if forms with codes "3d" and "3e" exist in tbl_forms.');
                }

                $userIds = array_column($selectedProtocols, 'user_id');
                $protocolIds = array_column($selectedProtocols, 'protocol_id');
                
                // Get existing amendments in bulk
                $existingAmendments = Amendments::whereIn('user_ID', $userIds)
                    ->whereIn('Protocol_ID', $protocolIds)
                    ->get()
                    ->keyBy(function ($item) {
                        return $item->user_ID . '|' . $item->Protocol_ID;
                    })
                    ->toArray();

                Log::info('Existing amendments count:', [count($existingAmendments)]);

                // Get existing form assignments in bulk
                $existingForms = FormUser::whereIn('user_ID', $userIds)
                    ->whereIn('form_id', array_values($forms))
                    ->get()
                    ->groupBy('user_ID')
                    ->map(function ($userForms) {
                        return $userForms->pluck('form_id')->toArray();
                    })
                    ->toArray();

                Log::info('Existing forms count:', [count($existingForms)]);

                $amendmentsToCreate = [];
                $formsToAssign = [];

                foreach ($selectedProtocols as $protocol) {
                    $key = $protocol['user_id'] . '|' . $protocol['protocol_id'];
                    
                    // Check if amendment doesn't exist
                    if (!isset($existingAmendments[$key])) {
                        $amendmentsToCreate[] = [
                            'user_ID' => $protocol['user_id'],
                            'Protocol_ID' => $protocol['protocol_id'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }

                    // Check and prepare form assignments
                    $userExistingForms = $existingForms[$protocol['user_id']] ?? [];
                    
                    foreach ($forms as $formId) {
                        if (!in_array($formId, $userExistingForms)) {
                            $formsToAssign[] = [
                                'user_ID' => $protocol['user_id'],
                                'form_id' => $formId,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                    }
                }

                Log::info('Amendments to create:', [count($amendmentsToCreate)]);
                Log::info('Forms to assign:', [count($formsToAssign)]);

                // Bulk insert amendments
                if (!empty($amendmentsToCreate)) {
                    Amendments::insert($amendmentsToCreate);
                    Log::info('Amendments inserted successfully');
                }

                // Bulk insert forms
                if (!empty($formsToAssign)) {
                    FormUser::insert($formsToAssign);
                    Log::info('Forms inserted successfully');
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Amendments assigned successfully! Forms 3d and 3e have been assigned to the selected users.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error assigning amendments: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error assigning amendments: ' . $e->getMessage()
            ], 500);
        }
    }
}