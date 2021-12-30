<?php
namespace App\Repositories;
use App\Models\SampleCertificate;
use Prettus\Repository\Eloquent\BaseRepository;

class SampleCertificateRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return SampleCertificate::class;
    }


    public function searchSampleCertificate($q){
        return $this->model->whereRaw("(reference LIKE '%$q%' OR sample_reference LIKE '%$q%')")->orderBy('sample_reference','asc')->limit(LIMIT_SEARCH)->get();
    }
}
