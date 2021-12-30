<?php
namespace App\Repositories;
use App\Models\UploadManifest;
use App\Models\UploadDocumentStorage;
use App\Models\ShineAppDocumentStorage;
use App\Models\ShineDocumentStorage;
use App\Models\Survey;
use App\Models\Item;
use App\Models\ItemInfo;
use App\Models\SurveyInfo;
use App\Models\SurveyDate;
use App\Models\SitePlanDocument;
use App\Models\Sample;
use App\Models\DropdownDataProperty;
use App\Models\Area;
use App\Models\Location;
use App\Models\LocationInfo;
use App\Models\LocationVoid;
use App\Models\LocationConstruction;
use App\Models\DropdownDataLocation;
use App\Models\DropdownItem\ProductDebrisType;//3
use App\Models\DropdownItemValue\ProductDebrisTypeValue;//3
use App\Models\DropdownItem\Extent;//4
use App\Models\DropdownItemValue\ExtentValue;//4
use App\Models\DropdownItem\AsbestosType;//5
use App\Models\DropdownItemValue\AsbestosTypeValue;//5
use App\Models\DropdownItem\ActionRecommendation;//7
use App\Models\DropdownItemValue\ActionRecommendationValue;//7
use App\Models\DropdownItem\AdditionalInformation;//8
use App\Models\DropdownItemValue\AdditionalInformationValue;//8
use App\Models\DropdownItem\SampleComment;//9
use App\Models\DropdownItemValue\SampleCommentValue;//9
use App\Models\DropdownItem\SpecificLocation;//11
use App\Models\DropdownItemValue\SpecificLocationValue;//11
use App\Models\DropdownItem\AccessibilityVulnerability;//12
use App\Models\DropdownItemValue\AccessibilityVulnerabilityValue;//12
use App\Models\DropdownItem\LicensedNonLicensed;//13
use App\Models\DropdownItemValue\LicensedNonLicensedValue;//13
use App\Models\DropdownItem\UnableToSample;//14
use App\Models\DropdownItemValue\UnableToSampleValue;//14
use App\Models\DropdownItem\ItemNoAccess;//15
use App\Models\DropdownItemValue\ItemNoAccessValue;//15
use App\Models\DropdownItem\NoACMComments;//16
use App\Models\DropdownItemValue\NoACMCommentsValue;//16
use App\Models\DropdownItem\PriorityAssessmentRisk;//18
use App\Models\DropdownItemValue\PriorityAssessmentRiskValue;//18
use App\Models\DropdownItem\MaterialAssessmentRisk;//19
use App\Models\DropdownItemValue\MaterialAssessmentRiskValue;//19
use App\Models\DropdownItem\SampleId;//500
use App\Models\DropdownItemValue\SampleIdValue;//500
use Prettus\Repository\Eloquent\BaseRepository;
use App\Jobs\SendClientEmail;
use Carbon\Carbon;

class UploadManifestRepository extends BaseRepository {

    private $dataSample = [];

    function model()
    {
        return UploadManifest::class;
    }

    public function createManifest($survey_id) {
        try {
            $dataCreate = [
                'survey_id' => $survey_id,
                'status' => 'uploading',
            ];
            $manifest = UploadManifest::create($dataCreate);
            return \CommonHelpers::successResponse('Create manifest successfully !',$manifest->id);
        } catch (\Exception $e) {
            return \CommonHelpers::failResponse(STATUS_FAIL,$e->getMessage());
        }
    }

    public function createUploadData($type, $manifest_id, $object_data) {
        try {
            if ($type == 'survey') {
                $datas = json_decode($object_data, TRUE);

                // update manifest from data
                UploadManifest::where('id', $manifest_id)
                                ->update([
                                    'total_floor' => intval($datas['floorCount']),
                                    'total_room' => intval($datas['roomCount']),
                                    'total_record' => intval($datas['recordCount']),
                                    'total_image' => intval($datas['imageCount']),
                                    'total_plan' => intval($datas['plansCount']),
                                ]);
            }

            // insert yo upload data
            $uploadData = UploadDocumentStorage::create([
                'manifest_id' => $manifest_id,
                'type' => $type,
                'data' => $object_data,
                'survey_id' => $datas['surveyDetailId'] ?? 0
            ]);

            return \CommonHelpers::successResponse('Create upload data successfully !', $uploadData->id);
        } catch (\Exception $e) {
            return \CommonHelpers::failResponse(STATUS_FAIL,$e->getMessage());
        }
    }

    public function createUploadImage($type, $record_id, $manifest_id, $survey_id, $file) {
        if (!is_null($file) and $file->isValid()) {
            try {
                $path = \CommonHelpers::getFileStoragePath($record_id, $type, $survey_id, true);
                \Storage::disk('local')->put($path, $file);
                ShineAppDocumentStorage::create(
                    [
                        'manifest_id' => $manifest_id,
                        'record_id' => $record_id,
                        'type' => $type,
                        'survey_id' => $survey_id,
                        'path' => $path. $file->hashName(),
                        'file_name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);

                return \CommonHelpers::successResponse('Upload Api file successfully', $file->hashName());
            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,$e->getMessage());
            }
        } else {
            return \CommonHelpers::failResponse(STATUS_FAIL,'File not exist or invalid !');
        }
    }

    public function insertUploadData($manifest_id) {
        try {
            $survey_id =  UploadManifest::where('id', $manifest_id)->value('survey_id');
            $survey = Survey::find($survey_id);
            if (is_null($survey)) {
                return \CommonHelpers::failResponse(403,'Survey does not exist');
            }
            // No Duplicated sent back
            if ($survey->status == LOCKED_SURVEY_STATUS) {
                $this->uploadData($manifest_id, $survey_id, $survey->property_id);

                 $this->uploadPhoto($manifest_id, $survey_id);
                 UploadManifest::where('id', $manifest_id)->update(['status' => 'completed']);
                 return \CommonHelpers::successResponse('Upload Api Data successfully');
            } else {
                return \CommonHelpers::failResponse(403,'Survey does not ready for send back');
            }

        } catch (\Exception $e) {
            return \CommonHelpers::failResponse(500,$e->getMessage());
        }

    }

    public function uploadData($manifest_id, $survey_id, $property_id) {
        $list_data = UploadDocumentStorage::where('manifest_id', $manifest_id)->get();

         // CHECK INACCESSIBLE SURVEY
        foreach ($list_data as $update) {
            if ($update->type == 'survey') {
                $data_update = json_decode($update->data);
                $data = json_decode(json_encode($data_update));

                if ($data->isInaccessible == 1) {
                   $this->updateSurvey($data, true, $property_id, $data->reason);
                   //send email abort
                   return false;
                }
            }
        }
        $linkedItems = [];
        // NORMAL SURVEY
        foreach ($list_data as $update) {
            $data_update = json_decode($update->data, TRUE);
            $data = json_decode(json_encode($data_update));


            switch ($update->type) {
                case 'area':
                    $area_id = ($data->isNew == 1) ? false : $data->floorDetailId;
                    $this->updateOrCreateArea($data, $area_id, $survey_id, $property_id, $update->id);
                    break;

                case 'room':
                    $location_id = ($data->isNew == 1) ? false : $data->roomDetailId;
                    $this->updateOrCreateLocation($data, $location_id, $survey_id, $property_id, $update->id);
                    break;

                case 'record':

                    // create or update OS item first for exsit sample

                    if (isset($data->sample->isOS) and $data->sample->isOS == 1) {
                        $item_id = ($data->isNew == 1) ? false : $data->recordDetailId;
                        $this->updateOrCreateItem($data, $item_id, $survey_id, $property_id, $manifest_id, $update->id);
                    } else {
                        $linkedItems[] = $update;
                    }
                    break;
                case 'pplan':
                    $this->createPPlan($data, $survey_id, $property_id, $update->id);
                    break;

                case 'survey':

                    $this->updateSurvey($data, false, $property_id);
                    break;

                default:

                    break;
            }
        }

        // create or update VRS item
        if (count($linkedItems) > 0) {
            foreach ($linkedItems as $update) {

                $data_update = (json_decode($update->data, TRUE));
                $data = json_decode(json_encode($data_update));

                $item_id = ($data->isNew == 1) ? false : $data->recordDetailId;
                $this->updateOrCreateItem($data, $item_id, $survey_id, $property_id, $manifest_id, $update->id);
            }
        }

    }


    //update survey property setting from mobile data
    public function updateSurvey($data, $isInaccessible = false, $property_id, $reason = 0) {
        $status = $isInaccessible ? ABORTED_SURVEY_STATUS : SENT_BACK_FROM_DEVICE_SURVEY_STATUS;

        $dataAsset =  ($data->assetUse ?? null);
        $dataAssetType =  ($data->propertyAccessType ?? null);

        $dataUpdate['PrimaryUse'] = $dataAsset->primary ?? null;
        $dataUpdate['primaryusemore'] = $dataAsset->primaryOther ?? null;
        $dataUpdate['SecondaryUse'] = $dataAsset->secondary ?? null;
        $dataUpdate['secondaryusemore'] = $dataAsset->secondaryOther ?? null;
        $dataUpdate['programmeType'] = ($dataAssetType->accessPrimary ?? null);


        $dataConstruct =  ($data->construction ?? null);

        $dataUpdate['constructionAge'] = $dataConstruct->assetAge ?? null;
        $dataUpdate['constructionType'] = $dataConstruct->contructionType ?? null;
        $dataUpdate['electricalMeter'] = ($dataConstruct->electricalMeter ?? null);
        $dataUpdate['gasMeter'] = ($dataConstruct->gasMeter ?? null);
        $dataUpdate['loftVoid'] = ($dataConstruct->loftVoid ?? null);

        $dataSizeVolume =  ($data->sizeVolume ?? null);

        $dataUpdate['sizeFloors'] = $dataSizeVolume->numberFloors ?? null;
        $dataUpdate['sizeStaircases'] = $dataSizeVolume->numberStaircases ?? null;
        $dataUpdate['sizeLifts'] = $dataSizeVolume->numberLifts ?? null;
        $dataUpdate['sizeNetArea'] = $dataSizeVolume->netAreaPerFloor ?? null;
        $dataUpdate['sizeGrossArea'] = $dataSizeVolume->grossArea ?? null;
        $dataUpdate['sizeComments'] = $dataSizeVolume->comments ?? null;
        $dataUpdate['property_status'] = $data->propertyStatus ?? null;
        $dataUpdate['property_occupied'] = $data->propertyOccupied ?? null;

        $survey_comment = Survey::find($data->surveyDetailId);
        if ($dataUpdate['sizeComments']) {
            // store comment history
            \CommentHistory::storeCommentHistory('property', $property_id, $dataUpdate['sizeComments'], $survey_comment->reference ?? null);
        }
        $dataSurvey =  json_encode($dataUpdate);

        Survey::where('id', $data->surveyDetailId)->update(['status' => $status, 'is_locked' => SURVEY_UNLOCKED]);
        SurveyInfo::where('survey_id', $data->surveyDetailId)->update(['property_data' => $dataSurvey]);
        SurveyDate::where('survey_id', $data->surveyDetailId)->update(['sent_back_date' => time()]);
    }

    public function getSurveyInfoScore($id) {
        $data = DropdownDataProperty::find($id);
        return is_null($data) ? 0 : $data->score;
    }

    public function updateOrCreateArea($data, $area_id, $survey_id, $property_id, $upload_id){
        $objectStatus = $data->objectStatus ?? 0;
        $statusReason = $data->statusReason ?? 0;
        if ($objectStatus == RELEASE_FROM_SCOPE) {
            $area_survey = Area::find($area_id);
            //unlock from register
            Area::where('record_id', ($area_survey->record_id ?? 0))->where('survey_id', 0)->update(['is_locked' => 0]);
            Area::where('id', $area_id)->forcedelete();
        } else {
            $dataFloorDetails =  $data->floorDetails ?? null;
            $survey = Survey::find($survey_id);
            $dataArea = [
                'property_id' => $property_id,
                'survey_id' => $survey_id,
                'is_locked' => 0,
                'description' => $dataFloorDetails->description ?? null,
                'area_reference' => $dataFloorDetails->reference ?? null,
            ];
            if ($objectStatus == DECOMMISSION) {
                $dataArea['decommissioned'] = $objectStatus ?? 0;
                $dataArea['decommissioned_reason'] = $statusReason ?? 0;

            } else {
                $dataArea['decommissioned'] = 0;
                $dataArea['not_assessed'] = $objectStatus ?? 0;
                $dataArea['not_assessed_reason'] = $statusReason ?? 0;
            }

            if ($area_id) {
                $dataArea['updated_by'] = $survey->surveyor_id ?? 0;
                Area::where('id', $area_id)->update($dataArea);
                $area = Area::find($area_id);
            } else {
                $dataArea['created_by'] = $survey->surveyor_id ?? 0;
                $area = Area::create($dataArea);
                if ($area) {
                    $refArea = "AF" . $area->id;
                    Area::where('id', $area->id)->update(['record_id' => $area->id, 'reference' => $refArea]);
                }
            }
            if ($objectStatus == DECOMMISSION) {
                // store comment history
                \CommentHistory::storeDeccomissionHistory('decommission','area', $area->id, $statusReason, $area->survey->reference ?? null);
            }
            //update correct id
            $this->updateCorrectId($area->id, $upload_id);
        }

    }

    public function updateOrCreateLocation($data, $location_id, $survey_id, $property_id, $upload_id) {
        $objectStatus = $data->objectStatus ?? 0;
        $statusReason = $data->statusReason ?? 0;
        if ($objectStatus == RELEASE_FROM_SCOPE) {
            $location_survey = Location::find($location_id);
            //unlock from register
            Location::where('record_id', ($location_survey->record_id ?? 0))->where('survey_id', 0)->update(['is_locked' => 0]);

            Location::where('id', $location_id)->forcedelete();
        } else {
            $parent_id = $data->parentId;
                $area_id = UploadDocumentStorage::where('id', $parent_id)->value('correct_id');
                $area = Area::find($area_id);
                if (is_null($area)) {
                    if ($location_id) {
                        Location::where('id', $location_id)->forcedelete();
                    }
                    return false;
                }


            $dataRoomDetails =  ($data->roomLocationDetails);
            $survey = Survey::find($survey_id);
            $dataLocation = [
                    'area_id'                      => $area_id,
                    'survey_id'                    => $survey_id,
                    'property_id'                  => $property_id,
                    'is_locked'                    => 0,
                    'state'                        => isset($data->isAccessible) and ($data->isAccessible == 1) ? LOCATION_STATE_ACCESSIBLE : LOCATION_STATE_INACCESSIBLE,
                    'description'                  => $dataRoomDetails->description ?? null,
                    'location_reference'           => $dataRoomDetails->reference ?? null
                ];

            if ($objectStatus == DECOMMISSION) {
                $dataLocation['decommissioned'] = $objectStatus ?? 0;
                $dataLocation['decommissioned_reason'] = $statusReason ?? 0;
            } elseif ($area->decommissioned == 1) {
                $dataLocation['decommissioned'] = $area->decommissioned;
                $dataLocation['decommissioned_reason'] = $area->decommissioned_reason;
            } else {
                $dataLocation['decommissioned'] = 0;
                $dataLocation['not_assessed'] = $objectStatus ?? 0;
                $dataLocation['not_assessed_reason'] = $statusReason ?? 0;
            }

            $dataLocationInfo = [
                    'reason_inaccess_key'          => $dataRoomDetails->reason ?? null,
                    'reason_inaccess_other'        => $dataRoomDetails->reasonOther ?? null,
                    'comments'                     => $dataRoomDetails->comments ?? null,
            ];

            $roomDetails =  ($dataRoomDetails->constructionDetails ?? null);
            $dataLocationVoid = [
                    'ceiling'      => $this->getParentDropdownID($roomDetails->ceilingVoid ?? null),
                    'ceiling_other' => $roomDetails->ceilingVoidOther ?? null,
                    'cavities'         => $this->getParentDropdownID($roomDetails->cavities ?? null),
                    'cavities_other'    => $roomDetails->cavitiesOther ?? null,
                    'risers'           => $this->getParentDropdownID($roomDetails->risers ?? null),
                    'risers_other'      => $roomDetails->risersOther ?? null,
                    'ducting'          => $this->getParentDropdownID($roomDetails->ducting ?? null),
                    'ducting_other'     => $roomDetails->ductingOther ?? null,
                    'boxing'           => $this->getParentDropdownID($roomDetails->boxing ?? null),
                    'boxing_other'      => $roomDetails->boxingOther ?? null,
                    'pipework'         => $this->getParentDropdownID($roomDetails->pipework ?? null),
                    'pipework_other'    => $roomDetails->pipeworkOther ?? null,
                    'floor'        => $this->getParentDropdownID($roomDetails->floorVoid ?? null),
                    'floor_other'   => $roomDetails->floorVoidOther ?? null,
                ];


            $dataLocationContruction = [
                    'ceiling'          => $roomDetails->ceilingOther ? $this->getOtherConstructionData("ceiling", $roomDetails->ceiling) : ($roomDetails->ceiling ?? null),
                    'ceiling_other'     => $roomDetails->ceilingOther ?? null,
                    'walls'            => $roomDetails->wallsOther ? $this->getOtherConstructionData("wall", $roomDetails->walls) : ($roomDetails->walls ?? null),
                    'walls_other'       => $roomDetails->wallsOther ?? null,
                    'doors'            => $roomDetails->doorsOther ? $this->getOtherConstructionData("door", $roomDetails->doors) : ($roomDetails->doors ?? null),
                    'doors_other'       => $roomDetails->doorsOther ?? null,
                    'floor'            => $roomDetails->floorOther ? $this->getOtherConstructionData("floor", $roomDetails->floor) : ($roomDetails->floor ?? null),
                    'floor_other'       => $roomDetails->floorOther ?? null,
                    'windows'          => $roomDetails->windowsOther ? $this->getOtherConstructionData("window", $roomDetails->windows) : ($roomDetails->windows ?? null),
                    'windows_other'     => $roomDetails->windowsOther ?? null,
                ];

            if ($location_id) {
                $dataLocation['updated_by'] = $survey->surveyor_id ?? 0;
                $locationUpdate = Location::where('id', $location_id)->update($dataLocation);
                $location = Location::find($location_id);
            } else {
                $dataLocation['created_by'] = $survey->surveyor_id ?? 0;
                $location = Location::create($dataLocation);

                $refLocation = "RL" . $location->id;
                Location::where('id', $location->id)->update(['record_id' => $location->id, 'reference' => $refLocation]);
            }
            LocationInfo::updateOrCreate(['location_id' => $location->id], $dataLocationInfo);
            LocationVoid::updateOrCreate(['location_id' => $location->id], $dataLocationVoid);
            LocationConstruction::updateOrCreate(['location_id' => $location->id], $dataLocationContruction);

            $survey_comment = Survey::find($survey_id);
            if (isset($dataRoomDetails->comments)) {
                // store comment history
                \CommentHistory::storeCommentHistory('location', $location->id, $dataRoomDetails->comments ?? null, $survey_comment->reference ?? null);
            }
            if ($objectStatus == DECOMMISSION) {
                // store decommission history
                \CommentHistory::storeDeccomissionHistory('decommission','location', $location->id, $statusReason, $location->survey->reference ?? null);
            }
            $this->updateCorrectId($location->id, $upload_id);
        }
    }

    public function getParentDropdownID($ids) {
        // convert string to int
        $array = array_filter(explode(",", $ids));
        $newArray = [];
        foreach ($array as $key => $value) {
            $newArray[$key] = intval($value);
        }
        $original_ids = implode(",",$newArray) ;
        if (!is_null($ids) and ($ids != '')) {
            $data = DropdownDataLocation::whereRaw("id IN ($ids)")->first();
            if (!is_null($data) and !empty($data)) {
                if ($data->parent_id != 0) {
                    $original_ids = $data->parent_id. ','.$ids;
                }
            }
        }
        return$original_ids;
    }

    public function getOtherConstructionData($type, $dropdowns = NULL) {
        if ($type == "ceiling") {
            if (!$dropdowns) {
                return 1060;
            } elseif (strpos($dropdowns, "1060") === FALSE) {
                return $dropdowns . ",1060";
            }

        } elseif ($type == "door") {
            if (!$dropdowns) {
                return 1513;
            } elseif (strpos($dropdowns, "1513") === FALSE) {
                return $dropdowns . ",1513";
            }

        } elseif ($type == "floor") {
            if (!$dropdowns) {
                return 1405;
            } elseif (strpos($dropdowns, "1405") === FALSE) {
                return $dropdowns . ",1405";
            }

        } elseif ($type == "wall") {
            if (!$dropdowns) {
                return 1168;
            } elseif (strpos($dropdowns, "1168") === FALSE) {
                return $dropdowns . ",1168";
            }

        } elseif ($type == "window") {
            if (!$dropdowns) {
                return 1558;
            } elseif (strpos($dropdowns, "1558") === FALSE) {
                return $dropdowns . ",1558";
            }

        }

        return $dropdowns;
    }

    public function updateOrCreateItem($data, $item_id, $survey_id, $property_id, $manifest_id, $upload_id) {

        $objectStatus = $data->objectStatus ?? 0;
        $statusReason = $data->statusReason ?? 0;
        if ($objectStatus == RELEASE_FROM_SCOPE) {
            $item_survey = Item::find($item_id);
            //unlock from register
            Item::where('record_id', ($item_survey->record_id ?? 0))->where('survey_id', 0)->update(['is_locked' => 0]);
            Item::where('id', $item_id)->forcedelete();
        } else {
            $parent_id = $data->parentId;
            // find location_id

            $location_id = UploadDocumentStorage::where('id', $parent_id)->value('correct_id');

            // get item state from text
            $itemType = \CommonHelpers::getItemStateFromText($data->itemType ?? null);
            $state = $itemType['state'] ?? 0;
            $assessment =  $itemType['isFullAssessment'] ?? 0;

            // find area_id
            $location =  Location::find($location_id);
            // location out of scope
            if (is_null($location)) {
                if ($item_id) {
                    Item::where('id', $item_id)->forcedelete();
                }
                return false;
            }
            $area_id = $location->area_id;
            $total_risk = 0;
            $survey = Survey::find($survey_id);
            $dataItem = [
                'area_id'           => $area_id,
                'survey_id'         => $survey_id,
                'property_id'       => $property_id,
                'location_id'       => $location_id,
                'state'             => $state,
                'name'              => $data->reference ?? null,
                'is_locked'         => 0,
                // 'decommissioned'    => isset($data->isDecommissioned) and ($data->isDecommissioned == 1) ? ITEM_DECOMMISSION : ITEM_UNDECOMMISSION
            ];

            if ($objectStatus == DECOMMISSION) {
                $dataItem['decommissioned'] = $objectStatus ?? 0;
                $dataItem['decommissioned_reason'] = $statusReason ?? 0;
                // if location decommission
            } elseif ($location->decommissioned == 1) {
                $dataItem['decommissioned'] = $location->decommissioned;
                $dataItem['decommissioned_reason'] = $location->decommissioned_reason;
            }else {
                $dataItem['decommissioned'] = 0;
                $dataItem['not_assessed'] = $objectStatus ?? 0;
                $dataItem['not_assessed_reason'] = $statusReason ?? 0;
            }

            $dataItemInfo = [
                'extent' => $data->extent ?? null,
                'comment' => $data->comment ?? null,
                'assessment' => $assessment,
                'is_r_and_d_element' => isset($data->rAndD) and ($data->rAndD == true) ? 1 : 0
            ];

            if (!$item_id) {
                $dataItem['created_by'] = $survey->surveyor_id ?? 0;
                $itemCreate = Item::create($dataItem);

                $reference = 'IN'. $itemCreate->id;
                //update item when create success
                Item::where('id', $itemCreate->id)->update(['record_id' => $itemCreate->id, 'reference' => $reference]);
                $item = Item::find($itemCreate->id);
            } else {
                $dataItem['updated_by'] = $survey->surveyor_id ?? 0;
                Item::where('id', $item_id)->update($dataItem);
                $item = Item::where('id', $item_id)->first();
            }

            $survey_comment = Survey::find($survey_id);
            if (isset($data->comment)) {
                // store comment history
                \CommentHistory::storeCommentHistory('item', $item->id, $data->comment ?? null, $survey_comment->reference ?? null);
            }
            if ($objectStatus == DECOMMISSION) {
                // store decommission history
                \CommentHistory::storeDeccomissionHistory('decommission','item', $item->id, $statusReason, $survey_comment->reference ?? null);
            }
            //update item info
            ItemInfo::updateOrCreate(['item_id' => $item->id], $dataItemInfo);
            //correct id
            $this->updateCorrectId($item->id, $upload_id);
            //details specific location

            $this->insertDropdownValue($item->id, SPECIFIC_LOCATION_ID, 0,  ($data->specificLocation ?? null), $data->specificLocationOther ?? null);

            //check sample
            $dataItemDetailsInfoSample =  ($data->sample ?? null);
            $samplehandle = $this->handleSample($item->record_id, $dataItemDetailsInfoSample ?? null, $manifest_id);
            // insert sample link id
            $this->insertDropdownValue($item->id, SAMPLE_ID, 0, $samplehandle);

            //details tab
            $this->insertDropdownValue($item->id, PRODUCT_DEBRIS_TYPE_ID, 0, $data->productDebris ?? null, $data->productDebrisOtherAsbestosOther ?? null);
            $this->insertDropdownValue($item->id, ASBESTOS_TYPE_ID, 0, $data->asbestosType ?? null, $data->asbestosTypeOther ?? null);

            // $this->insertDropdownValue($item->id, LICENSED_NONLICENSED_ID, 0,\CommonHelpers::checkArrayKey($data->item->details->asbestosType ?? null, null));


            $this->insertDropdownValue($item->id, EXTENT_ID, 0, $data->measurement ?? null);
            //remove
            // $this->insertDropdownValue($item->id, ACCESSIBILITY_VULNERABILITY_ID, 0, $data->accessibilityVulnerability ?? null);
            // $this->insertDropdownValue($item->id, ADDITIONAL_INFORMATION_ID, 0, $data->additionalInformation ?? null, $dataItemDetailsInfo->additionalInformationOther ?? null);
            $reason_inaccess_item  = $data->reason ?? 0;
            $reason_inaccess_item_other = $data->reasonOther ?? '';
            if (is_string($reason_inaccess_item)) {
                $reason_inaccess_item = 592;
                $reason_inaccess_item_other = $data->reason ?? '';
            }
            $this->insertDropdownValue($item->id, ITEM_NO_ACCESS_ID, 0, $reason_inaccess_item ,$reason_inaccess_item_other);

            $dataItemDetailsInfoMaterial =  ($data->materialAssessment ?? null);
            $dataItemDetailsInfoPriority =  ($data->priorityAssessment ?? null);
            // mas tab
            $this->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY, (int) ($dataItemDetailsInfoMaterial->productDebrisType ?? null));
            $this->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY, (int) ($dataItemDetailsInfoMaterial->damageDeterioration ?? null));
            $this->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY, (int) ($dataItemDetailsInfoMaterial->surfaceTreatment ?? null));
            $this->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY, (int) ($dataItemDetailsInfoMaterial->asbestosType ?? null));
            //send high risk email
            if ($dataItemDetailsInfoMaterial->damageDeterioration == 608) {
                 \Queue::laterOn(CLIENT_EMAIL_QUEUE,30,new SendClientEmail($survey->property_id, HIGH_RISK_ITEM_EMAILTYPE,$survey_id));
            }
            // count total mas
            if (isset($data->itemType)  and ($data->itemType == 'inaccessibleLimited')) {
                $total_mas = 12;
            } else {
                $total_mas = $this->getMasScore($dataItemDetailsInfoMaterial->productDebrisType ?? null) + $this->getMasScore($dataItemDetailsInfoMaterial->damageDeterioration ?? null) + $this->getMasScore($dataItemDetailsInfoMaterial->surfaceTreatment ?? null) + $this->getMasScore($dataItemDetailsInfoMaterial->asbestosType ?? null);
            }

            // pas tab
            $dataItemDetailsInfoPasOC =  ($dataItemDetailsInfoPriority->normalOccupancyActivity ?? null);
            $dataItemDetailsInfoPasLikeHood =  ($dataItemDetailsInfoPriority->likelihoodOfDisturbance ?? null);
            $dataItemDetailsInfoPasHuman =  ($dataItemDetailsInfoPriority->humanExposurePotential ?? null);
            $dataItemDetailsInfoPasMaintain =  ($dataItemDetailsInfoPriority->maintenanceActivity ?? null);

            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY, (int) ($dataItemDetailsInfoPasOC->primary ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY, (int) ($dataItemDetailsInfoPasOC->secondary ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY, (int) ($dataItemDetailsInfoPasLikeHood->location ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY, (int) ($dataItemDetailsInfoPasLikeHood->accessibility ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY, (int) ($dataItemDetailsInfoPasLikeHood->extentAmount ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY, (int) ($dataItemDetailsInfoPasHuman->number ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY, (int) ($dataItemDetailsInfoPasHuman->frequency ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY, (int) ($dataItemDetailsInfoPasHuman->averageTime ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY, (int) ($dataItemDetailsInfoPasMaintain->type ?? null));
            $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY, (int) ($dataItemDetailsInfoPasMaintain->frequency ?? null));

            //count total pas
            $total_pas = round( ($this->getPasScore($dataItemDetailsInfoPasOC->primary ?? null) + $this->getPasScore($dataItemDetailsInfoPasOC->secondary ?? null))/2 );
            $total_pas += round( ($this->getPasScore($dataItemDetailsInfoPasLikeHood->location ?? null) + $this->getPasScore($dataItemDetailsInfoPasLikeHood->accessibility ?? null) + $this->getPasScore($dataItemDetailsInfoPasLikeHood->extentAmount ?? null))/3 );
            $total_pas += round( ($this->getPasScore($dataItemDetailsInfoPasHuman->number ?? null) + $this->getPasScore($dataItemDetailsInfoPasHuman->frequency ?? null) + $this->getPasScore($dataItemDetailsInfoPasHuman->averageTime ?? null))/3 );
            $total_pas += round( ($this->getPasScore($dataItemDetailsInfoPasMaintain->type ?? null) + $this->getPasScore($dataItemDetailsInfoPasMaintain->frequency ?? null))/2 );

            if ($state == ITEM_NOACM_STATE) {
                $total_mas = 0;
                $total_pas = 0;
                $total_risk = 0;
            } else {
                $total_risk = $total_mas + $total_pas;
            }

             Item::where('id', $item->id)->update(['total_mas_risk' => $total_mas, 'total_pas_risk' => $total_pas, 'total_risk' => $total_risk]);
            // action recommendations
            $this->insertDropdownValue($item->id, ACTIONS_RECOMMENDATIONS_ID, 0, $data->actionsRecommendations ?? null, $data->actionsRecommendationsOther ?? null );
        }
    }

    public function insertDropdownValue($item_id, $dropdown_item_id, $dropdown_data_item_parent_id, $dropdown_data_item_id, $other = null) {
        if (is_array($dropdown_data_item_id)) {
            if ($dropdown_item_id == SPECIFIC_LOCATION_ID) {
                if (strpos($dropdown_data_item_id, ',') !== false) {
                    $dropdown_data_item_id = $dropdown_data_item_id;
                } else {
                    $dropdown_data_item_id = (int)$dropdown_data_item_id;
                }
            } else {
                $dropdown_data_item_id = end($dropdown_data_item_id);
            }
        }
        if (is_array($other)) {
            $other = implode(",", $other);
        }
        if ($dropdown_item_id == SPECIFIC_LOCATION_ID) {
            if (strpos($dropdown_data_item_id, ',') !== false) {
                $dropdown_data_item_id = $dropdown_data_item_id;
            } else {
                $dropdown_data_item_id = (int)$dropdown_data_item_id;
            }
        }


        $dataDropdownValue = [
            'dropdown_item_id' => $dropdown_item_id,
            'dropdown_data_item_parent_id' => $dropdown_data_item_parent_id,
            'dropdown_data_item_id' => $dropdown_data_item_id,
            'dropdown_other' => $other
        ];

        switch ($dropdown_item_id) {
            case PRODUCT_DEBRIS_TYPE_ID:
                $data =  ProductDebrisTypeValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case EXTENT_ID:
                $data =  ExtentValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ASBESTOS_TYPE_ID:
                $data =  AsbestosTypeValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ACTIONS_RECOMMENDATIONS_ID:
                $data =  ActionRecommendationValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ADDITIONAL_INFORMATION_ID:
                $data =  AdditionalInformationValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case SAMPLE_COMMENTS_ID:
                $data =  SampleCommentValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case SPECIFIC_LOCATION_ID:
                $data =  SpecificLocationValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ACCESSIBILITY_VULNERABILITY_ID:
                $data =  AccessibilityVulnerabilityValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case LICENSED_NONLICENSED_ID:
                $data =  LicensedNonLicensedValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case UNABLE_TO_SAMPLE_ID:
                $data =  UnableToSampleValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ITEM_NO_ACCESS_ID:
                $dataDropdownValue['dropdown_data_item_id'] = ($dataDropdownValue['dropdown_data_item_id'] == '') ? 0 : $dataDropdownValue['dropdown_data_item_id'];

                $data =  ItemNoAccessValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case PRIORITY_ASSESSMENT_RISK_ID:
                $data =  PriorityAssessmentRiskValue::updateOrCreate(['item_id' => $item_id , 'dropdown_data_item_parent_id' => $dropdown_data_item_parent_id,], $dataDropdownValue);
                break;
            case NO_ACM_COMMENTS_ID:
                $data =  NoACMCommentsValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case MATERIAL_ASSESSMENT_RISK_ID:
                $data =  MaterialAssessmentRiskValue::updateOrCreate(['item_id' => $item_id, 'dropdown_data_item_parent_id' => $dropdown_data_item_parent_id,], $dataDropdownValue);
                break;
            case SAMPLE_ID:
                $data =  SampleIdValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case SUB_SAMPLE_ID:
                $data =  SubSampleIdValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
        }
    }

    public function handleSample($item_record_id, $data, $manifest_id) {
        if (is_null($data)) {
            return 0;
        }
        $sample_id = 0;
        // new app sample logic update 12082020
        if (isset($data->originalSampleID) and !is_null($data->originalSampleID) and ($data->originalSampleID != '') and ($data->originalSampleID > 0)) {
           $old_sample = Sample::find($data->originalSampleID);
           if (!is_null($old_sample)) {
               return $old_sample->id;
           }
        }
        // create new sample
        if ( isset($data->reference) and !is_null($data->reference) and ($data->reference != '')) {
            if ($data->isOS == 1) {
                $sampleCreate = Sample::create([
                    'description' => $data->reference,
                    'comment_key' => (int) $data->comment == 0 ? 522 : (int) $data->comment,
                    'comment_other' => $data->commentOther ?? 522,
                    'original_item_id' => $item_record_id,
                ]);
                Sample::where('id', $sampleCreate->id)->update(['reference' => 'SR'.$sampleCreate->id]);

                $this->dataSample[$sampleCreate->id] = $data->reference;

                return $sampleCreate->id;
            } else {
                if (!empty($this->dataSample)) {
                    foreach ($this->dataSample as $key => $value) {
                        if ($value == $data->reference) {
                            return $key;
                        }
                    }
                }
                // update sample
                // $itemUniqueId = $data->originalItemID;
                // $os_item_id = UploadDocumentStorage::where('manifest_id', $manifest_id)->where('unique_id','like',"%$itemUniqueId%")->value('correct_id');
                // if ($os_item_id) {
                //     $sample_id = SampleIdValue::where('item_id', $itemUniqueId)->value('dropdown_data_item_id');
                // }
            }
        }
        return $sample_id;
    }

    public function createPPlan($data, $survey_id, $property_id, $upload_id) {
            $dataPlan = [
                "property_id" => $property_id,
                "name" => $data->reference ?? null,
                "plan_reference" => $data->description ?? null,
                "survey_id" => 0,
                "type" => 1,
                "category" => $survey_id,
                "added" => time(),
                "document_present" => 1,
                "note" => $data->comment ?? null,
            ];

            $plan = SitePlanDocument::create($dataPlan);

            $planRef = "PP" . $plan->id;

            SitePlanDocument::where('id', $plan->id)->update(['reference' => $planRef]);

            $this->updateCorrectId($plan->id, $upload_id);
    }

    public function updateCorrectId($correct_id, $upload_id) {
        UploadDocumentStorage::where('id', $upload_id)->update(['correct_id' => $correct_id]);
    }

    public function uploadPhoto($manifest_id, $survey_id) {
        $list_image = ShineAppDocumentStorage::where('manifest_id', $manifest_id)->get();
        if (!is_null($list_image)) {
            foreach ($list_image as $img_update) {
                if ($img_update->type == PROPERTY_SURVEY_IMAGE) {
                   $object_id = $survey_id;
                   $type = PROPERTY_SURVEY_IMAGE;
                } else {
                    $object_id = UploadDocumentStorage::where('id', $img_update->record_id)->value('correct_id');
                    $type =  $img_update->type;
                }
                ShineDocumentStorage::updateOrCreate([ 'object_id' => $object_id, 'type' => $type],
                    [
                        'path' => $img_update->path,
                        'file_name' => $img_update->file_name,
                        'mime' => $img_update->mine,
                        'size' => $img_update->size,
                        'addedDate' =>  Carbon::now()->timestamp,
                    ]);
            }
        }
    }

    public function getMasScore($mas_id) {
        $mas_id = (int) $mas_id;
        $data = MaterialAssessmentRisk::find($mas_id);
        return is_null($data) ? 0 : $data->score;
    }

    public function getPasScore($pas_id) {
        $pas_id = (int) $pas_id;
        $data = PriorityAssessmentRisk::find($pas_id);
        return is_null($data) ? 0 : $data->score;
    }

}
