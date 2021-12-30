<?php


namespace App\Services\ShineCompliance;


trait CreateTempHazard
{
    protected function createTempHazard($model, $assess_type = 0)
    {
        $data_hazard = [
            'name' => self::HAZARD_NAME,
            'property_id' => $model->property_id,
            'assess_id' => $model->assess_id,
            'area_id' => $model->area_id,
            'assess_type' => $assess_type,
            'location_id' => $model->location_id,
            'decommissioned' => 0,
            'total_risk' => 0,
            'type' => self::HAZARD_TYPE,
            'is_temp' => 1
        ];
        $hazard = $this->hazardRepository->create($data_hazard);
        $hazard->reference = 'HZ'.$hazard->id;
        $hazard->record_id = $hazard->id;
        if ($assess_type == ASSESSMENT_FIRE_TYPE) {
            $hazard->reference = 'FH'.$hazard->id;
        } elseif ($assess_type == ASSESSMENT_WATER_TYPE) {
            $hazard->reference = 'WH'.$hazard->id;
        } else {
            $hazard->reference = 'HZ'.$hazard->id;
        }
        $hazard->save();

        return $hazard->id;
    }
}
