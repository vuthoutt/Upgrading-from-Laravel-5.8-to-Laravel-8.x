<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\Sample;
use Prettus\Repository\Eloquent\BaseRepository;

class SampleRepository extends BaseRepository
{

    public function model()
    {
        return Sample::class;
    }

}
