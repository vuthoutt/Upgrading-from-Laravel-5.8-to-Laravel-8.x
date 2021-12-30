<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AdminToolRollbackRepository;
use App\Repositories\SurveyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\DocumentRepository;
use App\Http\Request\ToolBox\RemoveRequest;
use App\Models\Zone;
use App\Models\AdminTool;
use App\Models\AdminToolRollback;
use App\Models\Property;
use App\Models\Area;
use App\Models\Location;
use App\Models\Item;
use Exception;

class ToolBoxLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $route;

    public function __construct(AdminToolRollbackRepository $adminToolRollbackRepository, SurveyRepository $surveyRepository, ProjectRepository $projectRepository,DocumentRepository $documentRepository)
    {
        $this->adminToolRollbackRepository = $adminToolRollbackRepository;
        $this->surveyRepository = $surveyRepository;
        $this->projectRepository = $projectRepository;
        $this->documentRepository = $documentRepository;
        $this->route = (object)[];
        $this->route->name  = \Route::currentRouteName();
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index()
    {
        $removes = AdminTool::with('user')->whereRaw("action LIKE 'remove%'")->where('roll_back',0)->get();
        $removes_back = AdminTool::with('user')->whereRaw("action LIKE 'remove%'")->where('roll_back',1)->get();

        $moves = AdminTool::with('user')->whereRaw("action LIKE 'move%'")->where('roll_back',0)->get();
        $moves_back = AdminTool::with('user')->whereRaw("action LIKE 'move%'")->where('roll_back',1)->get();

        $merges = AdminTool::with('user')->whereRaw("action LIKE 'merge%'")->where('roll_back',0)->get();
        $merges_back = AdminTool::with('user')->whereRaw("action LIKE 'merge%'")->where('roll_back',1)->get();

        $unlocks = AdminTool::with('user')->whereRaw("action LIKE 'unlock%'")->where('roll_back',0)->get();
        $unlocks_back = AdminTool::with('user')->whereRaw("action LIKE 'unlock%'")->where('roll_back',1)->get();

        $reverts = AdminTool::with('user')->whereRaw("action LIKE 'revert%'")->where('roll_back',0)->get();
        $reverts_back = AdminTool::with('user')->whereRaw("action LIKE 'revert%'")->where('roll_back',1)->get();

        return view('admin_tool_log.index',[
            'removes' => $removes,
            'removes_back' => $removes_back,
            'moves' => $moves,
            'moves_back' => $moves_back,
            'merges' => $merges,
            'merges_back' => $merges_back,
            'unlocks' => $unlocks,
            'unlocks_back' => $unlocks_back,
            'reverts' => $reverts,
            'reverts_back' => $reverts_back
        ]);
    }

    public function revertBackAction(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=> $validator->errors()]);
        }

        $data_rollback = AdminToolRollback::where('admin_tool_id',$request->id)->first();
        $admin_tool = AdminTool::find($request->id);
        if (is_null($data_rollback)) {
            return response()->json(['errors'=> 'backup does not exist']);
        }

        $data = json_decode($data_rollback->data);
        //convert object to array
        $data =  json_decode(json_encode($data), true);

        switch ($request->type) {
            case 'removeGroup':
                $rollback = $this->adminToolRollbackRepository->revertRemoveGroup($data,$request->id);
                break;

            case 'moveSurvey':
                $rollback = $this->adminToolRollbackRepository->revertMoveSurvey($data,$request->id);
                break;

            case 'moveProject':
                $rollback = $this->adminToolRollbackRepository->revertMoveProject($data,$request->id);
                break;

            case 'moveLocation':
                $rollback = $this->adminToolRollbackRepository->revertMoveLocation($data,$request->id);
                break;

            case 'moveItem':
                $rollback = $this->adminToolRollbackRepository->revertMoveItem($data,$request->id);
                break;

            case 'mergeSurvey':
                $rollback = $this->adminToolRollbackRepository->revertMergeSurvey($data,$request->id);
                break;

            case 'mergeRoom':
                $rollback = $this->adminToolRollbackRepository->revertMergeRoom($data,$request->id);
                break;

            case 'mergeArea':
                $rollback = $this->adminToolRollbackRepository->revertMergeArea($data,$request->id);
                break;

            case 'removeGroup':
                $rollback = $this->adminToolRollbackRepository->revertRemoveGroup($data,$request->id);
                break;

            case 'removeProperty':
                $rollback = $this->adminToolRollbackRepository->revertRemoveProperty($data,$request->id);
                break;

            case 'removeSurvey':
                $rollback = $this->adminToolRollbackRepository->revertRemoveSurvey($data,$request->id);
                break;

            case 'removeRegisterItem':
                $rollback = $this->adminToolRollbackRepository->revertRemoveItem($data,$request->id);
                break;

            case 'removeRegisterLocation':
                $rollback = $this->adminToolRollbackRepository->revertRemoveRegisterLocation($data,$request->id);
                break;

            case 'removeRegisterArea':
                $rollback = $this->adminToolRollbackRepository->revertRemoveRegisterArea($data,$request->id);
                break;

            case 'removeProject':
                $rollback = $this->adminToolRollbackRepository->revertRemoveProject($data,$request->id);
                break;

            case 'remove_property_plan':
                $rollback = $this->adminToolRollbackRepository->revertSitePlanDocument($data,$request->id);
                break;

            case 'remove_property_historical':
                $rollback = $this->adminToolRollbackRepository->revertRemoveHistoricalDocument($data,$request->id);
                break;

            case 'remove_survey_ac':
                $rollback = $this->adminToolRollbackRepository->revertRemoveAirtestCer($data,$request->id);
                break;

            case 'remove_survey_sc':
                $rollback = $this->adminToolRollbackRepository->revertRemoveSampleCer($data,$request->id);
                break;

            case 'remove_survey_plan':
                $rollback = $this->adminToolRollbackRepository->revertSitePlanDocument($data,$request->id);
                break;

            case 'remove_tender_doc':
                $rollback = $this->adminToolRollbackRepository->revertRemoveProjectDocument($data,$request->id);
                break;

            case 'remove_contractor_doc':
                $rollback = $this->adminToolRollbackRepository->revertRemoveProjectDocument($data,$request->id);
                break;

            case 'remove_gsk_doc':
            case 'remove_preconstruction_doc':
            case 'remove_design_doc':
            case 'remove_commercial_doc':
            case 'remove_planning_doc':
            case 'remove_prestart_doc':
            case 'remove_site_rec_doc':
            case 'remove_completion_doc':
                $rollback = $this->adminToolRollbackRepository->revertRemoveProjectDocument($data,$request->id);
                break;

            case 'remove_incident_doc':
                $rollback = $this->adminToolRollbackRepository->revertIncidentDocument($data,$request->id);
                break;

            default:
                # code...
                break;
        }
        $comment_audit = \Auth::user()->full_name  . " Rollback Action : " .  $admin_tool->description  ?? '';
        \CommonHelpers::logAudit(ADMIN_TOOL_TYPE,$request->id, AUDIT_ACTION_ROLLBACK, $request->type ?? '', 0 ,$comment_audit, 0 ,0);
        if (isset($rollback) and !is_null($rollback)) {
            \Session::flash('msg', $rollback['msg']);
            return response()->json(['status_code' => $rollback['status_code'], 'success'=> $rollback['msg']]);
        } else {

        }
    }

}
