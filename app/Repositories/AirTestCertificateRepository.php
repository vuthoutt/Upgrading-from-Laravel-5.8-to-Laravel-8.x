<?php
namespace App\Repositories;
use App\Models\AirTestCertificate;
use Prettus\Repository\Eloquent\BaseRepository;

class AirTestCertificateRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return AirTestCertificate::class;
    }


    public function searchAirTestCertificate($q){
        return $this->model->whereRaw("(reference LIKE '%$q%' OR air_test_reference LIKE '%$q%')")->orderBy('air_test_reference','asc')->limit(LIMIT_SEARCH)->get();
    }
}
