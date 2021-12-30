<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\SurveyService;
use App\Services\ShineCompliance\ItemService;
use App\Jobs\SendClientEmailNotification;
use Carbon\Carbon;
use mikehaertl\pdftk\Pdf as PDFTK;
use Illuminate\Http\Request;


class GenerateSurveyPDFController extends Controller
{
    private $propertyService;
    private $homeService;
    private $surveyService;
    private $itemService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        SurveyService $surveyService,
        ItemService $itemService
    )
    {
        $this->surveyService = $surveyService;
        $this->itemService = $itemService;
    }

    /**
     * Show my organisation by id.
     *
     */

    public function viewPDF(Request $request){
        $type = $request->type;
        $id = $request->id;
        if($type == VIEW_SURVEY_PDF){
            return $this->viewPDFSurvey($id);
        } elseif ($type == VIEW_WORK_PDF) {
            return $this->viewPDFWork($id);
        }
        return false;
    }

    private function viewPDFSurvey($id){
        $publish_survey_pdf = $this->surveyService->getPublishedSurvey($id);

        //log audit
        if (!is_null(\Auth::user())) {
            $comment = (\Auth::user()->full_name ?? 'system')  . " view survey PDF "  . $publish_survey_pdf->name ?? '' . ' on '. optional($publish_survey_pdf->survey)->reference;
            \CommonHelpers::logAudit(SURVEY_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_VIEW, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
        }


        if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
            return response()->file($publish_survey_pdf->path);

        }
        return abort(404);
    }

    private function viewPDFWork($id){
        $publish_survey_pdf = $this->surveyService->getPublishedWorkRequest($id);
        if(!is_null($publish_survey_pdf)){
            //log audit
            if (!is_null(\Auth::user())) {
                $comment = (\Auth::user()->full_name ?? 'system')  . " view work request PDF "  . $publish_survey_pdf->name . ' on '. optional($publish_survey_pdf->survey)->reference;
                \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_VIEW, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
            }


            if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
                return response()->file($publish_survey_pdf->path);

            }
        }

        return abort(404);
    }
    public function publishSurveyPDF($survey_id, Request $request){

        $survey_draf = $request->has('survey_draf') ? true : false;
        try {

            //generate survey pdf for public survey
            \DB::beginTransaction();
            $relation = [
                'property','property.propertyInfo','property.clients',
                'property.propertyInfo.propertyInfoUser','property.clients.clientAddress','property.propertySurvey',
                'client', 'client.clientInfo',
                'project','publishedSurvey',
                'surveySetting','surveyInfo','surveyDate','surveyAnswer','surveyAnswer.dropdownQuestionData','surveyAnswer.dropdownAnswerData',
                'location','locationUndecommission.area','locationUndecommission.locationInfo','locationUndecommission.locationConstruction',
                'itemUndecommission', 'itemUndecommission.AsbestosTypeValue','itemUndecommission.asbestosTypeView','itemUndecommission.area','itemUndecommission.location','itemUndecommission.sample','itemUndecommission.productDebrisView','itemUndecommission.extentView','itemUndecommission.accessibilityVulnerabilityView',
                'itemUndecommission.additionalInformationView', 'itemUndecommission.licensedNonLicensedView','itemUndecommission.actionRecommendationView',
                'itemUndecommission.ItemNoAccessValue.ItemNoAccess',
                'itemUndecommission.ActionRecommendationValue','itemUndecommission.specificLocationView',
                'itemUndecommission.pasPrimary.getData','itemUndecommission.pasSecondary.getData',
                'itemUndecommission.pasLocation.getData','itemUndecommission.pasAccessibility.getData',
                'itemUndecommission.pasExtent.getData','itemUndecommission.pasNumber.getData',
                'itemUndecommission.pasHumanFrequency.getData','itemUndecommission.pasAverageTime.getData',
                'itemUndecommission.pasType.getData','itemUndecommission.pasMaintenanceFrequency.getData',
                'itemUndecommission.masProductDebris.getData','itemUndecommission.masDamage.getData',
                'itemUndecommission.masTreatment.getData','itemUndecommission.masAsbestos.getData'];

            $survey = $this->surveyService->getSurveyPublish($relation ,$survey_id);

            if (is_null($survey)) {
                return redirect()->back()->with('err', 'Survey does not exist');
            }
            $user = \Auth::user();
            $property_name = $survey->property->name ?? "";
            if ($survey_draf) {
                $comment = $user->first_name . " " . $user->last_name . " published Survey draft " . $survey->reference . " on property " . $property_name;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_DRAFT_PUBLISH, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                $message_response = "Survey Published as Draft Successfully!";
            } else {
                $time_now = Carbon::now()->timestamp;

                $publish_survey = $this->surveyService->updateSurvey($survey_id,['status' => PULISHED_SURVEY_STATUS, 'is_locked' => SURVEY_LOCKED]);
                $publish_date = $this->surveyService->updateSurveyDate($survey_id, ['published_date' =>   $time_now]);

                $survey->surveyDate->published_date = $time_now;
                // lock survey
                $this->surveyService->lockSurvey($survey_id);
                // missing send notification emails, update later + audit traitr
                //send mail queue
                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                    $survey->client->name,
                    SURVEY_APPROVED_EMAILTYPE,
                    $survey->lead_by,
                    $survey->property->property_reference ?? '',
                    $survey->property->name ?? '',
                    $survey->property->propertyInfo->pblock ?? '',
                    $survey->property,
                    $survey->reference,
                    \Auth::user()->clients->name
                ));

                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                    $survey->client->name,
                    SURVEY_APPROVED_EMAILTYPE,
                    $survey->second_lead_by,
                    $survey->property->property_reference ?? '',
                    $survey->property->name ?? '',
                    $survey->property->propertyInfo->pblock ?? '',
                    $survey->reference,
                    \Auth::user()->clients->name
                ));

                //log audit
                $comment = $user->first_name . " " . $user->last_name . "  published Survey " . $survey->reference . " on property " . $property_name;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_PUBLISH, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);

                $message_response = "Survey Published Successfully!";
            }
            $path_publish_survey = $this->generateSurveyPDF($survey);

            $sample_certificates_pdf = $this->surveyService->getSampleCertificateBySurvey('shineDocumentStorage',$survey_id);
            $plans = $this->surveyService->getSitePlanDocumentBySurveyPDF('shineDocumentStorage',$survey_id);

            $arr_pdf_merge = [$path_publish_survey];
            if(!$sample_certificates_pdf->isEmpty()){
                foreach ($sample_certificates_pdf as $sc){
                    if($sc->shineDocumentStorage){
                        $path = storage_path().'/app/'.$sc->shineDocumentStorage->path;
                        if(isset($sc->shineDocumentStorage) && file_exists($path)){
                            $file_info = pathinfo($path);
                            if($file_info['extension'] == 'pdf'){
                                $arr_pdf_merge[] = storage_path().'/app/'.$sc->shineDocumentStorage->path;
                            }
                        }
                    }
                }
            }
            if(!$plans->isEmpty()) {
                foreach ($plans as $p){
                    if($p->shineDocumentStorage){
                        $path = storage_path().'/app/'.$p->shineDocumentStorage->path;
                        if(isset($p->shineDocumentStorage) && file_exists($path)){
                            $file_info = pathinfo($path);
                            if($file_info['extension'] == 'pdf') {
                                $arr_pdf_merge[] = storage_path() . '/app/' . $p->shineDocumentStorage->path;
                            }
                        }
                    }
                }
            }

            $pdf_merger = new PDFTK($arr_pdf_merge, [
//                'useExec' => true,
            ]);

            $pdf_merger->allow('AllFeatures')      // Change permissions
            ->flatten() ;                // Merge form data into document (doesn't work well with UTF-8!)

            if(!$pdf_merger->saveAs($path_publish_survey)){
                $error = $pdf_merger->getError();
            }

            if($path_publish_survey){
                $publishSurvey = \CommonHelpers::successResponse($message_response);
            } else {
                $publishSurvey = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish survey. Please try again!');
            }
            \DB::commit();
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            $publishSurvey = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish survey. Please try again!');
        }

        if (isset($publishSurvey) and !is_null($publishSurvey)) {
            if ($publishSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $publishSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $publishSurvey['msg']);
            }
        }
    }

    private function generateSurveyPDF($survey){
        if($survey){
            $property_id = $survey->property_id ?? '';
            $category = $survey->id ?? '';
            $data_siteplan = $this->surveyService->getSitePlanDocumentbySurvey($property_id,$category);
            $survey_ref = $survey->reference ?? "";
            $survey_revision = count($survey->surveyPublishSurvey ?? []);
            //UPDATE ASBESTOS TYPE FOR INACCESSIBLE ITEMS:
            $this->itemService->updateAsbestosType($survey->id);
            $this->itemService->updateActionRecommentdationNoAcm($survey->id);
            $header = $survey_ref . "-" . date("d/m/Y") . "-" . date("H:i") . "-UID" . \Auth::user()->id;

            $sample_survey = $this->surveyService->getActiveSamplesTable($survey->property_id, $survey->id) ;
            $item_survey = $survey->itemUndecommission ?? '';
            // count item noacm and asbestos type != send to lab and other
            $count_item_tested = 0;
            $location_highrisk_accessible = [];
            $inaccessible_locations = $count_acm_item = $count_noacm_item = [];
            $high_risk_item = $inaccessible_items = $action_recommendation_items = $action_recommendation_removal_items = $acm_items = $item_r_and_d = $location_items = [];
            $location_inaccessible_void = $location_inaccessible_void_display = [];
            $samples = [];
            $survey_method_question_data = $survey->surveyAnswer;

            if(count($item_survey)){

                $item_survey = $this->itemService->sortItemSurvey($item_survey);
                foreach ($item_survey as $item){
                    // get location specific, MAS score need to convert to view later
                    $item->specific_location = $item->specificLocationView->specific_location ?? '';
                    //mas
                    $item->product_type = $item->masProductDebris->getData->score ?? 0;
                    $item->extend_damage = $item->masDamage->getData->score ?? 0;
                    $item->surface_treatment = $item->masTreatment->getData->score ?? 0;
                    $item->asbestos_fibre = $item->masAsbestos->getData->score ?? 0;
                    //pas
                    $pasPrimary = $item->pasPrimary->getData->score ?? 0;
                    $pasSecondary = $item->pasSecondary->getData->score ?? 0;
                    $pasLocation = $item->pasLocation->getData->score ?? 0;
                    $pasAccessibility = $item->pasAccessibility->getData->score ?? 0;
                    $pasExtent = $item->pasExtent->getData->score ?? 0;
                    $pasNumber = $item->pasNumber->getData->score ?? 0;
                    $pasHumanFrequency = $item->pasHumanFrequency->getData->score ?? 0;
                    $pasAverageTime = $item->pasAverageTime->getData->score ?? 0;
                    $pasType = $item->pasType->getData->score ?? 0;
                    $pasMaintenanceFrequency = $item->pasMaintenanceFrequency->getData->score ?? 0;
                    $item->primary = round(($pasPrimary + $pasSecondary)/2);
                    $item->likelihood =  round(($pasLocation + $pasAccessibility + $pasExtent)/3);
                    $item->human_exposure_potential = round(($pasNumber + $pasHumanFrequency + $pasAverageTime)/3);
                    $item->maintenance_activity = round(($pasType + $pasMaintenanceFrequency)/2);

                    $asbestos_type = $item->AsbestosTypeValue->dropdown_data_item_id ?? NULL;
                    // 394,395,396,397,398 is child of Other, 380 is send to lab
                    if($item->state != ITEM_NOACM_STATE && isset($asbestos_type) && !in_array($asbestos_type, [380, 394,395,396,397,398])){
                        $count_item_tested++;
                    }

                    if($item->state == ITEM_ACCESSIBLE_STATE){
                        if($item->total_mas_risk >= 10){
                            $high_risk_item[] = $item;
                            if (!in_array($item->location_id, $location_highrisk_accessible)){
                                $location_highrisk_accessible[] = $item->location_id;
                            }
                        }
                    }

                    if($item->state == ITEM_INACCESSIBLE_STATE){
                        $inaccessible_items[] = $item;
                    }

                    if(isset($item->ActionRecommendationValue) && isset($item->ActionRecommendationValue->dropdown_data_item_id) && $item->ActionRecommendationValue->dropdown_data_item_id > 0){
                        $action_recommendation_items[] = $item;
                        if(in_array($item->ActionRecommendationValue->dropdown_data_item_id, ACTION_RECOMMENDATION_LIST_ID)){
                            $action_recommendation_removal_items[] = $item;
                        }
                    }

                    if(isset($item->itemInfo->is_r_and_d_element) && $item->itemInfo->is_r_and_d_element > 0){
                        $item_r_and_d[] = $item;
                    }

                    if($item->sample){
                        if($item->record_id == $item->sample->original_item_id){
                            $samples[] = $item;
                        }
                    }

                    if($item->state != ITEM_NOACM_STATE){
                        $acm_items[] = $item;
                    }

                    // get total acm item and no acm item
                    if(!array_key_exists($item->location_id,$count_acm_item)){
                        $count_acm_item[$item->location_id] = 0;
                    }

                    if(!array_key_exists($item->location_id,$count_noacm_item)){
                        $count_noacm_item[$item->location_id] = 0;
                    }
                    $location_items[$item->location_id]['items'][] = $item;
                    if($item->decommissioned == ITEM_UNDECOMMISSION){
                        if($item->state == ITEM_NOACM_STATE){
                            $count_noacm_item[$item->location_id] ++;
                        } else{
                            $count_acm_item[$item->location_id] ++;
                        }
                    }

                    $location_items[$item->location_id]['total_acm_item'] = $count_acm_item[$item->location_id];
                    $location_items[$item->location_id]['total_noacm_item'] = $count_noacm_item[$item->location_id];
                }
            }

            //get location inaccess list
            $locations = $survey->locationUndecommission;
            $location_order = [];
            foreach ($locations as $key => $location) {
                $intAreaRef = preg_replace("/[^a-zA-Z0-9]+/", ".", $location->area->area_reference);
                $intAreaRef = $intAreaRef ?? $location->area->id;
                $intLocRef = preg_replace("/[^a-zA-Z0-9]+/", ".", $location->location_reference);
                $intLocRef = $intLocRef ?? $location->id;
                $location_order[$intAreaRef][$location->area->id][$intLocRef][$location->id][] = $location;
            }
            $sorted_location = [];
            if(count($location_order)){
                ksort($location_order);
                foreach ($location_order as $k1 => $v1) {
                    ksort($v1);
                    foreach ($v1 as $k2 => $v2) {
                        ksort($v2);
                        foreach ($v2 as $v3) {
                            ksort($v3);
                            foreach ($v3 as $v4) {
                                ksort($v4);
                                foreach ($v4 as $v5) {
                                    $sorted_location[] = $v5;
                                }
                            }
                        }
                    }
                }
            }
            $action_recommendation_items = $this->sortItemRecommendation($action_recommendation_items);
            $data_inacc_loc = $this->getLocationInaccessibleData($locations);
            if(count($data_inacc_loc)){
                $location_inaccessible_void = $data_inacc_loc[0];
                $location_inaccessible_void_display = $data_inacc_loc[1];
                $inaccessible_locations = $data_inacc_loc[2];
            }
            $pdf = \PDF::loadView('shineCompliance.pdf.publish_survey', [
                'is_pdf' => true,
                'survey' => $survey,
                'survey_revision' => $survey_revision,
                'sample_survey' => $sample_survey,
                'data_siteplan' => $data_siteplan,
                'count_item_tested' => $count_item_tested,
                'count_location_high_risk' => count($location_highrisk_accessible),
                'high_risk_item' => $high_risk_item,
                'inaccessible_locations' => $inaccessible_locations,
                'location_inaccessible_void' => $location_inaccessible_void,
                'location_inaccessible_void_display' => $location_inaccessible_void_display,
                'inaccessible_items' => $inaccessible_items,
                'survey_method_question_data' => $survey_method_question_data,
                'action_recommendation_items' => $action_recommendation_items,
                'samples' => $samples,
                'locations' => $sorted_location,
                'location_items' => $location_items,
                'action_recommendation_removal_items' => $action_recommendation_removal_items,
                'item_r_and_d' => $item_r_and_d,
                'acm_items' => $acm_items
            ])
                ->setOption('header-font-size', 8)
                ->setOption('footer-font-size', 8)
                ->setOption('footer-right', "Page [page] of [toPage]")
                ->setOption('header-right', $header)

                ->setOption('footer-left', $survey_ref)
                ->setOption('cover', view('shineCompliance.pdf.survey_cover',[
                    'is_pdf' => true,
                    'survey' => $survey,
                    'property' => $survey->property]))
            ;
            $is_local = env('APP_ENV') != 'local';
            if($is_local){
                $toc_name = "shineCompliance.publishedSurveyToc.xsl";
                $toc_path = \Config::get('view.paths')[0] . "/pdf/".$toc_name;
                $pdf->setOption('toc' , true)
                    ->setOption('xsl-style-sheet',$toc_path);
            }
            $file_name = $survey_ref;
            $file_type = 'pdf';
            $mime = "application/pdf";
            if($survey_revision > 0){
                $file_name = $survey_ref . "." . $survey_revision;
            }
            $file_name .= "." . $file_type;

            $save_path = storage_path('app/data/pdfs/surveys') ."/" . $file_name;
            // allow overwrite
            $pdf->save($save_path, true);
            $data_ps = [                'survey_id' => $survey->id,
                'name' => $survey_revision > 0 ? $survey_ref . "." . $survey_revision : $survey_ref,
                'revision' => $survey_revision,
                'type' => $file_type,
                'size' => filesize($save_path),
                'filename' => $file_name,
                'mime' => $mime,
                'path' => $save_path,
                'created_by' => \Auth::user()->id,
                'is_large_file' => filesize($save_path) < 100000000 ? 0 : 1];
            $this->surveyService->createPublishedSurvey($data_ps);

            return $save_path;
        }
        return false;
    }

    private function sortItemRecommendation($items){
        $sorted_item = [];
        $item_order = [];
        foreach ($items as $key => $item) {
            $intAreaRef = preg_replace("/[^a-zA-Z0-9]+/", ".", $item->area->area_reference);
            $intAreaRef = $intAreaRef ?? $item->area->id;
            $intLocRef = preg_replace("/[^a-zA-Z0-9]+/", ".", $item->location->location_reference);
            $intLocRef = $intLocRef ?? $item->location->id;
            $item_order[$intAreaRef][$item->area->id][$intLocRef][$item->location->id][] = $item;
        }
        if(count($item_order)){
            ksort($item_order);
            foreach ($item_order as $k1 => $v1) {
                ksort($v1);
                foreach ($v1 as $k2 => $v2) {
                    ksort($v2);
                    foreach ($v2 as $k3 => $v3) {
                        ksort($v2);
                        foreach ($v3 as $k4 => $v4) {
                            ksort($v4);
                            foreach ($v4 as $v5) {
                                $sorted_item[] = $v5;
                            }
                        }
                    }
                }
            }
        }
        return $sorted_item;
    }

    private function getLocationInaccessibleData($locations){
        $location_inaccessible_void = $location_inaccessible_void_display = $inaccessible_locations = [];
        if(count($locations)){
            foreach ($locations as $location){

                //inaccess location
                if(isset($location->state) && $location->state == LOCATION_STATE_INACCESSIBLE){
                    $inaccessible_locations[] = $location;
                }
                //inaccess reason answers in construction void
                $location_construction =  $location->locationVoid ?? NULL;
                if($location_construction){
                    $inaccess_ceiling_void = in_array(1108,explode(",",$location->locationVoid->ceiling));
                    $inaccess_floor_void = in_array(1453,explode(",",$location->locationVoid->floor));
                    $inaccess_cavities_void = in_array(1216,explode(",",$location->locationVoid->cavities));
                    $inaccess_riser_void = in_array(1280,explode(",",$location->locationVoid->risers));
                    $inaccess_ducting_void = in_array(1344,explode(",",$location->locationVoid->ducting));
                    $inaccess_boxing_void = in_array(1733,explode(",",$location->locationVoid->boxing));
                    $inaccess_pipewor_void = in_array(1606,explode(",",$location->locationVoid->pipework));
                    if($inaccess_ceiling_void || $inaccess_floor_void || $inaccess_cavities_void
                        || $inaccess_riser_void || $inaccess_ducting_void || $inaccess_boxing_void || $inaccess_pipewor_void){
                        $location_inaccessible_void[] = $location;
                        // check to display Inaccessible Void Room/locations in table
                        if($inaccess_ceiling_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->area_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->location_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1108]['void_type'] = 'Ceiling Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1108]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ceiling, optional($location->locationVoid)->ceiling_other );
                        }

                        if($inaccess_floor_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->area_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->location_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1453]['void_type'] = 'Floor Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1453]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->floor, optional($location->locationVoid)->floor_other );
                        }

                        if($inaccess_cavities_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->area_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->location_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1216]['void_type'] = 'Cavities Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1216]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->cavities, optional($location->locationVoid)->cavities_other );
                        }

                        if($inaccess_riser_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->area_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->location_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1280]['void_type'] = 'Risers Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1280]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->risers, optional($location->locationVoid)->risers_other );
                        }

                        if($inaccess_ducting_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->area_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->location_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1344]['void_type'] = 'Ducting Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1344]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ducting, optional($location->locationVoid)->ducting_other );
                        }

                        if($inaccess_boxing_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->area_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->location_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1733]['void_type'] = 'Boxing Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1733]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->boxing, optional($location->locationVoid)->boxing_other );
                        }

                        if($inaccess_pipewor_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->area_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->location_reference ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1606]['void_type'] = 'Pipework Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1606]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->pipework, optional($location->locationVoid)->pipework_other );
                        }
                    }
                }
            }
            return [$location_inaccessible_void, $location_inaccessible_void_display, $inaccessible_locations];
        }
        return [];
    }

    public function downloadPDF($type, $id){

        if($type == DOWNLOAD_SURVEY_PDF){
            return $this->downloadPDFSurvey($id);
        } elseif ($type == DOWNLOAD_WORK_PDF) {
            return $this->downloadPDFWorkRequest($id);
        } elseif ($type == DOWNLOAD_AUDIT_PDF) {
            return $this->downloadPDFAudit($id);
        }
        return abort(404);
    }

    private function downloadPDFSurvey($id){
        $publish_survey_pdf = $this->surveyService->getPublishedSurvey($id);
        //log audit
        $comment = \Auth::user()->full_name  . " download survey PDF "  . $publish_survey_pdf->name . ' on '. optional($publish_survey_pdf->survey)->reference;
        \CommonHelpers::logAudit(SURVEY_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_DOWNLOAD, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
        if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->download($publish_survey_pdf->path, $publish_survey_pdf->filename, $headers);

        }
        return abort(404);
    }

    private function downloadPDFWorkRequest($id){
        $publish_survey_pdf = $this->surveyService->getPublishedWorkRequest($id);
        //log audit
        $comment = \Auth::user()->full_name  . " download survey PDF "  . $publish_survey_pdf->name . ' on '. optional($publish_survey_pdf->survey)->reference;
        \CommonHelpers::logAudit(SURVEY_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_DOWNLOAD, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
        if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->download($publish_survey_pdf->path, $publish_survey_pdf->filename, $headers);

        }
        return abort(404);
    }
}
