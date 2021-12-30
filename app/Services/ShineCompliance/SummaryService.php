<?php

namespace App\Services\ShineCompliance;

use App\Jobs\SendClientEmailNotification;
use App\Jobs\SendSampleEmail;
use App\Repositories\ShineCompliance\SummaryRepository;
use Carbon\Carbon;

class SummaryService {

    private $summaryRepository;

    public function __construct(
        SummaryRepository $summaryRepository
    ){
        $this->summaryRepository = $summaryRepository;

    }

    public function getFireDocumentsSummary()
    {
        return $this->summaryRepository->getFireDocumentsSummary();
    }

    public function getFireHazardARSummary()
    {
        return $this->summaryRepository->getFireHazardARSummary();
    }

    public function getFireAssessmentSummary()
    {
        return $this->summaryRepository->getFireAssessmentSummary();
    }
}
