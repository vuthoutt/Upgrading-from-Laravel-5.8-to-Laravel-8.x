<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\Client;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ClientRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Client::class;
    }

    function getFindClient($id){
        return $this->model->find($id);
    }

    public function getAllClients()
    {
        return $this->model->all();
    }
    public function getListClients() {
        return $this->model->whereIn('client_type', [2, 0])->get();
    }

    public function getClient($client_id, $relation = []) {
        $client =  $this->model->with($relation)->where('id', $client_id)->first();
        return is_null($client) ? [] : $client;
    }

    public function createClient($data) {
        $client = $this->model->create($data);
        return is_null($client) ? [] : $client;
    }

    public function updateClient($client_id, $data_update){
        return $this->model->where('id', $client_id)->update($data_update);
    }

    public function getClientWhereIn($contractors){
        return $this->model->whereIN('id', $contractors)->get();
    }

    public function getClientPrivilege($view_client_list){
        return $this->model->where('client_type', 1)->get();
    }

    public function getAllClientByListId($client_ids, $limit, $query) {
        return $this->model->where('client_type','!=',1)
                            ->whereIn('id',$client_ids)
                            ->where('name', 'like', "%$query%")
                            ->paginate($limit);
    }

    public function getAllClientsWithRelations($relations){
        return $this->model->with($relations)->get();
    }

    public function getAllClientGroupsWithRelations($client_id, $relations){
        return $this->model->with($relations)->where('id', $client_id)->whereIn('client_type',[0,2])->first();
    }

    public function showClient($job_role, $request, $limit = 500, $offset = 0, $order_by = "", $search = ""){
        $builder = $this->model->query();
        $builder->with('zonePrivilege')->whereIn('client_type', [0,2]);
//        $is_add_group_arr = $job_role->getGeneralValueViewByType($request->tab, 'is_add_group');
//        $is_all_group_arr = $job_role->getGeneralValueViewByType($request->tab, 'is_all_group');
        if($search){
            $builder->whereRaw($search);
        }
//        $builder
        if ($order_by){
            $builder = $builder->orderByRaw($order_by);
        }
        $builder = $builder->select("tbl_clients.id as id", "tbl_clients.name as name", "tbl_clients.reference as reference");
        return DataTables::of($builder)
            ->editColumn('name', function ($data) {
                return '<a href="' . route('contractor', ['client_id' => $data->id]) .'">'. ($data->name ?? '') .'</a>';
            })
            ->editColumn('reference', function ($data) {
                return $data->reference ?? '';
            })
//            ->addColumn('is_add_group', function ($data) use ($is_add_group_arr, $request) {
//                return '<div class="custom-control custom-checkbox text-center">
//                            <input type="checkbox" class="custom-control-input select-add-group" name="select-add-group[]" id="'.$data->id.'-select-add-group'.$request->tab.'"
//                            value="'.$data->id.'"'.(in_array($data->id, $is_add_group_arr) ? 'checked' : '').'
//                            >
//                            <label class="custom-control-label" for="'.$data->id.'-select-add-group'.$request->tab.'"></label>
//                        </div>';
//            })
//            ->addColumn('is_all_group', function ($data) use ($is_all_group_arr, $request) {
//                return '<div class="custom-control custom-checkbox text-center">
//                            <input type="checkbox" class="custom-control-input select-all-group" name="select-add-group[]" id="'.$data->id.'-select-all-group'.$request->tab.'"
//                            value="'.$data->id.'"'.(in_array($data->id, $is_all_group_arr) ? 'checked' : '').'
//                            >
//                            <label class="custom-control-label" for="'.$data->id.'-select-all-group'.$request->tab.'"></label>
//                        </div>';
//            })
            ->addColumn('view', function ($data) {
                return '<a href="javascript:void(0)" class="view-client" data-id="'.$data->id.'">View</a>';
            })
            ->rawColumns(['name', 'reference', 'view'])
            ->make(true);
    }

    public function showOrganisation($job_role, $organisation_type, $request, $limit = 500, $offset = 0, $order_by = "", $search = ""){
        try {
        $builder = $this->model->query();
        $data_view_value = $job_role->jobRoleViewValue;
        $data_edit_value = $job_role->jobRoleEditValue;
        if($request->tab == JOB_ROLE_VIEW){
            $organisation_listing = $data_view_value->organisation_listing ? json_decode($data_view_value->organisation_listing, true) : [];
        } else {

            $organisation_listing = $data_edit_value->organisation_listing ? json_decode($data_edit_value->organisation_listing, true) : [];
        }
        if($search){
            $builder->whereRaw($search);
        }
//        $builder
        if ($order_by){
            $builder = $builder->orderByRaw($order_by);
        }
        //get selected value here
        $builder = $builder->select("tbl_clients.id as id", "tbl_clients.name as name", "tbl_clients.reference as reference", "tbl_clients.client_type as client_type");
        return DataTables::of($builder)
            ->editColumn('name', function ($data) {
                return '<a href="' . route('contractor', ['client_id' => $data->id]) .'">'. ($data->name ?? '') .'</a>';
            })
            ->editColumn('reference', function ($data) {
                return $data->reference ?? '';
            })
            ->editColumn('client_type', function ($data) {
                return $data->client_type == 0 ? "My Organisation" : ($data->client_type == 2 ? "Client" : "Contractor");
            })
            ->addColumn('details', function ($data) use ( $organisation_listing, $request) {
                return '<div class="custom-control custom-checkbox text-center">
                            <input type="checkbox" class="custom-control-input organisation_detail" name="organisation_detail['.$data->id.']" id="'.$data->id.'-organisation-details-'.$request->tab.'"
                            data-value="'.$data->id.'"
                            value="'.JR_CONTRACTOR_DETAILS.'"'.((array_key_exists($data->id, $organisation_listing)  &&
                                    in_array(JR_CONTRACTOR_DETAILS, $organisation_listing[$data->id])) ? 'checked' : '').'
                            >
                            <label class="custom-control-label" for="'.$data->id.'-organisation-details-'.$request->tab.'"></label>
                        </div>';
            })
            ->addColumn('policy', function ($data) use ( $organisation_listing, $request) {
                return '<div class="custom-control custom-checkbox text-center">
                            <input type="checkbox" class="custom-control-input organisation_detail" name="organisation_detail['.$data->id.']" id="'.$data->id.'-organisation-policy-'.$request->tab.'"
                            data-value="'.$data->id.'"
                            value="'.JR_CONTRACTOR_POLICY_DOCUMENTS.'"'.((array_key_exists($data->id, $organisation_listing)  &&
                                    in_array(JR_CONTRACTOR_POLICY_DOCUMENTS, $organisation_listing[$data->id])) ? 'checked' : '').'
                            >
                            <label class="custom-control-label" for="'.$data->id.'-organisation-policy-'.$request->tab.'"></label>
                        </div>';
            })
            ->addColumn('departments', function ($data) use ( $organisation_listing, $request) {
                return '<div class="custom-control custom-checkbox text-center">
                            <input type="checkbox" class="custom-control-input organisation_detail" name="organisation_detail['.$data->id.']" id="'.$data->id.'-organisation-departments-'.$request->tab.'"
                            data-value="'.$data->id.'"
                            value="'.JR_CONTRACTOR_DEPARTMENTS.'"'.((array_key_exists($data->id, $organisation_listing)  &&
                                    in_array(JR_CONTRACTOR_DEPARTMENTS, $organisation_listing[$data->id])) ? 'checked' : '').'
                            >
                            <label class="custom-control-label" for="'.$data->id.'-organisation-departments-'.$request->tab.'"></label>
                        </div>';
            })
            ->addColumn('training_records', function ($data) use ( $organisation_listing, $request) {
                return '<div class="custom-control custom-checkbox text-center">
                            <input type="checkbox" class="custom-control-input organisation_detail" name="organisation_detail['.$data->id.']" id="'.$data->id.'-organisation-training-records-'.$request->tab.'"
                            data-value="'.$data->id.'"
                            value="'.JR_CONTRACTOR_TRAINING_RECORDS.'"'.((array_key_exists($data->id, $organisation_listing)  &&
                                    in_array(JR_CONTRACTOR_TRAINING_RECORDS, $organisation_listing[$data->id])) ? 'checked' : '').'
                            >
                            <label class="custom-control-label" for="'.$data->id.'-organisation-training-records-'.$request->tab.'"></label>
                        </div>';
            })
            ->rawColumns(['name', 'reference', 'client_type', 'details', 'policy', 'departments', 'training_records'])
            ->make(true);

        } catch (\Exception $e){
            dd($e);
        }
    }
}
