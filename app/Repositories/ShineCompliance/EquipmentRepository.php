<?php

namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\Equipment;
use App\Models\ShineCompliance\EquipmentType;
use App\Models\ShineCompliance\EquipmentTemplate;
use App\Models\ShineCompliance\EquipmentTemplateSection;
use App\Models\ShineCompliance\EquipmentDropdownData;
use App\Models\ShineCompliance\EquipmentSpecificLocation;
use App\Models\ShineCompliance\EquipmentModel;
use App\Models\ShineCompliance\EquipmentConstruction;
use App\Models\ShineCompliance\EquipmentTempAndPh;
use App\Models\ShineCompliance\EquipmentCleaning;
use App\Models\ShineCompliance\TempValidation;
use App\Models\ShineCompliance\TempValidationByTemplate;
use App\Models\ShineCompliance\Hazard;
use App\Models\ShineCompliance\EquipmentSpecificLocationValue;
use App\Models\ShineCompliance\Nonconformity;
use App\Models\ShineCompliance\NonconformityValidate;
use Prettus\Repository\Eloquent\BaseRepository;

class EquipmentRepository extends BaseRepository {

    use PullAssessmentToRegister;

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return Equipment::class;
    }

    public function getAllTypes() {
        return EquipmentType::all();
    }

    public function getAllTypesWaterTemperature() {
        return EquipmentType::where('template_id', '<>', EQUIPMENT_TEMPLATE_MISC)->get();
    }

    public function getEquipmentDetail($equip_id, $relations){
        return $this->model->with($relations)->where('id', $equip_id)->first();
    }

    public function getEquipmentDropdownData($dropdown_id) {
        return EquipmentDropdownData::where('dropdown_id', $dropdown_id)
                                    ->orderBy('order')
                                    ->orderBy('other','asc')
                                    ->orderBy('description')->get();
    }

    public function getEquipmentSpecificLocation($parent_id = 0) {
        return EquipmentSpecificLocation::with('allChildrens')->where('parent_id', $parent_id)
                                        ->orderBy('order')
                                        ->orderBy('description')->get();
    }

    public function getAllEquipmentsInSystem($system_id, $limit) {
        return $this->model->where('system_id', $system_id)->paginate($limit);
    }

    public function getEquipmentsByAssessId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->get();
    }

    public function getActiveSection($type, $is_water_temperature) {
        $equipment_type = EquipmentType::where('id', $type)->first();
        if (!is_null($equipment_type)) {
            $sections = EquipmentTemplateSection::with('section')->where('template_id', $equipment_type->template_id)->get();
            $active_field = [];
            $required = [];
            $validation = TempValidationByTemplate::where('template_id', $equipment_type->template_id)->get();

            if ($is_water_temperature) {
                foreach ($sections as $data) {
                    $active_field[] = $data->section->field_name ?? '';
                    if ($data->section->section == EQUIPMENT_SECTION_TEMP && $data->required == 1) {
                        $required[] = $data->section->field_name ?? '';
                    }
                }
            } else {
                foreach ($sections as $data) {
                    $active_field[] = $data->section->field_name ?? '';
                    if ($data->required == 1) {
                        $required[] = $data->section->field_name ?? '';
                    }
                }
            }

            $template_id = $equipment_type->template_id;

            return ['template_id' => $template_id, 'active_field' => $active_field, 'validation' => $validation,'required' => $required ];
        }

        return null;
    }

    public function searchEquipmentInAssessment($query_string,$property_id, $assess_id, $templates) {
        $builder = Equipment::where('property_id', $property_id)->where('assess_id', $assess_id)
                            ->whereRaw("(name LIKE '%$query_string%' OR reference LIKE '%$query_string%')")->limit(10)
                            ->where('decommissioned', 0);

        if (count($templates)) {
            $builder->whereIn('type', function ($subQuery) use ($templates) {
                $subQuery->select('id')
                        ->from(with(new EquipmentType())->getTable())
                        ->whereIn('template_id', $templates);
            });
        }

        return $builder->get();
    }

    public function searchEquipmentType($query_string) {
        return EquipmentType::whereRaw("(description LIKE '%$query_string%')")->limit(10)->get();
    }


    public function searchEquipmentTypeWaterTemperature($query_string) {
        return EquipmentType::whereRaw("(description LIKE '%$query_string%')")->where('template_id', '<>', EQUIPMENT_TEMPLATE_MISC)->limit(10)->get();
    }

    public function updateOrCreateModel($id, $data) {
        EquipmentModel::updateOrCreate(['equipment_id' => $id], $data);
        return true;
    }

    public function updateOrCreateConstruction($id, $data) {
        EquipmentConstruction::updateOrCreate(['equipment_id' => $id], $data);
        return true;
    }

    public function updateOrCreateTemp($id, $data) {
        EquipmentTempAndPh::updateOrCreate(['equipment_id' => $id], $data);
        return true;
    }
    public function updatTemp($id, $data) {
        EquipmentTempAndPh::updateOrCreate(['equipment_id' => $id], $data);
        return true;
    }

    public function updateOrCreateCleaning($id, $data) {
        EquipmentCleaning::updateOrCreate(['equipment_id' => $id], $data);
        return true;
    }

    public function insertDropdownValue($equipment_id,$dropdown_data_item_parent_id, $value, $other = null) {
        if (is_array($value)) {
            $value = implode(",",$value);
        }
        $dataDropdownValue = [
            'parent_id' => $dropdown_data_item_parent_id,
            'value' => $value,
            'other' => $other
        ];
        $data =  EquipmentSpecificLocationValue::updateOrCreate(['equipment_id' => $equipment_id], $dataDropdownValue);

    }

    public function getEquipmentSpecificLocationChild($id = 0) {
        return EquipmentSpecificLocation::with('childrens')->where('id', $id)
            ->orderBy('order')
            ->orderBy('description')->first();
    }

    // public function getAllEquipments($property_id, $limit,$request) {
    //     if($request->has('q')) {
    //         $builder = $this->model
    //             ->where('property_id', $property_id)
    //             ->where('assess_id', COMPLIANCE_ASSESSMENT_REGISTER);
    //         if (isset($request->q) && !empty($request->q)) {
    //             $condition_raw = "( `name` LIKE '%" . $request->q . "%'
    //                                 OR `reference` LIKE '%" . $request->q . "%' )";
    //             $builder->whereRaw($condition_raw);
    //         }
    //         return $builder->paginate($limit);
    //     }
    //     return Equipment::where('property_id', $property_id)->where('assess_id', 0)->paginate($limit);
    // }

    public function getAllEquipments($property_id, $limit) {
        return Equipment::where('property_id', $property_id)->where('assess_id', 0)->paginate($limit);
    }

    //assess_id = 0
    public function listEquimentProperty($property_id, $system_id) {
        if(!$system_id){
            return $this->model->where(['property_id'=>$property_id,'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->orderBy('name')->get();
        }
        return $this->model->where(['property_id'=>$property_id,'system_id' => $system_id])->orderBy('name')->get();
    }

    public function getSpecificlocationValue($equipment_id) {

        $dataValue =  EquipmentSpecificLocationValue::where('equipment_id', $equipment_id)->first();
        if (!is_null($dataValue)) {
            $dataValue_ids = explode(",",$dataValue->value);
            $allSpecifics = EquipmentSpecificLocation::whereIn('id', $dataValue_ids);

                $dataValue_ids = explode(",",$dataValue->value);

                if (is_null($allSpecifics->first())) {
                    $specificParentIds = [];
                } else {
                    $specificParentIds = $this->getallParentsIds($allSpecifics->first()->allParents ?? null);
                }
                // if does not exist parent : other selected
                if (empty($specificParentIds)) {
                    return $dataValue_ids;
                }
                array_push($specificParentIds, $dataValue_ids);
                return $specificParentIds;
        }
        return [];

    }

    public function getallParentsIds($data) {
        $id = [];
        if (!is_null($data)) {
            array_unshift($id, $data->id);
            if (!is_null($data->allParents)) {
                $parent1 = $data->allParents;
                array_unshift($id, $data->allParents->id);
                if (!is_null($parent1->allParents)) {
                    $parent2 = $parent1->allParents;
                    array_unshift($id, $parent1->allParents->id);
                    if (!is_null($parent2->allParents)) {
                        $parent3 = $parent2->allParents;
                        array_unshift($id, $parent2->allParents->id);
                    }
                }
            }
        }
        return $id;
    }

    public function getTemplateValidationFields($template_id) {
        return NonconformityValidate::where('template_id', $template_id)->pluck('field')->toArray();
    }

    public function getNonConformityValidate($template_id, $field, $tmv = null, $value = NULL) {
        $builder = NonconformityValidate::where('template_id', $template_id)->where('field', $field);

        if ($value) {
            $builder->where('value', $value);
        }

        $data = $builder->get();

        if (count($data) > 1) {
            return $builder->where('tmv', $tmv)->first();
        } else {
            return $builder->first();
        }

        return $builder->first();
    }

    public function checkNonComform($condition) {
        $exist = Nonconformity::where($condition)->first();
        return $exist ? $exist->id : false;
    }

    public function removeNonconformAfterChangeType($fields, $equipment_id) {
        $hazard_ids = Nonconformity::whereNotIn('field', $fields)
                    ->where('equipment_id', $equipment_id)->pluck('hazard_id')->toArray();
        // delete relation hazard
        Hazard::whereIn('id', $hazard_ids)->update(['is_deleted' => 1]);

        // delete nonconform
        Nonconformity::whereNotIn('field', $fields)->where('equipment_id', $equipment_id)->update(['is_deleted' => 1]);
        return true;
    }

    public function revertNonconformity($id) {
        if ($id) {
            $non_conform = Nonconformity::where('id', $id)->first();
            // revert relation hazard
            Hazard::where('id', $non_conform->hazard_id ?? 0)->update(['is_deleted' => 0, 'is_temp' => 1,'decommissioned' => 0]);

            // revert nonconform
            Nonconformity::where('id', $id)->update(['is_deleted' => 0]);
        }
        return true;
    }

    public function removeNonconformity($id) {
        if ($id) {
            $non_conform = Nonconformity::where('id', $id)->first();

            // remove relation hazard
            Hazard::where('id', $non_conform->hazard_id ?? 0)->update(['is_deleted' => 1]);

            // remove nonconform
            Nonconformity::where('id', $id)->update(['is_deleted' => 1]);
        }
        return true;
    }

    public function listEquipmentRegisterByID($equipment_ids, $relations){
        if(count($equipment_ids)){
            return $this->model->with($relations)->whereIn('id',$equipment_ids)
                ->where(['assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
        }
        return [];
    }

    public function updateOrCreateNonComform($data) {
        $non_conform = Nonconformity::create($data);
        $non_conform->reference = 'NC'.$non_conform->id;
        $non_conform->record_id = $non_conform->id;
        $non_conform->save();
        return true;
    }

    public function lockEquipmentsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => true]);
    }

    public function getOutletRegister($assess_id) {
        $register_outlet_template = REGISTER_OUTLET_TEMPLATE;
        $outlets = Equipment::with('area', 'location')->whereHas('equipmentType', function($query) use ($register_outlet_template) {
                        return $query->whereIn('template_id', $register_outlet_template);
                     })->where('assess_id', $assess_id)->get();

        $data = [];
        $outlet_name = [];
        $count_outlet = 0;
        if ($outlets) {
            foreach ($outlets as $outlet) {
                $data[$outlet->area_id .'v'. $outlet->location_id]['area_ref'] = $outlet->area->title_presentation ?? '';
                $data[$outlet->area_id .'v'. $outlet->location_id]['location_ref'] = $outlet->location->title_presentation ?? '';

                $data[$outlet->area_id .'v'. $outlet->location_id][$outlet->type][] = 1;
                $outlet_name[$outlet->type] = $outlet->equipmentType->description ?? '';
                $count_outlet++;
            }
        }
        $all_outlet_name_id = array_keys($outlet_name);

        return [
            'data' => $data,
            'outlet_names' => $outlet_name,
            'count_outlet' => $count_outlet,
            'all_outlet_name_ids' => $all_outlet_name_id
        ];
    }

    public function unlockEquipmentsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => false]);
    }

    public function listNaEquipments($property_id, $relations){
        return $this->model->with($relations)
            ->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                     'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER,
                     'property_id' => $property_id,
                     'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
                     'area_id' => COMPLIANCE_NA_ROOM_AREA,
                     'location_id' => COMPLIANCE_NA_ROOM_AREA])->get();
    }

    public function searchEquipment($q){
        // property privilege
        return $this->model->whereRaw("(name LIKE '%$q%' OR reference LIKE '%$q%')")
            ->where('decommissioned','=',0)
            ->orderBy('reference','asc')->limit(LIMIT_SEARCH)->get();
    }

    public function getEquipmentPaginate($property_id, $assess_id, $decommissioned) {
        $equipments = Equipment::where('property_id', $property_id)
                        ->where('assess_id', $assess_id)
                        ->where('decommissioned', $decommissioned)
                        ->get()->toArray();
        foreach($equipments as $key => $system) {
            $equipments[$key]['next_id'] = $equipments[$key + 1]['id'] ?? 0;
            $equipments[$key]['prev_id'] = $equipments[$key - 1]['id'] ?? 0;
            $equipments[$key]['first_id'] = $equipments[array_key_first($equipments)]['id'] ?? 0;
            $equipments[$key]['last_id'] = $equipments[array_key_last($equipments)]['id'] ?? 0;
        }

        return $equipments;
    }

    public function getEquipmentDetails($id, $relations){
        return $this->model->with($relations)->where('id', $id)->first();
    }
}
