<?php

namespace App\Http\Controllers;

use App\Models\ChartType;
use App\Models\Zone;
use App\Repositories\ChartRepository;
use App\Repositories\WorkRequestRepository;
use App\Repositories\ZoneRepository;
use Illuminate\Http\Request;
use App\Repositories\ProjectRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\AuditTrailRepository;
use App\Repositories\SurveyRepository;
use App\Models\Decommission;
use App\Models\DecommissionReason;
use App\Models\Department;
use App\Models\DepartmentContractor;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     * @param ProjectRepository $projectRepository
     * @param DocumentRepository $documentRepository
     * @param AuditTrailRepository $auditTrailRepository
     * @param ChartRepository $chartRepository
     * @param SurveyRepository $surveyRepository
     * @param ZoneRepository $zoneRepository
     * @param WorkRequestRepository $workRequestRepository
     */
    public function __construct(ProjectRepository $projectRepository,
                                DocumentRepository $documentRepository,
                                AuditTrailRepository $auditTrailRepository,
                                ChartRepository $chartRepository,
                                SurveyRepository $surveyRepository,
                                ZoneRepository $zoneRepository,
                                WorkRequestRepository $workRequestRepository
                                )
    {
        $this->documentRepository = $documentRepository;
        $this->projectRepository = $projectRepository;
        $this->auditTrailRepository = $auditTrailRepository;
        $this->chartRepository = $chartRepository;
        $this->zoneRepository = $zoneRepository;
        $this->surveyRepository = $surveyRepository;
        $this->workRequestRepository = $workRequestRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(\Auth::user()->is_site_operative == 1){
            return redirect()->route('zone', ['client_id' => 1]);
        }
        // $recentSites = $this->auditTrailRepository->getRecentSites(\Auth::user()->id);
        $recentViews = $this->auditTrailRepository->getRecentView(\Auth::user()->id);

        $myProjects = $this->projectRepository->getMyProjects(\Auth::user()->id, \Auth::user()->client_id);

        $surveys = $this->surveyRepository->getMyApprovalSurvey();

        $documents = $this->documentRepository->getMyApprovalDocument();

        $works = $this->workRequestRepository->getMyWorkWaitingForApproval();

        $theProjectDocs = array_merge($surveys,$documents,$works);

        // $checked_audits = $this->auditTrailRepository->getHomeAuditTrail(\Auth::user()->id);

        //for chart
        $charts = ChartType::all();
        $groups = Zone::all();
        $current_year =  date("Y");
        $years = [];
        for($i = 2018; $i <= $current_year; $i++){
            $years[$i] = $i;
        }
        $years[-1] = 'Decommissioned to Date';
        return view('home.index', [
            'myProjects' => $myProjects,
            'theProjectDocs' => $theProjectDocs,
            // 'checked_audits' => $checked_audits,
            // 'recentSites' => $recentSites,
            'charts'=>$charts,
            'groups'=>$groups,
            'years'=> $years,
            'recentViews'=> $recentViews
        ]);
    }

    public function notAssessedDropdown(Request $request) {
        $type = $request->has('type') ? $request->type : '';
        $data = DecommissionReason::where('type', $type)->where('parent_id', NOT_ASSESSED)->get();
        return response()->json(['status_code' => 200, 'data' => $data]);
    }

    public function decommissionDropdown(Request $request) {
        $type = $request->has('type') ? $request->type : '';
        $data = DecommissionReason::where('type', $type)->where('parent_id', DECOMMISSION)->get();
        return response()->json(['status_code' => 200, 'data' => $data]);
    }

    public function recommissionDropdown(Request $request) {
        $type = $request->has('type') ? $request->type : '';
        $data = DecommissionReason::where('type', $type)->where('parent_id', RECOMMISSION)->get();
        return response()->json(['status_code' => 200, 'data' => $data]);
    }

    public function departmentSelect(Request $request){
        $department_id = $request->id ?? 0;
        $client_type = $request->client_type ?? 0;
        if($client_type == 0){
            $data = Department::with('childrens')->find($department_id);
            $has_child = $data->childrens->count();
        } else {
            $data = DepartmentContractor::with('childrens')->find($department_id);
            $has_child = $data->childrens->count();
        }

        return response()->json(['status_code' => 200, 'data' => $data->childrens ?? [], 'has_child' => $has_child]);
    }

    public function departmentEditSelect(Request $request){
        $department_id = $request->id ?? 0;
        $this_depart = Department::find($department_id);
        $list_depart = Department::with('parents')->find($this_depart->parent_id);
        $has_parent = $list_depart->parents->count();

        return response()->json(['status_code' => 200, 'data' => $list_depart ?? [], 'has_parent' => $has_parent, 'current_id' => $department_id]);
    }

    public function getParent() {

    }
}
