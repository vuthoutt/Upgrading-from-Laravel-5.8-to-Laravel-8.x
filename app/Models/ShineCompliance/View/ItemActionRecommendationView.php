<?php

namespace App\Models\ShineCompliance\View;

use App\Models\ModelBase;

class ItemActionRecommendationView extends ModelBase
{
    protected $table = 'tbl_item_action_recommendation_view';

    protected $fillable = [
        "item_id",
        "action_recommendation",
    ];

    public function getactionRecommendationAttribute () {
        $action_recommendation = str_replace('Other ---',' ', $this->attributes['action_recommendation']);
        $action_recommendation = str_replace('---',' ',  $action_recommendation);
        if ($action_recommendation == 'Encapsulate Encapsulate ') {
            return 'Encapsulate';
        }
        if ($action_recommendation == 'Remove Remove ') {
            return 'Remove';
        }
        return $action_recommendation;
    }
}
