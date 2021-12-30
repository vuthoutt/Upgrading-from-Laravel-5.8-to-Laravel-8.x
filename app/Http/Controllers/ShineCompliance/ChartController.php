<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Helpers\CommonHelpers;
use App\Services\ShineCompliance\ChartService;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $chartService;
    public function __construct(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    //generate chart
    public function generateChart(Request $request){
        $chart_name = $request->chart_name;
        if($chart_name){
            if($chart_name == 'compliance_chart'){
                return $this->chartService->createComplianceChart($request);
            } else if($chart_name == 'accessibility_chart'){
                return $this->chartService->createAccessibilityChart($request);
            } else if($chart_name == 'risk_chart'){
                return $this->chartService->createRiskChart($request);
            } else if($chart_name == 'action_recommendation_chart'){
                return $this->chartService->createActionRecommendationChart($request);
            } else if($chart_name == 'reinspection_chart'){
                return $this->chartService->createReinspectionChart($request);
            } else if($chart_name == 'pre_planned_chart'){
                return $this->chartService->createPrePlannedChart($request);
            } else if($chart_name == 'document_management_chart'){
                return $this->chartService->createDocumentManagementChart($request);
            } else if($chart_name == 'quality_assurance_chart'){
                return $this->chartService->createQualityAssuranceChart($request);
            } else if($chart_name == 'EQR'){//equipment temperature records
                return $this->chartService->createTemperatureChart($request);
            } else if($chart_name == 'PRP'){//programme inspection
                return $this->chartService->createProgrammeReinspectionChart($request);
            }
        }
    }

}
