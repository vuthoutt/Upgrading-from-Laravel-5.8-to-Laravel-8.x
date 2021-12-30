<?php

namespace App\Models\View;

use App\Models\ModelBase;

class ItemDropdownDescriptionView extends ModelBase
{
    protected $table = 'tbl_item_dropdown_description_view';

    protected $fillable = [
        "item_id",
        "product_debris",
        "extent",
        "asbestos_type",
        "licensed_non_licensed",
        "action_recommendation",
        "additional_information",
        "accessibility_vulnerability",
    ];

    public function getProductDebrisAttribute () {
        $product_debris = str_replace('Non-asbestos ---','',  $this->attributes['product_debris']);
        $product_debris = str_replace('Asbestos ---','',  $product_debris);
        $product_debris = str_replace('Other ---',' ',  $product_debris);
        $product_debris = str_replace('---',' ',  $product_debris);
        return $product_debris;
    }

    public function getextentAttribute () {
        $extent = str_replace('Other ---',' ',  $this->attributes['extent']);
        $extent = str_replace('---',' ',  $extent);
        return $extent;
    }

    public function getasbestosTypeAttribute () {
        $asbestos_type = str_replace('Other ---',' ',  $this->attributes['asbestos_type']);
        $asbestos_type = str_replace('Other---',' ',  $this->attributes['asbestos_type']);
        $asbestos_type = str_replace('---',' ',  $asbestos_type);
        $asbestos_type = str_replace(',',' and ',  $asbestos_type);
        return $asbestos_type;
    }

    public function getlicensedNonLicensedAttribute () {
        $licensed_non_licensed = str_replace('Other ---',' ', $this->attributes['licensed_non_licensed']);
        $licensed_non_licensed = str_replace('---',' ',  $licensed_non_licensed);
        return $licensed_non_licensed;
    }

    public function getactionRecommendationAttribute () {
        $action_recommendation = str_replace('Other ---',' ', $this->attributes['action_recommendation']);
        $action_recommendation = str_replace('---',' ',  $action_recommendation);
        return $action_recommendation;
    }

    public function getadditionalInformationAttribute () {
        $additional_information = str_replace('Other ---',' ', $this->attributes['additional_information']);
        $additional_information = str_replace('---',' ',  $additional_information);
        return $additional_information;
    }

    public function getaccessibilityVulnerabilityAttribute () {
        $accessibility_vulnerability = str_replace('Other ---',' ', $this->attributes['accessibility_vulnerability']);
        $accessibility_vulnerability = str_replace('---',' ',  $accessibility_vulnerability);
        return $accessibility_vulnerability;
    }
}
