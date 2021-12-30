<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Http\Request\ShineCompliance\System\DocumentRequest;
use Illuminate\Http\Request;
use App\Http\Request\ShineCompliance\System\SystemRequest;
use App\Http\Request\ShineCompliance\System\EquipmentRequest;
use App\Http\Request\ShineCompliance\System\ProgrammeRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\ShineCompliance\SystemService;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\AssessmentService;

class SystemController extends Controller
{
    private $systemService;
    private $propertyService;
    private $assessmentService;

    public function __construct(SystemService $systemService, PropertyService $propertyService, AssessmentService $assessmentService)
    {
        $this->systemService = $systemService;
        $this->propertyService = $propertyService;
        $this->assessmentService = $assessmentService;
    }

    public function index($property_id,Request $request) {
        $systems =  $this->systemService->getAllSystem($property_id,9,$request);
        $property =  $this->propertyService->getProperty($property_id);
        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } elseif(\CommonHelpers::isSystemClient()){
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_SYSTEMS) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }
        //todo search ajax
        if($request->ajax()) {
            return view('shineCompliance.properties.systems.system_list', compact('can_add_new', 'systems', 'property_id', 'property'))->render();
        }
        $comment = \Auth::user()->full_name . " viewed system list on property " . $property->name;
        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);
        return view('shineCompliance.properties.systems.system_list', ['can_add_new' => $can_add_new, 'systems' => $systems, 'property_id' => $property_id, 'property' => $property]);

    }

    public function getAddSystem($property_id) {
        $system_types = $this->systemService->getAllSystemType();
        $classifications = $this->systemService->getAllSystemClassification();

        $property =  $this->propertyService->getProperty($property_id);

        $comment = \Auth::user()->full_name . " viewed system add page on property " . $property->name;
        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->id, $comment);
        return view('shineCompliance.properties.systems.system_add', ['system_types' => $system_types, 'classifications' => $classifications, 'property' => $property, 'property_id' => $property_id]);

    }

    public function getAddAssessmentSystem($assess_id) {
        $system_types = $this->systemService->getAllSystemType();
        $classifications = $this->systemService->getAllSystemClassification();
        $assessment = $this->assessmentService->getAssessmentDetail($assess_id);

        $comment = \Auth::user()->full_name . " viewed system add on assessment " . $assessment->name;
        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $assessment->id, AUDIT_ACTION_VIEW, $assessment->reference, $assessment->property_id, $comment);
        return view('shineCompliance.systems.add_system', ['system_types' => $system_types,
            'classifications' => $classifications, 'assess_id' => $assess_id ,'assessment' => $assessment ]);

    }

    public function postAddSystem($property_id, SystemRequest $request) {
        $data = $request->validated();
        $data['property'] = $property_id;
        $system = $this->systemService->updateOrCreateSystem($data);

        if (isset($system)) {
            if ($system['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.systems.detail',['property_id' => $property_id, 'id' => $system['data'] ?? 0])->with('msg', $system['msg']);
            } else {
                return redirect()->back()->with('err', $system['msg']);
            }
        }
    }

    public function postAddAssessmentSystem (SystemRequest $request) {
        $data = $request->validated();
        $system = $this->systemService->updateOrCreateSystem($data);

        if (isset($system)) {
            if ($system['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.assessment.system_detail',['id' => $system['data'] ?? 0])->with('msg', $system['msg']);
            } else {
                return redirect()->back()->with('err', $system['msg']);
            }
        }
    }

    public function detail($id) {

        $system = $this->systemService->getSystem($id,['systemType','systemClassification','property']);
        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
        } elseif(\CommonHelpers::isSystemClient()){
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_SYSTEMS) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $system->property_id)) {
                $can_update = false;
            }
        }

        $comment = \Auth::user()->full_name . " viewed system ".$system->name. " detail page on property " . ($system->property->name ?? '');
        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_VIEW, $system->reference, $system->property_id, $comment);
        return view('shineCompliance.properties.systems.system_detail',['system' => $system, 'can_update' => $can_update]);
    }

    public function assessmentSystemDetail($id) {

        $system = $this->systemService->getSystem($id,['systemType','systemClassification','property']);

        $comment = \Auth::user()->full_name . " viewed system ".$system->name. " detail page on property " . ($system->property->name ?? '');
        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_VIEW, $system->reference, $system->property_id, $comment);
        return view('shineCompliance.systems.index',['system' => $system]);
    }

    public function getEditSystem($id) {
        $system = $this->systemService->getSystem($id);
        $system_types = $this->systemService->getAllSystemType();
        $classifications = $this->systemService->getAllSystemClassification();

        $comment = \Auth::user()->full_name . " viewed system edit page". ($system->name ?? ''). "  on property " . ($system->property->name ?? '');
        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_VIEW, $system->reference, $system->property_id, $comment);
        return view('shineCompliance.properties.systems.system_edit', ['system' => $system,'system_types' => $system_types, 'classifications' => $classifications]);
    }

    public function getEditAssessmentSystem($id) {
        $system = $this->systemService->getSystem($id);
        $system_types = $this->systemService->getAllSystemType();
        $classifications = $this->systemService->getAllSystemClassification();

        $comment = \Auth::user()->full_name . " viewed system edit page". ($system->name ?? ''). "  on property " . ($system->property->name ?? '');
        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_VIEW, $system->reference, $system->property_id, $comment);
        return view('shineCompliance.systems.edit_system', ['system' => $system,'system_types' => $system_types, 'classifications' => $classifications]);
    }

    public function postEditSystem($id, SystemRequest $request) {
        $data = $request->validated();
        $system = $this->systemService->updateOrCreateSystem($data, $id);

        if (isset($system)) {
            if ($system['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.systems.detail',['id' => $id])->with('msg', $system['msg']);
            } else {
                return redirect()->back()->with('err', $system['msg']);
            }
        }
    }

    public function postEditAssessmentSystem($id, SystemRequest $request) {
        $data = $request->validated();
        $system = $this->systemService->updateOrCreateSystem($data, $id);

        if (isset($system)) {
            if ($system['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.assessment.system_detail',['id' => $system['data'] ?? 0])->with('msg', $system['msg']);
            } else {
                return redirect()->back()->with('err', $system['msg']);
            }
        }
    }

    public function decommission($id) {
        $decommissionSystem = $this->systemService->decommissionSystem($id);
        if (isset($decommissionSystem)) {
            if ($decommissionSystem['status_code'] == STATUS_OK) {
                return redirect()->back()->with('msg', $decommissionSystem['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionSystem['msg']);
            }
        }
    }

    public function decommissionProgramme($id) {
        $decommissionProgramme = $this->systemService->decommissionProgramme($id);
        if (isset($decommissionProgramme)) {
            if ($decommissionProgramme['status_code'] == STATUS_OK) {
                return redirect()->back()->with('msg', $decommissionProgramme['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionProgramme['msg']);
            }
        }
    }

    public function decommissionEquipment($id) {
        $decommissionEquipment = $this->systemService->decommissionEquipment($id);
        if (isset($decommissionEquipment)) {
            if ($decommissionEquipment['status_code'] == STATUS_OK) {
                return redirect()->back()->with('msg', $decommissionEquipment['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionEquipment['msg']);
            }
        }
    }

    public function programmeList($system_id, Request $request) {
        $system = $this->systemService->getSystem($system_id);
        $programmes =  $this->systemService->getAllProgrammes($system_id,9,$request);

        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } elseif(\CommonHelpers::isSystemClient()){
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_SYSTEMS)) {
                $can_add_new = false;
            }
        }

        $comment = \Auth::user()->full_name . " viewed programme list on system". ($system->name ?? ''). "  on property " . $system->property->name ?? '';
        \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $system->id, AUDIT_ACTION_VIEW, $system->reference, $system->property_id, $comment);
        //todo search ajax
        if($request->ajax()) {
            return view('shineCompliance.properties.systems.programme_list', compact('can_add_new', 'programmes', 'system_id', 'system'))->render();
        }
        return view('shineCompliance.properties.systems.programme_list', ['can_add_new' => $can_add_new, 'programmes' => $programmes,'system_id' => $system->id,'system' => $system]);
    }

    public function getProgrammeAdd($system_id) {
        return view('shineCompliance.properties.systems.programme_add', ['system_id' => $system_id]);
    }

    public function postProgrammeAdd($system_id, Request $request) {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_inspected' => 'nullable',
            'inspection_period' => 'required|integer',
            'comment' => 'nullable'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $programme = $this->systemService->updateOrCreateProgramme($system_id, $request->all());

        if (isset($programme) and !is_null($programme)) {
            \Session::flash('msg', $programme['msg']);
            return response()->json(['status_code' => $programme['status_code'], 'success'=> $programme['msg']]);
        }
    }

    public function programmeDetail($id) {
        $programme = $this->systemService->getProgramme($id);

        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
        } elseif(\CommonHelpers::isSystemClient()){
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_SYSTEMS)) {
                $can_update = false;
            }
        }

        $comment = \Auth::user()->full_name . " viewed programme detail ". $programme->name ." on system". ($programme->system->name ?? ''). "  on property " . ($programme->property->name ?? '');
        \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_VIEW, $programme->reference, $programme->property_id, $comment);

        return view('shineCompliance.properties.systems.programme_detail',['can_update' => $can_update, 'programme' => $programme]);
    }

    public function getProgrammeEdit($id) {
        $programme = $this->systemService->getProgramme($id);
        return view('shineCompliance.properties.systems.programme_edit', ['programme' => $programme]);
    }

    public function postProgrammeEdit($id, ProgrammeRequest $request) {
        $data = $request->validated();
        $system = $this->systemService->updateOrCreateProgramme(null, $data, $id);

        if (isset($system)) {
            if ($system['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.programme.detail',['id' => $id])->with('msg', $system['msg']);
            } else {
                return redirect()->back()->with('err', $system['msg']);
            }
        }
    }

    public function equipmentList($system_id, Request $request) {
        $system = $this->systemService->getSystem($system_id);

        $equipments =  $this->systemService->getAllEquipments($system_id,9, null,$request);

        $can_add_new = true;

        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT)) {
                $can_add_new = false;
            }
        }
        $comment = \Auth::user()->full_name . " viewed equipment list on system".$system->name. "  on property " . $system->property->name ?? '';
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $system->id, AUDIT_ACTION_VIEW, $system->reference, $system->property_id, $comment);

        if($request->ajax()) {
            return view('shineCompliance.properties.systems.equipment_list', compact('can_add_new', 'equipments', 'system_id', 'system'))->render();
        }
        return view('shineCompliance.properties.systems.equipment_list', ['can_add_new' => $can_add_new, 'equipments' => $equipments,'system_id' => $system->id, 'system' => $system]);
    }

    public function getEquipmentAdd($system_id) {
        $types = $this->systemService->getAllEquipmentType();

        $system = $this->systemService->getSystem($system_id);
        //audit
        $comment = \Auth::user()->full_name . " viewed equipment add page on system ".$system->name. "  on property " . $system->property->name ?? '';
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $system->id, AUDIT_ACTION_VIEW, $system->reference, $system->property_id, $comment);
        return view('shineCompliance.properties.systems.equipment_add', ['system' => $system, 'types' => $types]);
    }

    public function postEquipmentAdd($system_id, EquipmentRequest $request) {
        $data = $request->validated();
        $equipment = $this->systemService->updateOrCreateEquipment($system_id, $data);
        if (isset($equipment)) {
            if ($equipment['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.register_equipment.detail',['id' => $equipment['data']])->with('msg', $equipment['msg']);
            } else {
                return redirect()->back()->with('err', $equipment['msg']);
            }
        }
    }

    public function equipmentDetail($id) {
        $equipment = $this->systemService->getEquipment($id);

        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT)) {
                $can_update = false;
            }
        }

        $comment = \Auth::user()->full_name . " viewed equipment detail ". $equipment->name ." on system". ($equipment->system->name ?? ''). "  on property " . ($equipment->property->name ?? '');
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_VIEW, $equipment->reference, $equipment->property_id, $comment);
        return view('shineCompliance.properties.systems.equipment_detail',['can_update' => $can_update, 'equipment' => $equipment]);
    }

    public function getEquipmentEdit($id) {
        $types = $this->systemService->getAllEquipmentType();
        $equipment = $this->systemService->getEquipment($id);

        $comment = \Auth::user()->full_name . " viewed equipment ".$equipment->name." edit page on system".($equipment->system->name ?? ''). "  on property " . ($equipment->property->name ?? '');
        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $equipment->id, AUDIT_ACTION_VIEW, $equipment->reference, $equipment->property_id, $comment);
        return view('shineCompliance.properties.systems.equipment_edit', ['types' => $types,'equipment' => $equipment]);
    }

    public function postEquipmentEdit($id, EquipmentRequest $request) {
        $data = $request->validated();
        $equipment = $this->systemService->updateOrCreateEquipment(null, $data, $id);
        if (isset($equipment)) {
            if ($equipment['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.register_equipment.detail',['id' => $equipment['data']])->with('msg', $equipment['msg']);
            } else {
                return redirect()->back()->with('err', $equipment['msg']);
            }
        }
    }

    public function propertyEquipment($property_id, Request $request) {
        $equipments =  $this->systemService->getAllEquipments(null,9, $property_id,$request);
        $property =  $this->propertyService->getProperty($property_id);

        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        }
        if($request->ajax()) {
            return view('shineCompliance.properties.equipment', compact('can_add_new', 'equipments', 'property', 'property_id'))->render();
        }
        $comment = \Auth::user()->full_name . " viewed equipment list on property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);
        return view('shineCompliance.properties.equipment',['equipments' => $equipments,'can_add_new' => $can_add_new, 'property' => $property, 'property_id' => $property_id]);
    }

    public function searchSystem(Request $request) {
        $assess_id = $request->assess_id ?? 0;
        $property_id = $request->property_id ?? 0;
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->systemService->searchSystem($query_string, $property_id, $assess_id);
        return response()->json($data);
    }
}
