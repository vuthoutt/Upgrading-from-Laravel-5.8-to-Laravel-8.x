<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AirTestCertificate;
use Prettus\Repository\Eloquent\BaseRepository;

class AirTestCertificateRepository extends BaseRepository
{

    public function model()
    {
        return AirTestCertificate::class;
    }

}
