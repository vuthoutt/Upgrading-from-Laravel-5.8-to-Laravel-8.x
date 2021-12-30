<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\AppBaseController;
use App\Repositories\ApiTokenRepository;
use App\Repositories\DownloadManifestRepository;
use App\Repositories\SurveyRepository;

/**
 * Created by PhpStorm.
 * User: Hoang Tran
 * Date: 8/8/2019
 * Time: 3:55 PM
 */
class DropdownController extends  AppBaseController {
    private $apiTokenRepository;
    private $downloadManifestRepository;
    private $surveyRepository;
    public function __construct(ApiTokenRepository $apiTokenRepository,
                                SurveyRepository $surveyRepository,
                                DownloadManifestRepository $downloadManifestRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->downloadManifestRepository = $downloadManifestRepository;
        $this->surveyRepository = $surveyRepository;
    }

    public function getAllDropdown(){
        $parent_dropdown = $this->apiTokenRepository->getAllParentDropdown();
        $children_dropdown = $this->apiTokenRepository->getAllChildDropdown();
        $decommission = $this->apiTokenRepository->getAllDecommission();
        $decommissionReason = $this->apiTokenRepository->getAllDecommissionReason();
        $propertyAccessType = $this->apiTokenRepository->propertyAccessType();
        $propertyDropdownData = $this->apiTokenRepository->propertyDropdownData();
        $result = [];
        if(count($parent_dropdown) && ($children_dropdown)){
            $result = [
                "dropdown"     => $parent_dropdown,
                "dropdownData" => $children_dropdown,
                "status" => $decommission,
                "reason" => $decommissionReason,
                "propertyAccessType" => $propertyAccessType,
                "propertyDropdownData" => $propertyDropdownData
            ];
            return $this->sendResponse($result, 'successfully');
        }
        return $this->sendError('Empty Data', STATUS_FAIL_CLIENT);
    }


    public function getAuditDropdown() {
        $data = [
            'auditIDs' =>  []
        ];
        return $this->sendResponse($data, 'successfully');
    }
}
