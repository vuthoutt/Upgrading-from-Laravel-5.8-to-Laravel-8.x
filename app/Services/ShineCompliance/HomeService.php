<?php

namespace App\Services\ShineCompliance;

use App\Helpers\ComplianceHelpers;
use App\Repositories\ShineCompliance\HomeRepository;

class HomeService{

    private $homeRepository;

    public function __construct(HomeRepository $homeRepository){
        $this->homeRepository = $homeRepository;
    }

    public function getDecomissionReason($type){
        return $this->homeRepository->getDecomissionReason($type);
    }

    public function getRecomissionReason($type){
        return $this->homeRepository->getRecomissionReason($type);
    }
}
