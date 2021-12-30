<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\ClientAddress;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class ClientAddressRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ClientAddress::class;
    }

    public function updateOrCreateClientAddress($client_id, $data_info){
        return $this->model->updateOrCreate(['client_id' => $client_id],$data_info);
    }
}
