<?php

namespace App\Services\ShineCompliance;


use App\Helpers\CommonHelpers;
use App\Repositories\ShineCompliance\ChartRepository;
use App\Repositories\ShineCompliance\ClientRepository;
use App\Repositories\ShineCompliance\TempLogRepository;

class ChartService{

    private $chartRepository;
    private $equipmentService;
    private $systemService;
    private $tempLogRepository;

    public function __construct(
        ChartRepository $chartRepository,
        EquipmentService $equipmentService,
        SystemService $systemService,
        TempLogRepository $tempLogRepository)
    {
    $this->chartRepository = $chartRepository;
    $this->equipmentService = $equipmentService;
    $this->systemService = $systemService;
    $this->tempLogRepository = $tempLogRepository;

}



    public function createComplianceChart($request){
        $title = "Duty to Manage Compliance";
        $yAxis = "Pie";
        $xAxis = [] ;
        $total = CommonHelpers::createChartData('');
        $option = $request->option ?? 'overview';
        $data = $this->getComplianceChart($option);
        $data_chart[] = CommonHelpers::createChartData('Non Compliant', round($data['non_compliant'] * 100), round($data['non_compliant'] * 100),"#c5c5c5", []);
        $data_chart[] = CommonHelpers::createChartData('Compliant', round($data['compliant'] * 100), round($data['compliant'] * 100),"#3ba849", []);
        foreach ($data_chart as $key => $d){
            $xAxis[] = $d['name'] ;
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $data_chart];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createPrePlannedChart($request){
        $title = "Pre-Planned Maintenance Schedule";
        $yAxis = "Pie";
        $xAxis = [] ;
        $total = CommonHelpers::createChartData('');
        $option = $request->option ?? 'overview';
        $data = $this->getPrePlannedChart($option);
        $pre_plans[] = CommonHelpers::createChartData('Critical (<15)', $data['critical'] ?? 0, $data['critical'] ?? 0,'#FF3F2B');
        $pre_plans[] = CommonHelpers::createChartData('Urgent (15-30)', $data['urgent'] ?? 0, $data['urgent'] ?? 0,'#FB7500');
        $pre_plans[] = CommonHelpers::createChartData('Important (31-60)', $data['important'] ?? 0, $data['important'] ?? 0,'#FDC304');
        $pre_plans[] = CommonHelpers::createChartData('Attention (61-120)', $data['attention'] ?? 0, $data['attention'] ?? 0,'#0080B3');
        $pre_plans[] = CommonHelpers::createChartData('Deadline (120>)', $data['deadline'] ?? 0, $data['deadline'] ?? 0,'#C1C4C2');
        foreach ($pre_plans as $key => $pre){
            $xAxis[] = $pre['name'] ;
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $pre_plans];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createReinspectionChart($request){
        $title = "Re-Inspection Programme";
        $yAxis = "Pie";
        $xAxis = [] ;
        $total = CommonHelpers::createChartData('');
        $option = $request->option ?? 'overview';
        $data = $this->getReinspectionChart($option);
        $reinspections[] = CommonHelpers::createChartData('Critical (<15)', $data['critical'] ?? 0, $data['critical'] ?? 0,'#FF3F2B');
        $reinspections[] = CommonHelpers::createChartData('Urgent (15-30)', $data['urgent'] ?? 0, $data['urgent'] ?? 0,'#FB7500');
        $reinspections[] = CommonHelpers::createChartData('Important (31-60)', $data['important'] ?? 0, $data['important'] ?? 0,'#FDC304');
        $reinspections[] = CommonHelpers::createChartData('Attention (61-120)', $data['attention'] ?? 0, $data['attention'] ?? 0,'#0080B3');
        $reinspections[] = CommonHelpers::createChartData('Deadline (120>)', $data['deadline'] ?? 0, $data['deadline'] ?? 0,'#C1C4C2');
        foreach ($reinspections as $key => $re){
            $xAxis[] = $re['name'] ;
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $reinspections];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createActionRecommendationChart($request){
        $title = "Action/recommendations";
        $yAxis = "Pie";
        $xAxis = [] ;
        $total = CommonHelpers::createChartData('');
        $option = $request->option ?? 'overview';
        $data = $this->getActionRecommendationChart($option);
        $action_recommendations[] = CommonHelpers::createChartData('Remove', $data['remove'] ?? 0, $data['remove'] ?? 0,"#d42147", []);
        $action_recommendations[] = CommonHelpers::createChartData('Restrict Access & Remove', $data['restrict_access_remove'] ?? 0, $data['restrict_access_remove'] ?? 0,"#fabd2b", []);
        $action_recommendations[] = CommonHelpers::createChartData('Manage', $data['manage'] ?? 0, $data['manage'] ?? 0,"#3ca84a", []);
        $action_recommendations[] = CommonHelpers::createChartData('Remedial', $data['remedial'] ?? 0, $data['remedial'] ?? 0,"#4287f5", []);
        $action_recommendations[] = CommonHelpers::createChartData('Further Investigation Required', $data['further_investigation_required'] ?? 0, $data['further_investigation_required'] ?? 0,"#a846f2", []);
        $action_recommendations[] = CommonHelpers::createChartData('Other', $data['other'] ?? 0, $data['other'] ?? 0,"#34ebd2", []);
        foreach ($action_recommendations as $key => $ar){
            $xAxis[] = $ar['name'] ;
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $action_recommendations];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createRiskChart($request){
        $title = "Risks";
        $yAxis = "Pie";
        $xAxis = [] ;
        $total = CommonHelpers::createChartData('');
        $option = $request->option ?? 'overview';
        $data = $this->getDataRiskChart($option);
        $risks[] = CommonHelpers::createChartData('Very Low Risk', $data['very_low_risk'] ?? 0, $data['very_low_risk'] ?? 0,"rgb(249,232,0)", []);
        $risks[] = CommonHelpers::createChartData('Medium Risk', $data['medium_risk'] ?? 0, $data['medium_risk'] ?? 0,"rgb(246,165,0", []);
        $risks[] = CommonHelpers::createChartData('Low Risk', $data['low_risk'] ?? 0, $data['low_risk'] ?? 0,"rgb(117,76,36)", []);
        if($option != 'asbestos'){
            $risks[] = CommonHelpers::createChartData('High Risk', $data['high_risk'] ?? 0, $data['high_risk'] ?? 0,"rgb(255,0,0)", []);
        }
        $risks[] = CommonHelpers::createChartData('Very High Risk', $data['very_high_risk'] ?? 0, $data['very_high_risk'] ?? 0,"rgb(193,39,45)", []);
//        dd($risks);
        foreach ($risks as $key => $risk){
            $xAxis[] = $risk['name'] ;
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $risks];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createAccessibilityChart($request){
        $title = "Accessibility";
        $yAxis = "Pie";
        $xAxis = [] ;
        $total = CommonHelpers::createChartData('');
        $option = $request->option ?? 'overview';
        $data = $this->getDataAccessibilityChart($option);
        $accessibility[] = CommonHelpers::createChartData('Inaccessible', $data['inaccessible'] ?? 0, $data['inaccessible'] ?? 0,"#c5c5c5", []);
        $accessibility[] = CommonHelpers::createChartData('Accessible', $data['accessible'] ?? 0, $data['accessible'] ?? 0,"#3ba849", []);
        foreach ($accessibility as $key => $acc){
            $xAxis[] = $acc['name'] ;
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $accessibility];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createDocumentManagementChart($request){
        $title = "Document Management";
        $yAxis = "Pie";
        $xAxis = [] ;
        $total = CommonHelpers::createChartData('');
        $option = $request->option ?? 'overview';
        $data = $this->getDocumentChart($option);
        $documents[] = CommonHelpers::createChartData('Critical (<15 Days)', $data['critical'] ?? 0, $data['critical'] ?? 0,'#FF3F2B', []);
        $documents[] = CommonHelpers::createChartData('Urgent (15-30 Days)', $data['urgent'] ?? 0, $data['urgent'] ?? 0,'#FB7500', []);
        $documents[] = CommonHelpers::createChartData('Important (31-60 Days)', $data['important'] ?? 0, $data['important'] ?? 0,'#FDC304', []);
        $documents[] = CommonHelpers::createChartData('Attention (61-120 Days)', $data['attention'] ?? 0, $data['attention'] ?? 0,'#0080B3', []);
        $documents[] = CommonHelpers::createChartData('Deadline (120> Days)', $data['deadline'] ?? 0, $data['deadline'] ?? 0,'#C1C4C2', []);
        foreach ($documents as $key => $document){
            $xAxis[] = $document['name'] ;
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $documents];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createQualityAssuranceChart($request){
        $title = "Quality Assurance";
        $yAxis = "Pie";
        $xAxis = [] ;
        $total = CommonHelpers::createChartData('');
        $option = $request->option ?? 'overview';
        $data = $this->getQualityAssuranceChart($option);
        $quality_assurances[] = CommonHelpers::createChartData('Technical in Progress', $data['technical_in_progress'] ?? 0, $data['technical_in_progress'] ?? 0,'#C1C4C2', []);
        $quality_assurances[] = CommonHelpers::createChartData('Published for Approval', $data['approval'] ?? 0, $data['approval'] ?? 0,'#FDC304', []);
        $quality_assurances[] = CommonHelpers::createChartData('Rejected', $data['rejected'] ?? 0, $data['rejected'] ?? 0,'#FF3F2B', []);
        foreach ($quality_assurances as $key => $quality_assurance){
            $xAxis[] = $quality_assurance['name'] ;
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $quality_assurances];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    private function countTotalData($arr){
        $count = 0;
        if(count($arr)){
            if(array_key_exists('data', $arr)){
                //for pie chart
                foreach ($arr['data'] as $data) {
                    if(array_key_exists('total',$data)){
                        $count += $data['total'];
                    }
                }
            } else {
                foreach ($arr as $data) {
                    if(array_key_exists('total',$data)){
                        $count += $data['total'];
                    }
                }
            }
        }
        return $count;
    }

    private function responseData($arr_data, $total, $xAxis, $yAxis, $title){
        $total['total'] = $this->countTotalData($arr_data);
        if(array_key_exists('data', $arr_data)){
            //for pie
            $arr_data['data'][] = $total;
        } else {
            $arr_data[] = $total;
        }
        return collect([$xAxis, $arr_data, $yAxis, $title]);
        return json_encode([$xAxis, json_encode($arr_data), $yAxis, $title]);
    }

    private function responseStockData($arr_data, $total, $xAxis, $yAxis, $title){
        $total['total'] = $this->countTotalData($arr_data);
//        $total['name'] = $total['name'] . ": " . $this->countTotalData($arr_data);
        $total['name'] = "";
        if(array_key_exists('data', $arr_data)){
            //for pie
            $arr_data['data'][] = $total;
        } else {
            $arr_data[] = $total;
        }
        return  collect([$xAxis, $arr_data, $yAxis, $title]);
        return  json_encode([$xAxis, json_encode($arr_data), $yAxis, $title]);
    }

    public function createChartData($name, $total = 0, $y = 0, $color = 'transparent', $data = [], $other = false){
        $result = [];
        // demo {"name":"Critical (<15)","total":176,"data":[2,39,55,0,67,13],"other":" AND `dueDate` <= 1572148316 ","y":0,"color":"#FF3F2B"}
        $result['name'] = $name;// name for type
        $result['total'] = $total;// total in legend for that type
        $result['data'] = $data;// first column for Critial will be 2, next 39, 55, 0, 67, 13
//        $result['other'] = $other;// for old
        $result['y'] = $y;// for Pie chart only => all data will be 0 in Column chart
        $result['color'] = $color;// color for that type
        $result['other'] = $other;
        return $result;
    }

    public function setStockChartData($old_data, $data, $other = NULL)
    {
        if (array_key_exists('data', $old_data) && is_array($data)) {
            $old_data['data'] = $data;
        }
        if (array_key_exists('total', $old_data)) {
            foreach ($data as $sub_data){
                $old_data['total'] += $sub_data['y'];
            }
        }
        if (array_key_exists('other', $old_data) && $other) {
            $old_data['other'] = $other;// legend
        }
        return $old_data;
    }

    private function getDateQuater($data_date){
        $result = [];
        $data = explode("," ,$data_date);
        $quarter = $data[0] ?? NULL;
        $year = $data[1] ?? NULL;
        $date = CommonHelpers::get_dates_of_quarter($quarter, $year, 'Y-m-d H:i:s');
        $result['start_date'] = strtotime($date['start']);
        $result['end_date'] = strtotime($date['end']);
        return $result;
    }

    public function getDataAccessibilityChart($option){
        $data = [];
        switch ($option){
            case 'overview':
                $fire_exit = $this->chartRepository->getDataAccessibilityFireExist();
                $assembly_point = $this->chartRepository->getDataAccessibilityAssemblyPoint();
                $vehicle_parking = $this->chartRepository->getDataAccessibilityVehicleParking();
                $area_floor = $this->chartRepository->getDataAccessibilityAreaFloor();
                $room_location = $this->chartRepository->getDataAccessibilityRoomLocation();
                $voids = $this->chartRepository->getDataAccessibilityVoids();
                $acm = $this->chartRepository->getDataAccessibilityAcm();
                $equipment = $this->chartRepository->getDataAccessibilityEquipment();
                $data['inaccessible'] = ($fire_exit['inaccessible'] ?? 0) + ($assembly_point['inaccessible'] ?? 0) +
                    ($vehicle_parking['inaccessible'] ?? 0) +($area_floor['inaccessible'] ?? 0) +($room_location['inaccessible'] ?? 0) +
                    ($voids['inaccessible'] ?? 0) +($acm['inaccessible'] ?? 0) +($equipment['inaccessible'] ?? 0);
                $data['accessible'] = ($fire_exit['accessible'] ?? 0) + ($assembly_point['accessible'] ?? 0) +
                    ($vehicle_parking['accessible'] ?? 0) +($area_floor['accessible'] ?? 0) +($room_location['accessible'] ?? 0) +
                    ($voids['accessible'] ?? 0) +($acm['accessible'] ?? 0) +($equipment['accessible'] ?? 0);
                break;
            case 'fire_exit':
                $fire_exit = $this->chartRepository->getDataAccessibilityFireExist();
                $data['inaccessible'] = ($fire_exit['inaccessible'] ?? 0);
                $data['accessible'] = ($fire_exit['accessible'] ?? 0);
                break;
            case 'assembly_point':
                $assembly_point = $this->chartRepository->getDataAccessibilityAssemblyPoint();
                $data['inaccessible'] = ($assembly_point['inaccessible'] ?? 0);
                $data['accessible'] = ($assembly_point['accessible'] ?? 0);
                break;
            case 'vehicle_parking':
                $vehicle_parking = $this->chartRepository->getDataAccessibilityVehicleParking();
                $data['inaccessible'] = ($vehicle_parking['inaccessible'] ?? 0);
                $data['accessible'] = ($vehicle_parking['accessible'] ?? 0);
                break;
            case 'area_floor':
                $area_floor = $this->chartRepository->getDataAccessibilityAreaFloor();
                $data['inaccessible'] = ($area_floor['inaccessible'] ?? 0);
                $data['accessible'] = ($area_floor['accessible'] ?? 0);
                break;
            case 'room_location':
                $room_location = $this->chartRepository->getDataAccessibilityRoomLocation();
                $data['inaccessible'] = ($room_location['inaccessible'] ?? 0);
                $data['accessible'] = ($room_location['accessible'] ?? 0);
                break;
            case 'voids':
                $voids = $this->chartRepository->getDataAccessibilityVoids();
                $data['inaccessible'] = ($voids['inaccessible'] ?? 0);
                $data['accessible'] = ($voids['accessible'] ?? 0);
                break;
            case 'acm':
                $acm = $this->chartRepository->getDataAccessibilityAcm();
                $data['inaccessible'] = ($acm['inaccessible'] ?? 0);
                $data['accessible'] = ($acm['accessible'] ?? 0);
                break;
            case 'equipment':
                $equipment = $this->chartRepository->getDataAccessibilityEquipment();
                $data['inaccessible'] = ($equipment['inaccessible'] ?? 0);
                $data['accessible'] = ($equipment['accessible'] ?? 0);
                break;
        }
        return $data;
    }

    public function getDataRiskChart($option){
        $data = [];
        $arr_risk = ['very_low_risk', 'low_risk', 'medium_risk', 'high_risk', 'very_high_risk'];
        switch ($option) {
            case 'overview':
                $asbestos_risks = $this->chartRepository->getDataAsbestosRisk();
                $fire_risks = $this->chartRepository->getDataFireWaterRisk(ASSESSMENT_FIRE_TYPE);
                $water_risks = $this->chartRepository->getDataFireWaterRisk(ASSESSMENT_WATER_TYPE);//currently water is same as fire
                $data = $this->returnByRiskType([$asbestos_risks, $fire_risks, $water_risks], $arr_risk);
                break;
            case 'asbestos':
                $asbestos_risks = $this->chartRepository->getDataAsbestosRisk();
                $data = $this->returnByRiskType([$asbestos_risks], $arr_risk);
                break;
            case 'fire':
                $fire_risks = $this->chartRepository->getDataFireWaterRisk(ASSESSMENT_FIRE_TYPE);
                $data = $this->returnByRiskType([$fire_risks], $arr_risk);
                break;
            case 'water':
                $water_risks = $this->chartRepository->getDataFireWaterRisk(ASSESSMENT_WATER_TYPE);
                $data = $this->returnByRiskType([$water_risks], $arr_risk);
                break;

        }
        return $data;
    }

    public function getComplianceChart($option){
        $data = [];
        switch ($option) {
            case 'overview':
                $data_duty_manage = $this->chartRepository->getDataDutyManageCompliant();
                $data_rooms = $this->chartRepository->getDataRoomCompliant();
                $data_action_recommendation_asbestos = $this->chartRepository->getDataAsbestosActRecommendation(true);
                $data_action_recommendation_fire = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_FIRE_TYPE, true);
                $data_action_recommendation_gas = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_GAS_TYPE,true);
                $data_action_recommendation_water = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_WATER_TYPE,true);
                $data_action_recommendation_hs = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_HS_TYPE,true);

                $data_re_inspection_asbestos = $this->chartRepository->getDataReinspectionAsbestos();
                $data_re_inspection_fire = $this->chartRepository->getDataReinspectionAssessment(ASSESSMENT_FIRE_TYPE);
                $data_re_inspection_gas = $this->chartRepository->getDataReinspectionAssessment(ASSESSMENT_GAS_TYPE);
                $data_re_inspection_water = $this->chartRepository->getDataReinspectionAssessment(ASSESSMENT_WATER_TYPE);
                $data_re_inspection_hs = $this->chartRepository->getDataReinspectionAssessment(ASSESSMENT_HS_TYPE);

                $data_pre_planned_me = $this->chartRepository->getDataPrePlanned();

                $data = $this->handleDataComplianceChart(
                            $data_duty_manage,
                            $data_rooms,
                            ['asbestos' => $data_action_recommendation_asbestos,
                             'fire' => $data_action_recommendation_fire,
                             'gas' => $data_action_recommendation_gas,
                             'hs' => $data_action_recommendation_hs,
                             'water' => $data_action_recommendation_water],
                            ['asbestos' => $data_re_inspection_asbestos,
                             'fire' => $data_re_inspection_fire,
                             'gas' => $data_re_inspection_gas,
                             'hs' => $data_re_inspection_hs,
                             'water' => $data_re_inspection_water],
                            $data_pre_planned_me,
                            $option
                        );
                break;
            case 'asbestos':
                $data_duty_manage = $this->chartRepository->getDataDutyManageCompliant();
                $data_rooms = $this->chartRepository->getDataRoomCompliant();
                $data_action_recommendation_asbestos = $this->chartRepository->getDataAsbestosActRecommendation(true);
                $data_re_inspection_asbestos = $this->chartRepository->getDataReinspectionAsbestos();
                $data = $this->handleDataComplianceChart(
                            $data_duty_manage,
                            $data_rooms,
                            ['asbestos' => $data_action_recommendation_asbestos],
                            ['asbestos' => $data_re_inspection_asbestos],
                            [],
                            $option
                        );
                break;
            case 'fire':
                $data_duty_manage = $this->chartRepository->getDataDutyManageCompliant();
                $data_rooms = $this->chartRepository->getDataRoomCompliant();
                $data_action_recommendation_fire = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_FIRE_TYPE, true);
                $data_re_inspection_fire = $this->chartRepository->getDataReinspectionAssessment(ASSESSMENT_FIRE_TYPE);
                $data = $this->handleDataComplianceChart(
                            $data_duty_manage,
                            $data_rooms,
                            ['fire' => $data_action_recommendation_fire],
                            ['fire' => $data_re_inspection_fire],
                            [],
                            $option
                        );
                break;
            case 'water':
                $data_duty_manage = $this->chartRepository->getDataDutyManageCompliant();
                $data_rooms = $this->chartRepository->getDataRoomCompliant();
                $data_action_recommendation_water = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_WATER_TYPE,true);
                $data_re_inspection_water = $this->chartRepository->getDataReinspectionAssessment(ASSESSMENT_WATER_TYPE);
                $data = $this->handleDataComplianceChart(
                            $data_duty_manage,
                            $data_rooms,
                            ['water' => $data_action_recommendation_water],
                            ['water' => $data_re_inspection_water],
                            [],
                            $option
                        );
                break;

            case 'hs':
                $data_duty_manage = $this->chartRepository->getDataDutyManageCompliant();
                $data_rooms = $this->chartRepository->getDataRoomCompliant();
                $data_action_recommendation_hs = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_HS_TYPE,true);
                $data_re_inspection_hs = $this->chartRepository->getDataReinspectionAssessment(ASSESSMENT_HS_TYPE);
                $data = $this->handleDataComplianceChart(
                            $data_duty_manage,
                            $data_rooms,
                            ['hs' => $data_action_recommendation_hs],
                            ['hs' => $data_re_inspection_hs],
                            [],
                            $option
                        );
                break;

            case 'me':
                $data_pre_planned_me = $this->chartRepository->getDataPrePlanned();
                $data = $this->handleDataComplianceChart(
                            [],
                            [],
                            [],
                            [],
                             $data_pre_planned_me,
                            $option
                        );
                break;
        }
        $non_compliant =  1 - $data > 0 ? 1 - $data : 0;
        return ['non_compliant' => $non_compliant, 'compliant' =>  $data];
    }

    public function handleDataComplianceChart($data_duty_manage, $data_rooms, $data_action_recommendation, $data_re_inspection, $data_pre_planned_me, $option){
        $rate_compliant = 0;
        switch ($option) {
            case 'overview':
                //duty to manage
                $rate_duty_manage_compliant_asbestos = ($data_duty_manage['total_duty_manage'] > 0 ? $data_duty_manage['count_duty_manage_asbestos'] / $data_duty_manage['total_duty_manage'] : 0) * 0.5;
                $rate_duty_manage_compliant_fire = ($data_duty_manage['total_duty_manage'] > 0 ? $data_duty_manage['count_duty_manage_fire'] / $data_duty_manage['total_duty_manage'] : 0) * 0.5;
                $rate_duty_manage_compliant_gas = ($data_duty_manage['total_duty_manage'] > 0 ? $data_duty_manage['count_duty_manage_gas'] / $data_duty_manage['total_duty_manage'] : 0) * 0.5;
                $rate_duty_manage_compliant_water = ($data_duty_manage['total_duty_manage'] > 0 ? $data_duty_manage['count_duty_manage_water'] / $data_duty_manage['total_duty_manage'] : 0) * 0.5;
                $rate_duty_manage_compliant_me = ($data_pre_planned_me['total'] > 0 ? $data_pre_planned_me['count_programme'] / $data_pre_planned_me['total'] : 0 );

                //room current all are same
                $rate_accessible_room_compliant_asbestos = ($data_rooms['total_rooms'] > 0 ? $data_rooms['total_accessible_rooms'] / $data_rooms['total_rooms'] : 0) * 0.1;
                $rate_accessible_room_compliant_fire = ($data_rooms['total_rooms'] > 0 ? $data_rooms['total_accessible_rooms'] / $data_rooms['total_rooms'] : 0) * 0.1;
                $rate_accessible_room_compliant_gas = ($data_rooms['total_rooms'] > 0 ? $data_rooms['total_accessible_rooms'] / $data_rooms['total_rooms'] : 0) * 0.1;
                $rate_accessible_room_compliant_water = ($data_rooms['total_rooms'] > 0 ? $data_rooms['total_accessible_rooms'] / $data_rooms['total_rooms'] : 0) * 0.1;
                //action recommendation
                $rate_management_action_recommendation_compliant_asbestos = ($data_action_recommendation['asbestos']['total_action_recommendation'] > 0 ? ($data_action_recommendation['asbestos']['manage'] / $data_action_recommendation['asbestos']['total_action_recommendation']) : 0) * 0.2;
                $rate_management_action_recommendation_compliant_fire = ($data_action_recommendation['fire']['total_action_recommendation'] > 0 ? ($data_action_recommendation['fire']['manage'] / $data_action_recommendation['fire']['total_action_recommendation']) : 0) * 0.2;
                $rate_management_action_recommendation_compliant_gas = ($data_action_recommendation['gas']['total_action_recommendation'] > 0 ? ($data_action_recommendation['gas']['manage'] / $data_action_recommendation['gas']['total_action_recommendation']) : 0) * 0.2;
                $rate_management_action_recommendation_compliant_water = ($data_action_recommendation['water']['total_action_recommendation'] > 0 ? ($data_action_recommendation['water']['manage'] / $data_action_recommendation['water']['total_action_recommendation']) : 0) * 0.2;
                //re-inspection
                $rate_re_inspection_compliant_asbestos = ($data_re_inspection['asbestos']['count_property'] > 0 ? ($data_re_inspection['asbestos']['count_survey'] / $data_re_inspection['asbestos']['count_property']) : 0) * 0.2;
                $rate_re_inspection_compliant_fire = ($data_re_inspection['fire']['count_property'] > 0 ? ($data_re_inspection['fire']['count_assessment'] / $data_re_inspection['fire']['count_property']) : 0) * 0.2;
                $rate_re_inspection_compliant_gas = ($data_re_inspection['gas']['count_property'] > 0 ? ($data_re_inspection['gas']['count_assessment'] / $data_re_inspection['gas']['count_property']) : 0) * 0.2;
                $rate_re_inspection_compliant_water = ($data_re_inspection['water']['count_property'] > 0 ? ($data_re_inspection['water']['count_assessment'] / $data_re_inspection['water']['count_property']) : 0) * 0.2;

                $rate_asbestos = $rate_duty_manage_compliant_asbestos + $rate_accessible_room_compliant_asbestos + $rate_management_action_recommendation_compliant_asbestos + $rate_re_inspection_compliant_asbestos;
                $rate_fire = $rate_duty_manage_compliant_fire + $rate_accessible_room_compliant_fire + $rate_management_action_recommendation_compliant_fire + $rate_re_inspection_compliant_fire;
                $rate_gas = $rate_duty_manage_compliant_gas + $rate_accessible_room_compliant_gas + $rate_management_action_recommendation_compliant_gas + $rate_re_inspection_compliant_gas;
                $rate_water = $rate_duty_manage_compliant_water + $rate_accessible_room_compliant_water + $rate_management_action_recommendation_compliant_water + $rate_re_inspection_compliant_water;
                $rate_me = $rate_duty_manage_compliant_me;
                //current not counting $rate_gas
                $rate_compliant = ($rate_asbestos + $rate_fire + $rate_water + $rate_me) / 4;
                break;
            case 'asbestos':
                //duty to manage
                $rate_duty_manage_compliant_asbestos = ($data_duty_manage['total_duty_manage'] > 0 ? $data_duty_manage['count_duty_manage_asbestos'] / $data_duty_manage['total_duty_manage'] : 0) * 0.5;
                //room current all are same
                $rate_accessible_room_compliant_asbestos = ($data_rooms['total_rooms'] > 0 ? $data_rooms['total_accessible_rooms'] / $data_rooms['total_rooms'] : 0) * 0.1;
                //action recommendation
                $rate_management_action_recommendation_compliant_asbestos = ($data_action_recommendation['asbestos']['total_action_recommendation'] > 0 ? ($data_action_recommendation['asbestos']['manage'] / $data_action_recommendation['asbestos']['total_action_recommendation']) : 0) * 0.2;
                //re-inspection
                $rate_re_inspection_compliant_asbestos = ($data_re_inspection['asbestos']['count_property'] > 0 ? ($data_re_inspection['asbestos']['count_survey'] / $data_re_inspection['asbestos']['count_property']) : 0) * 0.2;
                $rate_asbestos = $rate_duty_manage_compliant_asbestos + $rate_accessible_room_compliant_asbestos + $rate_management_action_recommendation_compliant_asbestos + $rate_re_inspection_compliant_asbestos;
                $rate_compliant = $rate_asbestos;
                break;
            case 'fire':
                //duty to manage
                $rate_duty_manage_compliant_fire = ($data_duty_manage['total_duty_manage'] > 0 ? $data_duty_manage['count_duty_manage_fire'] / $data_duty_manage['total_duty_manage'] : 0) * 0.5;
                //room current all are same
                $rate_accessible_room_compliant_fire = ($data_rooms['total_rooms'] > 0 ? $data_rooms['total_accessible_rooms'] / $data_rooms['total_rooms'] : 0) * 0.1;
                //action recommendation
                $rate_management_action_recommendation_compliant_fire = ($data_action_recommendation['fire']['total_action_recommendation'] > 0 ? ($data_action_recommendation['fire']['manage'] / $data_action_recommendation['fire']['total_action_recommendation']) : 0) * 0.2;
                //re-inspection
                $rate_re_inspection_compliant_fire = ($data_re_inspection['fire']['count_property'] > 0 ? ($data_re_inspection['fire']['count_assessment'] / $data_re_inspection['fire']['count_property']) : 0) * 0.2;
                $rate_fire = $rate_duty_manage_compliant_fire + $rate_accessible_room_compliant_fire + $rate_management_action_recommendation_compliant_fire + $rate_re_inspection_compliant_fire;
                //current not counting $rate_gas
                $rate_compliant = $rate_fire;
                break;
            case 'water':
                //duty to manage
                $rate_duty_manage_compliant_water = ($data_duty_manage['total_duty_manage'] > 0 ? $data_duty_manage['count_duty_manage_water'] / $data_duty_manage['total_duty_manage'] : 0) * 0.5;
                //room current all are same
                $rate_accessible_room_compliant_water = ($data_rooms['total_rooms'] > 0 ? $data_rooms['total_accessible_rooms'] / $data_rooms['total_rooms'] : 0) * 0.1;
                //action recommendation
                $rate_management_action_recommendation_compliant_water = ($data_action_recommendation['water']['total_action_recommendation'] > 0 ? ($data_action_recommendation['water']['manage'] / $data_action_recommendation['water']['total_action_recommendation']) : 0) * 0.2;
                //re-inspection
                $rate_re_inspection_compliant_water = ($data_re_inspection['water']['count_property'] > 0 ? ($data_re_inspection['water']['count_assessment'] / $data_re_inspection['water']['count_property']) : 0) * 0.2;

                $rate_water = $rate_duty_manage_compliant_water + $rate_accessible_room_compliant_water + $rate_management_action_recommendation_compliant_water + $rate_re_inspection_compliant_water;
                //current not counting $rate_gas
                $rate_compliant = $rate_water;
                break;
            case 'me':
                //duty to manage
                $rate_compliant = ($data_pre_planned_me['total'] > 0 ? $data_pre_planned_me['count_programme'] / $data_pre_planned_me['total'] : 0 );
                break;
        }
        return $rate_compliant;

    }

    public function getPrePlannedChart($option){
        $data = [];
        $arr_types = ['critical', 'urgent', 'important', 'attention', 'deadline'];
        switch ($option) {
            case 'overview':
                $asbestos_pre_planned = [];
                $compliance_programmes = $this->chartRepository->getDataFireWaterPrePlanned();
                $data = $this->returnByRiskType([$compliance_programmes], $arr_types);
                break;
            case 'asbestos':
                break;
            case 'fire':
                break;
            case 'water':
                break;
        }
        return $data;
    }

    public function getReinspectionChart($option){
        $data = [];
        $arr_types = ['critical', 'urgent', 'important', 'attention', 'deadline'];
        switch ($option) {
            case 'overview':
                $asbestos_reinspection = $this->chartRepository->getDataAsbestosReinspection();
                $fire_reinspection = $this->chartRepository->getDataFireWaterReinspection(DOCUMENT_FIRE_TYPE, ASSESSMENT_FIRE_TYPE);
                $water_reinspection = $this->chartRepository->getDataFireWaterReinspection(DOCUMENT_WATER_TYPE, ASSESSMENT_WATER_TYPE);
                $data = $this->returnByRiskType([$asbestos_reinspection, $fire_reinspection, $water_reinspection], $arr_types);
                break;
            case 'asbestos':
                $asbestos_reinspection = $this->chartRepository->getDataAsbestosReinspection();
                $data = $this->returnByRiskType([$asbestos_reinspection], $arr_types);
                break;
            case 'fire':
                $fire_reinspection = $this->chartRepository->getDataFireWaterReinspection(DOCUMENT_FIRE_TYPE, ASSESSMENT_FIRE_TYPE);
                $data = $this->returnByRiskType([$fire_reinspection], $arr_types);
                break;
            case 'water':
                $water_reinspection = $this->chartRepository->getDataFireWaterReinspection(DOCUMENT_WATER_TYPE, ASSESSMENT_WATER_TYPE);
                $data = $this->returnByRiskType([$water_reinspection], $arr_types);
                break;
        }
        return $data;
    }

    public function getActionRecommendationChart($option){
        $data = [];
        $arr_types = ['remove', 'restrict_access_remove', 'manage', 'remedial', 'further_investigation_required', 'other'];
        switch ($option) {
            case 'overview':
                $asbestos_act_recommendation = $this->chartRepository->getDataAsbestosActRecommendation();
                $fire_act_recommendation = $this->chartRepository->getDataFireWaterActRecommendation();
                $data = $this->returnByRiskType([$asbestos_act_recommendation, $fire_act_recommendation], $arr_types);
                break;
            case 'asbestos':
                $asbestos_act_recommendation = $this->chartRepository->getDataAsbestosActRecommendation();
                $data = $this->returnByRiskType([$asbestos_act_recommendation], $arr_types);
                break;
            case 'fire':
                $fire_act_recommendation = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_FIRE_TYPE);
                $data = $this->returnByRiskType([$fire_act_recommendation], $arr_types);
                break;
            case 'water':
                $water_act_recommendation = $this->chartRepository->getDataFireWaterActRecommendation(ASSESSMENT_WATER_TYPE);
                $data = $this->returnByRiskType([$water_act_recommendation], $arr_types);
                break;
        }
        return $data;
    }

    public function returnByRiskType($data, $types){
        $return = [];
        foreach ($data as $d){
            foreach ($types as $type){
                if(!array_key_exists($type, $return)){
                    $return[$type] = 0;
                }
                $return[$type] += $d[$type] ?? 0;
            }
        }
        return $return;
    }

    public function getDocumentChart($option) {
        $data = [];
        $arr_types = ['critical', 'urgent', 'important', 'attention', 'deadline'];
        switch ($option) {
            case 'overview':
                $project_documents = $this->chartRepository->getDataProjectDocuments();
                $assessments = $this->chartRepository->getDataAssessments();
                $surveys = $this->chartRepository->getDataSurveys();
                $certificates = $this->chartRepository->getDataCertificates();
                $incident_reports = $this->chartRepository->getDataIncidentReports();
                $work_requests = $this->chartRepository->getDataWorkRequests();
                $data = $this->returnByRiskType([$project_documents, $assessments, $surveys, $certificates, $incident_reports, $work_requests], $arr_types);
                break;
            case 'project_documents':
                $project_documents = $this->chartRepository->getDataProjectDocuments();
                $data = $this->returnByRiskType([$project_documents], $arr_types);
                break;
            case 'assessments':
                $assessments = $this->chartRepository->getDataAssessments();
                $data = $this->returnByRiskType([$assessments], $arr_types);
                break;
            case 'certificates':
                $certificates = $this->chartRepository->getDataCertificates();
                $data = $this->returnByRiskType([$certificates], $arr_types);
                break;
            case 'incident_reports':
                $incident_reports = $this->chartRepository->getDataIncidentReports();
                $data = $this->returnByRiskType([$incident_reports], $arr_types);
                break;
            case 'surveys':
                $surveys = $this->chartRepository->getDataSurveys();
                $data = $this->returnByRiskType([$surveys], $arr_types);
                break;
            case 'work_requests':
                $work_requests = $this->chartRepository->getDataWorkRequests();
                $data = $this->returnByRiskType([$work_requests], $arr_types);
                break;
        }
        return $data;
    }

    public function getQualityAssuranceChart($option) {
        $data = [];
        $arr_types = ['technical_in_progress', 'approval', 'rejected'];
        switch ($option) {
            case 'overview':
                $project_documents = $this->chartRepository->getDataQualityAssuranceProjectDocuments();
                $assessments = $this->chartRepository->getDataQualityAssuranceAssessments();
                $surveys = $this->chartRepository->getDataQualityAssuranceSurveys();
                $certificates = $this->chartRepository->getDataQualityAssuranceCertificates();
                $incident_reports = $this->chartRepository->getDataQualityAssuranceIncidentReports();
                $work_requests = $this->chartRepository->getDataQualityAssuranceWorkRepuests();
                $data = $this->returnByRiskType([$project_documents, $assessments, $surveys, $certificates, $incident_reports, $work_requests], $arr_types);
                break;
            case 'project_documents':
                $project_documents = $this->chartRepository->getDataQualityAssuranceProjectDocuments();
                $data = $this->returnByRiskType([$project_documents], $arr_types);
                break;
            case 'assessments':
                $assessments = $this->chartRepository->getDataQualityAssuranceAssessments();
                $data = $this->returnByRiskType([$assessments], $arr_types);
                break;
            case 'certificates':
                $certificates = $this->chartRepository->getDataQualityAssuranceCertificates();
                $data = $this->returnByRiskType([$certificates], $arr_types);
                break;
            case 'surveys':
                $surveys = $this->chartRepository->getDataQualityAssuranceSurveys();
                $data = $this->returnByRiskType([$surveys], $arr_types);
                break;
            case 'incident_reports':
                $incident_reports = $this->chartRepository->getDataQualityAssuranceIncidentReports();
                $data = $this->returnByRiskType([$incident_reports], $arr_types);
                break;
            case 'work_requests':
                $work_requests = $this->chartRepository->getDataQualityAssuranceWorkRepuests();
                $data = $this->returnByRiskType([$work_requests], $arr_types);
                break;
        }
        return $data;
    }
     //stock chart

     public function createTemperatureChart($request, $equipment_id = null){
         $equipment_id = $equipment_id ? $equipment_id : $request->equipment_id;
         $title = "Equipment Temperature Records";
         $yAxis = "°C";
         $xAxis = $temp_type = [];
         //UNIX_TIMESTAMP(au.created_at) as created_at
         $option = isset($request->option) &&  $request->option != 'overview' ? $request->option : NULL;
         $total = CommonHelpers::createChartData('Total');
         $equipment = $this->equipmentService->getEquipmentDetails($equipment_id, ['tempLog']);
         if(!$option){
             $active = $this->equipmentService->getActiveSection($equipment->type, true);
             $active = $this->equipmentService->getTemperatureAndPhOnly($active);
             if(isset($active['active_field']) and count($active['active_field']) > 0){
                 if(in_array('ph',$active['active_field'])){
                     $pos = array_search('ph', $active['active_field']);
                     unset($active['active_field'][$pos]);
                 }
                 foreach($active['active_field'] as $dataRow){
                     $temp_type[$dataRow] = $equipment->getActiveTextAttribute($dataRow);
                 }
             }
         } else {
             $temp_type[$option] = $equipment->getActiveTextAttribute($option);
         }
         $other = $list_return = [];
         $logs = $this->tempLogRepository->getTemplogByDay($equipment->id);
         foreach ($temp_type as $active_key => $name){
             $data = [];
             $create_chart = CommonHelpers::createChartData($name, 0, 0,NULL, []);
             // $logs = $equipment->tempLog->where("$active_key", '!=', null);
             foreach ($logs as $log){
                 $value = intval($log->{$active_key}) ? intval($log->{$active_key}) : NULL;
                 if(isset($value)){
                     $type_color = [
                         'enabled' => true,
                         'fillColor' => ''
                     ];
                     $other[$log->created_at][] = ($name ?? '') . " : " . $value . " °C";
                     $data[] = ["x" =>$log->created_at * 1000 ?? 0, "y" => intval($log->{$active_key}) ? intval($log->{$active_key}) : 0, "marker" => $type_color, "log_id" => null, "type_text" => $name ?? ''] ;

                 }
             }
             if(count($data)){
                 $list_return[] = CommonHelpers::setStockChartData($create_chart, $data, 1, true, []);
             }
         }
         //for shared tooltip
         $new_return = [];
         foreach ($list_return as $return){
             $new_return[] = CommonHelpers::sharedTooltip($return, $other);
         }
         return $this->responseStockData($new_return, $total, $xAxis, $yAxis, $title);
    }


    public function createProgrammeReinspectionChart($request, $system_id = null){
        $system_id = $system_id ? $system_id : ($request->system_id ?? null);
        $title = "Re-inspection Programme";
        $yAxis = "Percentage";
        $xAxis = [] ; // will be list properties if select a zone, be list groups if select over view
//        $total_critical = $total_critical =$total_critical =$total_critical =$total_critical =$total_critical =$total_critical = 0;
        $critical_data = CommonHelpers::createChartData('Critical (<15)', 0, 0,'#FF3F2B');
        $urgent_data = CommonHelpers::createChartData('Urgent (15-30)', 0, 0,'#FB7500');
        $important_data = CommonHelpers::createChartData('Important (31-60)', 0, 0,'#FDC304');
        $attention_data = CommonHelpers::createChartData('Attention (61-120)', 0, 0,'#0080B3');
        $deadline_data = CommonHelpers::createChartData('Deadline (120>)', 0, 0,'#C1C4C2');
        $missing_data = CommonHelpers::createChartData('Missing', 0, 0,'#2cc93f');
        $total = CommonHelpers::createChartData('Total');
        $system = $this->systemService->getSystemDetail($system_id, $relation = ['programmes.documentInspection']);
        if($system && count($system->programmes)){
            foreach ($system->programmes as $k => $programme){
                $xAxis[] = $programme->reference . ' - ' . $programme->name;
                $count_critical = $count_urgent = $count_important = $count_attention = $count_deadline = $count_missing = 0;
                $day_remaining = $programme->days_remaining;
                if($day_remaining == 'Missing'){
                    $count_missing = 1;
                } else if($day_remaining < 15){
                    $count_critical = $day_remaining;
                } else if($day_remaining >= 15 && $day_remaining <= 30){
                    $count_urgent = $day_remaining;
                } else if($day_remaining >= 31 && $day_remaining <= 60){
                    $count_important = $day_remaining;
                } else if($day_remaining >= 61 && $day_remaining <= 120){
                    $count_attention = $day_remaining;
                } else if($day_remaining >= 120){
                    $count_deadline = $day_remaining;
                }
                $critical_data = CommonHelpers::setChartData($critical_data, $count_critical, NULL, $count_critical);
                $urgent_data = CommonHelpers::setChartData($urgent_data, $count_urgent, NULL, $count_urgent);
                $important_data = CommonHelpers::setChartData($important_data, $count_important, NULL, $count_important);
                $attention_data = CommonHelpers::setChartData($attention_data, $count_attention, NULL, $count_attention);
                $deadline_data = CommonHelpers::setChartData($deadline_data, $count_deadline, NULL, $count_deadline);
                $missing_data = CommonHelpers::setChartData($missing_data, 0, NULL, $count_missing);
           }
        } else {
            //for empty data
            $critical_data = CommonHelpers::createChartData('Critical (<15)', 0, 0,'#FF3F2B', [0]);
            $urgent_data = CommonHelpers::createChartData('Urgent (15-30)', 0, 0,'#FB7500', [0]);
            $important_data = CommonHelpers::createChartData('Important (31-60)', 0, 0,'#FDC304', [0]);
            $attention_data = CommonHelpers::createChartData('Attention (61-120)', 0, 0,'#0080B3', [0]);
            $deadline_data = CommonHelpers::createChartData('Deadline (120>)', 0, 0,'#C1C4C2', [0]);
            $missing_data = CommonHelpers::createChartData($missing_data, 0, 0,'#2cc93f', [0]);
            $xAxis[] = 'No Data';
        }
        return $this->responseData([$critical_data, $urgent_data, $important_data, $attention_data, $deadline_data, $missing_data], $total, $xAxis, $yAxis, $title);
    }

}
