<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\ClientInfo;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class ClientInfoRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ClientInfo::class;
    }

    public function updateOrCreateClientInfo($client_id, $data_info){
        return $this->model->updateOrCreate(['client_id' => $client_id],$data_info);
    }

    public function updateClientInfo($client_id,$data){
        return $this->model->where('client_id', $client_id)->update($data);
    }

}
