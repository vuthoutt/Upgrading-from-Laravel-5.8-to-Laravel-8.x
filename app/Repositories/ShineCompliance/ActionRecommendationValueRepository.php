<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\DropdownItemValue\ActionRecommendationValue;
use Prettus\Repository\Eloquent\BaseRepository;

class ActionRecommendationValueRepository extends BaseRepository
{

    public function model()
    {
        return ActionRecommendationValue::class;
    }

}
