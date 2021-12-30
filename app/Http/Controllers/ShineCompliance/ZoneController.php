<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\ZoneService;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\EquipmentService;
use Yajra\DataTables\DataTables;
use App\Services\ShineCompliance\SystemService;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Property;
use App\Models\Zone;
use App\Http\Request\ShineCompliance\Zone\ZoneRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Request\ShineCompliance\User\UserUpdateRequest;

class ZoneController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ZoneService $zoneService,
        PropertyService $propertyService,
        SystemService $systemService,
        EquipmentService $equipmentService,
        ClientService $clientService
    )
    {
        $this->zoneService = $zoneService;
        $this->propertyService = $propertyService;
        $this->clientService = $clientService;
        $this->systemService = $systemService;
        $this->equipmentService = $equipmentService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index(Request $request)
    {
        if (\Auth::user()->is_site_operative == 1) {
            return redirect()->route('zone.operative', ['client_id' => 1]);
        }
        $client_id = 1;
        $zones = $this->zoneService->getAllZone(9, $request->q ?? '', $client_id);
        $client = $this->clientService->getFindClient($client_id);

        $can_add_new = true;
        if (\CommonHelpers::isSystemClient()) {
            $can_add_new = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_CLIENTS, $client_id);
        } else {
            $can_add_new = false;
        }
        //todo search ajax
        if ($request->ajax()) {
            return view('shineCompliance.zones.index', ['can_add_new' => $can_add_new, 'zones' => $zones, 'client' => $client])->render();
        }
        return view('shineCompliance.zones.index', ['can_add_new' => $can_add_new, 'zones' => $zones, 'client' => $client]);
    }

    public function zoneDetail($id, Request $request)
    {
        if ($zone = $this->zoneService->getZone($id, ['property'])) {
            $decommissioned_reason_group = $this->zoneService->getAllProGroupDecommissionReason();
            $can_update = false;
            if (\CommonHelpers::isSystemClient()) {
                $can_update = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_ALL_PROPERTIES, $id, JOB_ROLE_GENERAL, $zone->client_id ?? 0);
            } else {
                $can_update = false;
            }
            // audit
            $comment = \Auth::user()->full_name . " viewed Property Group Detail page " . $zone->zone_name;
            \ComplianceHelpers::logAudit(GROUP_TYPE, $zone->id, AUDIT_ACTION_VIEW, $zone->reference, $zone->id, $comment);
            return view('shineCompliance.zones.zone_detail', compact('can_update', 'zone', 'decommissioned_reason_group'));
        }
        abort(404);
    }

    public function decommissionZone($zone_id, Request $request)
    {
        $decommissioned_reason = $request->has('decommissioned_reason_group') ? $request->decommissioned_reason_group : 0;
        $decommissionZone = $this->zoneService->decommissionZone($zone_id, $decommissioned_reason);
        if (isset($decommissionZone)) {
            if ($decommissionZone['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionZone['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionZone['msg']);
            }
        }
    }

    public function detail($id, Request $request)
    {
        if ($zone = $this->zoneService->getZone($id)) {
            if (\Auth::user()->is_site_operative == 1) {
                return redirect()->route('zone.operative.detail', ['zone_id' => $id]);
            }
            $can_add_new = true;
            if (\CommonHelpers::isSystemClient()) {
                $can_add_new = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_ALL_PROPERTIES, $id, JOB_ROLE_GENERAL, $zone->client_id ?? 0);
            } else {
                $can_add_new = false;
            }
            //todo search ajax
            $properties = $this->propertyService->getListByZone($zone->client_id, $id, ['parents'], $request);
            $property_status = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_STATUS_ID);
            $system_types = $this->systemService->getAllSystemType();
            $equipment_types = $this->equipmentService->getAllTypes();
            //todo search ajax
            if ($request->ajax()) {
                return view('shineCompliance.zones.detail', compact('zone', 'properties', 'property_status', 'system_types', 'equipment_types', 'can_add_new'))->render();
            }

            // audit
            $comment = \Auth::user()->full_name . " viewed Property Group Detail page " . $zone->zone_name;
            \ComplianceHelpers::logAudit(GROUP_TYPE, $zone->id, AUDIT_ACTION_VIEW, $zone->reference, $zone->id, $comment);
            return view('shineCompliance.zones.detail', compact('can_add_new', 'zone', 'properties', 'property_status', 'system_types', 'equipment_types'));
        }
        abort(404);
    }

    public function updateOrCreateZone(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'zone_id' => 'nullable',
            'zone_name' => 'required|max:255',
            'zone_image' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        if ($request->has('zone_id')) {
            $createZone = $this->zoneService->updateZone($request);
        } else {
            $createZone = $this->zoneService->createZone($request);
        }
        if (isset($createZone) and !is_null($createZone)) {
            \Session::flash('msg', $createZone['msg']);
            return response()->json(['status_code' => $createZone['status_code'], 'message' => $createZone['msg'], 'id' => $createZone['data']]);
        }
    }

    public function zoneMapChild(Request $request)
    {
//        $zone_id = $request->zone_id ?? 0;
//        $client_id = $request->client_id ?? 1;
//
//        $zone = Zone::find($zone_id);
//
//        $corporate = Zone::where('parent_id',$zone_id)->where('zone_name','like','%Corporate%')->first();
//        $commercial = Zone::where('parent_id',$zone_id)->where('zone_name','like','%Commercial%')->first();
//        $domestic = Zone::where('parent_id',$zone_id)->where('zone_name','like','%Domestic%')->first();
//        $non_domestic = Zone::where('parent_id',$zone_id)->where('zone_name','like','%Communal%')->first();

        return view('shineCompliance.zones.zone_map_child', [
//            'zone' => $zone,
//            'corporate' => $corporate,
//            'commercial' => $commercial,
//            'domestic' => $domestic,
//            'non_domestic' => $non_domestic,
//            'client_id' => $client_id
        ]);
    }

    public function registerOverall($zone_id, Request $request)
    {
        $zone = $this->zoneService->getZone($zone_id);
        $type = null;
        $property_ids = $this->propertyService->getPropertyByZone($zone_id);
        $permission = $this->propertyService->getPropertyRegisterPermission();

        $register_data = $this->zoneService->getRegisterOverallSummary($property_ids, $zone_id, $permission['asbestos'], $permission['fire'], $permission['water'], $permission['health_and_safety']);

        $decommission_register_data = $this->zoneService->getDecomissionedRegisterOverallSummary($property_ids, $zone_id);
        return view('shineCompliance.zones.register.overall', [
            'zone_id' => $zone_id,
            'zone' => $zone,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'hs' => $permission['health_and_safety'],
            'register_data' => $register_data,
            'decommission_register_data' => $decommission_register_data ?? [],
            'type' => $type
        ]);
    }

    public function registerAsbestos($zone_id, Request $request)
    {

        $zone = $this->zoneService->getZone($zone_id);
        $type = ASBESTOS;
        $property_ids = $this->propertyService->getPropertyByZone($zone_id);
        $register_data = $this->zoneService->getRegisterSummary($property_ids);
        $decommission_register_data = $this->zoneService->getRegisterSummary($property_ids, 1);
        $section = $request->section ?? false;

        $permission = $this->propertyService->getPropertyRegisterPermission();
        $section_data = $this->zoneService->getRiskItem($property_ids, $section, $request->decommissioned ?? 0);
        $data_summary = $section_data['data'];
        $zone->breadcrumb_title = $section_data['breadcrumb'];

        // log audit
        \ComplianceHelpers::logAudit(REGISTER_ASBESTOS, $zone->id, AUDIT_ACTION_VIEW, $zone->reference);

        return view('shineCompliance.zones.register.asbestos', [
            'zone_id' => $zone_id,
            'zone' => $zone,
            'register_data' => $register_data,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'decommission_register_data' => $decommission_register_data,
            'section' => $section,
            'section_data' => $section_data,
            'data_summary' => $data_summary,
            'hs' => $permission['health_and_safety'],
            'type' => $type
        ]);
    }

    public function registerFire($zone_id, Request $request)
    {

        $zone = $this->zoneService->getZone($zone_id);
        $type = FIRE;
        $property_ids = $this->propertyService->getPropertyByZone($zone_id);
        $register_data = $this->zoneService->getHazardRegisterSummary($property_ids, ASSESSMENT_FIRE_TYPE);

        $decommission_register_data = $this->zoneService->getHazardRegisterSummary($property_ids, ASSESSMENT_FIRE_TYPE, 1);

        $section = $request->section ?? false;

        $permission = $this->propertyService->getPropertyRegisterPermission();
        $section_data = $this->zoneService->getRiskHazard($property_ids, $section, ASSESSMENT_FIRE_TYPE, $request->decommissioned ?? 0);
        $section_hazard = $section_data['hazards'];
        $zone->breadcrumb_title = $section_data['breadcrumb'];
        return view('shineCompliance.zones.register.fire', ['zone_id' => $zone_id,
            'zone' => $zone,
            'register_data' => $register_data,
            'decommission_register_data' => $decommission_register_data,
            'section_hazard' => $section_hazard,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'hs' => $permission['health_and_safety'],
            'section' => $section,
            'type' => $type
        ]);
    }

    public function registerGas($zone_id, Request $request)
    {
        $zone = $this->zoneService->getZone($zone_id);
        $type = GAS;
        return view('shineCompliance.zones.register.gas', ['zone_id' => $zone_id, 'zone' => $zone, 'type' => $type]);
    }

    public function registerWater($zone_id, Request $request)
    {
        $zone = $this->zoneService->getZone($zone_id);
        $type = WATER;
        $property_ids = $this->propertyService->getPropertyByZone($zone_id);
        $register_data = $this->zoneService->getHazardRegisterSummary($property_ids, ASSESSMENT_WATER_TYPE);
        $decommission_register_data = $this->zoneService->getHazardRegisterSummary($property_ids, ASSESSMENT_WATER_TYPE, 1);

        $section = $request->section ?? false;

        $permission = $this->propertyService->getPropertyRegisterPermission();
        $section_data = $this->zoneService->getRiskHazard($property_ids, $section, ASSESSMENT_WATER_TYPE, $request->decommissioned ?? 0);
        $section_hazard = $section_data['hazards'];
        $zone->breadcrumb_title = $section_data['breadcrumb'];
        return view('shineCompliance.zones.register.water', ['zone_id' => $zone_id,
            'zone' => $zone,
            'register_data' => $register_data,
            'decommission_register_data' => $decommission_register_data,
            'section_hazard' => $section_hazard,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'hs' => $permission['health_and_safety'],
            'section' => $section,
            'type' => $type
        ]);
    }

    public function registerHS($zone_id, Request $request)
    {
        $zone = $this->zoneService->getZone($zone_id);
        $type = HS;
        $property_ids = $this->propertyService->getPropertyByZone($zone_id);
        $register_data = $this->zoneService->getHazardRegisterSummary($property_ids, ASSESSMENT_HS_TYPE);
        $decommission_register_data = $this->zoneService->getHazardRegisterSummary($property_ids, ASSESSMENT_HS_TYPE, 1);

        $section = $request->section ?? false;

        $permission = $this->propertyService->getPropertyRegisterPermission();
        $section_data = $this->zoneService->getRiskHazard($property_ids, $section, ASSESSMENT_HS_TYPE, $request->decommissioned ?? 0);
        $section_hazard = $section_data['hazards'];
        $zone->breadcrumb_title = $section_data['breadcrumb'];
        return view('shineCompliance.zones.register.hs', ['zone_id' => $zone_id,
            'zone' => $zone,
            'register_data' => $register_data,
            'decommission_register_data' => $decommission_register_data,
            'section_hazard' => $section_hazard,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'hs' => $permission['health_and_safety'],
            'section' => $section,
            'type' => $type
        ]);
    }

    //
    public function listMixedProperties($type, Request $request)
    {
        if (\Auth::user()->is_site_operative == 1) {
            return redirect()->route('zone.operative.detail', ['zone_id' => 2]);
        }
        if ($type == CORPORATE_PROPERTIES) {
            $title = 'Corporate Properties';
            $zones = $this->zoneService->getMixedZones('Corporate');
        } else if ($type == COMMERCIAL_PROPERTIES) {
            $title = 'Commercial Properties';
            $zones = $this->zoneService->getMixedZones('Commercial');
        } else if ($type == HOUSING_DOMESTIC) {
            $title = 'Housing – Domestic';
            $zones = $this->zoneService->getMixedZones('Domestic');
        } else if ($type == HOUSING_COMMUNAL) {
            $title = 'Housing - Communal';
            $zones = $this->zoneService->getMixedZones('Communal');
        } else {
            abort(404);
        }

        $can_add_new = false;
        //todo search ajax
        $properties = $this->propertyService->getListByZone(1, $zones->pluck('id')->toArray(), ['parents'], $request);
        $property_status = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_STATUS_ID);
        $system_types = $this->systemService->getAllSystemType();
        $equipment_types = $this->equipmentService->getAllTypes();
        if ($request->ajax()) {
            return view('shineCompliance.zones.mixed_zone', compact( 'properties', 'property_status', 'system_types', 'equipment_types', 'can_add_new', 'title', 'type'))->render();
        }

        // audit
        $comment = \Auth::user()->full_name . " viewed $title Detail page ";
        \ComplianceHelpers::logAudit(GROUP_TYPE, 0, AUDIT_ACTION_VIEW, 0, 0, $comment);
        return view('shineCompliance.zones.mixed_zone', compact('can_add_new', 'properties', 'property_status', 'system_types', 'equipment_types', 'title', 'type'));
    }
}
