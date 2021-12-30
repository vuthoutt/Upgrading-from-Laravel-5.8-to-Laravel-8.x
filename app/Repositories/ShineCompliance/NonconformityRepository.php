<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\Nonconformity;
use Prettus\Repository\Eloquent\BaseRepository;

class NonconformityRepository extends BaseRepository
{
    use PullAssessmentToRegister;

    public function model()
    {
        return Nonconformity::class;
    }
}
