<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\SampleCertificate;
use Prettus\Repository\Eloquent\BaseRepository;

class SampleCertificateRepository extends BaseRepository
{

    public function model()
    {
        return SampleCertificate::class;
    }

    public function getSampleCertificateBySurvey($realtion,$survey_id){
        $sampleCertificate = SampleCertificate::with($realtion)->where('survey_id',$survey_id)->get();
        return $sampleCertificate ?? [];
    }
}
