<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\ZoneRepository;
use App\Repositories\ItemRepository;
use App\Repositories\PropertyRepository;
use App\Models\Item;
use App\Models\Property;
use App\Models\Zone;
use App\Http\Request\Client\UpdateOrganisationRequest;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ZoneRepository $zoneRepository, ItemRepository $itemRepository, ClientRepository $clientRepository,PropertyRepository $propertyRepository)
    {
        $this->zoneRepository = $zoneRepository;
        $this->itemRepository = $itemRepository;
        $this->clientRepository = $clientRepository;
        $this->propertyRepository = $propertyRepository;

    }

    /**
     * Show my organisation by id.
     *
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $client_id = $request->client_id ?? 1;
        //for gsk
        $client_id = 1;
        // check is site operative
        if($user->is_site_operative == 1){
            if ($request->has('zone_id')) {
                return $this->indexOperative($request, $request->zone_id);
            } else {
                return $this->indexOperative($request);
            }

        }
        $breadcrumb_data = (object)[];
        $breadcrumb_data->client_id = $client_id;
        // property privilege
        if (\CommonHelpers::isSystemClient()) {
            $property_id_privs =    \CompliancePrivilege::getPermission(PROPERTY_PERMISSION,'sql');
        } else {
            $property_id_privs =    \CompliancePrivilege::getPropertyContractorPermission('sql');
        }

        if ($request->has('zone_id')) {
            $zone_current = $request->zone_id;
            $zones =  \DB::select("SELECT z.*,IFNULL(p.prop_id,0) as prop_id from tbl_zones z
                                    left join (select zone_id, count(id) as prop_id from tbl_property where decommissioned = 0 and id
                                    IN $property_id_privs group by zone_id) p on p.zone_id = z.id
                                    where z.client_id = $client_id and  z.parent_id = $zone_current
                                ");
            $breadcrumb_data->zone_current = Zone::with('allParents')->find($request->zone_id);
        } else {

            $zones = \DB::select("SELECT z.*, IFNULL(p.prop_id,0) as prop_id from tbl_zones z
                                    LEFT JOIN tbl_zones zChild ON zChild.parent_id = z.id
                                    LEFT JOIN (select zone_id, count(id) as prop_id from tbl_property where decommissioned = 0 and id
                                    IN $property_id_privs group by zone_id) p on p.zone_id = zChild.id
                                    where z.client_id = $client_id and  z.parent_id = 0 group by z.id
                                ");
        }

        $client = $this->clientRepository->findWhere(['id' => $client_id])->first();

        $client_property_ids = Property::select('id')->where(['client_id' => $client_id, 'decommissioned' => 0])
                                                ->whereRaw("id IN $property_id_privs");


        $items = Item::with('area', 'location','itemInfo','productDebrisView','property')->whereIn('property_id', $client_property_ids)->where('survey_id', 0)->get();

        $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'zones', $client_property_ids, 0);
        if ($request->has('type')) {
            $type = $request->type;
        } else {
            $type = false;
        }

        switch ($type) {
            case TYPE_All_ACM_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', '!=', null)->where('state', '!=', ITEM_NOACM_STATE)->all();
                $title = 'Client All ACM Items Summary';

                $table_id = 'property-all-acm-items';
                break;

            case TYPE_HIGH_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[10, 99])->all();
                $title = 'Client High Risk ACM Item Summary';
                $table_id = 'high-risk-items';
                break;

            case TYPE_MEDIUM_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[7, 9])->all();
                $title = 'Client Medium Risk ACM Item Summary';
                $table_id = 'medium-risk-items';
                break;

            case TYPE_LOW_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[5, 6])->all();
                $title = 'Client Low Risk ACM Item Summary';
                $table_id = 'low-risk-items';
                break;

            case TYPE_VERY_LOW_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[0, 4])->all();
                $title = 'Client Very Low Risk ACM Item Summary';
                $table_id = 'vlow-risk-items';
                break;

            case TYPE_NO_RISK_ITEM_SUMMARY:
                $items_summary_table = $this->itemRepository->countNoACMItems('zones' , 0, $client_property_ids, isset($area_id) ? $area_id : 0,isset($location_id) ? $location_id : 0 );
                $title = 'Client No Risk (NoACM) Item Summary';
                $table_id = 'no-risk-items';
                break;

            case TYPE_INACCESS_ACM_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_INACCESSIBLE_STATE)->all();
                $title = 'Client Inaccessible ACM Item Summary';
                $table_id = 'inaccessible-acm-items';
                break;

            case TYPE_INACCESS_ROOM_SUMMARY:
                $items_summary_table = $this->itemRepository->countInaccessibleRooms('zones',$client_property_ids);

                $title = 'Client Inaccessible Room/locations Summary';
                $table_id = 'inaccessible-room';
                break;

            default:
                $items_summary_table = [];
                $title = '';
                $table_id = '';
                break;
        }

        $breadcrumb_data->table_title = $title;

        return view('zones.index',[
            'dataSummary' => $dataSummary,
            'type' => $type,
            'title' => $title,
            'table_id' => $table_id,
            'items_summary_table' => $items_summary_table,
            'client' => $client,
            'breadcrumb_data' => $breadcrumb_data,
            'zones' => $zones,
            'client_id' => $client_id,
            'pagination_type' => TYPE_CLIENT
        ]);
    }

    public function indexOperative($request, $parent_id = null)
    {
        $client_id = $request->client_id ?? 1;
        $breadcrumb_data = (object)[];
        $breadcrumb_data->client_id = $client_id;
        $breadcrumb_data->table_title = "";
        $client = $this->clientRepository->findWhere(['id' => $client_id])->first();
        if (is_null($parent_id)) {
            $all_zones = $this->zoneRepository->getZoneOperative($client_id);
        } else {
            $all_zones = $this->zoneRepository->getParentZoneOperative($client_id, $parent_id);
            $breadcrumb_data->zone_current = Zone::with('allParents')->find($parent_id);
        }

        return view('zones.operative',[
            'breadcrumb_data' => $breadcrumb_data,
            'client' => $client,
            'all_zones' => $all_zones,
        ]);
    }


    public function zoneGroup($zone_id, Request $request) {
        $client_id = $request->client_id ?? 1;

        $user = Auth::user();
        // check is site operative

        $zone = Zone::with('allChildrens')->find($zone_id);
        $client = $this->clientRepository->findWhere(['id' => $client_id])->first();
        if (is_null($zone)) {
            abort(404);
        }
        if (!is_null($zone->allChildrens) and count($zone->allChildrens) > 0) {
            return redirect()->route('zone', ['zone_id' => $zone_id, 'client_id' => $client_id]);
        }
        if($user->is_site_operative == 1){
            return $this->zoneGroupOperative($request, $zone_id);
        }
        // property privilege
        if (\CommonHelpers::isSystemClient()) {
            $property_id_privs =  \CompliancePrivilege::getPermission(PROPERTY_PERMISSION);
        } else {
            $property_id_privs =    \CompliancePrivilege::getPropertyContractorPermission();
        }
            $client_property_group = Property::with('propertyInfo')
                                    ->where(['client_id' => $client_id, 'decommissioned' => 0, 'zone_id'=> $zone->id])
                                    ->whereIn('id', $property_id_privs)
                                    ->where('zone_id', $zone->id);
            $decommissioned_properties = Property::with('propertyInfo')->where(['client_id' => $client_id, 'zone_id'=> $zone->id])
                                    ->where('decommissioned' ,'!=', 0)
                                    ->whereIn('id', $property_id_privs)
                                    ->get();


        $client_property_group_ids = $client_property_group->pluck('id');
        $properties = $client_property_group->get();



        $items = Item::with('area', 'location','itemInfo','productDebrisView')->whereIn('property_id', $client_property_group_ids)->where('survey_id', 0)->get();

        $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'zones', $client_property_group_ids, 0);

        if ($request->has('type')) {
            $type = $request->type;
        } else {
            $type = false;
        }
        $breadcrumb_data = $zone;
        $breadcrumb_data->client_id = $client_id;
        switch ($type) {
            case TYPE_All_ACM_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', '!=', null)->where('state', '!=', ITEM_NOACM_STATE)->all();
                $title = 'Client All ACM Items Summary';

                $table_id = 'property-all-acm-items';
                break;

            case TYPE_HIGH_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[10, 99])->all();
                $title = 'Client High Risk ACM Item Summary';
                $table_id = 'high-risk-items';
                break;

            case TYPE_MEDIUM_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[7, 9])->all();
                $title = 'Client Medium Risk ACM Item Summary';
                $table_id = 'medium-risk-items';
                break;

            case TYPE_LOW_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[5, 6])->all();
                $title = 'Client Low Risk ACM Item Summary';
                $table_id = 'low-risk-items';
                break;

            case TYPE_VERY_LOW_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[0, 4])->all();
                $title = 'Client Very Low Risk ACM Item Summary';
                $table_id = 'vlow-risk-items';
                break;

            case TYPE_NO_RISK_ITEM_SUMMARY:
                $items_summary_table = $this->itemRepository->countNoACMItems('zones' , 0, $client_property_group_ids, isset($area_id) ? $area_id : 0,isset($location_id) ? $location_id : 0 );
                $title = 'Client No Risk (NoACM) Item Summary';
                $table_id = 'no-risk-items';
                break;

            case TYPE_INACCESS_ACM_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_INACCESSIBLE_STATE)->all();
                $title = 'Client Inaccessible ACM Item Summary';
                $table_id = 'inaccessible-acm-items';
                break;

            case TYPE_INACCESS_ROOM_SUMMARY:
                $items_summary_table = $this->itemRepository->countInaccessibleRooms('zones',$client_property_group_ids);
                $title = 'Client Inaccessible Room/locations Summary';
                $table_id = 'inaccessible-room';
                break;

            default:
                $items_summary_table = [];
                $title = '';
                $table_id = '';
                break;
        }

        $breadcrumb_data->table_title = $title;
        return view('zones.property_group',[
            'zone' => $zone,
            'dataSummary' => $dataSummary,
            'type' => $type,
            'title' => $title,
            'table_id' => $table_id,
            'items_summary_table' => $items_summary_table,
            'client' => $client,
            'breadcrumb_data' => $breadcrumb_data,
            'client_id' => $client_id,
            'properties' => $properties,
            'decommissioned_properties' => $decommissioned_properties,
            'zone_id' => $zone_id,
            'pagination_type' => TYPE_ZONE
        ]);
    }

    public function zoneGroupOperative($request, $zone_id)
    {
        $client_id = $request->client_id;
//        dd($request);
        $client = $this->clientRepository->findWhere(['id' => $client_id])->first();
        $zone = $this->zoneRepository->findWhere(['id' =>$zone_id])->first();
        $all_properties = $this->propertyRepository->getPropertyOperative($client_id, $zone->id);
//            Property::with('propertyInfo')->where(['client_id' => $client_id, 'zone_id'=> $zone->id])->get();
        $properties = $all_properties->where('decommissioned' ,'=', 0);
        $decommissioned_properties = $all_properties->where('decommissioned' ,'!=', 0);
//        dd($properties, $decommissioned_properties);
//        dd($properties);
        $breadcrumb_data = $zone;
        $breadcrumb_data->client_id = $client_id;
        $breadcrumb_data->table_title = "";
        return view('zones.property_group_operative',[
            'zone' => $zone,
            'client' => $client,
            'breadcrumb_data' => $breadcrumb_data,
            'client_id' => $client_id,
            'properties' => $properties,
            'decommissioned_properties' => $decommissioned_properties,
        ]);
    }

    public function updateOrCreateZone( Request $request) {
        $validator = \Validator::make($request->all(), [
            'zone_id' => 'nullable',
            'zone_name' => 'required|max:255',
            'zone_image' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        if($request->has('zone_id')) {
            $id = $request->zone_id;
            $dataUpdate = [
                'zone_name' => $request->zone_name,
                'zone_image' => $request->zone_image,
            ];
            $createZone = $this->zoneRepository->updateZone($id, $dataUpdate);
        } else {
            $dataCreate = [
                'zone_name' => $request->zone_name,
                'zone_image' => $request->zone_image,
                'client_id' => $request->client_id,
                'parent_id' => 0,
            ];
            $createZone = $this->zoneRepository->createZone($dataCreate);
        }
        if (isset($createZone) and !is_null($createZone)) {
            \Session::flash('msg', $createZone['msg']);
            return response()->json(['status_code' => $createZone['status_code'], 'message'=> $createZone['msg'], 'id' => $createZone['data']]);
        }
    }

    public function getClientZone(Request $request) {
        $client_id = 1;
        if ($request->has('client_id')) {
            $client_id = $request->client_id;
        }

        $data = $this->zoneRepository->getClientZone($client_id);
        return response()->json($data);
    }

    public function getClientZoneChild(Request $request) {
        $client_id = 1;
        if ($request->has('client_id')) {
            $client_id = $request->client_id;
        }

        $data = $this->zoneRepository->getClientZoneChild($client_id, $request->parent ?? 0);
        return response()->json($data);
    }

    public function zoneMap(Request $request){
        $client_id = $request->client_id ?? 1;
        if(!env('SETTING_PROPERTY_MAP')) {
            return redirect()->route('zone', ['client_id' => $client_id]);
        }
        return view('zones.zone_map',['client_id' => $client_id]);
    }

    public function zoneMapChild(Request $request){
        $zone_id = $request->zone_id ?? 0;
        $client_id = $request->client_id ?? 1;

        $zone = Zone::find($zone_id);

        $corporate = Zone::where('parent_id',$zone_id)->where('zone_name','like','%Corporate%')->first();
        $commercial = Zone::where('parent_id',$zone_id)->where('zone_name','like','%Commercial%')->first();
        $domestic = Zone::where('parent_id',$zone_id)->where('zone_name','like','%Domestic%')->first();
        $non_domestic = Zone::where('parent_id',$zone_id)->where('zone_name','like','%Communal%')->first();

        return view('zones.zone_map_child',[
            'zone' => $zone,
            'corporate' => $corporate,
            'commercial' => $commercial,
            'domestic' => $domestic,
            'non_domestic' => $non_domestic,
            'client_id' => $client_id
        ]);
    }
}
