<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\Notification;
use Prettus\Repository\Eloquent\BaseRepository;

class NotificationRepository extends BaseRepository
{

    public function model()
    {
        return Notification::class;
    }

    public function completeNotification($project_id){
        return $this->model->where('project_id', $project_id)->update(['status' => 2]);
    }
}
