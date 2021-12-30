<?php
namespace App\Helpers;
use App\Jobs\LogAuditTrail;
use App\Models\ShineCompliance\AssessmentFireSafetyAnswer;
use App\Models\ShineCompliance\AssessmentManagementAnswer;
use App\Models\ShineCompliance\AssessmentStatementAnswer;
use App\Models\ShineCompliance\ComplianceDocumentStorage;
use App\Models\ShineCompliance\EquipmentDropdownData;
use App\Models\ShineCompliance\PublishedAssessment;
use App\Models\ShineCompliance\ShineDocumentStorage;
use App\Models\ShineCompliance\EquipmentTemplateSection;
use App\Jobs\LogComplianceAuditTrail;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Imagick;

class ComplianceHelpers {

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

    public static function getFileStoragePath($object_id, $type, $survey_id = 0, $api = false , $audit = false) {
        $date = Carbon::now();
        switch ($type) {
            case USER_SIGNATURE:
                $particularPath = USER_SIGNATURE_PATH;
                break;
            case AVATAR:
                $particularPath = AVATAR_PATH;
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

            case AUDIT_USER_SIGNATURE:
                $particularPath = AUDIT_USER_SIGNATURE_PATH;
                break;

            case COMPLIANCE_SYSTEM_PHOTO:
                $particularPath = COMPLIANCE_SYSTEM_PHOTO_PATH;

            case COMPLIANCE_PROGRAMME_PHOTO:
                $particularPath = COMPLIANCE_PROGRAMME_PHOTO_PATH;
                break;
            case EQUIPMENT_PHOTO:
                $particularPath = EQUIPMENT_PHOTO_PATH;
                break;
            case EQUIPMENT_LOCATION_PHOTO:
                $particularPath = EQUIPMENT_LOCATION_PHOTO_PATH;
                break;
            case EQUIPMENT_ADDITION_PHOTO:
                $particularPath = EQUIPMENT_ADDITION_PHOTO_PATH;
                break;
            case COMPLIANCE_DOCUMENT_PHOTO:
                $particularPath = COMPLIANCE_DOCUMENT_PHOTO_PATH;
                break;
            case COMPLIANCE_HISTORICAL_DOCUMENT_PHOTO:
                $particularPath = COMPLIANCE_HISTORICAL_DOCUMENT_PHOTO_PATH;
                break;


            default:
                $particularPath = null;
                break;
        }
        $path = 'data/'. ( $api ? 'api/' : '') . ( $audit ? 'audit/' : '') . $particularPath .'/'. $date->format('Y/m/d')   .'/' .$object_id. '/';
        return $path;

    }

    public static function saveFileComplianceDocumentStorage($file, $id, $type, $survey_id = 0) {
        if (!is_null($file) and $file->isValid()) {
            try {
                $path = self::getFileStoragePath($id, $type, $survey_id);
                \Storage::disk('local')->put($path, $file);
                // create thumbnail and crop img for items, losing quality for locations/property
                if(in_array($type, [
                    EQUIPMENT_LOCATION_PHOTO,
                    EQUIPMENT_PHOTO,
                    EQUIPMENT_ADDITION_PHOTO,
                    COMPLIANCE_SYSTEM_PHOTO
                ])){
                    self::createThumbnail($path. $file->hashName(), true);
                }

                ComplianceDocumentStorage::updateOrCreate([ 'object_id' => $id, 'type' => $type],
                    [
                        'path' => $path. $file->hashName(),
                        'file_name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'addedBy' => \Auth::user()->id,
                        'addedDate' =>  Carbon::now()->timestamp,
                    ]);
                return self::successResponse('Store file successfully', $file->hashName());
            } catch (Exception $e) {
                return self::failResponse(STATUS_FAIL,'Failed to store file. Please try again !');
            }
        } else {
            return self::failResponse(STATUS_FAIL,'File not exist or invalid !');
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

    public static function getSystemFile($object_id, $type, $is_pdf = false) {
        switch ($type) {
            case COMPLIANCE_SYSTEM_PHOTO:
                 $no_img = '/img/sys_no_img.png';
                break;

            case COMPLIANCE_PROGRAMME_PHOTO:
                 $no_img = '/img/system.png';
                break;

            case EQUIPMENT_PHOTO:
                $no_img = '/img/equipment_no_img1.png';
                break;

            case EQUIPMENT_LOCATION_PHOTO:
                $no_img = '/img/item_location_no_img.png';
                break;

            case EQUIPMENT_ADDITION_PHOTO:
                $no_img = '/img/additional_no_img.png';
                break;

            case AREA_IMAGE:
                 $no_img = '/img/area-floor_no_img.png';
                break;

            default:
                $no_img = '/img/no-image-icon.png';
                break;
        }

        $file = ComplianceDocumentStorage::where('object_id', $object_id)->where('type', $type)->first();
        if (isset($file->path)) {
            $path_thumb = THUMB_NAIL . '/'. $file->path;
            if(is_file($path_thumb)){
                if($is_pdf){
                    return public_path() . '/'.$path_thumb;
                } else {
                    return asset($path_thumb);
                }

            } else if (is_file($file->path)) {
                if($is_pdf){
                    return public_path() . '/'.$file->path;
                } else {
                    return asset($file->path);
                }
            } else if (is_file( public_path() . '/'.$file->path)) {

                if($is_pdf){
                    return public_path() . '/'.$file->path;
                } else {
                    return asset($file->path);
                }
            } else {
                return $is_pdf == true ? public_path($no_img) : url($no_img) ;
            }
        } else {
            return $is_pdf == true ? public_path($no_img) : url($no_img) ;
        }
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

    public static function checkArrayKey($data,$key) {
        return isset($data[$key]) ? $data[$key] : null;
    }

    public static function getFileImage($object_id, $type, $is_pdf = false) {
        $type_check = $type;

        if($type == SUB_PROPERTY_IMAGE){
            $type_check = PROPERTY_IMAGE;
        }

        $file = ShineDocumentStorage::where('object_id', $object_id)->where('type', $type_check)->first();
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
                switch ($type) {
                    case PROPERTY_IMAGE:
                        return $is_pdf == true ? public_path('/img/pro_no_img.png') : url('/img/pro_no_img.png') ;
                        break;
                    case AVATAR:
                        return $is_pdf == true ? public_path('/img/profile_no_img.png') : url('/img/profile_no_img.png') ;
                        break;
                    case USER_SIGNATURE:
                        return $is_pdf == true ? public_path('/img/signature_no_img.png') : url('/img/signature_no_img.png') ;
                        break;
                    case ZONE_PHOTO:
                        return $is_pdf == true ? public_path('/img/zone_no_img.png') : url('/img/zone_no_img.png') ;
                        break;
                    case SUB_PROPERTY_IMAGE:
                        return $is_pdf == true ? public_path('/img/sub_no_img.png') : url('/img/sub_no_img.png') ;
                        break;
                    case LOCATION_IMAGE:
                        return $is_pdf == true ? public_path('/img/room_no_img.png') : url('/img/room_no_img.png') ;
                        break;
                    case ITEM_PHOTO_LOCATION:
                        return $is_pdf == true ? public_path('/img/item_no_photo.png') : url('/img/item_no_photo.png') ;
                        break;
                    case HAZARD_PHOTO:
                    case ITEM_PHOTO:
                        return $is_pdf == true ? public_path('/img/item_no_photo.png') : url('/img/item_no_photo.png') ;
                        break;
                    case HAZARD_LOCATION_PHOTO:
                    case EQUIPMENT_LOCATION_PHOTO:
                    case ITEM_LOCATION_PHOTO:
                        return $is_pdf == true ? public_path('/img/item_location_no_photo.png') : url('/img/item_location_no_photo.png') ;
                        break;
                    case HAZARD_ADDITION_PHOTO:
                    case ITEM_PHOTO_ADDITIONAL:
                    case EQUIPMENT_ADDITION_PHOTO:
                        return $is_pdf == true ? public_path('/img/additional_no_img.png') : url('/img/additional_no_img.png') ;
                        break;
                    case ASSEMBLY_POINT_PHOTO:
                    case FIRE_EXIT_PHOTO:
                    case VEHICLE_PARKING_PHOTO:
                    case PROPERTY_SURVEY_IMAGE:
                        return $is_pdf == true ? public_path('/img/No_Image_Available.png') : url('/img/No_Image_Available.png');
                    default:
                        // chuỗi câu lệnh
                        break;
                }

            }
        } else {
            switch ($type) {
                case PROPERTY_IMAGE:
                    return $is_pdf == true ? public_path('/img/pro_no_img.png') : url('/img/pro_no_img.png') ;
                    break;
                case AVATAR:
                    return $is_pdf == true ? public_path('/img/profile_no_img.png') : url('/img/profile_no_img.png') ;
                    break;
                case USER_SIGNATURE:
                    return $is_pdf == true ? public_path('/img/signature_no_img.png') : url('/img/signature_no_img.png') ;
                    break;
                case ZONE_PHOTO:
                    return $is_pdf == true ? public_path('/img/zone_no_img.png') : url('/img/zone_no_img.png') ;
                    break;
                case SUB_PROPERTY_IMAGE:
                    return $is_pdf == true ? public_path('/img/sub_no_img.png') : url('/img/sub_no_img.png') ;
                    break;
                case LOCATION_IMAGE:
                    return $is_pdf == true ? public_path('/img/room_no_img.png') : url('/img/room_no_img.png') ;
                    break;
                case ITEM_PHOTO_LOCATION:
                    return $is_pdf == true ? public_path('/img/item_no_photo.png') : url('/img/item_no_photo.png') ;
                    break;
                case HAZARD_PHOTO:
                case ITEM_PHOTO:
                    return $is_pdf == true ? public_path('/img/item_no_photo.png') : url('/img/item_no_photo.png') ;
                    break;
                case EQUIPMENT_LOCATION_PHOTO:
                case HAZARD_LOCATION_PHOTO:
                case ITEM_LOCATION_PHOTO:
                    return $is_pdf == true ? public_path('/img/item_location_no_img.png') : url('/img/item_location_no_img.png') ;
                    break;
                case HAZARD_ADDITION_PHOTO:
                case EQUIPMENT_ADDITION_PHOTO:
                case ITEM_PHOTO_ADDITIONAL:
                    return $is_pdf == true ? public_path('/img/additional_no_img.png') : url('/img/additional_no_img.png') ;
                    break;
                case ASSEMBLY_POINT_PHOTO:
                case FIRE_EXIT_PHOTO:
                case VEHICLE_PARKING_PHOTO:
                case PROPERTY_SURVEY_IMAGE:
                    return $is_pdf == true ? public_path('/img/No_Image_Available.png') : url('/img/No_Image_Available.png');
                default:
                    // chuỗi câu lệnh
                    break;
            }
        }
    }

    public static function isSystemClient() {
        return \Auth::user()->clients->client_type == 0 ? true : false;
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

    public static function saveFileShineDocumentStorage($file, $id, $type, $survey_id = 0) {
        if (!is_null($file) and $file->isValid()) {
            try {
                $path = ComplianceHelpers::getFileStoragePath($id, $type, $survey_id);
                Storage::disk('local')->put($path, $file);
                //create thumbnail and crop img for items, losing quality for locations/property
                if(in_array($type, [PROPERTY_PHOTO,LOCATION_IMAGE,ITEM_PHOTO,ITEM_PHOTO_LOCATION,ITEM_PHOTO_ADDITIONAL,PROPERTY_SURVEY_IMAGE])){
                    self::createThumbnail($path. $file->hashName(), true);
                }
                ShineDocumentStorage::updateOrCreate(
                    [ 'object_id' => $id, 'type' => $type],
                    [
                        'path' => $path. $file->hashName(),
                        'file_name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'addedBy' => \Auth::user()->id,
                        'addedDate' =>  Carbon::now()->timestamp,
                    ]);
                return ComplianceHelpers::successResponse('Store file successfully', $file->hashName());
            } catch (Exception $e) {
                return ComplianceHelpers::failResponse(STATUS_FAIL,'Failed to store file. Please try again !');
            }
        } else {
            return ComplianceHelpers::failResponse(STATUS_FAIL,'File not exist or invalid !');
        }
    }

    public static function checkFile($object_id, $type) {
        $file = ComplianceDocumentStorage::where('object_id', $object_id)->where('type', $type)->first();
        if (!is_null($file)) {
            if (is_file($file->path)) {
               return true;
            }
        }
        return false;

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

    public static function getMultiselectData($data) {
        if (is_array($data)) {
            return implode(",",$data);
        }
        return null;
    }

    public static function getFireSafety($fire_safety, $fire_safety_other)
    {
        if (!empty($fire_safety)) {
            $fire_safety_arr = explode(',', $fire_safety);
            $fire_safety_display = [];
            $has_other = false;
            foreach ($fire_safety_arr as $item) {
                $fire_safety_answer = AssessmentFireSafetyAnswer::where('id', $item)->first();
                if ($fire_safety_answer && !$fire_safety_answer->other) {
                    $fire_safety_display[] = $fire_safety_answer->description;
                } else {
                    $has_other = true;
                }
            }

            if ($has_other && !empty($fire_safety_other)) {
                $fire_safety_display[] = $fire_safety_other;
            }
            return $fire_safety_display;
        }

        return [];
    }

    public static function getEquipmentMultiselect($data) {

        $sql = "SELECT group_concat(description SEPARATOR ', ') as des FROM `cp_equipment_dropdown_data` where FIND_IN_SET(id,'$data')";
        $data = \DB::select($sql);
        return $data[0]->des ?? '';
    }

    public static function getEquipmentMultiselectWitOther($dropdownData, $object, $otherKey)
    {
        $values = '';
        $data = explode(',', $dropdownData);

        foreach ($data as $dropdown) {
            $dropdownValue = EquipmentDropdownData::find($dropdown);
            if ($dropdownValue) {
                if(!$dropdownValue->other) {
                    $values .= $dropdownValue->description ?? '';
                } else {
                    $values .= $object->{$otherKey};
                }
                $values .= ', ';
            }
        }
        return rtrim($values, ', ');
    }

    public static function getPhColor($ph) {
        if (is_null($ph)) {
            return '';
        }

        $ph = intval($ph);
        switch ($ph) {
            case 0:
                $color = '#e71b24';
                break;
            case 1:
                $color = '#fa5424';
                break;
            case 2:
                $color = '#ffa425';
                break;
            case 3:
                $color = '#ffcd16';
                break;
            case 4:
                $color = '#dddf26';
                break;
            case 5:
                $color = '#94d61d';
                break;
            case 6:
                $color = '#4bb500';
                break;
            case 7:
                $color = '#009c0d';
                break;
            case 8:
                $color = '#00a65c';
                break;
            case 9:
                $color = '#00bfb9';
                break;
            case 10:
                $color = '#0188c8';
                break;
            case 11:
                $color = '#004cc6';
                break;
            case 12:
                $color = '#3226b4';
                break;
            case 13:
                $color = '#4816b5';
                break;
            case 14:
                $color = '#3a118c';
                break;

            default:
                $color = '';
                break;
        }

        return $color;
    }

    public static function equipmentActiveField($template_id, $field) {
        $active = EquipmentTemplateSection::where('template_id', $template_id)->where('field', $field)->first();

        return $active ? true : false;
    }

    public static function getYesNoFromVarible($data) {
        if ($data) {
            return 'Yes';
        }
        return 'No';
    }

    public static function getDateTimeToStringToday() {
        return Carbon::now()->toDayDateTimeString();
    }

    public static function  getPdfViewingAssessment($status, $link_view, $link_download) {
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

        return '<a href="'.$link_view.'" target="_blank" alt="View PDF" title="Document PDF"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" class="fileicon" alt="View File" border="0" /></a>
                <a href="'.$link_download.'" class="btn btn-outline-secondary btn-sm" alt="Download PDF" title="Document Download">
                    <i class="fa fa-download"></i>
                </a>';
    }

    public static function getAssessmentStatementAnswer($statement_id)
    {
        $answer = AssessmentStatementAnswer::where('id', $statement_id)->first();
        if ($answer) {
            return $answer->description;
        }

        return '';
    }

    public static function getAssessmentManagementInfoAnswerDescription($answer_id)
    {
        $answer = AssessmentManagementAnswer::where('id', $answer_id)->first();
        if ($answer) {
            return $answer->description;
        }

        return '';
    }

    public static function getProjectDocumentCategory($cat) {
        switch ($cat) {
            case PLANNING_DOC_CATEGORY:
                return 'Planning';
                break;

            case PRE_START_DOC_CATEGORY:
                return 'Pre-Start';
                break;

            case SITE_RECORDS_DOC_CATEGORY:
                return 'Site Records';
                break;

            case COMPLETION_DOC_CATEGORY:
                return 'Completion';
                break;

            case APPROVAL_DOC_CATEGORY:
                return 'Approval';
                break;

            default:
                return '';
                break;
        }
    }

    public static function responseDataTable($draw = 0, $recordsTotal = 0, $recordsFiltered = 0, $data = []){
        //The draw counter that this object is a response to - from the draw parameter sent as part of the data request
        // https://datatables.net/manual/server-side#Returned-data
        return collect(['draw' => $draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered, 'data' => $data]);
    }

    public static function getCriticalTypeText($type, $type_id) {
        switch ($type) {
            case 'assessment':

                    if( $type_id == ASSESS_TYPE_FIRE_EQUIPMENT) { return 'Fire Equipment Assessment'; }

                    elseif( $type_id == ASSESS_TYPE_FIRE_RISK_TYPE_1) { return 'Fire Risk Assessment (Type 1)'; }

                    elseif( $type_id == ASSESS_TYPE_FIRE_RISK_TYPE_2) { return 'Fire Risk Assessment (Type 2)'; }

                    elseif( $type_id == ASSESS_TYPE_FIRE_RISK_TYPE_3) { return 'Fire Risk Assessment (Type 3)'; }

                    elseif( $type_id == ASSESS_TYPE_FIRE_RISK_TYPE_4) { return 'Fire Risk Assessment (Type 4)'; }

                break;
            case 'audit':
                if ($type_id == 1) {
                    return 'Asbestos Analyst (LW) Audit';
                } elseif($type_id == 2) {
                    return 'Asbestos Analyst (NNL & NL) Audit';
                } elseif($type_id == 3) {
                    return 'Asbestos Surveyor Audit';
                } elseif($type_id == 4) {
                    return 'Asbestos LARC (LW) Audit';
                } elseif($type_id == 5) {
                    return 'Asbestos LARC (NNL & NL) Audit';
                }
                break;
            case 'survey':
                if ($type_id == 1) {
                    return 'Management Survey';
                } elseif($type_id == 2) {
                    return 'Refurbishment Survey';
                } elseif($type_id == 3) {
                    return 'Re-Inspection Survey';
                } elseif($type_id == 4) {
                    return 'Demolition Survey';
                } elseif($type_id == 5) {
                    return 'Management Survey – Partial';
                }
                break;
            default:
                return '';
                break;
        }

        return '';
    }

    public static function getAssessmentPdfViewing($object_id, $status,  $link_view, $link_download) {
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
        $file = PublishedAssessment::where('id', $object_id)->orderBy('revision', 'desc')->first();
        // CHECK FILE EXIST
        if (isset($file->path) && is_file($file->path)) {
            return '<a href="'.$link_view.'" target="_blank"><img src="' . asset('img/pdf-'.$statusText.'.png') .'" width="19" height="19" class="fileicon" alt="View File" border="0" /></a>
                <a href="'.$link_download.'" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-download"></i>
                </a>';
        } else {
            return '<img src="'.asset('img/nofile.png').'" width="19" height="19" alt="No File" border="0" />';
        }
    }

    public static function getAssessmentDecomissionReasonType($type) {

        $decomission_type = 'assessment_water';
        switch ($type) {
            case ASSESSMENT_FIRE_TYPE:
                $decomission_type = "assessment_fire";
                break;
            case ASSESSMENT_WATER_TYPE:
                $decomission_type = "assessment_water";
                break;
            case ASSESSMENT_HS_TYPE:
                $decomission_type = "assessment_hs";
                break;
            default:
                $decomission_type = "assessment_water";
                break;
        }

        return $decomission_type;
    }
}
