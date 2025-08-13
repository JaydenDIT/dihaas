<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\FamilyDetail;
use App\Models\Proforma;
use App\Services\DocumentRequirementService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CitizenFormFillUpController extends Controller
{
    //Step 2 - Complete Family Detail
    public function completeFamilyDetail($id)
    {
        try {
            DB::beginTransaction();
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
            $familyMembers = FamilyDetail::where('proforma_id', $proforma->proforma_id)->count();
            if ($familyMembers == 0) {
                return response()->json(['message' => 'Add at least one family member'], 422);
            }
            $data['form_fillup_step'] = 'step2-completed'; //default status
            $proforma->update($data);
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'Family Detail save draft-step2',
                'action_remark' => 'Step 2 completed',
            ]);
            DB::commit();
            return response()->json(['message' => 'Successfully save', 'proforma_id' => $proforma->proforma_id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Saving failed', 'error' => $e->getMessage()], 422);
        }
    }


    //step 3 completed

    public function completeUploadDocument($id)
    {
        try {
            DB::beginTransaction();
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
            $documents = DocumentRequirementService::getDocumentsWithRequirement($proforma);
            $requiredDocumentsLeft = $documents
                ->where('required', true)
                ->filter(function ($doc) {
                    return empty($doc->uploaded_file);
                })
                ->count();

            if ($requiredDocumentsLeft > 0) {
                return response()->json(['message' => 'Upload all required documents'], 422);
            }
            $data['form_fillup_step'] = 'step3-completed'; //default status
            $proforma->update($data);

            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'Proforma document save draft-step3',
                'action_remark' => 'Step 3 completed',
            ]);
            DB::commit();
            return response()->json(['message' => 'Successfully save', 'proforma_id' => $proforma->proforma_id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Saving failed', 'error' => $e->getMessage()], 422);
        }
    }

    //final form submission
    public function proformaFormSubmit($id)
    {
        try {
            DB::beginTransaction();
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
            $proforma->form_fillup_step = 'submitted';
            $proforma->save();
            WorkflowHandler::forwardApplication($proforma); //set the sequence to next 

            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => 1,
                'action_name' => 'forward',
                'action_remark' => 'Form Submit by Applicant ' . $proforma->applicant_name,
            ]);
            DB::commit();
            return response()->json(['message' => 'Proforma updated successfully', 'proforma_id' => $proforma->proforma_id], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Proforma update failed', 'error' => $e->getMessage()], 422);
        }
    }
}
