<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\ContractorSetupRepository;

class ContractorSetupService{

    private $contractorSetupRepository;

    public function __construct(ContractorSetupRepository $contractorSetupRepository){
        $this->contractorSetupRepository = $contractorSetupRepository;
    }

    public function getAllContractorSetup(){
        return $this->contractorSetupRepository->getAllContractorSetup();
    }

}
