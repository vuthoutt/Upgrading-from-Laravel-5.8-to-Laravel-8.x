<?php


namespace App\Repositories\ShineCompliance;


trait PullAssessmentToRegister
{
    public function getByRecordIdAndPropertyId($record_id, $property_id)
    {
        return $this->model->where('record_id', $record_id)
            ->where('property_id', $property_id)
            ->where('assess_id', 0)
            ->first();
    }

    public function getByAssessId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->get();
    }

    public function getRegisterByRecordId($record_id)
    {
        $result = $this->model->where('assess_id', 0)
                            ->where('record_id', $record_id)
                            ->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }
}
