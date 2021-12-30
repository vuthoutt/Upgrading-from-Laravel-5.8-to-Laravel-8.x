<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\PublishedAssessment;
use Prettus\Repository\Eloquent\BaseRepository;

class PublishedAssessmentRepository extends BaseRepository
{

    public function model()
    {
        return PublishedAssessment::class;
    }
}
