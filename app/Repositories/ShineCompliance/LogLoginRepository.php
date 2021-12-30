<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\LogLogin;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class LogLoginRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return LogLogin::class;
    }

    public function createLogLogin($data){
        return $this->model->create($data);
    }

}
