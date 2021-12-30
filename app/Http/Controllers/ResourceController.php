<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessBluelightService;
use App\Models\Client;
use App\Models\PrivilegeChild;
use App\Models\PrivilegeUpdate;
use App\Models\PrivilegeView;
use App\Models\ProjectType;
use App\Models\Role;
use App\Models\RoleUpdate;
use App\Models\SummaryType;
use App\Models\TemplateDocument;
use App\Models\Department;
use App\Models\DepartmentContractor;
use App\Services\ShineCompliance\IncidentReportService;
use App\User;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\SurveyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\ItemRepository;
use App\Repositories\LocationRepository;
use App\Http\Request\ToolBox\RemoveRequest;
use App\Models\Zone;
use App\Models\Property;
use App\Models\Area;
use App\Models\Location;
use App\Models\Elearning;
use App\Models\Item;
use App\Models\SummaryPdf;
use App\Models\TemplatesCategory;
use App\Models\Template;
use App\Models\LastRevision;
use App\Models\DocumentBluelightService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PHPUnit\Runner\Exception;
use ZipArchive;

class ResourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $route;

    private $incidentReportService;

    public function __construct(PropertyRepository $propertyRepository,
                                LocationRepository $locationRepository,
                                ItemRepository $itemRepository,
                                ClientRepository $clientRepository,
                                SurveyRepository $surveyRepository,
                                ProjectRepository $projectRepository,
                                DocumentRepository $documentRepository,
                                IncidentReportService $incidentReportService)
    {
        $this->clientRepository = $clientRepository;
        $this->surveyRepository = $surveyRepository;
        $this->projectRepository = $projectRepository;
        $this->documentRepository = $documentRepository;
        $this->itemRepository = $itemRepository;
        $this->locationRepository = $locationRepository;
        $this->propertyRepository = $propertyRepository;
        $this->incidentReportService = $incidentReportService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function eLearning()
    {
        $eLearning = Elearning::where('user_id', \Auth::user()->id)->first();
        $disable = is_null($eLearning) ? 'disabled' : '';
        return view('resources.e_learning',['disable' => $disable]);
    }

    public function trainingVideo($type, Request $request) {
        $file_name =  $request->file_name;
        return view('resources.training_video', ['file_name' => $file_name, 'type' => $type]);
    }

    public function resourceDocument(){
        // $list_doc_permission = \CompliancePrivilege::getPermission(CATEGORY_BOX_PERMISSION);
        $list_doc_permission = Template::pluck('id')->toArray();

        $list_doc_update_permission = Template::pluck('id')->toArray();

        $template_cat = TemplatesCategory::with('template')->get();

        return view('resources.resource_document', ['template_cat' => $template_cat,'list_doc_permission' =>  $list_doc_permission,
            'list_doc_update_permission' => $list_doc_update_permission ]);
    }

    public function postResourceDocument(Request $request){
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'name' => 'required|max:255',
            'category' => 'required',
            'document' => 'required_without:id|file|max:100000',
            'date' => 'nullable|date_format:d/m/Y',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $resourceDoc = $this->updateOrCreateDoc($request->all(), $request->id);
        } else {
            $resourceDoc = $this->updateOrCreateDoc($request->all());
        }

        if (isset($resourceDoc) and !is_null($resourceDoc)) {
            \Session::flash('msg', $resourceDoc['msg']);
            return response()->json(['status_code' => $resourceDoc['status_code'], 'success'=> $resourceDoc['msg'], 'id' => $resourceDoc['data'] ?? 0]);
        }
    }

    public function postResourceCategory(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'category_title' => 'required|string|max:255',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->id == 0) {
            $docCat = $this->updateOrCreateCategory($request->all());
        } else {
            $docCat = $this->updateOrCreateCategory($request->all(), $request->id);
        }


        if (isset($docCat) and !is_null($docCat)) {
            \Session::flash('msg', $docCat['msg']);
            return response()->json(['status_code' => $docCat['status_code'], 'success'=> $docCat['msg'], 'id' => $docCat['data'] ?? 0]);
        }
    }

    public function updateOrCreateCategory($data, $id = null) {
        if (!empty($data)) {
            $dataCat = [
                'category' => \CommonHelpers::checkArrayKey($data,'category_title'),
                'order' => 0
            ];

            try {
                if(is_null($id)) {
                    $resourceDocCat =  TemplatesCategory::create($dataCat);
                    $message = 'Create new template category successful!';
                    //log audit
                    \CommonHelpers::logAudit(TEMPLATE_CATEGORY_TYPE, $resourceDocCat->id, AUDIT_ACTION_ADD, $dataCat['category']);
                } else {
                    $resourceDocCat =  TemplatesCategory::where('id', $id)->update($dataCat);
                    $message = 'Update template category successful!';

                    //log audit
                    \CommonHelpers::logAudit(TEMPLATE_CATEGORY_TYPE, $id, AUDIT_ACTION_EDIT, $dataCat['category']);
                }
                return $response = \CommonHelpers::successResponse($message);

            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update template category. Please try again!');
            }
        }
    }

    public function updateOrCreateDoc($data, $id = null) {
        if (!empty($data)) {
            $dataDepart = [
                'name' => \CommonHelpers::checkArrayKey($data,'name'),
                'category_id' => \CommonHelpers::checkArrayKey($data,'category'),
                'added' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'date')),
            ];

            try {
                if(is_null($id)) {
                    $dataDepart['created_by'] = \Auth::user()->id;
                    $resourceDoc =  Template::create($dataDepart);
                    $message = 'Resource Document Created successful!';

                    //log audit
                    \CommonHelpers::logAudit(TEMPLATE_TYPE, $resourceDoc->id, AUDIT_ACTION_ADD, $dataDepart['name'], $dataDepart['category_id']);
                    //update EMP
                    // if (\CompliancePrivilege::checkPermission(RESOURCE_EMP_VIEW_PRIV)) {
                        // \CompliancePrivilege::setViewEMP(RESOURCE_EMP_VIEW_PRIV, $resourceDoc->id);
                    // }
                    //update EMP
                    // if (\CompliancePrivilege::checkUpdatePermission(RESOURCE_EMP_UPDATE_PRIV)) {
                        // \CompliancePrivilege::setUpdateEMP(RESOURCE_EMP_UPDATE_PRIV, $resourceDoc->id);
                    // }
                } else {
                    $updateHistorical =  Template::where('id', $id)->update($dataDepart);
                    $resourceDoc = Template::where('id', $id)->first();
                    $message = 'Resource Document Updated successful!';
                    //log audit
                    \CommonHelpers::logAudit(TEMPLATE_TYPE, $id, AUDIT_ACTION_EDIT, $dataDepart['name'], $dataDepart['category_id']);
                }
                if (isset($data['document'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $resourceDoc->id, RESOURCE_DOCUMENT);
                }
                return $response = \CommonHelpers::successResponse($message);

            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update Resource Document document. Please try again!');
            }
        }
    }

    public function updateOrCreateRole(Request $request) {

        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255|unique:tbl_role|unique:tbl_role_update',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $dataRole = [
            'name' => $request->name ?? ''
        ];

        try {
            if($request->id == 0) {
                $dataRole['created_by'] = \Auth::user()->id;
                $role =  Role::create($dataRole);
                $dataRole['role_id'] = $role->id;
                $roleUpdate =  RoleUpdate::create($dataRole);
                $message = 'Created Job Role successfully!';

                //log audit
                \CommonHelpers::logAudit(ROLE, $role->id, AUDIT_ACTION_ADD, $dataRole['name']);
            } else {
                $updateRole =  Role::where('id', $request->id)->update($dataRole);
                $updateRoleUpdate =  RoleUpdate::where('role_id', $request->id)->update($dataRole);
                $message = 'Updated Job Role successfully!';
                //log audit
                \CommonHelpers::logAudit(ROLE, $request->id, AUDIT_ACTION_EDIT, $dataRole['name']);
            }
            \Session::flash('msg', $message);
            return $response = \CommonHelpers::successResponse($message);

        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update Job Role. Please try again!');
        }
    }

    public function bluelightService() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        $zones = Zone::with('bluelightService')->get();
        return view('resources.bluelight_service',['zones' => $zones]);
    }

    public function getZipbluelightService($zone_id) {
        $zip = LastRevision::where('zone_id', $zone_id)->first();
        $zone = Zone::find($zone_id);
        if (isset($zip->path)) {
            if (is_file($zip->path)) {
                $comment = "ID".\Auth::user()->id." download Bluelight Services Register on Group ".$zone->zone_name;
                \CommonHelpers::logAudit(SUMMARY_TYPE, $zone->id, AUDIT_ACTION_EXPORT,'asbestosRegister',0, $comment,0);

                return response()->download($zip->path);
            } else {
                return redirect()->back()->with('err', 'No zip file available. Please generate before download !');
            }
        } else {
                return redirect()->back()->with('err', 'No zip file available. Please generate before download !');
        }
    }

    public function generateZipbluelightService($zone_id) {
        LastRevision::updateOrCreate(['zone_id' => $zone_id],
                                    [ 'status' => 1]);
        //queue here
        $user = \Auth::user();
        \Queue::pushOn(BLUELIGHT_SERVICE_PROCESS,new ProcessBluelightService($zone_id, $user));
        $zone =  Zone::find($zone_id);
        $comment = "ID".$user->id." generate Bluelight Services Register on Group ".$zone->zone_name;
        \CommonHelpers::logAudit(SUMMARY_TYPE, $zone->id, AUDIT_ACTION_EXPORT,'asbestosRegister',0, $comment,0);
        return redirect()->back()->with('msg', 'Generating file will take time . Please wait !');
    }

    public function generateZipProcess($zone_id) {
        try{
            $user = \Auth::user();
            // \DB::beginTransaction();
            $zone = Zone::find($zone_id);

            $comment = "ID".$user->id." generate Bluelight Services Register on Group ".$zone->zone_name;
            \CommonHelpers::logAudit(SUMMARY_TYPE, $zone->id, AUDIT_ACTION_EXPORT,'asbestosRegister',0, $comment,0);

            $list_site =  Property::where('zone_id', $zone_id)->where('decommissioned',0)->get();
            $last_revision = LastRevision::where('zone_id', $zone_id)->first();

            if ( isset($last_revision->last_revision) and ($last_revision->last_revision >= $zone->last_revision)) {
                # code...
            } else {
                $last_revision = LastRevision::create(['zone_id' => $zone_id],
                                    [ 'status' => 1]);
            }
                LastRevision::where('zone_id', $zone_id)->update(['last_revision' => time()]);
                $new_id =  $last_revision->id;

                foreach ($list_site as $property) {
                    $next_number = \CommonHelpers::getCounter('summaries');
                    $ssRef = "SS" . sprintf("%03d", $next_number);

                    $data_comment = $this->getCommentRegisterPDF(PROPERTY_REGISTER_PDF, $property->id, $ssRef, $user);

                    // $fileName = 'AsbestosRegister'. "-".$property->name. "-" . $property->reference . "-" . date("d/m/Y");
                    $fileName = "AsbestosRegister" . $property->reference. date("d_m_Y"). '.' .$next_number. '.pdf';
                    $items = $this->itemRepository->getRegisterItems(PROPERTY_REGISTER_PDF, $property->id);
                    $inaccessible_locations = $this->locationRepository->getInaccessibleLocations(PROPERTY_REGISTER_PDF, $property->id);

                    //delete old pdf
                    $document_register = DocumentBluelightService::where('zone_id', $zone_id)->get();
                    // if (!is_null($document_register)) {
                    //     foreach ($document_register as $key => $doc) {
                    //         \File::delete($doc->path);
                    //     }
                    // }

                    $pdf = $this->generateRegisterPdf($user, $items, $inaccessible_locations, $property, $data_comment['footer_left'], $ssRef, TYPE_ASBESTOS_REGISTER, $fileName, $new_id, $next_number);
                }


            //---------------------merge PDF of group----------------------------------
            // $path_zip = storage_path('app').'/data/zone_zip';
            // if(!\File::exists($path_zip)) {
            //         // path does not exist
            //         mkdir($path_zip, 0777 , true);
            //     }
            $fileZipName = 'AsbestosRegister'. $zone->reference . "_" . date("d_m_Y");
            $fileZipName .= ".zip";
            $destination = $fileZipName;
            //get last_revision id of new or update of list PDF

            $id = isset($last_revision->id) ? $last_revision->id : $new_id;
            $list_document = DocumentBluelightService::where('zone_id', $zone_id)->get();

            $zipper =  new ZipArchive();


            if (count($list_document) > 0) {
                if ($zipper->open($destination, ZipArchive::CREATE) === TRUE){
                    foreach ($list_document as $doc) {
                        if ($doc && file_exists($doc->path)) {
                            //audit trail
                            $new_filename = substr($doc->path,strrpos($doc->path,'/') + 1);
                            $zipper->addFile($doc->path,$new_filename);
                        }
                    }
                }
            $zipper->close();

            // \DB::commit();
            // remove
            DocumentBluelightService::where('zone_id', $zone_id)->delete();
            //delete zip file after download
            return response()->download($destination, $fileZipName, array('Content-Type: application/octet-stream','Content-Length: '. filesize($destination)))->deleteFileAfterSend(true);
            // LastRevision::updateOrCreate(['zone_id' => $zone_id],
            //                             [ 'status' => 2, 'path' => $destination]);
            } else {
                return redirect()->back()->with('err', 'Property Group does not contain any register pdfs !');
            }
        } catch(\Exception $e){
            // \DB::rollBack();

            return redirect()->back()->with('err', 'Failed to generate . Please try later !');

        }
    }

    public function generateRegisterPdf($user,$items, $inaccessible_locations, $property, $footer_left, $ss_ref, $type, $file_name, $new_id, $next_number, $type_doc = 'bls') {

        //set warning for cover page
        $risk_type_one = $risk_type_two = NULL;
        if(isset($property->propertyType) && !$property->propertyType->isEmpty()){
            $risk_type_one = $property->propertyType->where('id',1)->first();
            $risk_type_two = $property->propertyType->where('id',2)->first();
        }
        $property->warning_message = $this->propertyRepository->getWarningMessage($property);
        $is_has_inaccessible_voids = $this->propertyRepository->isHasInaccessibleVoidLocation($property->id);
        $pdf = \PDF::loadView('pdf.register_pdf', [
            'items' => $items,
            'is_pdf' => true,
            'inaccessible_locations' => $inaccessible_locations,
            'property' => $property,
            'risk_type_one'=>$risk_type_one,
            'risk_type_two'=>$risk_type_two,
            'ss_ref' => $ss_ref,
            'type' => $type
        ])
            ->setOption('header-font-size', 8)
            ->setOption('footer-font-size', 8)
            ->setOption('footer-right', "Page [page] of [toPage]")
            ->setOption('footer-left', $footer_left)
            ->setOption('cover', view('pdf.register_cover',[
                'property' => $property,
                'is_pdf' => true,
                'is_has_inaccessible_voids' => $is_has_inaccessible_voids,
                'risk_type_one'=>$risk_type_one,
                'risk_type_two'=>$risk_type_two,
                'ss_ref' => $ss_ref,
                'type' => $type]))
        ;
        $is_local = env('APP_ENV') != 'local';
        if($is_local){
            $toc_name = "publishedSurveyToc.xsl";
            $toc_path = \Config::get('view.paths')[0] . "/pdf/".$toc_name;
            $pdf->setOption('toc' , true)
                ->setOption('xsl-style-sheet',$toc_path);
        }

        $file_type = 'pdf';

        $mime = "application/pdf";

        $save_path = storage_path('app/data/pdfs/registers') ."/" . $file_name;

        //for overwrite
        $pdf->save($save_path, true);

        SummaryPdf::create([
            'reference'=> $ss_ref,
            'type'=> $type,
            'object_id'=> $property->id,
            'file_name'=> $file_name,
            'path'=> $save_path
        ]);
        DocumentBluelightService::updateOrCreate(['property_id'=> $property->id, 'zone_id' => $property->zone_id],
                                                [ 'name' => $file_name, 'property_reference' => $property->reference, 'created_by' => 1,
                                                    'type' => $type_doc, 'last_revision_id' => $new_id, 'number_record' => $next_number,
                                                    'path' => $save_path, 'created_date' => time()
                                                    ]);
        return file_exists($save_path) ? ['path'=>$save_path,'file_name'=>$file_name] :  [];
    }

        //get data comment and property reference
    private function getCommentRegisterPDF($type, $id, $ss_ref, $user){
        $comment = '';
        $property_id = '';
        if($type == PROPERTY_REGISTER_PDF){
            $property = Property::where('id',$id)->first();
            $property_id = $property->id ?? '';
            $footer_left = "Asbestos Register " . $property->name . " " . date("d_m_Y"); // Them reference
            $comment = $user->first_name . " " . $user->last_name . " exported to PDF Summary Type : ".$ss_ref." Asbestos Register on " . $property->name ?? ''; // Them referecen + description , ,;
        } else if($type == AREA_REGISTER_PDF){
            $area = Area::with('property')->where('id',$id)->first();
            $property_id = $area->property->id ?? '';
            $footer_left = "Area Check" . " - " . $area->property->name ?? '' . " - " . $area->area_reference . " - " . $ss_ref . " - " . date("d/m/y") . "-" . date("H:i") . "-UID" . sprintf("%03d", $user->id);
            $description = implode(', ', array_filter([$area->property->name ?? '' , $area->area_reference ?? '' , $area->description ?? '']));
            $comment = $user->first_name . " " . $user->last_name . " exported to PDF Summary Type : $ss_ref Asbestos Register on " . $description;
        } else if($type == LOCATION_REGISTER_PDF){
            $location = Location::with('property','area')->where('id',$id)->first();
            $property_id = $location->property->id ?? '';
            $footer_left = "Room Check" . " - " . $location->property->name ?? '' . " - " . $location->location_reference . " - " . $ss_ref . " - " . date("d/m/y") . "-" . date("H:i") . "-UID" . sprintf("%03d", $user->id);
            $description = implode(', ', array_filter([$location->property->name ?? '', $location->area->area_reference ?? '' ,
            $location->area->description ?? '', $location->location_reference ?? '' , $location->description ?? '']));
            $comment = $user->first_name . " " . $user->last_name . " exported to PDF Summary Type : $ss_ref Asbestos Register on " . $description;
        }
        return ['comment' => $comment, 'property_id' => $property_id, 'footer_left' => $footer_left];
    }

    public function editJobRole(){
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        $job_roles = Role::all();
        return view('resources.list_job_role',['job_roles' => $job_roles]);
    }

    public function getJobRole(Request $request) {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        // add note
        //  PROPERTY LISTING
        $job_role = Role::find($request->job_role);
        if(!$job_role){
            return abort(404);
        }
        $zones_view = $zones_update = [];
        $data_view = Role::where('id',$request->job_role)->first();
        $data_update =  RoleUpdate::where('role_id',$request->job_role)->first();
        // remove all properties if not all properties are checked
        $data_view->privilige = $this->checkAllProperties(explode(",", $data_view->property), $data_view->view_privilege, 1);
        $data_update->privilige = $this->checkAllProperties(explode(",", $data_update->property), $data_update->update_privilege, 2);
        $data_view->list_checked_zone = $this->propertyRepository->listZoneId($data_view->property);
        $data_update->list_checked_zone = $this->propertyRepository->listZoneId($data_update->property);
        $privilege_view = PrivilegeView::with('allChildrens')->where(['parent_id'=>0])->orderBy('order')->get();
        $privilege_update = PrivilegeUpdate::with('allChildrens')->where(['parent_id'=>0])->orderBy('order')->get();
        $clients_list = Client::with('zones')->get();
        $clients = $clients_list->where('id',1);
        $clients_contractor = $clients_list->where('id','!=',1);
        $project_types = ProjectType::all();
        $dynamic_data = PrivilegeChild::all();
        $project_informations = $organisation = [];
        $summary_data = SummaryType::all();
        $template_document = TemplateDocument::with('template')->get();
        if(!$dynamic_data->isEmpty()){
            $project_informations = $dynamic_data->where('type',PROJECT_INFORMATION_TYPE);
            $organisation = $dynamic_data->where('type',ORGANISATION_INFORMATION_TYPE);
            $document_approval = $dynamic_data->where('type',DOCUMENT_APPROVAL);
        }

        return view('resources.job_role',[
            'job_role' => $job_role, 'privilege_view' => $privilege_view,
            'privilege_update' => $privilege_update, 'clients'=> $clients, 'clients_contractor' => $clients_contractor,
            'data_view' => $data_view, 'data_update' => $data_update, 'project_types' => $project_types, 'template_document' => $template_document,
            'project_informations' => $project_informations, 'organisation'=> $organisation, 'summary_data' => $summary_data, 'document_approval'=>$document_approval,
            'role_id'=>$request->job_role]);
    }
    // 1 view, 2 update
    private function checkAllProperties($data_property, $data_normal, $type){
        if(count($data_normal)){
            //compare total properties of Shine vs save data
            $count_properties = Property::all()->count();
            if($type == 1){
                if(count($data_property) && ($count_properties != count($data_property))){
                    return array_diff( $data_normal, [ALL_PROPERTY_VIEW] );
                }
            } else {
                if(count($data_property) && ($count_properties != count($data_property))){
                    return array_diff( $data_normal, [ALL_PROPERTY_UPDATE] );
                }
            }
        }
        return $data_normal;
    }

    public function ajaxShowListProperty(Request $request){
        //role_id
        $properties = Property::where('zone_id', $request->group_id)->get();
        $role = NULL;
        $role_properties = [];
        $data = [];
        if($request->role_id && $request->tab){
            if($request->tab == VIEW_TAB){
                //get data properties view privilege
                $role = Role::where('id',$request->role_id)->first();
            } else {
                //get data properties update privilege
                $role = RoleUpdate::where('role_id',$request->role_id)->first();
            }
            if($role){
                $role_properties = explode(",", $role->property);
            }

            if(!$properties->isEmpty()){
                foreach ($properties as $k => $p){
                    $data[$k][] = $p->id;
                    $data[$k][] = $p->name;
                    $data[$k][] = count($role_properties) ? in_array($p->id, $role_properties) : false;

                }
            }
        }
        //need to check is there any property selected in this role
        return ['data'=>$data];
    }
    //need show flash message when save is done \Session::flash('msg', $document['msg']);
    // need get all uncheck zones to remove properties in that zones
    public function ajaxSaveListProperty(Request $request){
        //need to send tick and untick in checkboxes
        $properties = Property::where('zone_id', $request->group_id)->pluck('id')->toArray();
        $post_properties = $request->list_property ?? [];
        $role = NULL;
        $role_properties  = [];
        if($request->tab == VIEW_TAB){
            //get data properties view privilege
            $role = Role::where('id',$request->role_id)->first();
        } else {
            //get data properties update privilege
            $role = RoleUpdate::where('role_id',$request->role_id)->first();
        }
        if($role){
            $role_properties = explode(",", $role->property);
            //get list remove properties to remove, compare post list vs list properties in group
            $arr_properties_remove = array_diff($properties ,$post_properties);

            if(count($role_properties) && count($arr_properties_remove)){
                // Search for the array key and unset
                foreach($arr_properties_remove as $key){
                    $keyToDelete = array_search($key, $role_properties);
                    unset($role_properties[$keyToDelete]);
                }
            }
            $list_id =  implode(",", array_filter(array_unique( array_merge($role_properties, $post_properties))));

            $role->property = $list_id;
            $role->save();

        }
        return ['status'=>200];
        //merge new properties and unique array to avoid duplicate properties
    }

    public function saveRole(Request $request){

        $role_view = Role::where('id',$request->role_save_id)->first();
        $role_update = RoleUpdate::where('role_id',$request->role_save_id)->first();

        if($role_view && $role_update){
            try{
                DB::beginTransaction();
                if(in_array(ALL_PROPERTY_VIEW,explode(",",$request->view_privilege_data))){
                    $role_view->property = implode(",", Property::all()->pluck('id')->toArray());
                }
                if(in_array(ALL_PROPERTY_UPDATE,explode(",",$request->update_privilege_data))){
                    $role_update->property = implode(",", Property::all()->pluck('id')->toArray());
                }
                $role_view->view_privilege = $request->view_privilege_data;
                $role_view->project_type = $request->project_type_view;
                $role_view->project_information = $request->project_info_view;
                $role_view->contractor = $request->organisation_view;
                $role_view->report = $request->reporting_view;
                $role_view->category_box = $request->category_view;
                $role_view->is_everything = $request->view_is_everything;
                $role_view->is_operative = $request->view_is_operative;
                $role_view->save();

                $role_update->update_privilege = $request->update_privilege_data;
                $role_update->project_type = $request->project_type_update;
                $role_update->project_information = $request->project_info_update;
                $role_update->contractor = $request->organisation_update;
                $role_update->category_box = $request->category_update;
                $role_update->data_center = $request->data_center_update;
                $role_update->is_everything = $request->update_is_everything;
                $role_update->save();
                if(isset($request->view_is_operative)){
                    //update all user in this role to site operative
                    User::where(['role' => $request->role_save_id])->update(['is_site_operative' => $request->view_is_operative]);

                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                dd($e->getMessage());
            }
        }
        return redirect()->back()->with('msg','Save successfully');
    }

    public function download_backup($file_name){
        $file = storage_path().'/app/back_up/'.$file_name;
        if (file_exists($file)) {
            return response()->download($file, $file_name);
        } else {
            return 'File does not exist';
        }
    }

    public function download_backup_folder($type,$file_name){
        $sub = ($type == 1) ? 'Stevenage' : 'Ware_RandD';
        if ($type == 3) {
            $sub = 'Weybridge';
        }
        $file = storage_path().'/app/back_up/'.$sub. '/'.$file_name;
        if (file_exists($file)) {
            return response()->download($file, $file_name);
        } else {
            return 'File does not exist';
        }
    }

    public function departmentList(Request $request ){
        $parent_id = $request->parent_id ?? 0;
        if (\Auth::user()->clients->client_type == 1) {
            $departments = DepartmentContractor::with('allChildrens')->where('parent_id',$parent_id)->get();
            $department_current = DepartmentContractor::with('allParents')->find($parent_id);
        } else {
            $departments = Department::with('allChildrens')->where('parent_id',$parent_id)->get();
            $department_current = Department::with('allParents')->find($parent_id);
        }

        return view('resources.department_tool',[
            'departments' => $departments,
            'parent_id' => $parent_id,
            'department_current' => $department_current
        ]);

    }

    public function postDepartmentManagement(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'department_name' => 'required|string|max:255',
            'parent_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->id == 0) {
            $department = $this->updateOrCreateDepartment($request->all(),\Auth::user()->clients->client_type);
        } else {
            $department = $this->updateOrCreateDepartment($request->all(),\Auth::user()->clients->client_type, $request->id);
        }
        if (isset($department) and !is_null($department)) {
            \Session::flash('msg', $department['msg']);
            return response()->json(['status_code' => $department['status_code'], 'success'=> $department['msg']]);
        }

    }

    public function updateOrCreateDepartment($data,$client_type, $id = null) {
        if (!empty($data)) {
            $dataDepart = [
                'name' => \CommonHelpers::checkArrayKey($data,'department_name'),
                'parent_id' => $data['parent_id'],
                'client_id' => \Auth::user()->client_id
            ];

            try {
                if(is_null($id)) {
                    if ($client_type == 1) {
                        $department =  DepartmentContractor::create($dataDepart);
                    } else {
                        $department =  Department::create($dataDepart);
                    }
                    $message = 'Created Department Successfully!';
                    //log audit
                    \CommonHelpers::logAudit(DEPARTMENT_TYPE, $department->id, AUDIT_ACTION_ADD, $dataDepart['name'], $data['parent_id']);

                } else {
                    if ($client_type == 1) {
                        $updateDepartment =  DepartmentContractor::where('id', $id)->update($dataDepart);
                    } else {
                        $updateDepartment =  Department::where('id', $id)->update($dataDepart);
                    }
                    $message = 'Updated Department Successfully!';
                    //log audit
                    \CommonHelpers::logAudit(DEPARTMENT_TYPE, $id, AUDIT_ACTION_EDIT, $dataDepart['name'], $data['parent_id']);
                }
                return $response = \CommonHelpers::successResponse($message);

            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update Department. Please try again!');
            }
        }
    }

    public function incidentReports(Request $request ){
        $liveIncidents = $this->incidentReportService->getIncidentReportByStatus([
            INCIDENT_REPORT_CREATED_STATUS,
            INCIDENT_REPORT_READY_QA,
            INCIDENT_REPORT_AWAITING_APPROVAL,
            INCIDENT_REPORT_REJECT,
        ]);
        $completedIncidents = $this->incidentReportService->getIncidentReportByStatus(INCIDENT_REPORT_COMPLETE);
        $decommissionedIncidents = $this->incidentReportService->getDecommissionedReports();

        return view('resources.incident_reporting',[
            'liveIncidents' => $liveIncidents,
            'completedIncidents' => $completedIncidents,
            'decommissionedIncidents' => $decommissionedIncidents,
        ]);

    }
}
