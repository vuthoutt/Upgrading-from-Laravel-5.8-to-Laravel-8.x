<?php
namespace App\Helpers;
use App\Models\Project;
use App\Models\ShineCompliance\AssetClass;
use App\Models\ShineCompliance\ConstructionMaterial;
use App\Models\ShineCompliance\IncidentReportDocument;
use App\Models\ShineCompliance\IncidentReportDropdownData;
use App\Models\ShineCompliance\IncidentReportInvolvedPerson;
use App\Models\ShineCompliance\IncidentReportPublished;
use App\Models\ShineCompliance\PropertyInfoDropdownData;
use App\Models\ShineCompliance\VulnerableOccupantType;
use App\Models\ShineDocumentStorage;
use App\Models\PropertyProgrammeType;
use App\Models\SummaryPdf;
use App\Models\DropdownDataSurvey;
use App\Models\DropdownDataLocation;
use App\Models\SurveyAnswer;
use App\Models\PropertyDropdown;
use App\Models\ShineAsbestosLeadAdmin;
use App\Models\DropdownDataProperty;
use App\Models\PublishedSurvey;
use App\Models\AuditTrail;
use App\Models\Counter;
use App\Models\Property;
use App\Models\PublishedWorkRequest;
use App\Models\Survey;
use App\Models\Item;
use App\Repositories\ItemRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Jobs\LogAuditTrail;
use App\Jobs\SendClientEmail;
use Imagick;

use DateTime;
class CommonHelpers {

    public static function encryptPassword($password, $salt) {
        return crypt($password, '$6$' . substr(sha1($salt), -16));
    }

    public static function getNavColor($color) {

        switch ($color) {
            case 'red':
                $colorNav = 'red_gradient';
                break;

            case 'orange':
                $colorNav = 'orange_gradient';
                break;

            case 'blue':
                $colorNav = 'light_blue_gradient';
                break;

            default:
                $colorNav = 'red_gradient';
                break;
        }
        return $colorNav;
    }

    public static function getComplianceNavColor($color) {
        switch ($color) {
            case 'red':
                $colorNav = 'red_color';
                break;

            case 'orange':
                $colorNav = 'orange_color';
                break;

            case 'blue':
                $colorNav = 'blue_color';
                break;

            default:
                $colorNav = 'red_color';
                break;
        }
        return $colorNav;
    }

    public static function getNavItemColor($color) {
        switch ($color) {
            case 'red':
                $colorNav = 'red_gradient_nav';
                break;

            case 'orange':
                $colorNav = 'orange_gradient_nav';
                break;

            default:
                $colorNav = 'red_gradient_nav';
                break;
        }
        return $colorNav;
    }

    public static function getBreadcrumbColor($color) {
        switch ($color) {
            case 'red':
                $colorBr = 'red_alpha50';
                break;

            case 'orange':
                $colorBr = 'orange_alpha50';
                break;

            case 'blue':
                $colorBr = 'light_blue_alpha50';
                break;

            default:
                $colorBr = 'red_alpha50';
                break;
        }
        return $colorBr;
    }

    public static function isSystemClient() {
        return \Auth::user()->clients->client_type == 0 ? true : false;
    }

    public static function isClientUser() {
        return \Auth::user()->clients->client_type == 2 ? true : false;
    }

    public static function successResponse($msg = null, $data = null) {
        return [
                'status_code' => STATUS_OK,
                'msg' => $msg,
                'data' => $data
            ];
    }

    public static function failResponse($status_code, $msg) {
        return  [
                'status_code' => $status_code,
                'msg' => $msg
            ];
    }

    public static function getFile($object_id, $type, $is_pdf = false) {
        $file = ShineDocumentStorage::where('object_id', $object_id)->where('type', $type)->first();
        if (isset($file->path)) {
            $path_thumb = THUMB_NAIL . '/'. $file->path;
            if(is_file($path_thumb)){
                if($is_pdf){
                    return public_path() . '/'.$path_thumb;
                } else {
                    return asset($path_thumb);
                }
                return str_replace('index.php/','',url($file->path));
            } else if (is_file($file->path)) {
                if($is_pdf){
                    return public_path() . '/'.$file->path;
                } else {
                    return asset($file->path);
                }
                return str_replace('index.php/','',url($file->path));
            } else {
                return $is_pdf == true ? public_path('/img/no-image-icon.png') : url('/img/no-image-icon.png') ;
            }
        } else {
            return $is_pdf == true ? public_path('/img/no-image-icon.png') : url('/img/no-image-icon.png') ;
        }
    }

    public static function getAssetFile($url, $is_pdf = true) {
        if($is_pdf){
            return public_path() . '/'.$url;
        } else {
            return asset($url);
        }
    }

    public static function getFileName($object_id, $type) {
        $file = ShineDocumentStorage::where('object_id', $object_id)->where('type', $type)->first();
        if (isset($file->file_name) and !is_null($file->file_name)) {
            return $file->file_name;
        } else {
            return 'No File Present';
        }
    }

    public static function getFileStoragePath($object_id, $type, $survey_id = 0, $api = false) {
        $date = Carbon::now();
        switch ($type) {
            case USER_SIGNATURE:
                $particularPath = USER_SIGNATURE_PATH;
                break;

            case CLIENT_LOGO:
                $particularPath = CLIENT_LOGO_PATH;
                break;

            case UKAS_IMAGE:
                $particularPath = UKAS_IMAGE_PATH;
                break;

            case UKAS_TESTING_IMAGE:
                $particularPath = UKAS_TESTING_IMAGE_PATH;
                break;

            case PROPERTY_SURVEY_IMAGE:
                $particularPath = PROPERTY_SURVEY_IMAGE_PATH;
                break;

            case LOCATION_IMAGE:
                $particularPath = LOCATION_IMAGE_PATH;
                break;

            case ITEM_PHOTO:
                $particularPath = ITEM_PHOTO_PATH;
                break;

            case ITEM_PHOTO_LOCATION:
                $particularPath = ITEM_PHOTO_LOCATION_PATH;
                break;

            case ITEM_PHOTO_ADDITIONAL:
                $particularPath = ITEM_PHOTO_ADDITIONAL_PATH;
                break;

            case ZONE_PHOTO:
                $particularPath = ZONE_PHOTO_PATH;
                break;

            case DOCUMENT_FILE:
                $particularPath = DOCUMENT_FILE_PATH;
                break;

            case HISTORICAL_DATA:
                $particularPath = HISTORICAL_DATA_PATH;
                break;

            case SAMPLE_CERTIFICATE_FILE:
                $particularPath = SAMPLE_CERTIFICATE_FILE_PATH;
                break;

            case AIR_TEST_CERTIFICATE_FILE:
                $particularPath = AIR_TEST_CERTIFICATE_FILE_PATH;
                break;

            case TRAINING_RECORD_FILE:
                $particularPath = TRAINING_RECORD_FILE_PATH;
                break;

            case POLICY_FILE:
                $particularPath = POLICY_FILE_PATH;
                break;

            case WORK_REQUEST_FILE:
                $particularPath = WORK_REQUEST_FILE_PATH;
                break;

            case PLAN_FILE:
                if ($survey_id !== 0) {
                    $particularPath = SURVEY_PLAN_FILE_PATH;
                } else {
                    $particularPath = PROPERTY_PLAN_FILE_PATH;
                }
                break;

            case PLAN_FILE_ASSESSMENT:
                $particularPath = PLAN_FILE_ASSESSMENT_PATH;
                break;

            case NOTE_FILE_ASSESSMENT:
                $particularPath = NOTE_FILE_ASSESSMENT_PATH;
                break;

            case HAZARD_PHOTO:
                $particularPath = HAZARD_PHOTO_PATH;
                break;
            case HAZARD_LOCATION_PHOTO:
                $particularPath = HAZARD_LOCATION_PHOTO_PATH;
                break;
            case HAZARD_ADDITION_PHOTO:
                $particularPath = HAZARD_ADDITION_PHOTO_PATH;
                break;
            case ASSEMBLY_POINT_PHOTO:
                $particularPath = ASSEMBLY_POINT_PHOTO_PATH;
                break;
            case FIRE_EXIT_PHOTO:
                $particularPath = FIRE_EXIT_PHOTO_PATH;
                break;
            case VEHICLE_PARKING_PHOTO:
                $particularPath = VEHICLE_PARKING_PHOTO_PATH;
                break;
            case AVATAR:
                $particularPath = AVATAR_PATH;
                break;

            default:
                $particularPath = null;
                break;
        }
        $path = 'data/'. ( $api ? 'api/' : '') . $particularPath .'/'. $date->format('Y/m/d')   .'/' .$object_id. '/';
        return $path;

    }

    public static function getPdfPath($object_id, $type) {
        $file = SummaryPdf::where('object_id', $object_id)->where('type', $type)->first();
        if (isset($file->path)) {
            return $file->path;
        } else {
            return false;
        }
    }

    public static function checkFile($object_id, $type) {
        $file = ShineDocumentStorage::where('object_id', $object_id)->where('type', $type)->first();
        if (!is_null($file)) {
            if (is_file($file->path)) {
               return true;
            }
        }
        return false;

    }

    public static function toTimeStamp($time) {
        if (is_null($time)) {
            return 0;
        }
        // convert datepicker to d/m/y format
        $time = Carbon::createFromFormat('d/m/Y', $time);
        // convert to timestamp
        $time = Carbon::parse($time)->timestamp;
        return $time;
    }

    public static function dmytounixorblank($datestring) {
        if (!$datestring) {
            return "";
        };
        if (strlen($datestring) == 10) {
            $datestring = $datestring . " 00:00";
        }
        $timestamp = date_create_from_format('d/m/Y H:i', $datestring)->getTimestamp();
        if ($timestamp < 80000) {
            return "";
        } else {
            return $timestamp;
        }
    }

    public static function toTimeDateTime($time) {
        if (is_null($time)) {
            return 0;
        }
        // convert datepicker to d/m/y format
        $time = Carbon::createFromFormat('d/m/Y', $time);
        // convert to timestamp
        $time = Carbon::parse($time)->timestamp;
        return $time;
    }

    public static function countAllUsersFromDepartment($departments, $client_id) {

        $total = 0;
        foreach ($departments as $department) {
            $total += CommonHelpers::countUsersFromDepartments($client_id, $department->id);
        }
        return $total;
    }

    public static function countUsersFromDepartments($clientId, $departmentId) {
        $user = User::where('client_id', $clientId)->where('department_id', $departmentId)->count();
        return $user;
    }

    public static function countUsersFromDepartmentsRecursive($clientId, $department) {

        $count = User::where('client_id', $clientId)->where('department_id', $department->id)->count();
        //recursive
        foreach( $department->allChildrens as $child ) {
            $child->count = 0;
            $child->count += User::where('client_id', $clientId)->where('department_id', $child->id)->count();;
            foreach( $child->allChildrens as $child2 ) {
                $child->count += self::countChildUsers( $child2, $clientId );
            }
        }
        foreach ($department->allChildrens as $child){
            $count += $child->count;
        }

        return $count;
    }

    public static function countChildUsers( $child, $clientId) {
        $child->count = 0;
        $child->count += User::where('client_id', $clientId)->where('department_id', $child->id)->count();;
        foreach( $child->allChildrens as $child2 ) {
            $child->count += self::countChildUsers( $child2, $clientId);
        }
        return $child->count;
    }

    public static function saveFileShineDocumentStorage($file, $id, $type, $survey_id = 0) {
        if (!is_null($file) and $file->isValid()) {
            try {
                $path = CommonHelpers::getFileStoragePath($id, $type, $survey_id);
                Storage::disk('local')->put($path, $file);
                //create thumbnail and crop img for items, losing quality for locations/property
                if(in_array($type, [
                    PROPERTY_PHOTO,
                    LOCATION_IMAGE,
                    ITEM_PHOTO,
                    ITEM_PHOTO_LOCATION,
                    ITEM_PHOTO_ADDITIONAL,
                    PROPERTY_SURVEY_IMAGE,
                    HAZARD_PHOTO,
                    HAZARD_LOCATION_PHOTO,
                    HAZARD_ADDITION_PHOTO,
                    ASSEMBLY_POINT_PHOTO,
                    FIRE_EXIT_PHOTO,
                    VEHICLE_PARKING_PHOTO,
                    AVATAR
                ])){
                    self::createThumbnail($path. $file->hashName(), true);
                }
                ShineDocumentStorage::updateOrCreate([ 'object_id' => $id, 'type' => $type],
                    [
                        'path' => $path. $file->hashName(),
                        'file_name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'addedBy' => \Auth::user()->id,
                        'addedDate' =>  Carbon::now()->timestamp,
                    ]);
                return CommonHelpers::successResponse('Store file successfully', $file->hashName());
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to store file. Please try again !');
            }
        } else {
            return CommonHelpers::failResponse(STATUS_FAIL,'File not exist or invalid !');
        }
    }
    // create thumb nail of image
    // new thumb will have 4x3 ratio
    public static function createThumbnail($alias_path, $is_crop = true){
        $ratio = 0.8; // height/width 3/4
        $quality = 90;
        $path = public_path() . '/'. $alias_path;
        $path_info = pathinfo($alias_path);

        try{
            $img = new Imagick($path);
            $img->trimImage(0);
            $img->setImagePage(0, 0, 0, 0);
            $size = $img->getImageGeometry();
            $new_path = storage_path('app'). '/'. THUMB_NAIL . '/'. $path_info['dirname'] ;
            $new_file = $new_path . '/' . $path_info['basename'];
            if(!File::exists($new_path)) {
                // path does not exist
                mkdir($new_path, 0755 , true);
            }
            if($is_crop){
                $width = $size['width'];
                $height = $size['height'];
                $new_w = $new_h = NULL;
                if($height/$width < $ratio){
                    $new_h = $height;
                    $new_w = round($height / $ratio);
                    // height is smaller than width => crop by height
                } else {
                    // crop by width
                    $new_w = $width;
                    $new_h = round($width * $ratio);
                }
                $img->cropImage($new_w, $new_h, ($width - $new_w)/2, ($height - $new_h)/2);
            }
            $img->setImageCompressionQuality(80);
            self::autoRotateImage($img);
            $img->stripImage();
            $img->writeImage($new_file);
            $img->clear();
            $img->destroy();
            return true;
        } catch (\Exception $e){
            dd($e->getMessage());
            Log::error($e);
            return false;
        }
        return false;
    }
    public static function autoRotateImage($image) {
        $orientation = $image->getImageOrientation();
        switch($orientation) {
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateimage("#000", 180); // rotate 180 degrees
            break;


            case Imagick::ORIENTATION_RIGHTTOP:
                $image->rotateimage("#000", 90); // rotate 90 degrees CW
            break;


           case Imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateimage("#000", -90); // rotate 90 degrees CCW
            break;
        }

       // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
       $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
        return true;


    }

    public static function getUserFullname($user_id) {
        $user = User::where('id', $user_id)->first();
        if (!is_null($user)) {
            return $user->full_name;
        } else {
            return '';
        }
    }

    public static function getProgrammeType($programmeType, $programmeTypeOther, $type = 'programme') {
        if ($programmeType == PROGRAME_TYPE_OTHER and $type = 'programme') {
            return $programmeTypeOther;
        } elseif ($programmeType == 13 and $type = 'primary') {
            return $programmeTypeOther;
        } else {
            if ($type == 'programme') {
                return PropertyProgrammeType::where('id', $programmeType)->value('description');
            } else {
                return DropdownDataProperty::where('id', $programmeType)->value('description');
            }
        }
    }

    public static function getPropertyInfoField($propertyInfo, $field)
    {
        if (isset($propertyInfo[$field])) {
            switch ($field) {
                case 'programme_type':
                    return PropertyProgrammeType::find($propertyInfo[$field])->description ?? '';
                case 'asset_use_primary':
                case 'asset_use_secondary':
                    $dropdown = DropdownDataProperty::find($propertyInfo[$field]);
                    if ($dropdown && $dropdown->other == -1) {
                        return $propertyInfo[$field . '_other'];
                    } else {
                        return $dropdown->description ?? '';
                    }
                case 'property_status':
                case 'property_occupied':
                case 'listed_building':
                case 'parking_arrangements':
                case 'evacuation_strategy':
                case 'fra_overall':
                case 'property_type':
                    $dropdown = PropertyInfoDropdownData::find($propertyInfo[$field]);
                    if ($dropdown && $dropdown->other) {
                        return $propertyInfo[$field . '_other'];
                    } else {
                        return $dropdown->description ?? '';
                    }
                case 'stairs':
                case 'floors':
                case 'wall_construction':
                case 'wall_finish':
                $dropdownIds = explode(",",$propertyInfo[$field]);
                $dropdownDescriptions = PropertyInfoDropdownData::whereIn('id', $dropdownIds)->get();
                $description = [];
                if (!is_null($dropdownDescriptions)) {
                    foreach ($dropdownDescriptions as $dropdownDescription) {
                        if ($dropdownDescription->other == 0) {
                            $description[] = $dropdownDescription->description;
                        } else {
                            $description[] = $propertyInfo[$field . '_other'];
                        }
                    }
                    return implode(", ",$description);
                } else {
                    return '';
                }
                case 'asset_class':
                case 'asset_type':
                    $asset = AssetClass::where('id', $propertyInfo[$field])->first();
                    if ($asset) {
                        return $asset->description;
                    } else {
                        return '';
                    }
                case 'size_floors':
                case 'floors_above':
                case 'floors_below':
                case 'size_staircases':
                case 'size_lifts':
                    if ($propertyInfo[$field] == 'Other') {
                        return $propertyInfo[$field . '_other'] ?? null;
                    } else {
                        return $propertyInfo[$field];
                    }
                case 'electrical_meter':
                case 'gas_meter':
                case 'loft_void':
                    return PropertyDropdown::find($propertyInfo[$field])->description ?? '';
                case 'construction_age':
                case 'construction_type':
                case 'size_net_area':
                case 'size_gross_area':
                case 'nearest_hospital':
                case 'restrictions_limitations':
                case 'unusual_features':
                case 'avg_vulnerable_occupants':
                case 'max_vulnerable_occupants':
                case 'last_vulnerable_occupants':
                case 'vulnerable_occupant_type':
                    return $propertyInfo[$field];
                case 'construction_materials':
                    if (is_array($propertyInfo[$field])) {
                        $materials = '';
                        foreach ($propertyInfo[$field] as $materialId) {
                            if ($materialId != MATERIAL_OTHER) {
                                $materials .= ConstructionMaterial::find($materialId)->description ?? '';
                            } else {
                                $materials .= isset($propertyInfo['construction_material_other']) ? $propertyInfo['construction_material_other'] : '';
                            }
                            $materials .= ', ';
                        }
                        return rtrim($materials, ', ');
                    } else {
                        return '';
                    }
            }
        } else {
            return '';
        }

    }

    public static function getStyleFRARiskRating($propertyInfo)
    {
        if (isset($propertyInfo['fra_overall'])) {
            switch ($propertyInfo['fra_overall']) {
                case 49: // Intolerable
                    return 'background-color: #8a1812;color: white;text-align: center';
                case 14: // Substantial
                    return 'background-color: #e30b17;color: white;text-align: center';
                case 15: // Moderate
                    return 'background-color: #ad8049;color: white;text-align: center';
                case 16: // Tolerable
                    return 'background-color: #f7a416;color: white;text-align: center';
                case 48: // Trivial
                    return 'background-color: #f3e600;color: black;text-align: center';
            }
            return '';
        } else {
            return '';
        }
    }

    public static function getPropertyMaterials($property)
    {
        $constructionMaterials = '';
        if (count($property->constructionMaterials) > 0) {
            foreach ($property->constructionMaterials as $material) {
                if ($material->id == MATERIAL_OTHER) {
                    $constructionMaterials .= $material->pivot->other;
                } else {
                    $constructionMaterials .= $material->description;
                }
                $constructionMaterials .= ', ';
            }

            $constructionMaterials = rtrim($constructionMaterials, ', ');
        }

        return $constructionMaterials;
    }

    public static function getPropertyOtherMaterial($property)
    {
        $otherMaterial = '';

        if (count($property->constructionMaterials) > 0) {
            foreach ($property->constructionMaterials as $material) {
                if ($material->id == MATERIAL_OTHER) {
                    $otherMaterial = $material->pivot->other;;
                }
            }
        }

        return $otherMaterial;
    }

    public static function getPropertyText($propertyInfoId) {
        return PropertyInfoDropdownData::find($propertyInfoId)->description ?? '';
    }

    public static function getSurveyPropertyInfoText($type, $typeOther = null) {
        if ($type == 'Other') {
            return $typeOther;
        } else {
            return $type;
        }
    }

    public static function getPropertyRiskTypeClass($type) {
        switch ($type) {
            case 2://Property Build in or After 2000; No Asbestos Detected
                return 'spanWarningSuccess'; //green
                break;
            case 3://Domestic Property
            case 8://Embedded Space
            case 13://Communal
            case 14://Privately Owned Domestic Property
            case 15://Leased Commercial Property
            case 16://Leased Residential Property
                return 'spanWarningProcessing'; // orange
                break;

            case 19:
            case 20:
                return 'spanWarningInfo';
                break;

            case 5:
            case 7:
                return 'spanWarningProcessing'; // orange
                break;
            case 1://Duty to Manage
            case 9://Duty to Manage (Partial Responsibility Responsibility)
            case 10://Duty to Manage (Delegated Responsibility)
            case 11://Duty of Care
            case 12://Duty of Care (Employees Only)
                return 'spanWarningSurveying'; //red
                break;

            default:
                return 'spanWarningSuccess'; //green
                break;
        }
    }

    public static function allQuestion() {
        $questions = DropdownDataSurvey::where('dropdown_survey_id', DROPDOWN_SURVEY_ID)->where('parent_id', 0)->get();
        return !is_null($questions) ?  $questions : [];
    }

    public static function allAnswer() {
        $answers = DropdownDataSurvey::where('dropdown_survey_id', DROPDOWN_SURVEY_ID)->where('parent_id','!=', 0)->get();
        return !is_null($answers) ?  $answers : [];
    }

    public static function allAnswerQuestion($parent_id) {
        $answers = DropdownDataSurvey::where('dropdown_survey_id', DROPDOWN_SURVEY_ID)->where('parent_id', $parent_id)->select('id','description')->get()->toArray();
        return !is_null($answers) ?  $answers : [];
    }

    public static function allSeletedAnswers($survey_id) {
        return $selectedAnswer = SurveyAnswer::where('survey_id', $survey_id)->get();
    }

    public static function getSelectedMethodAnswer($survey_id, $question_id, $type = null) {
    $selectedAnswer = SurveyAnswer::where('survey_id', $survey_id)->where('question_id', $question_id)->first();
        if (!is_null($selectedAnswer)) {
            if ($type == SURVEY_ANSWER_OTHER) {
                return $selectedAnswer->answerOther;
            } else {
                return $selectedAnswer->answer_id;
            }
        }
    }

    public static function getMethodAnswerComment($survey_id, $question_id, $type = null) {
        $question = SurveyAnswer::where('survey_id', $survey_id)->where('question_id', $question_id)->first();
        // if ($type == 'other') {
            return !is_null($question) ?  $question->answerOther : '';
        // } else {
        //     return !is_null($question) ?  $question->comment : '';
        // }
    }

    public static function getDropdownById($id, $parent_id = 0) {
        $dropdowns = DropdownDataLocation::where('id', $id)->where('parent_id', $parent_id)->get();
        return !is_null($dropdowns) ? $dropdowns : [];
    }

    public static function checkArrayKey($data,$key) {
        return isset($data[$key]) ? $data[$key] : null;
    }

    public static function checkArrayKey2($data,$key) {
        return isset($data[$key]) ? (is_array($data[$key]) ? '' : $data[$key]) : 0;
    }

    public static function checkArrayKey3($data,$key) {
        return isset($data[$key]) ? $data[$key] : 0;
    }

    public static function getLocationOther($data,$key) {
        $data =  isset($data[$key]) ? $data[$key] : [];
        $data = array_filter($data);
        return implode("",$data);
    }
    public static function getMultiselectDataVoid($type, $typeOption) {
        if (!is_null($type)) {
            if (is_null($typeOption)) {
                return $type;
            } else {
                array_unshift($typeOption,$type);
                $data = implode(",",$typeOption);
                return $data;
            }
        }  else {
            return null;
        }

    }

    public static function getMultiselectDataContruction($typeOption) {
        if (!is_null($typeOption) and $typeOption !== '') {
            if (!is_array($typeOption)) {
                return $typeOption;
            } else {
                $data = implode(",",$typeOption);
                return $data;
            }
        }  else {
            return '';
        }
    }

    public static function getLocationVoidDetails($dropdownIds, $other) {
        if (is_null($dropdownIds) || $dropdownIds == "") {
            return '';
        }
        $dropdownIds = explode(",",$dropdownIds);
        $dropdownDescriptions = DropdownDataLocation::whereIn('id', $dropdownIds)->get();
        if (count(array_intersect($dropdownIds,[2281,2282,2283,2284,2285])) > 0) {
            return 'Out Of Scope';
        }
        $description = [];

        if (!is_null($dropdownDescriptions)) {
            foreach ($dropdownDescriptions as $dropdownDescription) {
                if ($dropdownDescription->other == 0) {
                    $description[] = $dropdownDescription->description;
                } else {
                    $description[] = $other;
                }
            }
            return implode(", ",$description);
        } else {
            return '';
        }
    }

    public static function getMasRiskColor($score) {
        switch (true) {
                case ($score == 0):
                    $color = "green";
                    break;
                case ($score < 5):
                    $color = "yellow";
                    break;
                case ($score < 7):
                    $color = "orange";
                    break;
                case ($score < 10):
                    $color = "brown";

                    break;
                case ($score >= 10 ):
                    $color = "red";
                    break;
                default:
                    $color = "green";
                    break;
        }
        return $color;
    }

    public static function getMasRiskText($score) {
        switch (true) {
                case ($score == 0):
                    $risk = "No Risk";
                    break;
                case ($score < 5):
                    $risk = "Very Low";
                    break;
                case ($score < 7):
                    $risk = "Low";
                    break;
                case ($score < 10):
                    $risk = "Medium";
                    break;
                case ($score >= 10):
                    $risk = "High";
                    break;
                default:
                    $risk = "";
                    break;
        }
        return $risk;
    }

    public static function getTotalRiskText($score) {
        switch (true) {
            case ($score == 0):
                $return['risk'] = "No Risk";
                $return['bg_color'] = "green";
                $return['color'] = "#FFF";
                break;
            case ($score < 10):
                $return['risk'] = "Very Low";
                $return['bg_color'] = "yellow";
                $return['color'] = "#000";
                break;
            case ($score < 14):
                $return['risk'] = "Low";
                $return['bg_color'] = "orange";
                $return['color'] = "#FFF";
                break;
            case ($score < 20):
                $return['risk'] = "Medium";
                $return['bg_color'] = "brown";
                $return['color'] = "#FFF";
                break;
            case ($score < 25):
                $return['risk'] = "High";
                $return['bg_color'] = "red";
                $return['color'] = "#FFF";
                break;
            default:
                $return['risk'] = "";
                $return['bg_color'] = "";
                $return['color'] = "#FFF";
                break;
        }
        return $return;
    }

    public static function getTotalText($number){
        switch (TRUE) {
            case $number == 0:
                $color = "green";
                $risk = "No Risk";
                break;
            case $number < 10:
                $color = "yellow";
                $risk = "Very Low";
                break;
            case $number < 14:
                $color = "orange";
                $risk = "Low";
                break;
            case $number < 20:
                $color = "brown";
                $risk = "Medium";
                break;
            case $number < 25:
                $color = "red";
                $risk = "High";
                break;
        }
        return ['color' => $color, 'risk' => $risk];
    }

    public static function getTotalRiskHazardText($score) {
        switch (true) {
            case ($score == 0):
                $return['risk'] = "No Risk";
                $return['bg_color'] = "badge-no-risk";
                $return['color'] = "#FFF";
                break;
            case ($score < 4):
                $return['risk'] = "Very Low";
                $return['bg_color'] = "badge-very-low-risk";
                $return['color'] = "#000";
                break;
            case ($score < 10):
                $return['risk'] = "Low";
                $return['bg_color'] = "badge-low-risk";
                $return['color'] = "#FFF";
                break;
            case ($score < 16):
                $return['risk'] = "Medium";
                $return['bg_color'] = "badge-medium-risk";
                $return['color'] = "#FFF";
                break;
            case ($score < 21):
                $return['risk'] = "High";
                $return['bg_color'] = "badge-high-risk";
                $return['color'] = "#FFF";
                break;
            case ($score < 26):
                $return['risk'] = "Very High";
                $return['bg_color'] = "badge-very-high-risk";
                $return['color'] = "#FFF";
                break;
        }
        return $return;
    }

    public static function getTotalHazardText($number){
        switch (TRUE) {
            case $number == 0:
                $color = "badge-no-risk";
                $risk = "No Risk";
                break;
            case $number < 4:
                $color = "badge-very-low-risk";
                $risk = "Very Low";
                break;
            case $number < 10:
                $color = "badge-low-risk";
                $risk = "Low";
                break;
            case $number < 16:
                $color = "badge-medium-risk";
                $risk = "Medium";
                break;
            case $number < 21:
                $color = "badge-high-risk";
                $risk = "High";
                break;
            case $number < 26:
                $color = "badge-very-high-risk";
                $risk = "Very High";
                break;
        }
        return ['color' => $color, 'risk' => $risk];
    }

    public static function getTotalHazardTextPDF($number){
        switch (TRUE) {
            case $number == 0:
                $color = "#28a745";
                break;
            case $number < 4:
                $color = "#ffff00";
                break;
            case $number < 10:
                $color = "#ffbf00";
                break;
            case $number < 16:
                $color = "#948a54";
                break;
            case $number < 21:
                $color = "#ff0000";
                break;
            case $number < 26:
                $color = "#c00000";
                break;
        }
        return $color;
    }

    public function getBreadcrumbRegister($section, $type, $id) {
        switch ($section) {
            case SECTION_DEFAULT:
                $breadcrumbs = [
                    'name' => 'properties',
                ];
                break;

            default:
                $color = 'orange';
                break;
        }
    }

    public static function getProductDebisText($product_debris) {
        $product_debris = str_replace('Non-asbestos ---','',  $product_debris);
        $product_debris = str_replace('Asbestos ---','',  $product_debris);
        $product_debris = str_replace('Other ---',' ',  $product_debris);
        $product_debris = str_replace('Other---',' ',  $product_debris);
        $product_debris = str_replace('---',' ',  $product_debris);
        return $product_debris;
    }

    public static function getAsbestosTypeText($asbestos_type) {
        $asbestos_type = str_replace('Other ---',' ',  $asbestos_type);
        $asbestos_type = str_replace('Other---',' ',  $asbestos_type);
        $asbestos_type = str_replace('---',' ',  $asbestos_type);
        $asbestos_type = str_replace(',',' and ',  $asbestos_type);
        return $asbestos_type;
    }

    public static function convertTimeStampToTime($time, $return = null) {
        if (is_null($time) || $time == 0) {
            return $return;
        }
        return date("d/m/Y", $time);
    }

    public static function getDaysRemaninng($dueTime) {

        $timeRemain = intval(($dueTime - time()) / 86400);
        $time = ($timeRemain > 0) ? $timeRemain : 0;

        return str_pad($time, 3, '0', STR_PAD_LEFT);
    }

    public static function getCriticalDaysRemaninng($dueTime) {

        $timeRemain = intval(($dueTime - time()) / 86400);

        return str_pad($timeRemain, 3, '0', STR_PAD_LEFT);
    }

    public static function getCriticalDaysDate($dueTime) {
        $dueTime = strtotime($dueTime);
        $timeRemain = intval(($dueTime - time()) / 86400);
        $time = ($timeRemain > 0) ? $timeRemain : 0;

        return str_pad($time, 3, '0', STR_PAD_LEFT);
    }
    public static function getDocumentDaysRemain($deadline) {
        if (is_null($deadline) || $deadline == 0) {
            $daysRemain = "NA";
        } else {
            $now = Carbon::now()->endOfDay()->timestamp;
            $daysRemain = ceil(($deadline - $now) / 86400);
            if ($daysRemain == -0) {
                return 0;
            }
        }

        return $daysRemain;
    }

    public static function getDocumentRiskColor($daysRemain) {

        $riskColor = "";

        if ($daysRemain <= 14) {
            $riskColor = "red";
        } elseif ($daysRemain >= 15 && $daysRemain <= 30) {
            $riskColor = "orange";
        } elseif ($daysRemain >= 31 && $daysRemain <= 60) {
            $riskColor = "yellow";
        } elseif ($daysRemain >= 61 && $daysRemain <= 120) {
            $riskColor = "blue";
        } else {
            $riskColor = "grey";
        }

        return $riskColor;

    }
    public static function convertArrayUnique2String($array) {
        $array = array_unique($array);
        $array = array_diff($array, [-1]);
        $array = implode(",", $array);
        $array = str_replace(',,', ',', $array);

        return $array;
    }

    public static function getProjectFileColor($status) {
        $color = 'orange';
        switch ($status) {
            case PROJECT_DOC_CREATED:
            case PROJECT_DOC_CANCELLED:
                $color = 'gray';
                break;
            case PROJECT_DOC_COMPLETED:
                $color = 'green';
                break;
            case PROJECT_DOC_REJECTED:
                $color = 'red';
                break;
            case PROJECT_DOC_PUBLISHED:
                $color = 'orange';
                break;
        }
        return $color;
    }

    public static function getDocumentReference($doc_cat, $id) {
        switch ($doc_cat) {
            case PLANNING_DOC_CATEGORY:
                $refDocument = "PD" . $id;
                break;
            case PRE_START_DOC_CATEGORY:
                $refDocument = "SD" . $id;
                break;
            case COMPLETION_DOC_CATEGORY:
                $refDocument = "CD" . $id;
                break;
            case TENDER_DOC_CATEGORY:
                $refDocument = "TD" . $id;
                break;
            case CONTRACTOR_DOC_CATEGORY:
                $refDocument = "OD" . $id;
                break;
            case SITE_RECORDS_DOC_CATEGORY:
                $refDocument = "RD" . $id;
                break;
            case GSK_DOC_CATEGORY:
                $refDocument = "GD" . $id;
                break;
            case PRE_CONSTRUCTION_DOC_CATEGORY:
            case DESIGN_DOC_CATEGORY:
                $refDocument = "DD" . $id;
                break;
            case COMMERCIAL_DOC_CATEGORY:
                $refDocument = "FD" . $id;
                break;
            default:
                $refDocument = "";
                break;
        }
        return $refDocument;
    }

    public static function getPropertyDropdownData($id) {
        $dropdowns = PropertyDropdown::where('id', $id)->value('description');
        return is_null($dropdowns) ? null : $dropdowns;
    }

    public static function getSurveyTitle($survey_type, $is_RD_element = 0){
        switch ($survey_type) {
            case 1:
                $surveyTitle = ($is_RD_element) ? "Management and Refurbishment Survey"
                    : "Management Survey";
                break;
            case 2:
                $surveyTitle = "Refurbishment Survey";
                break;
            case 3:
                $surveyTitle = "Re-Inspection Report";
                break;
            case 4:
                $surveyTitle = "Demolition Survey";
                break;
            case 5:
                $surveyTitle = "Management Survey Partial";
                break;
            default:
                $surveyTitle = "";
        }
        return $surveyTitle;
    }

    public static function generateQuarterTime() {
        $currentTime = time();
        $startMonth = 1;
        $startYear = 2018;
        $timeNext = mktime(0, 0, 0, $startMonth, 1, $startYear);

        while (TRUE) {
            $time = $timeNext;
            // set show options
            $oMonth = $startMonth;
            $oYear = ($oMonth > 12) ? $startYear + 1 : $startYear;
            $oQuarter = ($oMonth > 12) ? ceil($oMonth / 3) - 4 : ceil($oMonth / 3);
            // next loops
            $startMonth += 3;
            if ($startMonth > 12) {
                $startMonth = 2;
                $startYear++;
            }
            $timeNext = mktime(0, 0, 0, $startMonth, 1, $startYear);
            if ($timeNext > $currentTime) break;

            echo "<option value='" . $oQuarter . "," . $oYear ."' data-time-next='" . ($timeNext - 1) . "' name='department-systemowner'>Quarter #" . $oQuarter . "." . $oYear . "</option>";


        };
    }

    public static function renderTableSummary($samples)
    {
        $table = "";
        $table .= '<table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th width="15%">Sample</th>
                        <th width="15%">Room/location</th>
                        <th width="40%">Product Type</th>
                        <th width="30%">Asbestos Type</th>
                    </tr>
                    </thead>
                    <tbody>';
        if (count($samples)) {
            foreach ($samples as $sample) {
                $table .= '<tr>
                                <td>' . $sample->sampleReference . '</td>
                                <td>' . $sample->location->location_reference . '</td>
                                <td>' . $sample->productDebrisView->product_debris ?? '' . '</td>
                                <td>' . $sample->asbestosTypeView->asbestos_type ?? '' . '</td>

                            </tr>';
            }
        } else {
            echo "<tr><td colspan='4'>No Sample Found</td></tr>";
        }
        $table .= '</tbody>
                </table>';

        return $table;
    }

    public static function  getPdfViewing($status) {
        if ($status == 2) {
            return "<span class='orange_text'>Locked</span>";
        } else {
            switch ($status) {
                case 1:
                    $statusText = "orange";
                    break;
                case 2:
                    $statusText = "orange";
                    break;
                case 3:
                    $statusText = "orange";
                    break;
                case 4:
                    $statusText = "orange";
                    break;
                case 5:
                    $statusText = "green";
                    break;
                case 6:
                    $statusText = "red";
                    break;
                case 7:
                    $statusText = "orange";
                    break;
                default:
                    $statusText = "green";
                    break;
            }
            // Get latest published survey and display

            // if ($thisfile) {
                return '<a href=""><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>
                <a href="" class="btn"><i class="icon-download"></i></a>';
            // } else {
            //     return '<img src="img/nofile.png" width="19" height="19" alt="No File" border="0" />';
            // }
        }
    }

    public static function getLatestPdfBySurvey($survey_id){

        $published_survey = PublishedSurvey::where('survey_id', $survey_id)->orderBy('revision', 'desc')->first();
        return $published_survey->id ?? '';
    }

    public static function getLatestPdfBySurveyApproval($published_survey){
        // dd($published_survey->SortByDesc('revision'));
        $published_survey = $published_survey->SortByDesc('revision')->first();
        return $published_survey->id ?? '';
    }

    public static function  getPdfViewingSurvey($status, $link_view, $link_download) {
        switch ($status) {
            case 1:
            case 2:
            case 3:
                $statusText = "grey";
                break;
            case 4:
                $statusText = "orange";
                break;
            case 6:
            case 8:
                $statusText = "red";
                break;
            case 5:
            default:
                $statusText = "green";
                break;
        }

        return '<a href="'.$link_view.'" target="_blank" alt="View PDF" title="Document PDF"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" class="fileicon" alt="View File" border="0" /></a>
                <a href="'.$link_download.'" class="btn btn-outline-secondary btn-sm" alt="Download PDF" title="Document Download">
                    <i class="fa fa-download"></i>
                </a>';
    }

    public static function getSurveyPdfFile(){

    }

    public static function getFilePdfViewing($object_id , $type, $status = 0, $name = '', $property_id = 0) {
        $statusText = "";

        switch ($status) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 7:
                $statusText = "orange";
                break;
            case 6:
                $statusText = "red";
                break;
            case 5:
            default:
                $statusText = "green";
                break;
        }
        $file = ShineDocumentStorage::where('object_id', $object_id)->where('type', $type)->first();
        if (isset($file->path)) {
            if (is_file($file->path)) {
                $link = route('retrive_file',['type'=>  $type ,'id'=> $object_id, 'name' => $name, 'property_id' => $property_id ]);

                return '<a href="'.$link.'"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>
                <a href="'.$link.'" class="btn btn-outline-secondary btn-sm"><i class="fa fa-download"></i></a>';
            } else {
                return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
            }
        } else {
            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
        }
    }

    public static function getIncidentDocumentFilePdfViewing($object_id, $name = '', $status = 0) {
        // CHECK STATUS
        switch ($status) {
            case 1:
            case 2:
            case 3:
                $statusText = "orange";
                break;
            case 5:
                $statusText = "red";
                break;
            case 6:
                $statusText = "grey";
                break;
            case 4:
            default:
                $statusText = "orange";
                break;
        }
        $file = IncidentReportDocument::where('id', $object_id)->first();
        if (isset($file->path)) {
            if (is_file(storage_path().'/app/'.$file->path)) {
                $link = route('shineCompliance.incident_reporting.retrieve_document_file',['id'=> $object_id, 'name' => $name ]);

                return '<a href="'.$link.'"><img src="' . asset('img/pdf-'. $statusText .'.png') .'" width="19" height="19" alt="View File" border="0" /></a>
                <a href="" class="btn"><i class="icon-download"></i></a>';
            } else {
                return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
            }
        } else {
            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
        }
    }

    /**
     * GET WORK PDF VIEWING
     * @param $object_id
     * @param $status
     * @param bool $getLatest
     * @return string
     */
    public static function getWorkPdfViewing($object_id, $status, $getLatest = true) {

//        if ($status == 1) {
//            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
//        }
        // CHECK STATUS
        switch ($status) {
            case 1:
            case 2:
            case 3:
                $statusText = "orange";
                break;
            case 5:
                $statusText = "red";
                break;
            case 6:
                $statusText = "grey";
                break;
            case 4:
            default:
                $statusText = "green";
                break;
        }

        // GET LATEST OR NOT
        if ($getLatest) {
            $file = PublishedWorkRequest::where('work_request_id', $object_id)->orderBy('revision', 'desc')->first();
        }else{
            $file = PublishedWorkRequest::where('id',$object_id)->first();
        }

        // CHECK FILE EXIST
        if (isset($file->path) && is_file($file->path)) {
            $linkView = route('survey.view.pdf',['type'=> VIEW_WORK_PDF,'id'=> $file->id]);
            $linkDownload = route('survey.download.pdf',['type'=>VIEW_WORK_PDF,'id'=> $file->id]);
            if($status != 6){
                return '<a href="'.$linkView.'" target="_blank"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>
                    <a href="'.$linkDownload.'" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-download"></i>
                    </a>';
            } else {
                //for completed work request, disable previous revision pdf
                return '<a href="javascript:void(0)"><img title="PDF no longer available due to GDPR Regulations." src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>';
            }
        } else {
            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
        }
    }

    public static function getIncidentReportPdfViewing($object_id, $status, $getLatest = true)
    {
        // CHECK STATUS
        switch ($status) {
            case 1:
            case 2:
            case 3:
                $statusText = "orange";
                break;
            case 5:
                $statusText = "red";
                break;
            case 6:
                $statusText = "grey";
                break;
            case 4:
            default:
                $statusText = "green";
                break;
        }

        // GET LATEST OR NOT
        if ($getLatest) {
            $file = IncidentReportPublished::where('incident_id', $object_id)->orderBy('revision', 'desc')->first();
        }else{
            $file = IncidentReportPublished::where('id',$object_id)->first();
        }

        // CHECK FILE EXIST
        if (isset($file->path) && is_file($file->path)) {
            $linkView = route('shineCompliance.incident_reporting.view.pdf',['type'=> VIEW_SURVEY_PDF,'id'=> $file->id]);
            $linkDownload = route('shineCompliance.incident_reporting.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> $file->id]);
            if($status != 6){
                return '<a href="'.$linkView.'" target="_blank" alt="View PDF" title="Document PDF"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>
                    <a href="'.$linkDownload.'" class="btn btn-outline-secondary btn-sm" alt="Download PDF" title="Document Download">
                        <i class="fa fa-download"></i>
                    </a>';
            } else {
                //for completed work request, disable previous revision pdf
                return '<a href="javascript:void(0)"><img title="PDF no longer available due to GDPR Regulations." src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>';
            }
        } else {
            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
        }
    }

    public static function getSurveyPdfViewing($object_id, $status, $getLatest = true) {
        // CHECK STATUS
        switch ($status) {
            case 1:
            case 2:
            case 3:
            case 7:
                $statusText = "grey";
                break;
            case 4:
                $statusText = "orange";
                break;
            case 6:
            case 8:
                $statusText = "red";
                break;
            case 5:
            default:
                $statusText = "green";
                break;
        }

        // GET LATEST OR NOT
        $file = PublishedSurvey::where('survey_id', $object_id)->orderBy('revision', 'desc')->first();

        // CHECK FILE EXIST
        if (isset($file->path) && is_file($file->path)) {
            $linkView = route('survey.view.pdf',['type'=> VIEW_SURVEY_PDF,'id'=> $file->id]);
            $linkDownload = route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> $file->id]);
            return '<a href="'.$linkView.'" target="_blank"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>
                    <a href="'.$linkDownload.'" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-download"></i>
                    </a>';
        } else {
            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
        }
    }

    public static function checkProjectCompletedProgressStage($project_id, $stage = PROJECT_STAGE_PRE_CONSTRUCTION)
    {
        $stages = [
            PROJECT_STAGE_PRE_CONSTRUCTION,
            PROJECT_STAGE_DESIGN,
            PROJECT_STAGE_COMMERCIAL,
            PROJECT_STAGE_PLANNING,
            PROJECT_STAGE_PRE_START,
            PROJECT_STAGE_SITE_RECORD,
            PROJECT_STAGE_COMPLETION,
        ];
        $project = Project::where('id', $project_id)->first();
        if ($project) {
            if ($project->status == PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS) {
                if (array_search($stage, $stages) < array_search($project->progress_stage, $stages)) {
                    return true;
                } elseif (array_search($stage, $stages) == array_search($project->progress_stage, $stages)) {
                    $stageDocuments = $project->document()
                                              ->where('category', $project->progress_stage)->count();
                    $stageCompletedDocuments = $project->document()
                                                        ->where('category', $project->progress_stage)
                                                        ->where('status', PROJECT_DOC_COMPLETED)->count();
                    if ($stageDocuments == $stageCompletedDocuments) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public static function getProjectDocViewing($object_id , $type, $status = 0, $name = '', $property_id = 0, $auto_approve = 0) {
        switch ($status) {
            case PROJECT_DOC_COMPLETED:
                $statusText = "green";
                break;
            case PROJECT_DOC_REJECTED:
                $statusText = "red";
                break;
            case PROJECT_DOC_PUBLISHED:
                $statusText = "orange";
                break;
            default:
                $statusText = "grey";
                break;
        }
        if ($auto_approve == 1) {
            $statusText = "blue";
        }
        $file = ShineDocumentStorage::where('object_id', $object_id)->where('type', $type)->first();
        if (isset($file->path)) {
            if (is_file($file->path)) {
                $link = route('retrive_file',['type'=>  $type ,'id'=> $object_id, 'property_id' => $property_id ]);

                return '<a href="'.$link.'"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>
                <a href="" class="btn"><i class="icon-download"></i></a>';
            } else {
                return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
            }
        } else {
            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
        }
    }

    public static function getProjectDocuments($object_id , $type, $status = 0, $name = '', $property_id = 0) {
        $statusText = "";

        switch ($status) {
            case 1:
                $statusText = 'orange';
                break;
            case 2:
                $statusText = 'green';
                break;
            case 3:
                $statusText = 'red';
                break;
            default:
                $statusText = 'orange';
        }
        $file = ShineDocumentStorage::where('object_id', $object_id)->where('type', $type)->first();

        if (isset($file->path)) {
            if (is_file($file->path)) {
                $link = route('retrive_file',['type'=>  $type ,'id'=> $object_id, 'name' => $name, 'property_id' => $property_id ]);

                return '<a href="'.$link.'"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" alt="View File" border="0" /></a>
                <a href="" class="btn"><i class="icon-download"></i></a>';
            } else {
                return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
            }
        } else {
            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
        }
    }

    public static function pdfDisplayRow($items, $criteria, $risk, $riskScore, $numberItem, $summary_type, $category, $categoryScore, $is_pdf = false) {
        if (count($items)) {
            $count = 0;
            $groups = [];
            if ($summary_type == "volume") {
                foreach ($items as $item) {
                    switch ($criteria) {
                        case "estate":
                        case "zone":
                        case "property":
                            $propID = $item->property_id;
                            $propName = $item->property->name ?? '';
                            break;
                        case "areafloor":
                            $propID = $item->area_id;
                            $propName = $item->area->area_reference ?? '';
                            break;
                        case "roomlocation":
                            $propID = $item->location_id;
                            $propName = $item->location->location_reference ?? '';
                            break;
                    }

                    if (isset($groups[$propID]) && isset($groups[$propID][$item->productDebrisView->product_debris])) {

                        $groups[$propID][$item->productDebrisView->product_debris]['frequency']++;
                        if ($groups[$propID][$item->productDebrisView->product_debris]['extent'] == $item->Extent) {
                            $groups[$propID][$item->productDebrisView->product_debris]['volume'] += $item->asbestosQuantityValue;
                        } else {
                            $groups[$propID][$item->productDebrisView->product_debris]['other'] .= " (" . $item->asbestosQuantityValue . " " . $item->Extent . ")";
                        }
                    } else {
                        $groups[$propID][$item->productDebrisView->product_debris] = [
                            'propName'  => $propName,
                            'name'      => $item->productDebrisView->product_debris,
                            'frequency' => 1,
                            'volume'    => $item->asbestosQuantityValue,
                            'extent'    => $item->Extent,
                            'other'     => ""
                        ];
                    }
                    $count++;
                    if ($count == $numberItem && $numberItem != "allitem") {
                        break;
                    }
                }

                foreach ($groups as $group) {
                    foreach ($group as $row) {
                        echo "<tr><td style='padding-left:19px'>" . $row['propName'] . "</td>";
                        echo "<td style='padding-left:5px'>" . $row['name'] . "</td>";
                        echo "<td align=\"center\">" . $row['frequency'] . "</td>";
                        echo "<td style='padding-left: 69px;'>" . $row['volume'] . " " . $row['extent'] . $row['other'] . "</td></tr>";
                    }
                }


                //end summary = volume
            } else {
                // switch ($criteria) {
                //     case "estate":
                //     case "zone":
                //         usort($items, ['items', 'compareByPropertyAndLocationAndMAS']);
                //         break;
                //     case "property":
                //         usort($items, ['items', 'compareByAreaAndLocationAndMAS']);
                //         break;
                //     case "areafloor":
                //         usort($items, ['items', 'compareByLocationAndMAS']);
                //         break;
                //     case "roomlocation":
                //         usort($items, ['items', 'compareMAS']);
                //         break;
                // }
                foreach ($items as $item) {

                    echo "<tr>";
                    echo "<td align=\"center\">$item->reference</td>";
                    switch ($criteria) {
                        case "estate":
                        case "zone":
                            echo "<td style='padding-left:19px'> " . ($item->property->name ?? '') . " </td>";
                            echo "<td style='padding-left:19px'> " . ($item->location->location_reference ?? '') . " </td>";
                            break;
                        case "property":
                            echo "<td style='padding-left:19px'> " . $item->area->area_reference ?? '' . " </td>";
                            echo "<td style='padding-left:19px'> " . ($item->location->location_reference ?? ''). " </td>";
                            break;
                        case "areafloor":
                        case "roomlocation":
                            echo "<td style='padding-left:19px'> " . ($item->location->location_reference ?? ''). " </td>";
                            break;
                    }
                    echo "<td style='padding-left:5px'> " . optional($item->productDebrisView)->product_debris . "</td>";
                    echo "<td align=\"center\">";
                    if ($item->total_risk <= 0) {
                        echo "N/A";
                    } else {
                        switch ($summary_type) {
                            case "material":
                                $colour = \CommonHelpers::getMasRiskColor($item->total_mas_risk);
                                $pas = sprintf("%02d", number_format($item->total_mas_risk));
                                break;
                            case "priority":
                                $colour = \CommonHelpers::getMasRiskColor($item->total_pas_risk);
                                $pas = sprintf("%02d", number_format($item->total_pas_risk));
                                break;
                            case "priorityforaction":
                                $result = self::getTotalRiskText($item->total_risk);
                                $colour = $result['bg_color'];
                                $pas = sprintf("%02d", number_format($item->total_risk));
                                break;
                            case "riskassessment":
                                $colour_mas = \CommonHelpers::getMasRiskColor($item->total_mas_risk);
                                $mas_score = sprintf("%02d", number_format($item->total_mas_risk));
                                $colour_pas = \CommonHelpers::getMasRiskColor($item->total_pas_risk);
                                $pas_score = sprintf("%02d", number_format($item->total_pas_risk));
                                $colour = \CommonHelpers::getTotalText($item->total_risk)['color'];
                                $oas_score = sprintf("%02d", number_format($item->total_risk));
                            default:
                                $colour = \CommonHelpers::getTotalText($item->total_risk)['color'];
                                $pas = sprintf("%02d", number_format($item->total_risk));
                            break;
                                break;
                        }
                        if($summary_type == "riskassessment"){
                            $src_mas = \CommonHelpers::getAssetFile('img/ass-'.$colour_mas.'.png', $is_pdf);
                            echo "<img class=\"paddingTopImg\" src=\"$src_mas\" /> $mas_score". " </td>";
                            $src_pas = \CommonHelpers::getAssetFile('img/ass-'.$colour_pas.'.png', $is_pdf);
                            echo "<td align=\"center\">"."<img class=\"paddingTopImg\" src=\"$src_pas\" /> $pas_score". " </td>";
                            $src_oas = \CommonHelpers::getAssetFile('img/ass-'.$colour.'.png', $is_pdf);
                            echo "<td align=\"center\">"."<img class=\"paddingTopImg\" src=\"$src_oas\" /> $oas_score";
                        } else {
                            $src = \CommonHelpers::getAssetFile('img/ass-'.$colour.'.png', $is_pdf);
                            echo "<img class=\"paddingTopImg\" src=\"$src\" /> $pas";
                        }
                    }
                    echo "</td></tr>";
                    $count++;
                    if ($count == $numberItem && $numberItem != "allitem") {
                        break;
                    }
                }
            }

            if ($count == 0) {
                echo "<tr><td colspan='5' align='center'><strong>No data found</strong></td></tr>";
            }
        } else {
            if($summary_type == "priorityforaction"){
                echo "<tr><td colspan='5' align='center'><strong>No data found</strong></td></tr>";
            }
            echo "<tr><td colspan='7' align='center'><strong>No data found</strong></td></tr>";
        }
    }

    public static function DisplayCategoryBox($score) {
    $result = [];
    $color = $cate = "";
    switch ($score) {
        case $score < 8 && $score > -1:
            $color = "green";
            $cate = "D";
            break;
        case $score > 7 && $score < 13:
            $color = "yellow";
            $cate = "C";
            break;
        case $score > 12 && $score < 17:
            $color = "orange";
            $cate = "B";
            break;
        case $score > 16:
            $color = "red";
            $cate = "A";
            break;
        default:
            break;
    }
    $result[] = $color;
    $result[] = $cate;

    return $result;
}
    //return a new sample for data in chart
    public static function createChartData($name, $total = 0, $y = 0, $color = 'transparent', $data = [], $other = false){
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

    public static function setChartData($old_data, $data, $y = null, $is_custome_total = 0)
    {
        if (array_key_exists('data', $old_data) && isset($data)) {
            $old_data['data'][] = intval($data);
        }
        if (array_key_exists('total', $old_data)) {
            if(isset($data)){
                if($is_custome_total && is_numeric($is_custome_total) && $is_custome_total != 0){
                    $old_data['total'] += 1;
                } else {
                    $old_data['total'] += $data;
                }
            } else {
                //for pie
                $old_data['total'] = $y;
            }

        }
        if (array_key_exists('y', $old_data) && isset($y)) {
            $old_data['y'] = intval($y);
        }
        return $old_data;
    }

    public static function logAudit($object_type, $object_id, $action_type, $object_reference, $object_parent_id = 0, $comments =  null, $archive_id = 0, $property_id = 0) {
        $auditType = (in_array($object_type, [TEMPLATE_TYPE, USER_TYPE])) ? "admin" : "system";
        if (is_null($comments)) {
            if ($action_type == AUDIT_ACTION_ADD) {
                $comment_action = 'added new';
            } elseif ($action_type == AUDIT_ACTION_EDIT) {
                $comment_action = 'edited';
            } elseif ($action_type == AUDIT_ACTION_VIEW) {
                $comment_action = 'viewed';
            } else{
                $comment_action = $action_type;
            }

            $comments = \Auth::user()->full_name . " " . $comment_action . " " . $object_type . " " . $object_reference;
        }

        if (in_array($object_reference , ['areaCheck', 'roomCheck', 'asbestosRegister'])) {
           $property_id = $object_parent_id;
        }
        if(\Auth::user()->client_id == 1) {
            $department = \Auth::user()->department;
        } else {
            $department = \Auth::user()->departmentContractor;
        }
        $user_depart = self::getDepartmentRecursiveNameAudit($department);

        $data = [
            'property_id' => $property_id,
            'type' => $auditType,
            'object_type' => $object_type,
            'object_id' => $object_id,
            'object_parent_id' => $object_parent_id,
            'object_reference' => $object_reference,
            'action_type' => $action_type,
            'user_id' => \Auth::user()->id,
            'user_client_id' => \Auth::user()->client_id,
            'user_name' => \Auth::user()->full_name,
            'department' => $user_depart,
            'date' => time(),
            'archive_id' => $archive_id,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'comments' => $comments
        ];

        try {
            //queue log audit
            \Queue::laterOn(LOG_AUDIT_QUEUE,30,new LogAuditTrail($data));
        } catch (Exception $e) {

        }
    }

    public static function get_dates_of_quarter($quarter = 'current', $year = null, $format = null)
    {
        if ( !is_numeric($year) ) {
            $year = (new DateTime)->format('Y');
        }
        $current_quarter = ceil((new DateTime)->format('n') / 3);
        switch (  strtolower($quarter) ) {
            case 'this':
            case 'current':
                $quarter = ceil((new DateTime)->format('n') / 3);
                break;

            case 'previous':
                $year = (new DateTime)->format('Y');
                if ($current_quarter == 1) {
                    $quarter = 4;
                    $year--;
                } else {
                    $quarter =  $current_quarter - 1;
                }
                break;

            case 'first':
                $quarter = 1;
                break;

            case 'last':
                $quarter = 4;
                break;

            default:
                $quarter = (!is_int($quarter) || $quarter < 1 || $quarter > 4) ? $current_quarter : $quarter;
                break;
        }
        if ( $quarter === 'this' ) {
            $quarter = ceil((new DateTime)->format('n') / 3);
        }
        $start = new DateTime($year.'-'.(3*$quarter-2).'-1 00:00:00');
        $end = new DateTime($year.'-'.(3*$quarter).'-'.($quarter == 1 || $quarter == 4 ? 31 : 30) .' 23:59:59');

        return array(
            'start' => $format ? $start->format($format) : $start,
            'end' => $format ? $end->format($format) : $end,
        );
    }

    public static function getAuditObjectType($type) {
        switch ($type) {
            case PROPERTY_PDF:
                $type = PROPERTY_TYPE;
                break;

            case TRAINING_RECORD_FILE:
                $type = TRAINING_RECORD_TYPE;
                break;

            case PLAN_FILE:
                $type = SITE_PLAN_DOCUMENT_TYPE;
                break;

            case HISTORICAL_DATA:
                $type = HISTORICAL_DATA_TYPE;
                break;

            case SAMPLE_CERTIFICATE_FILE:
                $type = SAMPLE_CERTIFICATE_TYPE;
                break;

            case AIR_TEST_CERTIFICATE_FILE:
                $type = AIR_TEST_CERTIFICATE_TYPE;
                break;

            case POLICY_FILE:
                $type = POLICY_TYPE;
                break;

            case RESOURCE_DOCUMENT:
                $type = RESOURCE_DOCUMENT_TYPE;
                break;

            default:
                $type = DOCUMENT_TYPE;
                break;
        }
        return $type;

    }

    public static function getCounter($type) {
        $next_number = Counter::where('count_table_name',$type)->first()->total ?? 1;
        $ss_ref = "SS" . sprintf("%03d", $next_number);

        Counter::where('count_table_name','summaries')->update(['total'=> $next_number +1]);
        return $next_number;
    }

    public static function changeSurveyStatus($survey_id) {
        Survey::where('id', $survey_id)->where('status', SENT_BACK_FROM_DEVICE_SURVEY_STATUS)->update(['status' => READY_FOR_QA_SURVEY_STATUS]);
    }

    public static function isRegisterUpdated($property_id, $done = false) {
        if (isset($property_id)) {
            $registerUpdate = ($done) ? 0 : 1;
            Property::where('id', $property_id)->update(['register_updated' => $registerUpdate]);

            $property = Property::find($property_id);
            $property->zone->last_revision = time();
            $property->push();
        }
        if (!$done) {
            // \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmail($property_id, ASBESTOS_REGISTER_EMAILTYPE));
        }
    }

    public static function getDecommissionReasonWarning($type) {
        switch ($type) {
            case 'area':
                return 'Please note this action will decommission all Rooms/items within the floor. Are you sure you want to Decommission this Floor?';
                break;

            case 'location':
                return 'Please note this action will decommission all Items within the Room/location';
                break;

            case 'item':
                return 'Please note this action may break any links to other Samples. Are you sure you want to do this?';
                break;

            default:
                # code...
                break;
        }
    }

    public static function getRecommissionReasonWarning($type) {
        switch ($type) {
            case 'area':
                return 'Please note this action will Recommission all Rooms/items within the floor';
                break;

            case 'location':
                return 'Please note this action will Recommission all Items within the Room/location';
                break;

            case 'item':
                return '';
                break;

            default:
                # code...
                break;
        }
    }

    public static function getItemStateText($isACM, $isAccessible, $isFullAssessment) {
        if ($isACM == 0) {
            return 'noacm';
        } elseif($isACM == 1 and $isAccessible == 1) {
            return 'accessible';
        } elseif( $isACM == 1 and $isAccessible == 0 and $isFullAssessment == 0 ) {
            return 'inaccessibleLimited';
        } elseif( $isACM == 1 and $isAccessible == 0 and $isFullAssessment == 1 ) {
            return 'inaccessibleFull';
        }
    }

    public static function getItemStateFromText($text) {
        $state = 0;
        $isFullAssessment = 0;

        switch ($text) {
            case 'noacm':
                $state = ITEM_NOACM_STATE;
                break;

            case 'accessible':
                $state = ITEM_ACCESSIBLE_STATE;
                break;

            case 'inaccessibleLimited':
                $state = ITEM_INACCESSIBLE_STATE;
                $isFullAssessment = ITEM_LIMIT_ASSESSMENT;
                break;

            case 'inaccessibleFull':
                $state = ITEM_INACCESSIBLE_STATE;
                $isFullAssessment = ITEM_FULL_ASSESSMENT;
                break;

            default:
                # code...
                break;
        }

        return [
            'state' => $state,
            'isFullAssessment' => $isFullAssessment
        ];
    }

    public static function getGetAdminAsbestosLead() {
        $user_ids =  ShineAsbestosLeadAdmin::all()->pluck('user_id')->toArray();
        return $user_ids;
    }

    public static function getOsItem($original_item_id, $survey_id) {
        $item = Item::where('record_id', $original_item_id)->where('survey_id', $survey_id)->first();
        return $item->id ?? 0;
    }

    //first_page_url
    //last_page_url
    //next_page_url
    //prev_page_url
    //total
    //current_position
    public static function setPathPagination($request, $data, $type, $id_check = 0) {
        //for area, location, item
        //set first_page_url, last_page_url, next_page_url, prev_page_url
        $first_position = $next_position = $previous_position = $last_position = $total  = 0;
        $pagination = [];
        if($data){
            $total = count($data);
            if(isset($request->position) && $total > 0){
                //current = 5, total = 6=> no next no last
                $next_position = $request->position + 1 < $total ? $request->position + 1 : $total - 1;
                $previous_position = $request->position - 1 >= 0 ? $request->position - 1 : 0;
                $last_position = $total - 1;
            }

            $pagination['total'] = count($data);
            if($type){
                if($type == 'sample'){
                    $pagination['first_page_url'] = route('sample_getEdit', ['survey_id' => isset($data[$first_position]->survey_id) ? $data[$first_position]->survey_id : '','sample_id' => isset($data[$first_position]->id) ? $data[$first_position]->id : '','position' => $first_position,  'pagination_type' => TYPE_SAMPLE]);
                    $pagination['prev_page_url'] = route('sample_getEdit', ['survey_id' => isset($data[$previous_position]->survey_id) ? $data[$previous_position]->survey_id : '','sample_id' => isset($data[$previous_position]->id) ? $data[$previous_position]->id : '','position' => $previous_position, 'pagination_type' => TYPE_SAMPLE]);
                    $pagination['next_page_url'] = route('sample_getEdit', ['survey_id' => isset($data[$next_position]->survey_id) ? $data[$next_position]->survey_id : '','sample_id' => isset($data[$next_position]->id) ? $data[$next_position]->id : '','position' => $next_position, 'pagination_type' => TYPE_SAMPLE]);
                    $pagination['last_page_url'] = route('sample_getEdit', ['survey_id' => isset($data[$last_position]->survey_id) ? $data[$last_position]->survey_id : '','sample_id' => isset($data[$last_position]->id) ? $data[$last_position]->id : '','position' => $last_position, 'pagination_type' => TYPE_SAMPLE]);
                } else {
                    //area location
                    $pagination['first_page_url'] = $request->fullUrlWithQuery(['position' => $first_position, $type => isset($data[$first_position]->id) ? $data[$first_position]->id : '']);
                    $pagination['prev_page_url'] = $request->fullUrlWithQuery(['position' => $previous_position, $type => isset($data[$previous_position]->id) ? $data[$previous_position]->id : '']);
                    $pagination['next_page_url'] = $request->fullUrlWithQuery(['position' => $next_position, $type => isset($data[$next_position]->id) ? $data[$next_position]->id : '']);
                    $pagination['last_page_url'] = $request->fullUrlWithQuery(['position' => $last_position, $type => isset($data[$last_position]->id) ? $data[$last_position]->id : '']);
                }
            } else {
                //id = substr($url, strrpos($url, '/') + 1);
                // route('item.index', ['id' => $dataRow->id,'position' => $position,'category' => isset($type) ? $type : '', 'pagination_type' => $pagination_type]
                $pagination['first_page_url'] = route('item.index', ['id' => isset($data[$first_position]->id) ? $data[$first_position]->id : '','position' => $first_position, 'category' => $request->category, 'pagination_type' => $request->pagination_type]);
                $pagination['prev_page_url'] = route('item.index', ['id' => isset($data[$previous_position]->id) ? $data[$previous_position]->id : '','position' => $previous_position,'category' => $request->category, 'pagination_type' => $request->pagination_type]);
                $pagination['next_page_url'] = route('item.index', ['id' => isset($data[$next_position]->id) ? $data[$next_position]->id : '','position' => $next_position,'category' => $request->category, 'pagination_type' => $request->pagination_type]);
                $pagination['last_page_url'] = route('item.index', ['id' => isset($data[$last_position]->id) ? $data[$last_position]->id : '','position' => $last_position,'category' => $request->category, 'pagination_type' => $request->pagination_type]);
            }
            $pagination['data_current'] = isset($data[$request->position]->id) && $id_check == $data[$request->position]->id ? $data[$request->position] : NULL ;
            $pagination['current_page'] = $request->position;

        }

        return isset($pagination['data_current']) ? $pagination : [];
    }

    public static function get_data_stamping($data){
        $user_created = User::with('clients', 'department', 'departmentContractor')->find($data->created_by ?? 0);
        $user_updated = User::with('clients', 'department', 'departmentContractor')->find($data->updated_by ?? 0);

        $result_return = [
            'data_stamping' => isset($data->updated_at) ? $data->updated_at->format('d/m/Y H:i') : 'N/A',
            'organisation'  => !is_null($user_updated) ? ($user_updated->clients->name ?? '' ).' - ' .\CommonHelpers::getDepartmentname($user_updated) : 'N/A',
            'username'      =>  $user_updated->full_name ?? 'N/A',
            'data_stamping_create'  => isset($data->created_at) ? $data->created_at->format('d/m/Y H:i') : 'N/A',
            'organisation_create'   => !is_null($user_created) ? ($user_created->clients->name ?? '' ).' - ' .\CommonHelpers::getDepartmentname($user_created) : 'N/A',
            'username_create'    =>  $user_created->full_name ?? 'N/A',
        ];

        return $result_return;
    }

    public static function checkDecommissionPermission() {
        if (in_array(\Auth::user()->id, [3,5, 7, 64, 131, 488, 728,675,131,488]) || (isset(\Auth::user()->userRole->id) && \Auth::user()->userRole->id == 1)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getDepartmentname($user) {
        if ($user->client_id == 1) {
            return $user->department->name ?? '';
        } else {
            return $user->departmentContractor->name ?? '';
        }
    }

    public static function getDepartmentRecursiveName($department) {
        $count = 1;
        if (isset($department->parents) and !is_null($department->parents)) {
            $count = self::countRecursiveParent($department->parents, $count);
        }
        return self::departmentRecursiveName($count);
    }

    public static function countRecursiveParent($department, $count) {
        $count ++ ;
        if (isset($department->parents) and !is_null($department->parents) ) {
            return self::countRecursiveParent($department->parents,$count);
        }
        return $count;
    }


    public static function departmentRecursiveName($count) {
        switch ($count) {
            case 0:

                return 'Directorate';
                break;
            case 1:

                return 'Division';
                break;
            case 2:

                return 'Department';
                break;

            case 3:
                return 'Teams';
                break;

            default:
                return '';
                break;
        }
    }

    public static function getDepartmentRecursiveNameAudit($department) {

        $name = [];
        $name[] = $department->name ?? '';
        if (isset($department->parents) and !is_null($department->parents)) {
            $name = self::deparmentRecursiveParent($department->parents, $name);

        }
        $name = array_reverse($name);
        return implode(" --- ",$name);
    }

    public static function deparmentRecursiveParent($department, $name) {
        $name[] = $department->name;
        if (isset($department->parents) and !is_null($department->parents) ) {
            return self::deparmentRecursiveParent($department->parents,$name);
        }
        return $name;
    }

    public static function regexXML($column, $response){
        $matches = array();
        preg_match_all('/<+(.*)>(.*)<\/+(.*)'.$column.'>/', $response, $matches);
        return isset($matches[2][0]) ? $matches[2][0] : "";
    }

    public static function regexAttrXML($column, $response, $status = 200){
        $matches = array();
        preg_match_all('/<'.$column.'>(\n*.*\n*.*\n*)<\/'.$column.'>/', $response, $matches);
        if($status == 200){
            return isset($matches[1][0]) ? $matches[1][0] : "";
        } else {
            return date("Y-m-d h:i:s") . ": server returned $status error during API call \n" . (isset($matches[1][0]) ? $matches[1][0] : "");
        }
    }

    public static function checkXMLData($list, $response){
        $len = count($list);
        $matches = array();
        for ($i = 0; $i < $len; $i ++){
            preg_match_all('/<+(.*)>(.*)<\/+(.*)'.$list[$i].'>/', $response, $matches);
            if(!isset($matches[2][0]) || (isset($matches[2][0]) && strlen($matches[2][0]) == 0)){
                // if one of attributes is null then return this attr
                return $list[$i];
            }
        }

        return "";
    }

    public static function getAdminToolActionText($action) {
        switch ($action) {
           case 'removeGroup':
               $name = 'Remove Group';
               break;

           case 'removeDocument':
               $name = 'Remove Document';
               break;

           case 'removeProperty':
               $name = 'Remove Property';
               break;

            case 'removeRegisterLocation':
               $name = 'Remove Register Location';
               break;

            case 'removeRegisterArea':
               $name = 'Remove Register Area';
               break;

           case 'removeSurvey':
               $name = 'Remove Survey';
               break;

            case 'removeRegisterItem':
               $name = 'Remove Register Item';
               break;

           case 'removeProject':
               $name = 'Remove Project';
               break;

            case 'remove_property_plan':
               $name = 'Remove Property Plan';
               break;

            case 'remove_property_historical':
               $name = 'Remove Historical Document';
               break;

            case 'remove_survey_ac':
               $name = 'Remove Air Test Certificate';
               break;

            case 'remove_survey_sc':
               $name = 'Remove Sample Certificate';
               break;

            case 'remove_survey_plan':
               $name = 'Remove Survey Plan';
               break;

            case 'remove_tender_doc':
               $name = 'Remove Tender Document';
               break;

            case 'remove_contractor_doc':
               $name = 'Remove Contractor Document';
               break;

            case 'remove_gsk_doc':
               $name = 'Remove GSK Document';
               break;

            case 'remove_incident_doc':
               $name = 'Remove Incident Report Document';
               break;

           case 'moveSurvey':
               $name = 'Move Survey';
           break;

           case 'moveLocation':
               $name = 'Move Location';
           break;

           case 'moveItem':
               $name = 'Move Item';
           break;

           case 'moveProject':
               $name = 'Move Project';
           break;

           case 'moveProject':
               $name = 'Move Project';
           break;

           case 'moveSurveyRoom':
               $name = 'Move Survey Room';
           break;

           case 'moveSurveyItem':
               $name = 'Move Survey Item';
           break;

           case 'unlockSurvey':
               $name = 'Unlock Survey';
           break;

           case 'mergeRoom':
               $name = 'Merge Room';
           break;

           case 'mergeArea':
               $name = 'Merge Area';
           break;

           case 'mergeSurvey':
               $name = 'Merge Survey';
           break;

           case 'mergeAreas':
               $name = 'Merge Area/Floors';
           break;

           case 'revertSurvey':
               $name = 'Reject Completed Survey';
           break;

           case 'revertProjects':
               $name = 'Revert Project';
           break;

           default:
                $name = '';
               break;
        }
        return $name;
    }

    public static function  getDocumentsViewing($status, $link_view, $link_download) {
        switch ($status) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 7:
                $statusText = "orange";
                break;
            case 6:
                $statusText = "red";
                break;
            case 5:
            default:
                $statusText = "green";
                break;
        }

        return '<a href="'.$link_view.'" target="_blank"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" class="fileicon" alt="View File" border="0" /></a>
                <a href="'.$link_download.'" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-download"></i>
                </a>';
    }

    public static function getProductDebrisAttribute ($value) {
        $product_debris = str_replace('Non-asbestos ---','',  $value);
        $product_debris = str_replace('Asbestos ---','',  $product_debris);
        $product_debris = str_replace('Other ---',' ',  $product_debris);
        $product_debris = str_replace('Other---',' ',  $product_debris);
        $product_debris = str_replace('---',' ',  $product_debris);
        return $product_debris;
    }


    public static function getDataIncidentReportInvolvedPerson($id) {
        if($id == "24" || $id == "47"){
            return null;
        }else{
            $id_remove_other_1 = str_replace("24, ","", $id);
            $id_remove_other_2 = str_replace(", 24","", $id_remove_other_1);
            $id_remove_other_3 = str_replace("47, ","", $id_remove_other_2);
            $id_remove_other_4 = str_replace(", 47","", $id_remove_other_3);
            $id_remove_other = str_replace("24","", $id_remove_other_4);
            $array_id = explode(', ',str_replace("47","", $id_remove_other));
            $description = IncidentReportDropdownData::whereIn('id',$array_id)->pluck('description')->implode(', ');
            return $description;
        }
    }
    public static function getDataInvolvedReasonOther($reasons = null)
    {
        $result = '';
        if (!empty($reasons)) {
            $reasons = trim($reasons, ", ");
            $result = ", " . $reasons;
        }
        return $result;
    }

    public static function getConvertValueToArray($data) {
        if (!is_null($data) and $data !== '') {
            $data = explode(",",$data);
            return $data;
        } else {
            return '';
        }
    }

    public static function convertTimeStamp($time, $return = null)
    {
        if (is_null($time) || $time == 0) {
            return $return;
        }
        return date("Y/m/d", $time);
    }

    public static function getRiskProgrammeText($number){
        switch (TRUE) {
            case $number <= 0:
                $color = "badge-high-risk";
                $risk = "Overdue";
                break;
            case $number < 15:
                $color = "badge-high-risk";
                $risk = "Critical";
                break;
            case $number <= 30:
                $color = "badge-low-risk text-dark";
                $risk = "Urgent";
                break;
            case $number <= 60:
                $color = "badge-very-low-risk text-dark";
                $risk = "Important";
                break;
            case $number <= 120:
                $color = "badge-primary";
                $risk = "Attention";
                break;
            case $number > 120:
                $color = "badge-secondary";
                $risk = "Deadline";
                break;
//            case $number < 26:
//                $color = "badge-very-high-risk";
//                $risk = "Very High";
//                break;
            default:
                $color = "";
                $risk = "";
                break;

        }
        return ['color' => $color, 'risk' => $risk];
    }
    public static function setStockChartData($old_data, $data, $is_custome_total = 0, $has_data,$other)
    {
        if (array_key_exists('data', $old_data) && is_array($data)) {
            $old_data['data'] = $data;
        }
        if (array_key_exists('total', $old_data)) {
            if(isset($data) && $has_data){
                if($is_custome_total && is_numeric($is_custome_total) && $is_custome_total != 0){
                    $old_data['total'] += 1;
                } else {
                    $old_data['total'] += $data[1] ?? 0;
                }
            }
        }
        if (array_key_exists('other', $old_data) && $other) {
            $old_data['other'] = $other;// legend
        }
        return $old_data;
    }

    public static function sharedTooltip($old_data, $other)
    {
        if ($other) {
            $old_data['other'] = $other;// legend
        }
        return $old_data;
    }
}
