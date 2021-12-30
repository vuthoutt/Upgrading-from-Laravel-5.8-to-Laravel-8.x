<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Http\Request\ShineCompliance\IncidentReport\IncidentReportRequest;
use App\Services\ShineCompliance\IncidentReportService;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\ZoneService;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\DepartmentService;
use App\Services\ShineCompliance\ContractorSetupService;
use App\Services\ShineCompliance\UserService;
use App\Services\ShineCompliance\RoleService;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Property;
use App\Models\Zone;
use App\Http\Request\ShineCompliance\Zone\ZoneRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Request\ShineCompliance\Client\UpdateOrganisationRequest;

class IncidentReportController extends Controller
{
    private $propertyService;
    private $userService;
    private $roleService;
    private $incidentReportService;
    private $clientService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        PropertyService $propertyService,
        UserService $userService,
        RoleService $roleService,
        ClientService $clientService,
        IncidentReportService $incidentReportService
    )
    {
        $this->propertyService = $propertyService;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->incidentReportService = $incidentReportService;
        $this->clientService = $clientService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index()
    {

    }

    public function getAdd(){
        $hs_leads = $this->incidentReportService->getAsbestosLeadIncident();
        $users = $this->userService->getAllIncidentReportingUsers();
        $report_types = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_FORM_TYPE);
        $apparent_cause_types = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_APPARENT_CAUSE_TYPE);
        $injury_types = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_INJURY_TYPE);
        $part_of_body_affected_types = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_PART_OF_BODY_AFFECTED_TYPE);
        $category_of_works = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_CATEGORY_OF_WORKS);
        $involved_select = [
            [
                'title' => INCIDENT_REPORT_INJURY_TYPE_TITLE,
                'name' => 'injury_type',
                'data' => $injury_types
            ],
            [
                'title' => INCIDENT_REPORT_PART_OF_BODY_AFFECTED_TITLE,
                'name' => 'part_of_body_affected',
                'data' => $part_of_body_affected_types
            ],
            [
                'title' => INCIDENT_REPORT_APPARENT_CAUSE_TITLE,
                'name' => 'apparent_cause',
                'data' => $apparent_cause_types
            ]
        ];
        return view('shineCompliance.incident_reporting.add', compact('hs_leads', 'users', 'report_types', 'involved_select', 'category_of_works'));
    }

    public function postAdd(IncidentReportRequest $request){
        $data = $request->validated();
        $result = $this->incidentReportService->createOrUpdateIncidentReporting($data);

        if ($result['status_code'] == STATUS_OK) {
            return redirect()->route('shineCompliance.incident_reporting.incident_Report', ['incident_id' => $result['data']->id])
                ->with('msg', $result['msg']);
        }

        return redirect()->back()->with('err', $result['msg']);
    }

    public function getEdit($id){
        if (!$incident_report = $this->incidentReportService->getIncidentDetail($id)) {
            return abort(404);
        }
        $hs_leads = $this->incidentReportService->getAsbestosLeadIncident();
        $users = $this->userService->getAllIncidentReportingUsers();
        $report_types = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_FORM_TYPE);
        $apparent_cause_types = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_APPARENT_CAUSE_TYPE);
        $injury_types = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_INJURY_TYPE);
        $part_of_body_affected_types = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_PART_OF_BODY_AFFECTED_TYPE);
        $category_of_works = $this->incidentReportService->getIncidentReportTypes(INCIDENT_REPORT_CATEGORY_OF_WORKS);
        $involved_select = [
            [
                'title' => INCIDENT_REPORT_INJURY_TYPE_TITLE,
                'name' => 'injury_type',
                'data' => $injury_types
            ],
            [
                'title' => INCIDENT_REPORT_PART_OF_BODY_AFFECTED_TITLE,
                'name' => 'part_of_body_affected',
                'data' => $part_of_body_affected_types
            ],
            [
                'title' => INCIDENT_REPORT_APPARENT_CAUSE_TITLE,
                'name' => 'apparent_cause',
                'data' => $apparent_cause_types
            ]
        ];
        $involved_persons = [];
        if (isset($incident_report->involvedPersons)) {
            $key = 0;
            foreach ($incident_report->involvedPersons as $involved_person) {
                if (isset($involved_person->injury_type)) {
                    $data_injury_types = explode(', ', $involved_person->injury_type);
                    $count = 0;
                    foreach ($data_injury_types as $data_injury_type) {
                        $involved_persons[$key]['injury_type'][$count]['injury_type'] = $data_injury_type;
                        $count++;
                    }
                }
                if (isset($involved_person->injury_type_other)) {
                    $data_injury_type_others = explode(', ', $involved_person->injury_type_other);
                    $count = 0;
                    foreach ($data_injury_type_others as $data_injury_type_other) {
                        $involved_persons[$key]['injury_type'][$count]['injury_type_other'] = $data_injury_type_other;
                        $count++;
                    }
                }

                if (isset($involved_person->part_of_body_affected)) {
                    $data_part_of_body_affected_list = explode(', ', $involved_person->part_of_body_affected);
                    $count = 0;
                    foreach ($data_part_of_body_affected_list as $data_part_of_body_affected) {
                        $involved_persons[$key]['part_of_body_affected'][$count]['part_of_body_affected'] = $data_part_of_body_affected;
                        $count++;
                    }
                }
                if (isset($involved_person->apparent_cause)) {
                    $data_apparent_causes = explode(', ', $involved_person->apparent_cause);
                    $count = 0;
                    foreach ($data_apparent_causes as $data_apparent_cause) {
                        $involved_persons[$key]['apparent_cause'][$count]['apparent_cause'] = $data_apparent_cause;
                        $count++;
                    }
                }
                if (isset($involved_person->apparent_cause_other)) {
                    $data_apparent_cause_others = explode(', ', $involved_person->apparent_cause_other);
                    $count = 0;
                    foreach ($data_apparent_cause_others as $data_apparent_cause_other) {
                        $involved_persons[$key]['apparent_cause'][$count]['apparent_cause_other'] = $data_apparent_cause_other;
                        $count++;
                    }
                }
                if (isset($involved_person->user_id)) {
                        $involved_persons[$key]['user_id'] = $involved_person->user_id;
                }
                if (isset($involved_person->non_user)) {
                    $involved_persons[$key]['non_user'] = $involved_person->non_user;
                }
                $key++;
            }
        }
        return view('shineCompliance.incident_reporting.edit', compact(
            'hs_leads',
            'users',
            'report_types',
            'involved_select',
            'incident_report',
            'involved_persons',
            'injury_types',
            'apparent_cause_types',
            'part_of_body_affected_types',
            'category_of_works'
        ));
    }

    public function postEdit($id, IncidentReportRequest $request){
        if (!$incident = $this->incidentReportService->getIncidentDetail($id)) {
            return abort(404);
        }
        $data = $request->validated();
        $result = $this->incidentReportService->createOrUpdateIncidentReporting($data, $incident->id);

        if ($result['status_code'] == STATUS_OK) {
            return redirect()->route('shineCompliance.incident_reporting.incident_Report', ['incident_id' => $result['data']->id])
                ->with('msg', $result['msg']);
        }

        return redirect()->back()->with('err', $result['msg']);
    }

    public function updateOrCreateDocument(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'incident_report_id' => 'required',
            'document' => 'required_without:id|file|',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $document = $this->incidentReportService->updateOrCreateDocument($request->all(), $request->id);
        } else {
            $document = $this->incidentReportService->updateOrCreateDocument($request->all());
        }

        if (isset($document) and !is_null($document)) {
            \Session::flash('msg', $document['msg']);
            return response()->json(['status_code' => $document['status_code'], 'success'=> $document['msg'], 'id' => $document['data']]);
        }
    }

    public function incidentReport($incident_id)
    {
        if (!$incident = $this->incidentReportService->getIncidentDetail($incident_id)) {
            return abort(404);
        }
        if ($incident->status == INCIDENT_REPORT_AWAITING_APPROVAL or $incident->status == INCIDENT_REPORT_COMPLETE) {
            $is_locked = true;
        } else {
            $is_locked = false;
        }

        return view('shineCompliance.incident_reporting.index', compact('incident', 'is_locked'));
    }

    public function postDecommission($incident_id, Request $request)
    {
        if(!$incident = $this->incidentReportService->getIncidentDetail($incident_id)){
            abort(404);
        }
        $reason_decommissioned = $request->get('decommission_reason');
        $result = $this->incidentReportService->decommissionIncident($incident, $reason_decommissioned);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function postRecommission($incident_id)
    {
        if(!$incident = $this->incidentReportService->getIncidentDetail($incident_id)){
            abort(404);
        }
        $result = $this->incidentReportService->recommissionIncident($incident);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function approvalIncident($incident_id){
        $approvalIncident = $this->incidentReportService->approvalIncident($incident_id);
        if (isset($approvalIncident) and !is_null($approvalIncident)) {
            if ($approvalIncident['status_code'] == 200) {
                return redirect()->back()->with('msg', $approvalIncident['msg']);
            } else {
                return redirect()->back()->with('err', $approvalIncident['msg']);
            }
        }
    }

    public function rejectIncident($incident_id, Request $request) {

        $rejectSurvey = $this->incidentReportService->rejectIncident($incident_id, $request->all());

        if (isset($rejectSurvey) and !is_null($rejectSurvey)) {
            if ($rejectSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $rejectSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $rejectSurvey['msg']);
            }
        }
    }

    public function cancelIncident($incident_id, Request $request) {
        if(!$incident = $this->incidentReportService->getIncidentDetail($incident_id)){
            abort(404);
        }
        $cancel_incident = $this->incidentReportService->cancelIncident($incident);
        if (isset($cancel_incident) and !is_null($cancel_incident)) {
            if ($cancel_incident['status_code'] == 200) {
                return redirect()->back()->with('msg', $cancel_incident['msg']);
            } else {
                return redirect()->back()->with('err', $cancel_incident['msg']);
            }
        }
    }

    public function searchIncident(Request $request) {

        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->incidentReportService->searchIncident($query_string);
        return response()->json($data);
    }
}
