<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SummaryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ZoneRepository;
use App\Repositories\ItemRepository;
use App\Export\CollectionExport;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Area;
use App\Models\SummaryPdf;
use App\Models\Location;
use App\Models\Survey;
use App\Models\Property;
use App\Models\Zone;
use App\Models\PropertyType;
use \PDF;

class SummaryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $all_summaries;
    private $route;
    public function __construct(SummaryRepository $summaryRepository,ClientRepository $clientRepository, ZoneRepository $zoneRepository, ItemRepository $itemRepository)
    {
        $this->summaryRepository = $summaryRepository;
        $this->zoneRepository = $zoneRepository;
        $this->itemRepository = $itemRepository;
        $this->clientRepository = $clientRepository;
        $this->all_summaries = $this->summaryRepository->getAllSummaries();
        $this->summary = $this->summaryRepository->getSummary(\Route::currentRouteName());

    }

    public function index() {
        $type = ASBESTOS;
        $this->all_summaries = $this->summaryRepository->getSummaries();
        return view('summary.index',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function material() {
        $type = ASBESTOS;
        return view('summary.material_risk',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function priority() {
        $type = ASBESTOS;
        return view('summary.priority_risk',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function overall() {
        $type = ASBESTOS;
        return view('summary.overall_risk',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function survey() {
        $type = ASBESTOS;
        return view('summary.technical_manager_survey_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function user() {
        $clients = $this->summaryRepository->getAllClient();
        $departmentList = $this->summaryRepository->getAllDepartments();
        $child_departments = $this->summaryRepository->getChildDepartments(5);
        $departmentContractorList = $this->summaryRepository->getAllDepartments('contractor', \Auth::user()->client_id);

        $all_zones = $this->summaryRepository->listZones();
        $type = ASBESTOS;
        return view('summary.user_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'clients' => $clients,
            'departmentList' => $departmentList,
            'child_departments' => $child_departments,
            'departmentContractorList' => $departmentContractorList,
            'all_zones' => $all_zones,
            'type' => $type
        ]);
    }

    public function postUser(Request $request) {

        $data = $this->summaryRepository->userSummary($request->all());
        $title = [
            'Audit Reference', 'Reference', 'Action Type', 'UserID', 'UserName', 'Organisation Name','Organisation Directorate',
            'Organisation Division','Organisation Department','Organisation Team', 'Date','Time','IP','Property Block','Property Name','Comments'
        ];
        $fileName = 'User_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function appUser() {
        $clients = $this->summaryRepository->getAllClient();
        $departmentList = $this->summaryRepository->getAllDepartments();
        $departmentContractorList = $this->summaryRepository->getAllDepartments('contractor', \Auth::user()->client_id);

        $all_zones = $this->summaryRepository->listZones();
        return view('summary.app_user_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'clients' => $clients,
            'departmentList' => $departmentList,
            'departmentContractorList' => $departmentContractorList,
            'all_zones' => $all_zones,
        ]);
    }

    public function postAppUser(Request $request) {
        $data = $this->summaryRepository->appUserSummary($request->all());
        $title = [
            'Audit Reference', 'Reference', 'Action Type', 'UserID', 'UserName', 'Organisation Name',
            'Date','Time','IP', 'Device Model and Type', 'Device Software Level', 'Shine App Version',
            'Property Block', 'Property Name','Comments'
        ];
        $fileName = 'App_User_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function areaCheck() {

        $type = ASBESTOS;
        return view('summary.area_check',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function roomCheck() {

        $type = ASBESTOS;
        return view('summary.location_check',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function reinspectionProgramme() {

        $type = ASBESTOS;
        return view('summary.reinspection_programme',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postReinspectionProgramme(Request $request) {
        $data = $this->summaryRepository->reinspectionProgramme();
        $title = [
            'Property Block', 'Property Name', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type',
            'Management Survey Reference',
            'Management Survey Surveying Finished',
            'Management Survey Completed Date', 'No. Register ACMs', 'Previous Re-Inspection Survey Reference',
            'Previous Re-Inspection Survey Status',
            'Previous Re-Inspection Survey Surveying Finished',
            'Previous Re-Inspection Survey Completion Date', 'Re-Inspection Due Date', 'Days', 'Warning'
        ];
        $fileName = 'Re-inspection_Programme_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function directorOverview() {

        $type = ASBESTOS;
        return view('summary.director_overview',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function managerOverview() {

        $type = ASBESTOS;
        return view('summary.manager_overview',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function kpiSummary() {

        $type = ASBESTOS;
        $clients = $this->clientRepository->findWhere(['client_type' => 1])->all();
        return view('summary.contractor_kpi_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'clients' => $clients,
            'type' => $type
        ]);
    }

    public function inaccessible() {

        $type = ASBESTOS;
        return view('summary.inaccessible_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postInaccessible(Request $request) {
        $type = $request->inaccessible_type;
        $inaccessible = $this->summaryRepository->inaccessibleSummary($type);

        $fileName = 'Inaccessible_'. $type .'_'. Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($inaccessible['data'], $inaccessible['title']), $fileName);

    }


    public function asbestosRemedialAction() {

        $type = ASBESTOS;
        return view('summary.asbestos_remedial',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function actionRecommendation() {

        $type = ASBESTOS;
        return view('summary.action_recommendation_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postActionRecommendation(Request $request) {
        $data = $this->summaryRepository->actionRecommendation();
        $title = [
            'Property Block', 'Property Name', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type',
            'Area/floor Reference','Area/floor Description', 'Room/location Reference', 'Room/location Description', 'Item Reference',
            'Product/debris Type', 'MAS Score', 'MAS Risk', 'A/R Type', 'Action/recommendation'
        ];
        $fileName = 'Action_Recommendation_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function genesisCommunalCsv() {

        $type = ASBESTOS;
        return view('summary.property_information',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postGenesisCommunalCsv(Request $request) {
        $data = $this->summaryRepository->propertyInformation($request);
        if((env('APP_DOMAIN') == 'LBHC')) {
            $title = [
                'PL Reference','Property  UPRN', 'Block Code','Parent Reference','Housing Area','Ward', 'Property Name',
                'Flat Number','Building Name', 'Street Number','Street Name','Estate Code', 'Town',
                'County','Postcode', 'Status','Asset Type','Tenure Type', 'Communal Area',
                'Responsibility','Property Group','Construction Age','Property Access Type'

            ];

        }
        else {
            $title = [
                'PL Reference','Property  UPRN', 'Block Code','Parent Reference','Service Area', 'Property Name',
                'Flat Number','Building Name', 'Street Number','Street Name','Estate Code', 'Town',
                'County','Postcode', 'Status','Asset Class','Asset Type','Tenure Type', 'Communal Area',
                'Responsibility','Property Group','Construction Age','Number of Floors','Property Access Type'

            ];
        }
        $fileName = 'Property_Information_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function postPropertyInfoTemplate(Request $request) {
        return \Storage::download('Property_Information_Template.csv');
        $data = $this->summaryRepository->propertyInfoTemplate();
        $title = [
            'PL Reference', 'Block Code', 'Property Name', 'Property Group', 'Property Postcode',
            'Status', 'Property Risk Type', 'Property Access Type', 'Construction Age', 'Construction Type',
            'No. Floors', 'No. Staircases', 'No. Lifts'
        ];
        $fileName = 'Property_Information_Template_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function projectSummary() {

        $type = ASBESTOS;
        return view('summary.project_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postprojectSummary(Request $request) {
        $client_id = 1;
        $data = $this->summaryRepository->projectSummary($client_id, Carbon::now()->timestamp);
        $title = [
            'Project Reference', 'Property UPRN','Property Block', 'Estate Code', 'Service Area', 'Property Name',
            'Flat Number','Building Name','Street Number','Street Name','Town','County','Postcode','Property Group', 'Asset Class', 'Asset Type','Tenure Type', 'Project Title',
            'Project Type', 'Asbestos Lead','Second Asbestos Lead','Project Status', 'Winning Contractor','WR Reference','WR Type','WR Requester','WR Requester Department', 'Linked Survey Type', 'Survey(s) for reference','Linked Survey', 'Linked Project Type', 'Linked Project',
            'Project Initiation', 'Project Start Date', 'Project Completion Date', 'Days', 'Created By',  'Archive Date'
        ];
        $fileName = 'Project_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);

    }

    public function registerItemChange() {

        $type = ASBESTOS;
        return view('summary.register_item_change',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postRegisterItemChange(Request $request) {
        $fromAuditTime = Carbon::now()->timestamp - 90 * 86400;
        $data = $this->summaryRepository->registerItemChange($fromAuditTime);
        $title = [
            'Item Name','Item Reference', 'Update Date', 'Time', 'IP Address', 'Type', 'User', 'Property Block',
            'Property Name', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type', 'Area/floor Reference', 'Area/floor Description', 'Room/location Reference',
            'Room/location Description'
        ];
        $fileName = 'Register_Item_Change_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function hdDocuments() {

        $type = ASBESTOS;
        return view('summary.hd_document_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postHdDocuments(Request $request) {
        $data = $this->summaryRepository->hdDocuments();
        $title = [
            'HD Document Reference', 'HD Document Title','Document Type', 'Document File', 'HD Document Date', 'HD Document Upload Date', 'User',
            'Property UPRN', 'Property Block', 'Property Name', 'Property Group'
        ];

        $fileName = 'HD_Document_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function surveySummary() {

        $type = ASBESTOS;
        return view('summary.survey_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postSurveySummary(Request $request) {
        $data = $this->summaryRepository->surveySummary();
        $title = [
            'Survey Reference', 'Survey Type', 'Survey Status', 'Risk Warning', 'Property Block', 'Property Name', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type',
            'Created Date', 'Started Date', 'Surveying Finished Date', 'No. Of Rejections', 'Completed Date',"Asbestos Lead",'Commissioned By','Contractor Name', 'Surveyor',
            'Technical Manager','Survey Cost','Organisation Reference' ,'WR Reference','WR Type','WR Requester','WR Requester Department', 'Accessible ACM Items', 'Inaccessible Rooms', 'Inaccessible Voids',
            'Inaccessible ACM Items', 'Days Remaining'
        ];

        $fileName = 'Survey_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function projectDocumentSummary() {

        $type = ASBESTOS;
        return view('summary.project_doc_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postProjectDocumentSummary(Request $request) {
        $client_id = 1;
        $data = $this->summaryRepository->projectDocumentSummary($client_id);
        $title = [
            'Project Document Reference', 'Project Reference', 'Property UPRN', 'Property Block', 'Estate Code', 'Service Area', 'Property Name',
            'Flat Number','Building Name','Street Number','Street Name','Town','County','Postcode', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type', 'Project Document Type', 'Contractor', 'Asbestos lead',
            'Project Document Name', '1st Published Date', 'Latest Publish Date', 'Approval Date', 'No. of Rejections',
            'Last Revision', 'Project Document Status', 'Departments'
        ];

        $fileName = 'Project_Document_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function priorityforaction() {

        $type = ASBESTOS;
        return view('summary.priority_for_action',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postpriorityforaction(Request $request) {

    }

    public function decommissionedItem() {

        $type = ASBESTOS;
        return view('summary.decommissioned_item_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postDecommissionedItem(Request $request) {
        $data = $this->summaryRepository->decommissionedItem();
        $title = [
            'Property Group', 'Asset Class', 'Asset Type','Tenure Type','Building Number', 'Building Name', 'Floor Reference', 'Room Reference', 'IN Number', 'Product Description', 'Decommission Reason', 'Full Name','organisation','Decommissioned Date'
        ];
        $fileName = 'Decommission_Item_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    // todo
    public function reinspectionFreeze() {

        $type = ASBESTOS;
        return view('summary.reinspection_freeze_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postReinspectionFreeze(Request $request) {

    }

    public function userCS() {

        $type = ASBESTOS;
        return view('summary.user_community_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postUserCS(Request $request) {
        $data = $this->summaryRepository->userCS();
        $title = [
            'User', 'User ID', 'Type', 'Site Operative View', 'Status', 'Job Title', 'Job Role', 'Telephone', 'Mobile', 'Email Address',
            'Organisation Name', 'Organisation ID', 'Directorate', 'Division', 'Department', 'Team', 'Last System Access', 'Notes', 'AA Training',
            'SOV Previous Course Date', 'SOV Net Course Date', 'SOV Risk Warning',
            'PMT Previous Course Date', 'PMT Net Course Date', 'PMT Risk Warning'
        ];
        $fileName = 'User_Community_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function sampleSummary() {

        $type = ASBESTOS;
        return view('summary.sample_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postSampleSummary(Request $request) {
        $property_id = is_null($request->property_id) ? 0 : $request->property_id;
        $data = $this->summaryRepository->sampleSummary($property_id);
        $title = [
            'Property Name', 'Asset Class', 'Asset Type','Tenure Type', 'Area/floor Reference', 'Area/floor Description', 'Room/location Reference',
            'Room/location Description', 'Item Reference', 'Item Name', 'Sample ID', 'Asbestos Type', 'MAS', 'PAS', 'OAS'
        ];
        $fileName = 'Sample_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function duplicationChecker() {

        $type = ASBESTOS;
        return view('summary.duplication_checker',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postDuplicationChecker(Request $request) {
        $type = $request->duplicate_action;
        $data = $this->summaryRepository->duplicationChecker($type);

        $fileName = 'Duplication_'.$type.'_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data['data'], $data['title']), $fileName);
    }

    public function orchardSummary() {

        $type = ASBESTOS;
        return view('summary.orchard_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postOrchardSummary(Request $request) {
        $type = $request->orchard_type;
        $data = $this->summaryRepository->orchardSummary($type);
        if($type == 1){
            $summary_name = 'Gap_Analysis_Summary_Properties_found_in_Orchard_but_not_found_in_Shine_';
        } else if($type == 2){
            $summary_name = 'Gap_Analysis_Summary_Properties_found_in_Shine_but_not_found_in_Orchard_';
        } else if($type == 3){
            $summary_name = 'Gap_Analysis_Summary_Properties_found_in_both_';
        }

        $fileName = $summary_name . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data['data'], $data['title']), $fileName);
    }

    public function photographySize() {

        $type = ASBESTOS;
        return view('summary.photography_type',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postPhotographySize(Request $request) {
        $data = $this->summaryRepository->photographySize();
        $title = [
            'Photograph Size (MB)', 'File Name', 'Record Type', 'Linked to' , 'Shine Reference', 'Upload Date','Username','Organisation Name',
        ];
        $fileName = 'Photography_Size_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function projectDocument() {

        $type = ASBESTOS;
        return view('summary.survey_document',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postProjectDocument(Request $request) {
        $data = $this->summaryRepository->projectDocument();
        $title = [
            'Survey Document Reference', 'Survey Document Type', 'Survey Document Name', 'Last Revision' , "Survey Document Status" , "Survey Reference" , "Organisation" ,
            "Property UPRN" , "Property Block" , "Property Name" , "Property Group", 'Asset Class', 'Asset Type','Tenure Type'
        ];
        $fileName = 'Survey_Document_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function santiaSampleSummary() {


        $type = ASBESTOS;
        return view('summary.santia_sample',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postSantiaSampleSummary(Request $request) {
        $survey_id = is_null($request->survey_id) ? 0 : $request->survey_id;
        $data = $this->summaryRepository->santiaSampleSummary($survey_id);
        $title = [
            'External Reference', 'Room/Location Reference', 'Room/Location Description', 'Product/Debris Type'
        ];
        $fileName = 'Santia_Sample_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function summaryService(Request $request) {
        $file_path = 'preview_pdf/'. time().'.html' ;
        $tagheader = $request->summary_title;
        $tagItemInfo = '';
        //log audit
        $next_number = \CommonHelpers::getCounter('summaries');
        switch ($request->summary_type) {
            case 'material':
            case 'priority':
            case 'riskassessment':
            case 'priorityforaction':
                $link =  route('summary.pdf_preview',[
                    'type' => $request->type,
                    'summary_type' => $request->summary_type,
                    'zone' => $request->zone,
                    'risk' => $request->risk,
                    'item_limit' => $request->item_limit,
                    'reason' => $request->reason,
                    'property_id' => $request->property_id,
                    'area' => $request->area,
                    'location' => $request->location,
                    'file_path' => $file_path,
                    'summary_name' => " SS" . sprintf("%03d", $next_number)
                ]);

                break;

            case 'survey':
                $link =  route('summary.survey_pdf_preview',[
                    'summary_type' => $request->summary_type,
                    'survey_id' => $request->survey_id,
                    'file_path' => $file_path,
                    'summary_name' => " SS" . sprintf("%03d", $next_number)
                ]);
                $survey = Survey::find($request->survey_id);
                $tagheader .= " - " . ($survey->property->name ?? '') . " - " . ($survey->reference ?? '');
                break;

            case 'areaCheck':
                $link =  route('summary.area_check_pdf_preview',[
                    'summary_type' => $request->summary_type,
                    'area' => $request->area,
                    'property_id' => $request->property_id,
                    'file_path' => $file_path,
                    'summary_name' => " SS" . sprintf("%03d", $next_number)
                ]);
                break;

            case 'roomCheck':
                $link =  route('summary.area_check_pdf_preview',[
                    'summary_type' => $request->summary_type,
                    'location' => $request->location,
                    'property_id' => $request->property_id,
                    'file_path' => $file_path,
                    'summary_name' => " SS" . sprintf("%03d", $next_number)
                ]);
                break;

            case 'asbestos_remedial':
                $link =  route('summary.asbestos_remedial_pdf_preview',[
                    'summary_type' => $request->summary_type,
                    'location' => $request->location,
                    'area' => $request->area,
                    'property_id' => $request->property_id,
                    'asbestos_remedial_type' => $request->asbestosRemedialType,
                    'action_recommendation' => $request->action_recommendation,
                    'action_recommendation_list' => $request->action_recommendation_list,
                    'asbestos_remedial_location_type' => $request->asbestos_remedial_location_type,
                    'risk' => $request->risk,
                    'product_debris' => $request->productDebris,
                    'product_debris_other' => $request->productDebrisOther,
                    'reason' => $request->reason_asbestos,
                    'file_path' => $file_path,
                    'summary_name' => " SS" . sprintf("%03d", $next_number)
                ]);
                break;

            case 'managerOverview':
            case 'directorOverview':
                // old summary.hight_chart
                $link =  route('chart.summary.generate',[
                    'summary_type' => $request->summary_type,
                    'chart_type' => 'directorOverview',
                    'data_date' => $request->form_select_quarter,
                    'file_path' => $file_path,
                    'summary_name' => " SS" . sprintf("%03d", $next_number)
                ]);
                $tagheader .= " - " . ($request->quater_name ?? '');
                break;
            case 'contractor_kpi':
                $link =  route('chart.summary.generate',[
                    'contractors' => implode(",",$request->contractor),
                    'summary_type' => $request->summary_type,
                    'chart_type' => 'contractor_kpi',
                    'data_date' => $request->form_select_quarter,
                    'file_path' => $file_path,
                    'summary_name' => " SS" . sprintf("%03d", $next_number)
                ]);
                break;
            default:
                # code...
                break;
        }

        $comment =  \Auth::user()->full_name  . " viewed  ". $request->summary_type ." Summary.";
        \CommonHelpers::logAudit(SUMMARY_TYPE, $next_number, AUDIT_ACTION_VIEW, $request->summary_type, $request->property_id ?? 0, $comment, 0 , $request->property_id ?? 0);
        // tag header
        $tagheader .= $request->has('reason') ? " - $request->reason" : "";
        $tagheader .=  " SS" . sprintf("%03d", $next_number) . "-" . date("d/m/y") . "-" . date("H:i") . "-UID" . sprintf("%03d", \Auth::user()->id);
        return response()->json(['status_code' =>200,
            'link'=> $link,
            'html_file' => $file_path,
            'tagheader' => urlencode($tagheader),
            'next_number' => $next_number,
            'summary_type' => $request->summary_type,
            'property_id' => $request->property_id ?? 0

        ]);
    }

    public function pdfPreview(Request $request) {
        $zone = $this->zoneRepository->findWhere(['id' => $request->zone])->first();
        $client_property_group_ids = Property::with('propertyInfo')->where(['decommissioned' => 0, 'zone_id'=> $zone->id])
                                                                    ->where('zone_id', $zone->id)->pluck('id');
        $riskScore = [];
        switch ($request->risk) {
            case "highrisk":
                $riskScore = [9, 999];
                break;
            case "mediumrisk":
                $riskScore = [6, 10];
                break;
            case "lowrisk":
                $riskScore = [4, 7];
                break;
            case "verylowrisk":
                $riskScore = [-1, 5];
                break;
            default:
                $riskScore = [0, 999];
                break;
        }

        if ($request->summary_type == 'priorityforaction') {
            $items =  Item::with('property', 'area', 'location','productDebrisView')
                            ->whereIn('property_id', $client_property_group_ids)
                            ->where('decommissioned', ITEM_UNDECOMMISSION)
                            ->where('survey_id', 0)
                            ->whereBetween('total_risk', $riskScore)
                            ->where('state', ITEM_ACCESSIBLE_STATE)->orderBy('total_risk','desc')->take($request->item_limit);
            $request->summary_name = 'Priority For Action';

        } else {
            $items =  Item::with('property', 'area', 'location','productDebrisView')
                            ->whereIn('property_id', $client_property_group_ids)
                            ->where('decommissioned', ITEM_UNDECOMMISSION)
                            ->where('survey_id', 0)
                            ->whereBetween('total_risk', $riskScore)
                            ->where('state', ITEM_INACCESSIBLE_STATE)->take($request->item_limit);
        }

        switch ($request->type) {
            case 'zone':
                $items =  $items->get();
                break;

            case 'property':
                $items =  $items->where('property_id', $request->property_id)->get();
                break;

            case 'areafloor':
                $items =  $items->where('property_id', $request->property_id)->where('area_id', $request->area_id)->get();
                break;

            case 'roomlocation':
                $items =  $items->where('property_id', $request->property_id)->where('location_id', $request->location_id)->get();
                break;

            default:
                $items =  $items->get();
                break;
        }

        $html = view('summary_pdf_template.item_risk', [
            'summary_type' => $request->summary_type,
            'criteria' => $request->type,
            'risk' => $request->risk,
            'riskScore' => $riskScore,
            'numberItem' => $request->item_limit,
            'items' => $items,
            'is_pdf' => true,
            'summary_name' => $request->summary_name
        ])->render();

        $file = \Storage::put( $request->file_path, $html);

        return view('summary_pdf_template.item_risk', [
            'summary_type' => $request->summary_type,
            'criteria' => $request->type,
            'risk' => $request->risk,
            'riskScore' => $riskScore,
            'numberItem' => $request->item_limit,
            'summary_name' => $request->summary_name,
            'items' => $items,
            'is_pdf' => false
        ]);
    }

    public function surveyPdfPreview(Request $request) {
        $items =  Item::with('area', 'location', 'location.locationInfo', 'specificLocationView',
                            'itemInfo','extentView', 'actionRecommendationView', 'asbestosTypeView', 'sample')
                        ->where('survey_id', $request->survey_id)->where('decommissioned', ITEM_UNDECOMMISSION)->get();

        $html = view('summary_pdf_template.survey', [
            'summary_type' => $request->summary_type,
            'items' => $items,
            'is_pdf' => true
        ])->render();

        $file = \Storage::put( $request->file_path, $html);

        return view('summary_pdf_template.survey', [
            'summary_type' => $request->summary_type,
            'items' => $items,
            'is_pdf' => false,
            'summary_name' => $request->summary_name
        ]);
    }

    public function postAreaCheck(Request $request) {
        $property =  Property::find($request->property_id ?? 0);
        $next_number = \CommonHelpers::getCounter('summaries');
        $summary_name = " SS" . sprintf("%03d", $next_number);

        if ($request->summary_type == 'areaCheck') {
            $validator = \Validator::make($request->all(), [
                'area' => 'required',
                'property_id' => 'required',
            ]);
            if ($validator->fails())
            {
                return redirect()->back()->with(['err'=>'Please fill information before generate!']);
            }
            $area = Area::find($request->area);
            $data_loc = Location::with('area', 'allItems.AsbestosTypeValue','allItems.asbestosTypeView','allItems.area','allItems.itemInfo',
                'allItems.location','allItems.sample','allItems.productDebrisView','allItems.extentView','allItems.accessibilityVulnerabilityView',
                'allItems.additionalInformationView', 'allItems.licensedNonLicensedView','allItems.actionRecommendationView',
                'allItems.ItemNoAccessValue.ItemNoAccess','allItems','allItems.PriorityAssessmentRiskValue','allItems.MaterialAssessmentRiskValue','allItems.SpecificLocationValue',
                'allItems.ActionRecommendationValue','allItems','countItemACM','allItems.pasPrimary.getData','allItems.pasSecondary.getData',
                'allItems.pasLocation.getData','allItems.pasAccessibility.getData','allItems.specificLocationView',
                'allItems.pasExtent.getData','allItems.pasNumber.getData',
                'allItems.pasHumanFrequency.getData','allItems.pasAverageTime.getData',
                'allItems.pasType.getData','allItems.pasMaintenanceFrequency.getData',
                'allItems.masProductDebris.getData','allItems.masDamage.getData',
                'allItems.masTreatment.getData','allItems.masAsbestos.getData')->where('area_id', $request->area)
                ->where('decommissioned', 0)->where('survey_id', 0)->orderBy('location_reference','asc')->get();
                $type_check = 'Area/Floor';
                $comment =  \Auth::user()->full_name  . " exported to PDF Summary Type : " .$type_check. " Check " .$summary_name . ' ' .($property->name ?? ''). ', ' .($area->area_reference ?? '').' - ' .($area->description ?? '') ;
        } else {
            $validator = \Validator::make($request->all(), [
                'location' => 'required',
                'property_id' => 'required',
            ]);
            if ($validator->fails())
            {
                return redirect()->back()->with(['err'=>'Please fill information before generate!']);
            }
            $data_loc = Location::with('area', 'allItems.AsbestosTypeValue','allItems.asbestosTypeView','allItems.area','allItems.itemInfo',
                'allItems.location','allItems.sample','allItems.productDebrisView','allItems.extentView',
                'allItems.accessibilityVulnerabilityView','allItems.PriorityAssessmentRiskValue','allItems.MaterialAssessmentRiskValue','allItems.SpecificLocationValue',
                'allItems.additionalInformationView', 'allItems.licensedNonLicensedView','allItems.actionRecommendationView',
                'allItems.ItemNoAccessValue.ItemNoAccess','allItems','countItemACM','allItems.specificLocationView',
                'allItems.ActionRecommendationValue','allItems.pasPrimary.getData','allItems.pasSecondary.getData',
                'allItems.pasLocation.getData','allItems.pasAccessibility.getData',
                'allItems.pasExtent.getData','allItems.pasNumber.getData',
                'allItems.pasHumanFrequency.getData','allItems.pasAverageTime.getData',
                'allItems.pasType.getData','allItems.pasMaintenanceFrequency.getData',
                'allItems.masProductDebris.getData','allItems.masDamage.getData',
                'allItems.masTreatment.getData','allItems.masAsbestos.getData'
                            )->whereIn('id', $request->location)->where('decommissioned', 0)->where('survey_id', 0)->orderBy('location_reference','asc')->get();
            $location_name = [];
            foreach ($data_loc as $value) {
                $location_name[] = ($value->location_reference ?? '') . ' - ' .($value->description ?? '');
            }
            $location_name = implode(",",$location_name);
            $type_check = 'Room/Location';
            $comment =  \Auth::user()->full_name  . " exported to PDF Summary Type : " .$type_check. " Check " .$summary_name . ' ' . ($property->name ?? ''). ', ' . ', '.$location_name ;
        }


        \CommonHelpers::logAudit(SUMMARY_TYPE, $request->next_number, AUDIT_ACTION_EXPORT, 'External PDF File (Generate)', $request->property_id ?? 0, $comment, 0 , $request->property_id ?? 0);

        if (!is_null($data_loc)) {
            foreach ($data_loc as $data) {
                if($data->allItems->count() > 0){

                    $count_item_tested = 0;
                    $count_acm_item = $count_noacm_item = [];
                    $data->allItems = $this->itemRepository->sortItemRegister($data->allItems);

                }
            }
            # code...
        }

        // tag header
        $tagheader = $request->summary_title;
        $tagheader .= $request->has('reason') ? " - $request->reason" : "";
        $tagheader .=  " SS" . sprintf("%03d", $next_number) . "-" . date("d/m/y") . "-" . date("H:i") . "-UID" . sprintf("%03d", \Auth::user()->id);

        $pdf = PDF::loadView('summary_pdf_template.area_check', [
            'summary_type' => $request->summary_type,
            'locationChecked' => $data_loc,
            'property' => $property,
            'summary_name' => $summary_name,
            'is_pdf' => true
        ])
            ->setOption('header-font-size', 8)
            ->setOption('footer-font-size', 6)
            ->setOption('footer-right', "Page [page] of [toPage]")
            ->setOption('footer-left', urldecode($tagheader));


        $save_path = storage_path('app/data/pdfs/registers') ."/" . $summary_name;
        //for overwrite
        $pdf->save($save_path, true);


        //comment use for search summary
        $comment = " Summary Type " .$type_check. " Check " . ($property->name ?? ''). ', ' .($area->area_reference ?? ''). ', ' .($location_name ?? '') ;

        SummaryPdf::create([
            'reference'=> $summary_name,
            'type'=> 3,
            'object_id'=> $request->property_id,
            'file_name'=> 'Asbestos_Register_'.date("d/m/Y"),
            'path'=> $save_path,
            'comment' => $comment
        ]);
        return response()->file($save_path);
    }

    public function asbestosRemedialPdfPreview(Request $request) {
        $data = Item::with('area', 'location','itemInfo', 'PriorityAssessmentRiskValue', 'MaterialAssessmentRiskValue', 'SpecificLocationValue')->where('property_id', $request->property_id)
                    ->where('survey_id', 0)
                    ->where('decommissioned', 0)
                    ->orderBy('location_id', 'desc');
        $listRemedialActions = implode(',', ACTION_RECOMMENDATION_LIST_ID);

        switch ($request->asbestos_remedial_type) {
            case 'actionRecommendation':

                $items = $data->whereHas('ActionRecommendationValue', function($query) use ($listRemedialActions) {
                                 return $query->whereIn('dropdown_data_item_id', ACTION_RECOMMENDATION_LIST_ID);
                                        })->get();
                break;

            case 'location':
                if ($request->asbestos_remedial_location_type == 'areafloor') {
                    $items = $data->where('area_id', $request->area)->get();
                } elseif ($request->asbestos_remedial_location_type == 'roomlocation') {
                   $items = $data->where('location_id', $request->location)->get();
                } else {
                    $items =  $data->get();
                }
                break;

            case 'riskType':
                switch ($request->risk) {
                    case 'highrisk':
                        $items =  $data->where('total_mas_risk','>', 9)->get();
                        break;
                    case 'mediumrisk':
                        $items =  $data->where('total_mas_risk','>', 6)->where('total_mas_risk','<', 10)->get();
                        break;

                    case 'lowrisk':
                        $items =  $data->where('total_mas_risk','>', 4)->where('total_mas_risk','<', 7)->get();
                        break;

                    case 'lowrisk':
                        $items =  $data->where('total_mas_risk','<', 5)->get();
                        break;

                    default:
                        $items =  $data->get();
                        break;
                    }

                break;

            case 'productDebris':
                $product_debris = $request->product_debris;
                $product_debris_other = $request->product_debris_other;
                if (in_array(366, $request->product_debris)) {
                    $items = $data->whereHas('ProductDebrisTypeValue', function($query) use ($product_debris_other) {
                                 return $query->where('dropdown_other','like', '%'.$product_debris_other.'%');
                                        })->get();
                } else {
                    $items = $data->whereHas('ProductDebrisTypeValue', function($query) use ($product_debris) {
                                 return $query->where('dropdown_other', end($product_debris));
                                        })->get();
                }
                break;

            default:
                $items = $data->get();
                break;
        }


        if(count($items) > 0){
            $items = $this->itemRepository->sortItemSurvey($items);
            $count_item_tested = 0;
            $count_acm_item = $count_noacm_item = [];
            foreach ($items as $item){
                $this->itemRepository->addParticularAttributeForItem($item, $count_acm_item, $count_noacm_item, $count_item_tested);
            }
        }

        $property =  Property::find($request->property_id);
        $comment =
        $html = view('summary_pdf_template.asbestos_remedial', [
            'summary_type' => $request->summary_type,
            'items' => $items,
            'property' => $property,
            'reason' => $request->reason,
            'is_pdf' => true,
            'summary_name' => $request->summary_name,
        ])->render();

        $file = \Storage::put( $request->file_path, $html);

        return view('summary_pdf_template.asbestos_remedial', [
            'summary_type' => $request->summary_type,
            'items' => $items,
            'property' => $property,
            'reason' => $request->reason,
            'is_pdf' => false,
            'summary_name' => $request->summary_name,
        ]);
    }

    public function hightChartPdfPreview(Request $request) {
        $property_risks =  PropertyType::where('order' , '>' , 0)->get();
        $cTime = time();
        $zones =  Zone::where('client_id', 1)->get();
        if (count($zones) > 0) {
            foreach ($zones as $key => $zone) {
                $groupNames[] = $zone->zone_name;
                $groupID = $zone->id;
                $Mtotal = 0;
            }
        }

        return view('summary_pdf_template.high_chart', [
            'summary_type' => $request->summary_type,
            'month' => $request->month,
            'next_month' => $request->next_month,
            'property_risks' => $property_risks,
            'cTime' => $cTime,
            'groupNames' => $groupNames,
            'Mtotal' => $Mtotal,
            'MS' => [],
        ]);
    }

    public function projectMetaData() {

        $type = ASBESTOS;
        return view('summary.project_meta_data',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary,
            'type' => $type
        ]);
    }

    public function postprojectMetaData(Request $request) {
        $data = $this->summaryRepository->projectMetaData();
        $title = [
            'Project Reference', 'Property Name', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type', 'Project Type ' , "Project Start Date" , "Project Created By"
        ];
        $fileName = 'Project_Metadata_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function rejectionTypeSummary(Request $request){

        return view('summary.survey_summary',[
            'all_summaries' => $this->all_summaries,
            'summary' => $this->summary
        ]);
    }

    public function postRejectionTypeSummary() {
        $data = $this->summaryRepository->getSurveyRejectionSummary();
        $title = [
            "Survey Reference","Contractor Name","Rejection Date","Rejection Time","Rejection Type","Rejection Note"
        ];
        $fileName = 'Rejection_Type_Summary_' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }
}
