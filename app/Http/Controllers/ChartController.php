<?php

namespace App\Http\Controllers;


use App\Helpers\CommonHelpers;
use App\Models\Client;
use App\Models\Department;
use App\Models\Project;
use App\Models\ProjectSponsor;
use App\Models\ProjectType;
use App\Models\PropertyType;
use App\Models\Zone;
use App\Repositories\ChartRepository;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ChartRepository $chartRepository)
    {
        $this->chartRepository = $chartRepository;
    }

    //generate chart
    public function generateChart(Request $request){
        $chart_type = $request->chart_type;
        if($chart_type){
            if($chart_type == 'MS'){
                return $this->createMSChart($request);//Management Surveys
            } else if($chart_type == 'NA'){
                return $this->createNAChart($request);//No Access Strategy
            } else if($chart_type == 'RIP'){
                return $this->createReinspectionChart($request);//Reinspection Chart
            } else if($chart_type == 'PD'){
                return $this->createProjectDeadlineChart($request);//Project Deadline Chart
            } else if($chart_type == 'PT'){
                return $this->createProductDebrisTypeChart($request);//Product Debris Type Chart
            } else if($chart_type == 'UL'){
                return $this->createUserLoginChart($request);//User Login Chart
            } else if($chart_type == 'PDD'){
                return $this->createProjectDocumentDeadlineChart($request);//Project Document Deadline Chart
            } else if($chart_type == 'DI'){
                return $this->createDecommissionItemChart($request);//Decommission Item Chart
            } else if($chart_type == 'DPT'){
                return $this->createDecommissionProductChart($request);//Decommission Produc Chart
            }
        }
    }

    //generate chart for PDF summaries
    public function generatePDFchart(Request $request){
        $chart_type = $request->summary_type;
//        dd($chart_type);
        if($chart_type){
            if(in_array($chart_type, ['directorOverview','managerOverview'])){
                $data_MS = json_decode($this->createMSChart($request));//Management Surveys
                $data_RIP = json_decode($this->createReinspectionChart($request));//Reinspection Chart
                $data_PD = json_decode($this->createProductDebrisTypeChart($request));//Product debris type
                $data_UL = json_decode($this->createUserLoginChart($request)); //User Login chart
                $data_PS = json_decode($this->createProjectSponsorChart($request)); //Project Sponsor chart
                $data_PDC = $data_PDDC = NULL;
                if($chart_type == 'managerOverview'){
                    $data_PDC = json_decode($this->createProjectDeadlineChart($request)); //Project Deadline chart
                    $data_PDDC = json_decode($this->createProjectDocumentDeadlineChart($request)); //Project Document Deadline chart
                }
//                dd($data_UL, $data_PD);
                // misising Project Sponsor, Project Completed, Document Provided, Tender Performance, Techical Performance, Complete Surveym, Survey Information
                $titleSummary = $request->summary_type == 'managerOverview' ? "Managers Overview - Asbestos" : "Directors Overview - Asbestos";
                //for render html
                $html = view('summary_pdf_template.directors_and_managers_overview', [
                    'isPDF'=> true,
                    'summary_type'=> $request->summary_type,
                    'data_MS' => $data_MS,
                    'data_RIP' => $data_RIP,
                    'data_PD' => $data_PD,
                    'data_UL' => $data_UL,
                    'data_PS' => $data_PS,
                    'data_PDC' => $data_PDC,
                    'data_PDDC' => $data_PDDC,
                    'titleSummary'=>$titleSummary,
                    'chartFixedWidth' => 1100
                ])->render();

                $file = \Storage::put( $request->file_path, $html);

                return view('summary_pdf_template.directors_and_managers_overview', [
                    'isPDF'=> false,
                    'summary_type'=> $request->summary_type,
                    'data_MS' => $data_MS,
                    'data_RIP' => $data_RIP,
                    'data_PD' => $data_PD,
                    'data_UL' => $data_UL,
                    'data_PS' => $data_PS,
                    'data_PDC' => $data_PDC,
                    'data_PDDC' => $data_PDDC,
                    'titleSummary'=>$titleSummary,
                    'chartFixedWidth' => 1100
                ]);
            } else if($chart_type == 'contractor_kpi') {
//                return abort(404);
                $list_contractor_ids = array_filter(explode(",",$request->contractors));
                $contractor_name  = [];
                $surveyInformationCategories = ['DO', 'CAFS', 'SPIR', 'RPS'];
                $surveyInformationLegendInfo = [
                    'Dropdown Override', 'Cross Area/Floor Sample Links', 'Sample per Item Ratio', 'Rejections Per Survey'
                ];
                if(count($list_contractor_ids)){
                    $data_PC = $data_DP = $data_TP = $data_CS = $data_SI = [];
                    foreach ($list_contractor_ids as $key => $contractor_id){
                        $contractor_name[] = Client::where('id', $contractor_id)->first()->name;
                        $data_PC[] = json_decode($this->createProjectCompleteChart($request, $contractor_id));//Project Complete
                        $data_DP[] = json_decode($this->createDocumentProvidedChart($request, $contractor_id));//Document Provided
                        $data_TP[] = json_decode($this->createTenderPerformanceChart($request, $contractor_id));//Tender Performance
////                        $data_TECHP[] = json_decode($this->createTechnicalPerformanceChart($request, $contractor_id));CONTRACTOR_DOC_CATEGORY //TECHNICAL Performance => merge into GSK/Contractor should remove?
                        $data_CS[] = json_decode($this->createCompleteSurveyChart($request, $contractor_id));//COMPLETED SURVEYS
                        $data_SI[] = json_decode($this->createSurveyInformationChart($request, $contractor_id, $surveyInformationLegendInfo));//SURVEY INFORMATION
                    }
                    $countCharts = count($list_contractor_ids);
                    $maxIndexCharts = $countCharts - 1;
                    $chartFixedWidth = 1100;
                    $firstPosition = 0;
                    $chartPadding = 300;
                    switch ($countCharts) {
                        case 1:
                            $firstPosition = 500;
                            $chartSIWidth = $chartFixedWidth;
                            break;
                        case 2:
                            $firstPosition = 300;
                            $chartPadding = 400;
                            $chartSIWidth = 500;
                            break;
                        case 3:
                            $firstPosition = 150;
                            $chartSIWidth = 350;
                            break;
                    }

                    $data_PC = $this->createChartForLegend($data_PC, 'Total Projects Completed', $firstPosition, $chartPadding);
                    $data_DP = $this->createChartForLegend($data_DP, 'Total Documents', $firstPosition, $chartPadding);
                    $data_TP = $this->createChartForLegend($data_TP, 'Total Tender Performance', $firstPosition, $chartPadding);
                    $data_CS = $this->createChartForLegend($data_CS, 'Total Completed Surveys', $firstPosition, $chartPadding);
                    $data_SI = $this->createColumnChartForLegend($data_SI, 'Total', $firstPosition, $chartPadding);
                    $total_SI = $this->countTotalSurveyInfo($data_SI);
                    //for render html
                    $html = view('summary_pdf_template.contractor_kpi', [
                        'isPDF'=> true,
                        'summary_type'=> $request->summary_type,
                        'data_PC' => $data_PC,
                        'data_DP' => $data_DP,
                        'data_TP' => $data_TP,
                        'data_CS' => $data_CS,
                        'data_SI' => $data_SI,
                        'total_SI' => $total_SI,
                        'list_contractor_ids' => $list_contractor_ids,
                        'contractor_name' => $contractor_name,
                        'chartFixedWidth' => $chartFixedWidth,
                        'firstPosition' => $firstPosition,
                        'chartSIWidth' => $chartSIWidth,
                        'chartPadding' => $chartPadding,
                        'maxIndexCharts' => $maxIndexCharts,
                        'countCharts' => $countCharts,
                        'surveyInformationCategories' => $surveyInformationCategories,
                        'surveyInformationLegendInfo' => $surveyInformationLegendInfo
                    ])->render();

                    $file = \Storage::put( $request->file_path, $html);

                    return view('summary_pdf_template.contractor_kpi', [
                        'isPDF'=> false,
                        'summary_type'=> $request->summary_type,
                        'data_PC' => $data_PC,
                        'data_DP' => $data_DP,
                        'data_TP' => $data_TP,
                        'data_CS' => $data_CS,
                        'data_SI' => $data_SI,
                        'total_SI' => $total_SI,
                        'list_contractor_ids' => $list_contractor_ids,
                        'contractor_name' => $contractor_name,
                        'chartFixedWidth' => $chartFixedWidth,
                        'firstPosition' => $firstPosition,
                        'chartSIWidth' => $chartSIWidth,
                        'chartPadding' => $chartPadding,
                        'maxIndexCharts' => $maxIndexCharts,
                        'countCharts' => $countCharts,
                        'surveyInformationCategories' => $surveyInformationCategories,
                        'surveyInformationLegendInfo' => $surveyInformationLegendInfo,
                    ]);
                }
            }
        }
    }
    // custome data reuturn must be format
    public function createChartForLegend($data, $name, $firstPosition, $chartPadding){
        //$arr_chart_type : [type] => pie [name] => Total Projects [center] => Array ( [0] => 150 [1] => ) [showInLegend]
        $result = [];
        if(count($data)){
            $last_chart_key = count($data) - 1;
            $tem_arr = [];
            $last_chart = NULL;

            foreach ($data as $key => $chart){
                $return = [];
                // key = 1 is data
                if(isset($chart[1])){
                    $chart_data = json_decode($chart[1]);
//                    dd($chart_data->data);
                    $return['data'] = $chart_data->data;
                    $return['name'] = $name;
                    $return['type'] = $chart_data->type;
                    $return['center'] = [$firstPosition + $key * $chartPadding, NULL]; // to display text Total : 3333
                    $return['showInLegend'] = $key == $last_chart_key ? true : false; // only last chart show legend
                    if($last_chart_key == $key){
                        $last_chart = $chart_data;
                    }
                    if(isset($chart_data->data)){
                        foreach ($chart_data->data as $k => $legend){ // data for each column
                            if(!array_key_exists($legend->name, $tem_arr)){
                                $tem_arr[$legend->name] = 0;
                            }
                            $tem_arr[$legend->name] += $legend->total;
                        }
                    }
                }
                $result[] = $return;
            }

            if(count($tem_arr)){//set total for last chart
                foreach ($tem_arr as $key => $total){
                    if(isset($last_chart)){
                        foreach ($last_chart->data as $k => $new_data){
                            if($new_data->name == $key){
                                $last_chart->data[$k]->total = $total;
                            }
                        }
                    }
                }
            }
//            $data[$last_chart_key][1] = json_encode($last_chart);
            $result[$last_chart_key]['data'] = $last_chart->data;
        }
        return $result;
    }

    // custome data reuturn must be format
    public function createColumnChartForLegend($data){
        //$arr_chart_type : [type] => pie [name] => Total Projects [center] => Array ( [0] => 150 [1] => ) [showInLegend]
        $result = $tem_arr = [];
        if(count($data)){
            $last_chart_key = count($data) - 1;
            $last_chart = NULL;

            foreach ($data as $key => $chart){
                $return = [];
                $return = json_decode($chart[1]);
                foreach ($return as $k => $legend){
                    if(!array_key_exists($legend->name, $tem_arr)){
                        $tem_arr[$legend->name] = 0;
                    }
                    $tem_arr[$legend->name] += $legend->total;

                    if($last_chart_key == $key){
                        if(array_key_exists($return[$k]->name, $tem_arr)){
                            $return[$k]->total = $tem_arr[$return[$k]->name];
                        }
                    }
                }
                $result[] = $return;
            }
        }
        return $result;
    }

    public function countTotalSurveyInfo($data){
        $return = [];
        if(count($data)){
            foreach ($data as $chart){
                foreach ($chart as  $item){
                    foreach ($item->data as $k => $column){
                        if(!array_key_exists($k, $return)){
                            $return[$k] = 0;
                        }
                        $return[$k] += $column;
                    }
                }
            }
        }
        return $return;
    }

    private function createMSChart($request){
        $zone = isset($request->zone) && $request->zone > 0 ? $request->zone : NULL;
//        dd($zone);
        $title = "Management Surveys";
        $yAxis = "Pie";
        $xAxis = 'Total properties';
        $ms_risks_data = $ms_temp = [];
        $ms_risks = PropertyType::where('order','>',0)->get();
        $ms_total = CommonHelpers::createChartData('Total');//ms total only for legend so no need y value and total for total will caculate at the end
        // caculate total record each type
        $ms_missing_data = $this->chartRepository->getDataMSMissingChart($zone);
        $ms_complete_data = $this->chartRepository->getDataMSComplete($zone);
        $ms_decommissioned_data = $this->chartRepository->getDataMSDecommissioned($zone);
        $ms_demolished_data = $this->chartRepository->getDataMSDemolished($zone);
        // create data for chart
        $ms_missing = CommonHelpers::createChartData('Missing', $ms_missing_data, $ms_missing_data,'#C1C4C2');
        $ms_missing_data > 0 ? $ms_temp[] = $ms_missing : '';//remove data is empty
        $ms_complete = CommonHelpers::createChartData('Complete', $ms_complete_data, $ms_complete_data,'#FF9500');
        $ms_complete_data > 0 ? $ms_temp[] =  $ms_complete : '';
        $ms_decommissioned = CommonHelpers::createChartData('No Longer under Management', $ms_decommissioned_data, $ms_decommissioned_data,'#8AB3D4');
        $ms_decommissioned_data > 0 ? $ms_temp[] =  $ms_decommissioned : '';//remove data is empty
        $ms_demolished = CommonHelpers::createChartData('Demolished', $ms_demolished_data, $ms_demolished_data,'#0084CC');
        $ms_demolished_data > 0 ? $ms_temp[] = $ms_demolished : '';//remove data is empty
        if(!$ms_risks->isEmpty()){
            foreach ($ms_risks as $ms){
                $data_ms = $this->chartRepository->getDataMSRiskType($zone, $ms->id);
                if($data_ms > 0){
                    // remove data has no value
                    $ms_risks_data[] = CommonHelpers::createChartData($ms->code, $data_ms, $data_ms, $ms->color);
                }
            }
        }
        $ms_total['total'] = $this->countTotalData([$ms_missing, $ms_complete, $ms_decommissioned, $ms_demolished, $ms_risks_data]);
        $ms_total['total'] += $this->countTotalData($ms_risks_data);
//        dd($ms_missing, $ms_complete, $ms_decommissioned, $ms_demolished, $ms_risks_data);
        // total will be last one and not apprear in chart only in legend
        $data = array_merge($ms_temp, $ms_risks_data, [$ms_total]);
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $data];
//        dd(json_encode([$xAxis, json_encode($data), $yAxis, $title]));
        return json_encode([$xAxis, json_encode($data_return), $yAxis, $title]);

    }

    public function createNAChart($request){
        $title = "Communal Survey Programme";
        $yAxis = "Total Properties";
        $xAxis = [];//list group name
        $zone = isset($request->zone) && $request->zone > 0 ? $request->zone : NULL;
        if($zone){
            $all_groups = Zone::where('id',$zone)->get();// get to foreach, first will not work
        } else {
            $all_groups = Zone::all();
        }

        $inacc_locations_total = $inacc_items_total = $inacc_void_total = 0;
        $inacc_locations_data = $inacc_items_data = $inacc_void_data = [];
        $total = CommonHelpers::createChartData('Total');
        foreach ($all_groups as $group){

            $total_locations = $this->chartRepository->getDataNAInaccessibleLocations($group->id);
            $total_items = $this->chartRepository->getDataNAInaccessibleItems($group->id);
            $total_voids = $this->chartRepository->getDataNAInaccessibleVoids($group->id);
            //for total in legend
            $inacc_locations_total += $total_locations;
            $inacc_items_total += $total_items;
            $inacc_void_total += $total_voids;
            //for data for each group
            $inacc_locations_data[] = $total_locations;
            $inacc_items_data[] = $total_items;
            $inacc_void_data[] = $total_voids;

            $xAxis[] = $group->zone_name;
        }
        $locations_data = CommonHelpers::createChartData('Inaccessible Room/locations', $inacc_locations_total, 0,'#c0392b',$inacc_locations_data);
        $items_data = CommonHelpers::createChartData('Inaccessible Items', $inacc_items_total, 0,'#f39c12',$inacc_items_data);
        $void_data = CommonHelpers::createChartData('Inaccessible Void', $inacc_void_total, 0,'#0084CC',$inacc_void_data);
        $total['total'] = $this->countTotalData([$locations_data, $items_data, $void_data]);
        return json_encode([$xAxis, json_encode([$locations_data, $items_data, $void_data, $total]), $yAxis, $title]);
    }

    //for reinspection chart
    public function createReinspectionChart($request){
        $zone_id = isset($request->zone) && $request->zone > 0 ? $request->zone : NULL;
        $title = "Re-inspection Programme";
        $yAxis = "Percentage";
        $xAxis = [] ; // will be list properties if select a zone, be list groups if select over view
//        $total_critical = $total_critical =$total_critical =$total_critical =$total_critical =$total_critical =$total_critical = 0;
        $critical_data = CommonHelpers::createChartData('Critical (<15)', 0, 0,'#FF3F2B');
        $urgent_data = CommonHelpers::createChartData('Urgent (15-30)', 0, 0,'#FB7500');
        $important_data = CommonHelpers::createChartData('Important (31-60)', 0, 0,'#FDC304');
        $attention_data = CommonHelpers::createChartData('Attention (61-120)', 0, 0,'#0080B3');
        $deadline_data = CommonHelpers::createChartData('Deadline (120>)', 0, 0,'#C1C4C2');
        $total = CommonHelpers::createChartData('Total');

        if($zone_id){
            $list_property_rein = $this->chartRepository->getPropertyRein($zone_id);
            if(!$list_property_rein->isEmpty()){
                // for a zone, list property data = day remain, total in data if not null = 1,
                // for list zone, data count by date type means one property will be plus one
                // => foreach property then foreach date type and check condition
                foreach ($list_property_rein as $property){
                    //X row will list zone property block for a zone
                    $xAxis[] = $property->pblock;
                    $count_critical = $count_urgent = $count_important = $count_attention = $count_deadline = 0;
                    if($property->last_day < 15){
                        $count_critical = $property->last_day;
                    } else if($property->last_day >= 15 && $property->last_day <= 30){
                        $count_urgent = $property->last_day;
                    } else if($property->last_day >= 31 && $property->last_day <= 60){
                        $count_important = $property->last_day;
                    } else if($property->last_day >= 61 && $property->last_day <= 120){
                        $count_attention = $property->last_day;
                    } else if($property->last_day >= 120){
                        $count_deadline = $property->last_day;
                    }
                    $critical_data = CommonHelpers::setChartData($critical_data, $count_critical, NULL, $count_critical);
                    $urgent_data = CommonHelpers::setChartData($urgent_data, $count_urgent, NULL, $count_urgent);
                    $important_data = CommonHelpers::setChartData($important_data, $count_important, NULL, $count_important);
                    $attention_data = CommonHelpers::setChartData($attention_data, $count_attention, NULL, $count_attention);
                    $deadline_data = CommonHelpers::setChartData($deadline_data, $count_deadline, NULL, $count_deadline);
                    // todo error dropdown group when select inspec and then select no accesss .. chart
                }
//                dd($critical_data, $urgent_data,$important_data,$attention_data,$deadline_data);
            } else {
                //for empty data
                $critical_data = CommonHelpers::createChartData('Critical (<15)', 0, 0,'#FF3F2B', [0]);
                $urgent_data = CommonHelpers::createChartData('Urgent (15-30)', 0, 0,'#FB7500', [0]);
                $important_data = CommonHelpers::createChartData('Important (31-60)', 0, 0,'#FDC304', [0]);
                $attention_data = CommonHelpers::createChartData('Attention (61-120)', 0, 0,'#0080B3', [0]);
                $deadline_data = CommonHelpers::createChartData('Deadline (120>)', 0, 0,'#C1C4C2', [0]);
                $xAxis[] = 'No Data';
            }
        } else {
            $list_zone = Zone::all();
            if(!$list_zone->isEmpty()){
                $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL;
                foreach ($list_zone as $zone){
                    $xAxis[] = $zone->zone_name;
                    //date_data is for summary chart only
                    $count_critical = $this->chartRepository->getPropertyReinGroup($zone->id, 1, $date_data);
                    $count_urgent = $this->chartRepository->getPropertyReinGroup($zone->id, 2, $date_data);
                    $count_important = $this->chartRepository->getPropertyReinGroup($zone->id, 3, $date_data);
                    $count_attention = $this->chartRepository->getPropertyReinGroup($zone->id, 4, $date_data);
                    $count_deadline = $this->chartRepository->getPropertyReinGroup($zone->id, 5, $date_data);
                    //set data
                    $critical_data = CommonHelpers::setChartData($critical_data, $count_critical, NULL);
                    $urgent_data = CommonHelpers::setChartData($urgent_data, $count_urgent, NULL);
                    $important_data = CommonHelpers::setChartData($important_data, $count_important, NULL);
                    $attention_data = CommonHelpers::setChartData($attention_data, $count_attention, NULL);
                    $deadline_data = CommonHelpers::setChartData($deadline_data, $count_deadline, NULL);
                }
            }
        }
        return $this->responseData([$critical_data, $urgent_data, $important_data, $attention_data, $deadline_data], $total, $xAxis, $yAxis, $title);
    }

    public function createProjectDeadlineChart($request){
        $title = "Project Deadlines";
        $yAxis = "Percentage";
        $xAxis = [] ;
        $project_deadlines = [];
        $project_deadlines[] = CommonHelpers::createChartData('Critical (<15)', 0, [],'#FF3F2B', [], 1);
        $project_deadlines[] = CommonHelpers::createChartData('Urgent (15-30)', 0, [],'#FB7500', [], 2);
        $project_deadlines[] = CommonHelpers::createChartData('Important (31-60)', 0, [],'#FDC304', [], 3);
        $project_deadlines[] = CommonHelpers::createChartData('Attention (61-120)', 0, [],'#0080B3', [], 4);
        $project_deadlines[] = CommonHelpers::createChartData('Deadline (120>)', 0, [],'#C1C4C2', [], 5);
        $total = CommonHelpers::createChartData('Total');

        $list_lead = $this->chartRepository->getDataLeaderPDChart();
        if(!$list_lead->isEmpty()){
            foreach ($list_lead as $lead){
                $xAxis[] = $lead->userName ?? 'Unknown' . $lead->lead_key;
                foreach ($project_deadlines as $key => $pd){
                    $total_data = $this->chartRepository->getPropertyPropjectDeadlinesPD($lead->lead_key, $pd['other']);
//                    var_dump($total_data);
                    $project_deadlines[$key] = CommonHelpers::setChartData($pd, $total_data);
                }
            }
        }
        return $this->responseData($project_deadlines, $total, $xAxis, $yAxis, $title);
    }

    public function createProductDebrisTypeChart($request){
        $title = "Product/debris Type";
        $yAxis = "Pie";
        $xAxis = 'Total items';
        $itemPDTypes = [];
        $itemPDTypes[] = CommonHelpers::createChartData('Asbestos Board(s)', 0, 0,'#00AF61', [], '( d.dropdown_data_item_parent_id IN(228, 214) OR d.dropdown_data_item_id IN(215,216,217,218,219,220,221,222,223,224,225,226,683,684,728,729,730,731,1848,229,230,231,232,233,732,733,734,735,736,737,738))');// parent 228, 214
        $itemPDTypes[] = CommonHelpers::createChartData('Cement Product(s)', 0, 0,'#FDC304', [], '(d.dropdown_data_item_parent_id = 290 OR d.dropdown_data_item_id IN(291,292,293,294,295,296,297,298,299,300,301,302,303,304,305,306,307,308,309,310,689,690,759,1849))');
        $itemPDTypes[] = CommonHelpers::createChartData('Gaskets', 0, 0,'#A953AC', [], '((d.dropdown_data_item_parent_id = 1952) OR d.dropdown_data_item_id IN(254,255,256,257,258,259,260))');
        $itemPDTypes[] = CommonHelpers::createChartData('Insulation', 0, 0,'#FB7500', [], '(d.dropdown_data_item_parent_id = 175 OR d.dropdown_data_item_id IN(176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,681,723,724,725))');
        $itemPDTypes[] = CommonHelpers::createChartData('Other Encapsulated Materials', 0, 0,'#FF3F2B', [], '(d.dropdown_data_item_parent_id IN (313, 322, 354, 358) OR d.dropdown_data_item_id IN(314,315,316,317,318,319,320,323,324,325,326,327,328,329,330,331,332,333,334,335,336,355,356,359,360,361,362,363,691,692,760,761,762,763,764,765,766,767,768,769,770,771,772,774,775,776,1846))');
        $itemPDTypes[] = CommonHelpers::createChartData('Paper/felt/cardboard', 0, 0,'#0080B3', [], '(d.dropdown_data_item_parent_id = 235 OR d.dropdown_data_item_id IN(236,237,238,239,240,241,242,243,244,245,685,739,740,741,742,743,744,745,1847))');
        $itemPDTypes[] = CommonHelpers::createChartData('Sprayed Coating(s)', 0, 0,'#00A182', [], '(d.dropdown_data_item_parent_id = 195 OR d.dropdown_data_item_id IN(201,202,203,204,206,207,208,209,210,211,682,726,727))');
        $itemPDTypes[] = CommonHelpers::createChartData('Textile(s)', 0, 0,'#FF0000', [], '(d.dropdown_data_item_parent_id IN (250, 262, 274, 280, 248, 249) OR d.dropdown_data_item_id IN (251,252,686,687,688,746,263,264,265,266,267,268,269,270,271,272,747,748,749,275,276,277,278,750,751,752,753,754,755,756,757,281,282,283,284,285,286,289,758,248, 249))'); // 248, 249 is old value
        $itemPDTypes[] = CommonHelpers::createChartData('Textured Coating', 0, 0,'#22adbf', [], '(d.dropdown_data_item_parent_id IN (338) OR d.dropdown_data_item_id IN(339,341,342,343,344,345,346,347,348,349,350,351,352,704))');
        $itemPDTypes[] = CommonHelpers::createChartData('Other', 0, 0,'#00D070', [], 'd.dropdown_data_item_id = 366');
        $total = CommonHelpers::createChartData('Total');
        $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL;
        foreach($itemPDTypes as $key => $val){
            $total_data = $this->chartRepository->getItemProductDerisTypes($val['other'], $date_data);
            $itemPDTypes[$key] = CommonHelpers::setChartData($val, null, $total_data);
        }
//        dd(123);
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $itemPDTypes];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);

    }

    public function createUserLoginChart($request){
        $title = "User Login";
        $yAxis = "Pie";
        $xAxis = 'Total login';
        $total = CommonHelpers::createChartData('Total');

        $user_departments = Department::all();
        // default color for each department
        $userLoginColors = ["#00ff80", "#C6C5BE", "#FB7500", "#0098D9", "#00A182", "#0071fb", "#dd0e9d", "#FDC304", "#0080B3", "#FF3F2B", "#c0392b", "#f39c12", "#2980b9", "#2c3e50", "#A953AC", "#00D070", "#00AF61"];
        $userLogin = [];
        $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL; // for summary chart

        foreach ($user_departments as $k => $department){
            $new_chart_element = CommonHelpers::createChartData($department->name, 0, 0,NULL, []);
            $total_login = $this->chartRepository->getTotalLoginDepartment($department->id, $date_data);
            $userLogin[$k] = CommonHelpers::setChartData($new_chart_element, null, $total_login);
        }
        $total_constractor_login = $this->chartRepository->getTotalLoginConstractor($date_data);
        $contractorsLogin = CommonHelpers::createChartData('Contractors', $total_constractor_login, $total_constractor_login,'#FF4D4B');
        $userLogin[] = $contractorsLogin;
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $userLogin];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createProjectDocumentDeadlineChart(){
        $title = "Project Document Deadlines";
        $yAxis = "Percentage";

        $xAxis = [] ;
        $project_docment_deadlines = [];
        $project_docment_deadlines[] = CommonHelpers::createChartData('Critical (<15)', 0, [],'#FF3F2B', [], 1);
        $project_docment_deadlines[] = CommonHelpers::createChartData('Urgent (15-30)', 0, [],'#FB7500', [], 2);
        $project_docment_deadlines[] = CommonHelpers::createChartData('Important (31-60)', 0, [],'#FDC304', [], 3);
        $project_docment_deadlines[] = CommonHelpers::createChartData('Attention (61-120)', 0, [],'#0080B3', [], 4);
        $project_docment_deadlines[] = CommonHelpers::createChartData('Deadline (120>)', 0, [],'#C1C4C2', [], 5);
        $total = CommonHelpers::createChartData('Total');

        $list_lead = $this->chartRepository->getDataLeaderPDDChart();
        if(!$list_lead->isEmpty()){
            foreach ($list_lead as $lead){
                $xAxis[] = $lead->userName ?? 'ID' . $lead->lead_key;
                foreach ($project_docment_deadlines as $key => $pd){
                    $total_data = $this->chartRepository->getPropjectDocmentDeadlines($lead->lead_key, $pd['other']);
//                    var_dump($total_data);
                    $project_docment_deadlines[$key] = CommonHelpers::setChartData($pd, $total_data);
                }
            }
        }
        return $this->responseData($project_docment_deadlines, $total, $xAxis, $yAxis, $title);
    }

    public function createDecommissionItemChart($request){
        $zone_id = isset($request->zone) && $request->zone > 0 ? $request->zone : NULL;
        $year = isset($request->year) && $request->year > 0 ? $request->year : 0;
        $zone_color = ['#c0392b','#f39c12','#2980b9','#2c3e50','#C1C4C2','#FF9500','#0084CC','#8AB3D4','#50A850','#FF7E3D','#FFFF25','#F86300',
            '#0080B3','#FDC304','#FF3F2B','#FB7500', '#784e29','#6eb55b','#32588a','#502885','#742885','#9090ab','#470d4f','#a086a3','#c9d126','#042b12',
        '#2c8f7b','#4affda','#065278','#911021','#ad6521','#eeff00','#292994','#a125e8','#521846'];
        $title = "Decommissioned Items";
        $yAxis = "Total Decommissioned Items";
        $xAxis = ['Quarter 1','Quarter 2','Quarter 3','Quarter 4'];//default X-asis will be listed as 4 quarter
        $items_decommission = [];
        $total = CommonHelpers::createChartData('Total');
        if($zone_id){
            $zones = Zone::where('id',$zone_id)->get();
        } else {
            $zones = Zone::all();
        }
        //having error when get date start and
        foreach ($zones as $k => $zone){
            $new_column = CommonHelpers::createChartData($zone->zone_name, 0, 0,$zone_color[$k], []);
            if($year > 0){
                for($i=1;$i<=4;$i++){
                    $date = CommonHelpers::get_dates_of_quarter($i, $year, 'Y-m-d H:i:s');
                    $total_item_decommission = $this->chartRepository->getItemDecommissonItem(strtotime($date['start']), strtotime($date['end']), $zone->id);
                    $new_column = CommonHelpers::setChartData($new_column, $total_item_decommission);
                }
                $items_decommission[] = $new_column;
            } else {
                $current_year =  date("Y");
                $start_year = 2018;
                $new_xAxis = [];
                for($i = $start_year ; $i <= $current_year ; $i++){
                    for($i2=1;$i2<=4;$i2++){
                        $new_xAxis[] = ['Q'.$i2.' '.$i];// ['Q1 2018', 'Q2 2018', ...]
                        $date = CommonHelpers::get_dates_of_quarter($i2, $i, 'Y-m-d H:i:s');
                        $total_item_decommission = $this->chartRepository->getItemDecommissonItem(strtotime($date['start']), strtotime($date['end']), $zone->id);
                        $new_column = CommonHelpers::setChartData($new_column, $total_item_decommission);
                    }
                }
                $items_decommission[] = $new_column;
                $xAxis = $new_xAxis;
            }
        }
        return $this->responseData($items_decommission, $total, $xAxis, $yAxis, $title);
    }

    public function createDecommissionProductChart($request){
        // Q3 the data from GSK live is dif with new framework cause all item in Q2 has decommissoned and currently, using DB from 11-9
        $zone_id = isset($request->zone) && $request->zone > 0 ? $request->zone : NULL;
        $year = isset($request->year) && $request->year > 0 ? $request->year : 0;
        $title = "Decommissioned Product Type";
        $yAxis = "Total Decommissioned Items";
        $xAxis = ['Quarter 1','Quarter 2','Quarter 3','Quarter 4'];
        $total = CommonHelpers::createChartData('Total');
        $decommissionProducts = [];
        $decommissionProducts[] = CommonHelpers::createChartData('Asbestos Board(s)', 0, 0,'#00AF61', [], '( d.dropdown_data_item_parent_id IN(228, 214) OR d.dropdown_data_item_id IN(215,216,217,218,219,220,221,222,223,224,225,226,683,684,728,729,730,731,1848,229,230,231,232,233,732,733,734,735,736,737,738))');
        $decommissionProducts[] = CommonHelpers::createChartData('Cement Product(s)', 0, 0,'#FDC304', [], '(d.dropdown_data_item_parent_id = 290 OR d.dropdown_data_item_id IN(291,292,293,294,295,296,297,298,299,300,301,302,303,304,305,306,307,308,309,310,689,690,759,1849))');
        $decommissionProducts[] = CommonHelpers::createChartData('Gaskets', 0, 0,'#A953AC', [], '((d.dropdown_data_item_parent_id = 1952) OR d.dropdown_data_item_id IN(254,255,256,257,258,259,260))');
        $decommissionProducts[] = CommonHelpers::createChartData('Insulation', 0, 0,'#FB7500', [], '(d.dropdown_data_item_parent_id = 175 OR d.dropdown_data_item_id IN(176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,681,723,724,725))');
        $decommissionProducts[] = CommonHelpers::createChartData('Other Encapsulated Materials', 0, 0,'#FF3F2B', [], '(d.dropdown_data_item_parent_id IN (313, 322, 354, 358) OR d.dropdown_data_item_id IN(314,315,316,317,318,319,320,323,324,325,326,327,328,329,330,331,332,333,334,335,336,355,356,359,360,361,362,363,691,692,760,761,762,763,764,765,766,767,768,769,770,771,772,774,775,776,1846))');
        $decommissionProducts[] = CommonHelpers::createChartData('Paper/felt/cardboard', 0, 0,'#0080B3', [], '(d.dropdown_data_item_parent_id = 235 OR d.dropdown_data_item_id IN(236,237,238,239,240,241,242,243,244,245,685,739,740,741,742,743,744,745,1847))');
        $decommissionProducts[] = CommonHelpers::createChartData('Sprayed Coating(s)', 0, 0,'#00A182', [], '(d.dropdown_data_item_parent_id = 195 OR d.dropdown_data_item_id IN(201,202,203,204,206,207,208,209,210,211,682,726,727))');
        $decommissionProducts[] = CommonHelpers::createChartData('Textile(s)', 0, 0,'#FF0000', [], '(d.dropdown_data_item_parent_id IN (250, 262, 274, 280, 248, 249) OR d.dropdown_data_item_id IN (251,252,686,687,688,746,263,264,265,266,267,268,269,270,271,272,747,748,749,275,276,277,278,750,751,752,753,754,755,756,757,281,282,283,284,285,286,289,758,248, 249))');
        $decommissionProducts[] = CommonHelpers::createChartData('Textured Coating', 0, 0,'#22adbf', [], '(d.dropdown_data_item_parent_id IN (338) OR d.dropdown_data_item_id IN(339,341,342,343,344,345,346,347,348,349,350,351,352,704))');
        $decommissionProducts[] = CommonHelpers::createChartData('Other', 0, 0,'#00D070', [], 'd.dropdown_data_item_id = 366');
        foreach ($decommissionProducts as $k => $val){
            if($year > 0){
                for($i=1;$i<=4;$i++){
                    $date = CommonHelpers::get_dates_of_quarter($i, $year, 'Y-m-d H:i:s');
                    $total_data = $this->chartRepository->getItemDecommissonProductTypes(strtotime($date['start']), strtotime($date['end']), $zone_id, $val['other']);
                    $decommissionProducts[$k] = CommonHelpers::setChartData($decommissionProducts[$k], $total_data);
                }
            } else {
                $current_year =  date("Y");
                $start_year = 2018;
                $new_xAxis = [];
                for($i = $start_year ; $i <= $current_year ; $i++){
                    for($i2=1;$i2<=4;$i2++){
                        $new_xAxis[] = ['Q'.$i2.' '.$i];// ['Q1 2018', 'Q2 2018', ...]
                        $date = CommonHelpers::get_dates_of_quarter($i2, $i, 'Y-m-d H:i:s');
                        $total_data = $this->chartRepository->getItemDecommissonProductTypes(strtotime($date['start']), strtotime($date['end']), $zone_id, $val['other']);
                        $decommissionProducts[$k] = CommonHelpers::setChartData($decommissionProducts[$k], $total_data);
                    }
                }
                $xAxis = $new_xAxis;
            }
        }
        return $this->responseData($decommissionProducts, $total, $xAxis, $yAxis, $title);
    }

    public function createProjectSponsorChart($request){
        $title = "Project Sponsor";
        $yAxis = "Percentage";
        $xAxis = [] ;
        $projectSponsorColors = ["#FF3F2B", "#0080B3", "#00AF61", "#FB7500", '#C1C4C2', '#FDC304'];
        $project_sponsors = [];
        $total = CommonHelpers::createChartData('Total');

        $project_types = ProjectType::all();
        if(!$project_types->isEmpty()){
            foreach ($project_types as $key => $pt){
                $project_sponsors[] = CommonHelpers::createChartData($pt->description, 0, [],$projectSponsorColors[$key], [], $pt->id);
            }
        }
        if(count($project_sponsors)){
            $project_sponsors_data = ProjectSponsor::all();
            $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL; // for summary chart
            if(!$project_sponsors_data->isEmpty()){
                foreach ($project_sponsors_data as $ps){
                    $xAxis[] = $ps->description;
                    foreach ($project_sponsors as $key => $val ){
                        $total_data = $this->chartRepository->getProjectSponsorData($val['other'], $ps->id, $date_data);
                        $project_sponsors[$key] = CommonHelpers::setChartData($val, $total_data);
                    }
                }
            }
        }
        return $this->responseData($project_sponsors, $total, $xAxis, $yAxis, $title);
    }

    public function createProjectCompleteChart($request, $contractor_id){
        $title = "Total Projects Completed";
        $yAxis = "Pie";
        $xAxis = [] ;
//        $projectSponsorColors = ["#FF3F2B", "#0080B3", "#00AF61", "#FB7500", '#C1C4C2', '#FDC304'];
        $project_complete = [];
        $total = CommonHelpers::createChartData('Total Projects Completed');

        $project_types = ProjectType::all();
        $project_completes = [];
        if(!$project_types->isEmpty()){
            foreach ($project_types as $key => $pt){
                $xAxis[] = $pt->description;
                $project_completes[] = CommonHelpers::createChartData($pt->description, 0, [],"", [], $pt->id);
            }
        }
        $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL; // for summary chart
        foreach ($project_completes as $key => $val ){
            $total_data = $this->chartRepository->getProjectCompleteData($val['other'], $contractor_id, $date_data);
            $project_completes[$key] = CommonHelpers::setChartData($val, null,$total_data);
        }
        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $project_completes];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createDocumentProvidedChart($request, $contractor_id){
        $title = "Documents Provided";
        $yAxis = "Pie";
        $xAxis = [] ;
//        $projectSponsorColors = ["#FF3F2B", "#0080B3", "#00AF61", "#FB7500", '#C1C4C2', '#FDC304'];
        $documents_provided = [];
        $total = CommonHelpers::createChartData('Total Documents');
        //create example charts
        $documents_provided[] = CommonHelpers::createChartData('Tender Documents', 0, [],"", [], TENDER_DOC_CATEGORY);
        $documents_provided[] = CommonHelpers::createChartData('Contractor Documents', 0, [],"", [], CONTRACTOR_DOC_CATEGORY);
        $documents_provided[] = CommonHelpers::createChartData('GSK Documents', 0, [],"", [], GSK_DOC_CATEGORY);

        $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL;
        foreach ($documents_provided as $key => $doc){
            $xAxis[] = $doc['name'] ;
            $total_data = $this->chartRepository->getDocumentProvideData($doc['other'], $contractor_id, $date_data);
            $documents_provided[$key] = CommonHelpers::setChartData($doc, null,$total_data);
        }

        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $documents_provided];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createTenderPerformanceChart($request, $contractor_id){
        $title = "Tender Performance";
        $yAxis = "Pie";
        $xAxis = [] ;
        $tender_performance = [];
        $total = CommonHelpers::createChartData('Total Tender Performance');
        //create example charts
        $tender_performance[] = CommonHelpers::createChartData('No Quotation Provided', 0, [],"", [],"d.document_present = 0");
        $tender_performance[] = CommonHelpers::createChartData('Successful', 0, [],"", [],"d.status > 2");
        $tender_performance[] = CommonHelpers::createChartData('Unsuccessful', 0, [],"", [],"d.status < 3");

        $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL;
        foreach ($tender_performance as $key => $doc){
            $xAxis[] = $doc['name'] ;
            $total_data = $this->chartRepository->getTenderPerformanceData($doc['other'], $contractor_id, $date_data);
            $tender_performance[$key] = CommonHelpers::setChartData($doc, null,$total_data);
        }

        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $tender_performance];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

//    public function createTechnicalPerformanceChart($request, $contractor_id){
//        $title = "Technical Performance";
//        $yAxis = "Pie";
//        $xAxis = [] ;
//        $tender_performance = [];
//        $total = CommonHelpers::createChartData('Total Technical Performance');
//        //create example charts
//        $tender_performance[] = CommonHelpers::createChartData('Planning', 0, [],"", [],"d.category = 0");
//        $tender_performance[] = CommonHelpers::createChartData('Completion', 0, [],"", [],"d.category > 2");
//        $tender_performance[] = CommonHelpers::createChartData('Site Record', 0, [],"", [],"d.category < 3");
//        $tender_performance[] = CommonHelpers::createChartData('Pre-site', 0, [],"", [],"d.category < 3");
//
//        $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL;
//        foreach ($tender_performance as $key => $doc){
//            $xAxis[] = $doc['name'] ;
//            $total_data = $this->chartRepository->getTenderPerformanceData($doc['other'], $contractor_id, $date_data);
//            $tender_performance[$key] = CommonHelpers::setChartData($doc, null,$total_data);
//        }
//
//        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $tender_performance];
//        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
//    }

    public function createCompleteSurveyChart($request, $contractor_id){
        $title = "Completed Survey";
        $yAxis = "Pie";
        $xAxis = [] ;
        $complete_survey = [];
        $total = CommonHelpers::createChartData('Total Completed Surveys');
        //create example charts
        $complete_survey[] = CommonHelpers::createChartData('Management', 0, [],"", [],"s.survey_type = 1");
        $complete_survey[] = CommonHelpers::createChartData('Demolition', 0, [],"", [],"s.survey_type = 4");
        $complete_survey[] = CommonHelpers::createChartData('Project Specific', 0, [],"", [],"s.survey_type = 2");
        $complete_survey[] = CommonHelpers::createChartData('Re-inspectio', 0, [],"", [],"s.survey_type = 5");
        $complete_survey[] = CommonHelpers::createChartData('Refurbishment', 0, [],"", [],"s.survey_type = 3");

        $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL;
        foreach ($complete_survey as $key => $survey){
            $xAxis[] = $survey['name'] ;
            $total_data = $this->chartRepository->getCompleteSurveyData($survey['other'], $contractor_id, $date_data);
            $complete_survey[$key] = CommonHelpers::setChartData($survey, null,$total_data);
        }

        $data_return = ['type' => 'pie', 'name' => $xAxis, 'data' => $complete_survey];
        return $this->responseData($data_return, $total, $xAxis, $yAxis, $title);
    }

    public function createSurveyInformationChart($request, $contractor_id, $surveyInformationLegendInfo){

        $title = "Survey Information";
        $yAxis = "Percentage";
        $xAxis = [] ;
        $complete_survey = [];
        $complete_survey[] = CommonHelpers::createChartData('Management', 0, [],"", [], "s.survey_type = 1");
        $complete_survey[] = CommonHelpers::createChartData('Demolition', 0, [],"", [], "s.survey_type = 4");
        $complete_survey[] = CommonHelpers::createChartData('Project Specific', 0, [],"", [], "s.survey_type = 2");
        $complete_survey[] = CommonHelpers::createChartData('Re-inspectio', 0, [],"", [], "s.survey_type = 5");
        $complete_survey[] = CommonHelpers::createChartData('Refurbishment', 0, [],"", [], "s.survey_type = 3");
        $surveyInformationLegendInfo = [
            'DO', 'CAFS', 'SPIR', 'RPS'
//            'Dropdown Override', 'Cross Area/Floor Sample Links', 'Sample per Item Ratio', 'Rejections Per Survey'
        ];
        $total = CommonHelpers::createChartData('Total');

        $date_data = isset($request->data_date) ? $this->getDateQuater($request->data_date) : NULL;
        foreach ($surveyInformationLegendInfo as $si){
            $xAxis[] = $si ;
            foreach ($complete_survey as $key => $survey){
//                dd($this->chartRepository->getRPSSurveyInfoData($survey['other'], $contractor_id, $date_data));
                if($si == 'DO'){
                    $total_data = $this->chartRepository->getDOSurveyInfoData($survey['other'], $contractor_id, $date_data);
                } else if($si == 'CAFS'){
                    $total_data = $this->chartRepository->getCAFSSurveyInfoData($survey['other'], $contractor_id, $date_data);
                } else if($si == 'SPIR'){
                    $total_data = $this->chartRepository->getSPIRSurveyInfoData($survey['other'], $contractor_id, $date_data);
                } else if($si == 'RPS'){
                    $total_data = $this->chartRepository->getRPSSurveyInfoData($survey['other'], $contractor_id, $date_data);
                }
                $complete_survey[$key] = CommonHelpers::setChartData($survey, $total_data);
            }
        }
        return $this->responseData($complete_survey, $total, $xAxis, $yAxis, $title);
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
        return json_encode([$xAxis, json_encode($arr_data), $yAxis, $title]);
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

}
