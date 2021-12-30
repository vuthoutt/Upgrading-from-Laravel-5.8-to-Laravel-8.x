<?php
namespace App\Repositories;
use App\Models\AdminToolRollback;
use App\Models\AdminTool;
use App\Models\Property;
use App\Models\PropertySurvey;
use App\Models\PropertyPropertyType;
use App\Models\PropertyInfo;
use App\Models\Zone;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Item;
use App\Models\Area;
use App\Models\ItemInfo;
use App\Models\Survey;
use App\Models\SurveyInfo;
use App\Models\SurveySetting;
use App\Models\SurveyDate;
use App\Models\Location;
use App\Models\Project;
use App\Models\DropdownValue;
use App\Models\Sample;
use App\Models\ItemComment;
use App\Models\LocationComment;
use App\Models\LocationVoid;
use App\Models\LocationInfo;
use App\Models\LocationConstruction;
use App\Models\PropertyComment;
use App\Models\Notification;
use App\Models\SitePlanDocument;
use App\Models\SampleCertificate;
use App\Models\HistoricDocCategory;
use App\Models\AirTestCertificate;
use App\Models\HistoricDoc;
use App\Models\ShineCompliance\ComplianceDocument;
use App\Helpers\CommonHelpers;
use App\Models\Document;
use App\Models\DropdownItemValue\ProductDebrisTypeValue;//3
use App\Models\DropdownItemValue\ExtentValue;//4
use App\Models\DropdownItemValue\AsbestosTypeValue;//5
use App\Models\DropdownItemValue\ActionRecommendationValue;//7
use App\Models\DropdownItemValue\AdditionalInformationValue;//8
use App\Models\DropdownItemValue\SampleCommentValue;//9
use App\Models\DropdownItemValue\SpecificLocationValue;//11
use App\Models\DropdownItemValue\AccessibilityVulnerabilityValue;//12
use App\Models\DropdownItemValue\LicensedNonLicensedValue;//13
use App\Models\DropdownItemValue\UnableToSampleValue;//14
use App\Models\DropdownItemValue\ItemNoAccessValue;//15
use App\Models\DropdownItemValue\NoACMCommentsValue;//16
use App\Models\DropdownItemValue\PriorityAssessmentRiskValue;//18
use App\Models\DropdownItemValue\MaterialAssessmentRiskValue;//19
use App\Models\DropdownItemValue\SampleIdValue;//500
use App\Models\DropdownItemValue\SubSampleIdValue;//502
use App\Models\ShineCompliance\IncidentReportDocument;

class AdminToolRollbackRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return AdminToolRollback::class;
    }

    public function revertRemoveGroup($data,$id) {
        try {
            \DB::beginTransaction();
            $this->revertRemoveProperty($data);
            $this->revertRemoveSurvey($data);
            $this->revertRemoveProject($data);
            $this->revertRemoveRegisterLocation($data);
            $this->revertRemoveItem($data);
            $this->revertSitePlanDocument($data);
            $this->revertRemoveProjectDocument($data);
            $this->revertRemoveHistoricalDocument($data);
            $this->revertRemoveAirtestCer($data);
            $this->revertRemoveSampleCer($data);
            // insert area
            if (count($data['area']) > 0) {
                foreach (array_chunk($data['area'],500) as $t)  {
                    Area::insert($t);
                }
            }
            if (count($data['history']) > 0) {
                foreach (array_chunk($data['history'],500) as $t)  {
                    HistoricDocCategory::insert($t);
                }
            }
            // insert zone
            if (count($data['zone']) > 0) {
                Zone::insert($data['zone']);
            }
             AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();
            return $response = \CommonHelpers::successResponse('Rollback \'Remove Property Group\' Action Successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to roll back this action. Please try again later!');
        }
    }

    public function revertRemoveItem($data, $id = false) {
        try {
            \DB::beginTransaction();
            //  insert item value
            if (count($data['item_accessibility_vulnerability']) > 0) {
                foreach (array_chunk($data['item_accessibility_vulnerability'],500) as $t)  {
                    AccessibilityVulnerabilityValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_action_recommendation']) > 0) {
                foreach (array_chunk($data['item_action_recommendation'],500) as $t)  {
                    ActionRecommendationValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_additional_information']) > 0) {
                foreach (array_chunk($data['item_additional_information'],500) as $t)  {
                    AdditionalInformationValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_asbestos_type']) > 0) {
                foreach (array_chunk($data['item_asbestos_type'],500) as $t)  {
                    AsbestosTypeValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_extent']) > 0) {
                foreach (array_chunk($data['item_extent'],500) as $t)  {
                    ExtentValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_no_access']) > 0) {
                foreach (array_chunk($data['item_no_access'],500) as $t)  {
                    ItemNoAccessValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_licensed_non_licensed']) > 0) {
                foreach (array_chunk($data['item_licensed_non_licensed'],500) as $t)  {
                    LicensedNonLicensedValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_material_assessment_risk']) > 0) {
                foreach (array_chunk($data['item_material_assessment_risk'],500) as $t)  {
                    MaterialAssessmentRiskValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_no_acm_comment']) > 0) {
                foreach (array_chunk($data['item_no_acm_comment'],500) as $t)  {
                    NoACMCommentsValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_priority_assessment_risk']) > 0) {
                foreach (array_chunk($data['item_priority_assessment_risk'],500) as $t)  {
                    PriorityAssessmentRiskValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_product_debris']) > 0) {
                foreach (array_chunk($data['item_product_debris'],500) as $t)  {
                    ProductDebrisTypeValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_sample_comment']) > 0) {
                foreach (array_chunk($data['item_sample_comment'],500) as $t)  {
                    SampleCommentValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_sample_id']) > 0) {
                foreach (array_chunk($data['item_sample_id'],500) as $t)  {
                    SampleIdValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_specific_location']) > 0) {
                foreach (array_chunk($data['item_specific_location'],500) as $t)  {
                    SpecificLocationValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_sub_sample']) > 0) {
                foreach (array_chunk($data['item_sub_sample'],500) as $t)  {
                    SubSampleIdValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_unable_to_sample']) > 0) {
                foreach (array_chunk($data['item_unable_to_sample'],500) as $t)  {
                    UnableToSampleValue::insert($t);
                }
            }
            // insert item value
            if (count($data['item_info']) > 0) {
                foreach (array_chunk($data['item_info'],500) as $t)  {
                    ItemInfo::insert($t);
                }
            }
            // insert item value
            if (count($data['item_comment']) > 0) {
                foreach (array_chunk($data['item_comment'],500) as $t)  {
                    ItemComment::insert($t);
                }
            }
            // insert item value
            if (count($data['items']) > 0) {
                foreach (array_chunk($data['items'],500) as $t)  {
                    Item::insert($t);
                }
            }
            if ($id) {
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Item Action\' Successfully!');
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveRegisterArea($data, $id = false) {
        try {
            \DB::beginTransaction();
            if (count($data['area']) > 0) {
                foreach (array_chunk($data['area'],500) as $t)  {
                    Area::insert($t);
                }
            }
            $this->revertRemoveRegisterLocation($data);
            $this->revertRemoveItem($data);
            AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();
            return \CommonHelpers::successResponse('Rollback \'Remove Register Area Action\' Successfully!');

        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveRegisterLocation($data, $id = false) {
        try {
            \DB::beginTransaction();
                // insert item value
            if (count($data['location_info']) > 0) {
                foreach (array_chunk($data['location_info'],500) as $t)  {
                    LocationInfo::insert($t);
                }
            }
            // insert item value
            if (count($data['location_comment']) > 0) {
                foreach (array_chunk($data['location_comment'],500) as $t)  {
                    LocationComment::insert($t);
                }
            }
            // insert item value
            if (count($data['location_construction']) > 0) {
                foreach (array_chunk($data['location_construction'],500) as $t)  {
                    LocationConstruction::insert($t);
                }
            }
            // insert item value
            if (count($data['location_void']) > 0) {
                foreach (array_chunk($data['location_void'],500) as $t)  {
                    LocationVoid::insert($t);
                }
            }

            if (count($data['location']) > 0) {
                foreach (array_chunk($data['location'],500) as $t)  {
                    Location::insert($t);
                }
            }
            if ($id) {
                $this->revertRemoveItem($data);
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Location Action\' Successfully!');
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveSurvey($data, $id = false) {
        try {
            \DB::beginTransaction();
            // insert survey date
            if (count($data['survey_date']) > 0) {
                foreach (array_chunk($data['survey_date'],500) as $t)  {
                    SurveyDate::insert($t);
                }
            }
            // insert survey setting
            if (count($data['survey_setting']) > 0) {
                foreach (array_chunk($data['survey_setting'],500) as $t)  {
                    SurveySetting::insert($t);
                }
            }
            // insert survey info
            if (count($data['survey_info']) > 0) {
                foreach (array_chunk($data['survey_info'],500) as $t)  {
                    SurveyInfo::insert($t);
                }
            }
            // insert survey
            if (count($data['survey']) > 0) {
                foreach (array_chunk($data['survey'],500) as $t)  {
                    Survey::insert($t);
                }
            }
                $area = explode(",",$data['area_id']);
                Area::whereIn('id', $area)->update(['is_locked' => 1]);

                $location = explode(",",$data['location_id']);
                Location::whereIn('id', $location)->update(['is_locked' => 1]);

                $item = explode(",",$data['item_id']);
                Item::whereIn('id', $item)->update(['is_locked' => 1]);
            if ($id) {
                $this->revertRemoveAirtestCer($data);
                $this->revertRemoveSampleCer($data);
                $this->revertSitePlanDocument($data);
                $this->revertRemoveRegisterLocation($data);
                if (count($data['area']) > 0) {
                    foreach (array_chunk($data['area'],500) as $t)  {
                        Area::insert($t);
                    }
                }
                $this->revertRemoveItem($data);
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Survey Action\' Successfully!');
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveProperty($data, $id = false){
        try {
            \DB::beginTransaction();
            // insert item value
           if (count($data['property']) > 0) {
               foreach (array_chunk($data['property'],500) as $t)  {
                   Property::insert($t);
               }
           }

           // insert item value
           if (count($data['property_survey']) > 0) {
               foreach (array_chunk($data['property_survey'],500) as $t)  {
                   PropertySurvey::insert($t);
               }
           }
           // insert item value
           if (count($data['property_info']) > 0) {
               foreach (array_chunk($data['property_info'],500) as $t)  {
                   PropertyInfo::insert($t);
               }
           }
           // insert item value
           if (count($data['property_type']) > 0) {
               foreach (array_chunk($data['property_type'],500) as $t)  {
                   PropertyPropertyType::insert($t);
               }
           }
           // insert item value
           if (count($data['property_comment']) > 0) {
               foreach (array_chunk($data['property_comment'],500) as $t)  {
                   PropertyComment::insert($t);
               }
           }
            if ($id) {
                $this->revertRemoveProject($data);
                $this->revertRemoveSurvey($data);
                $this->revertRemoveProjectDocument($data);
                $this->revertSitePlanDocument($data);

                if (count($data['area']) > 0) {
                    foreach (array_chunk($data['area'],500) as $t)  {
                        Area::insert($t);
                    }
                }
                if (count($data['history']) > 0) {
                    foreach (array_chunk($data['history'],500) as $t)  {
                        HistoricDocCategory::insert($t);
                    }
                }
                $this->revertRemoveHistoricalDocument($data);
                $this->revertRemoveRegisterLocation($data);
                $this->revertRemoveItem($data);
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Property Action\' Successfully!');
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveProject($data, $id = false) {
        try {
            \DB::beginTransaction();
            // insert historical category document
            if (count($data['notification']) > 0) {
                foreach (array_chunk($data['notification'],500) as $t)  {
                    Notification::insert($t);
                }
            }

            // insert project
            if (count($data['project']) > 0) {
                foreach (array_chunk($data['project'],500) as $t)  {
                    Project::insert($t);
                }
            }
            if ($id) {
                $this->revertRemoveProjectDocument($data);
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Project Action\' Successfully!');
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertSitePlanDocument($data, $id = false) {
        try {
            \DB::beginTransaction();
            // insert site plan document
            if (count($data['siteplane']) > 0) {
                foreach (array_chunk($data['siteplane'],500) as $t)  {
                    SitePlanDocument::insert($t);
                }
            }
            if ($id) {
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Plan Document\' Successfully!');
            }
           \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveProjectDocument($data, $id = false) {
        try {
            \DB::beginTransaction();
            // insert site plan document
            if (count($data['document']) > 0) {
                foreach (array_chunk($data['document'],500) as $t)  {
                    Document::insert($t);
                }
            }
            if ($id) {
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Project Document\' Successfully!');
            }
           \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertIncidentDocument($data, $id = false) {
        try {
            \DB::beginTransaction();
            // insert site plan document
            if (count($data['incident_doc']) > 0) {
                foreach (array_chunk($data['incident_doc'],500) as $t)  {
                    IncidentReportDocument::insert($t);
                }
            }
            if ($id) {
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Incident Report Document\' Successfully!');
            }
           \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveHistoricalDocument($data, $id = false) {
        try {
            \DB::beginTransaction();
            // insert site plan document
            if (count($data['history_doc']) > 0) {
                foreach (array_chunk($data['history_doc'],500) as $t)  {
                    ComplianceDocument::insert($t);
                }
            }
            if ($id) {
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Historical Document\' Successfully!');
            }
           \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveAirtestCer($data, $id = false){
        try {
            \DB::beginTransaction();
            // insert site plan document
            if (count($data['airtest_certificate']) > 0) {
                foreach (array_chunk($data['airtest_certificate'],500) as $t)  {
                    AirTestCertificate::insert($t);
                }
            }
            if ($id) {
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Air Test Certificate\' Successfully!');
            }
           \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertRemoveSampleCer($data, $id = false){
        try {
            \DB::beginTransaction();
            // insert site plan document
            if (count($data['sample_certificate']) > 0) {
                foreach (array_chunk($data['sample_certificate'],500) as $t)  {
                    SampleCertificate::insert($t);
                }
            }
            if ($id) {
                AdminTool::where('id',$id)->update(['roll_back' => 1]);
                \DB::commit();
                return \CommonHelpers::successResponse('Rollback \'Remove Sample Certificate\' Successfully!');
            }
           \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function revertMoveSurvey($data,$id) {
        try {
            \DB::beginTransaction();
            $survey_id = $data['survey_id'];
            $property_old = $data['property_old'];

            $sqls = [
                "item" => "UPDATE `tbl_items` SET `property_id` = $property_old WHERE `survey_id` = $survey_id",
                "location" => "UPDATE `tbl_location` SET `property_id` = $property_old WHERE `survey_id` = $survey_id",
                "area" => "UPDATE `tbl_area` SET `property_id` = $property_old WHERE `survey_id` = $survey_id",
                "siteplan" => "UPDATE `tbl_siteplan_documents` SET `property_id` = $property_old WHERE `survey_id` = $survey_id",
                "survey" => "UPDATE `tbl_survey` SET `property_id`= $property_old WHERE `id` =  $survey_id",
                ];
            foreach($sqls as $sql){
                $removeGroup = \DB::select($sql);
            }
            AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();

            return \CommonHelpers::successResponse('Rollback \'Move Survey Action\' Successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to roll back this action. Please try again later!');
        }
    }

    public function revertMoveProject($data,$id) {
        try {
            \DB::beginTransaction();
            $project_id = $data['project_id'];
            $property_old = $data['property_old'];

            Project::where('id', $project_id)->update(['property_id' => $property_old]);
            AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();

            return \CommonHelpers::successResponse('Rollback \'Move Project Action\' Successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to roll back this action. Please try again later!');
        }
    }

    public function revertMoveLocation($data,$id) {
        try {
            \DB::beginTransaction();
            $location_old = $data['location_old'];
            $area_old = $data['area_old'];

            $sql_revert = [
                "item" => "UPDATE `tbl_items` SET area_id = $area_old WHERE `location_id` = $location_old",
                "location" => "UPDATE `tbl_location` SET area_id = $area_old WHERE `id` = $location_old",
            ];

            foreach($sql_revert as $sql){
                $revertMergeSurvey = \DB::select($sql);
            }

            AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();

            return \CommonHelpers::successResponse('Rollback \'Move Item Action\' Successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to roll back this action. Please try again later!');
        }
    }

    public function revertMoveItem($data,$id) {
        try {
            \DB::beginTransaction();
            $item_old = $data['item_old'];
            $location_old = $data['location_old'];
            $area_old = $data['area_old'] ?? 0;

            \DB::select("UPDATE `tbl_items` SET area_id = $area_old, location_id = $location_old WHERE `id` = $item_old");

            AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();

            return \CommonHelpers::successResponse('Rollback \'Move Item Action\' Successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to roll back this action. Please try again later!');
        }
    }

    public function revertMergeSurvey($data, $id) {
        try {
            \DB::beginTransaction();
            $area_ids = $data['area'][0]['id'] ?? 0;
            $location_ids = $data['location'][0]['id'] ?? 0;
            $item_ids = $data['item'][0]['id'] ?? 0;
            $sample_ct_ids = $data['sample'][0]['id'] ?? 0;
            $airtest_ids = $data['airtest'][0]['id'] ?? 0;
            $survey_id = $data['survey_old'];

            $sql_revert = [
                "area" => "UPDATE tbl_area SET survey_id = $survey_id WHERE id IN ($area_ids)",
                "location" => "UPDATE tbl_location SET survey_id = $survey_id WHERE id IN ($location_ids)",
                "item" => "UPDATE tbl_items SET survey_id = $survey_id WHERE id IN ($item_ids)",
                "siteplan" => "UPDATE tbl_siteplan_documents SET survey_id = $survey_id WHERE id IN ($sample_ct_ids)",
                "sample" => "UPDATE tbl_sample_certificates SET survey_id = $survey_id WHERE id IN ($airtest_ids)",
                "airtest" => "UPDATE tbl_air_test_certificates SET survey_id = $survey_id WHERE id IN ($survey_id)",
            ];

            foreach($sql_revert as $sql){
                $revertMergeSurvey = \DB::select($sql);
            }
            $this->revertRemoveSurvey($data);
            AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();

            return \CommonHelpers::successResponse('Rollback \'Merge Survey Action\' Successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to roll back this action. Please try again later!');
        }
    }

    public function revertMergeRoom($data, $id) {
        try {
            \DB::beginTransaction();
            $item_ids = $data['item'][0]['id'] ?? 0;
            $location_old_id = $data['location_old_id'];
            $location_new_id = $data['location_new_id'];

            \DB::raw('UPDATE tbl_items SET location_id = $location_id WHERE id IN ($item_ids)');
            //delete merged location
            Location::where('id', $location_new_id)->delete();
            LocationInfo::where('location_id', $location_new_id)->delete();
            LocationComment::where('record_id', $location_new_id)->delete();
            LocationConstruction::where('location_id', $location_new_id)->delete();
            LocationVoid::where('location_id', $location_new_id)->delete();

            // revert 2 location
            LocationInfo::insert($data['old_location_info']);
            LocationComment::insert($data['old_location_comment']);
            LocationConstruction::insert($data['old_location_construction']);
            LocationVoid::insert($data['old_location_void']);
            Location::insert($data['old_location']);

            LocationInfo::insert($data['new_location_info']);
            LocationComment::insert($data['new_location_comment']);
            LocationConstruction::insert($data['new_location_construction']);
            LocationVoid::insert($data['new_location_void']);
            Location::insert($data['new_location']);

            AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();

            return \CommonHelpers::successResponse('Rollback \'Merge Room Action\' Successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to roll back this action. Please try again later!');
        }
    }

    public function revertMergeArea($data, $id) {
        try {
            \DB::beginTransaction();
            $location_ids = $data['location'][0]['id'] ?? 0;
            $item_ids = $data['item'][0]['id'] ?? 0;
            $area_id = $data['area_old'];

            $sql_revert = [
                "location" => "UPDATE tbl_location SET area_id = $area_id WHERE id IN ($location_ids)",
                "item" => "UPDATE tbl_items SET area_id = $area_id WHERE id IN ($item_ids)",
            ];
            foreach($sql_revert as $sql){
                $revertMergeSurvey = \DB::select($sql);
            }
            Area::insert($data['area']);

            AdminTool::where('id',$id)->update(['roll_back' => 1]);
            \DB::commit();
            return \CommonHelpers::successResponse('Rollback \'Merge Room Action\' Successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to roll back this action. Please try again later!');
        }
    }
}
