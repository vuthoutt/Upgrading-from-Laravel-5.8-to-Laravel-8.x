<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\ContractorSetup;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class ContractorSetupRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ContractorSetup::class;
    }

    function getAllContractorSetup(){
        return $this->model->all();
    }
}
