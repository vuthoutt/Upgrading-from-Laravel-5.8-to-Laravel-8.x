<?php
namespace App\Repositories\ShineCompliance;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\Zone;
use Illuminate\Support\Facades\DB;

class ZoneRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Zone::class;
    }

    public function getZone($id, $relation){
        return $this->model->with($relation)->find($id);
    }

    public function getAllZone($limit, $query, $client_id){
        // add privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $table_join_zone =  "(SELECT z.id,IFNULL(p.property_id,0) as count_property from tbl_zones z
                                left join
                                (select p.zone_id, count(p.id) as property_id from tbl_property p
                                join $table_join_privs on p.id = permission.prop_id group by p.zone_id) p on p.zone_id = z.id
                                where z.client_id = $client_id Group By z.id
                            ) as permission_zone" ;

        $zone_id_privs = \CompliancePrivilege::getGroupListingPermission(JR_ALL_PROPERTIES, $client_id);
        if($query){
            return Zone::where('zone_name', 'like', "%$query%")
                            ->select('tbl_zones.*', 'permission_zone.count_property')
                            ->where('tbl_zones.client_id', $client_id)
                            ->leftJoin(\DB::raw("$table_join_zone"), 'permission_zone.id', 'tbl_zones.id')
                            ->whereIn('tbl_zones.id', $zone_id_privs)
                            ->paginate($limit);
        }
        return Zone::leftJoin(\DB::raw("$table_join_zone"), 'permission_zone.id', 'tbl_zones.id')
                    ->where('tbl_zones.client_id', $client_id)
                    ->select('tbl_zones.*', 'permission_zone.count_property')
                    ->whereIn('tbl_zones.id', $zone_id_privs)
                    ->paginate($limit);
    }

    public function updateZone($id, $data){
        $this->model->updateZone($id, $data);
    }

    public function createZone($data)
    {
        return $this->model->create($data);
    }

    public function listGroupByClient($client_id, $parent_id){
        return $this->model->where(['client_id' => $client_id, 'parent_id' => $parent_id])->get();
    }

    public function searchZone($q, $user){
        $client_listing = $user->userRole->jobRoleViewValue->client_listing ?? "";
        $all_arr = [];
        if($client_listing){
            $client_listing_data = json_decode($client_listing, true);
            foreach ($client_listing_data as $arr){
                $all_arr = array_unique(array_filter(array_merge($all_arr, $arr)));
            }
        }
        return $this->model->where('reference','like',"%$q%")->orWhere('zone_name','like',"%$q%")->whereIn('id', $all_arr)->limit(LIMIT_SEARCH)->get();
    }

    public function getMixedZones($text){
        return $this->model->where('zone_name','like',"%$text%")->get();
    }
}
