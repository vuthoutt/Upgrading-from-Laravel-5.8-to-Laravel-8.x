<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Repositories\SurveyRepository;
use App\Repositories\ItemRepository;
use App\Jobs\SendProjectEmail;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SurveyRepository $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    public static function sendMailNotification ($type, $project_id, $contractor_id, $document_id = null) {
        $user =  \Auth::user();
        if($type <= 8) {
            $dataNoti = [
                'user_id' => $user->id,
                'client_id' => $user->client_id,
                'contractor_id' => $contractor_id,
                'project_id' => $project_id,
                'document_id' => is_null($document_id) ? 0 : $document_id,
                'type' => $type,
                'status' => 1,
                'added_date' => time()
            ];

            Notification::create($dataNoti);
        }

        //send email if need

        \Queue::pushOn(PROJECT_EMAIL_QUEUE,new SendProjectEmail($type, $project_id, $contractor_id, null));
    }

    public static function sendMailNotificationCommand ($type, $project_id, $contractor_id, $document_id = null) {
        if($type <= 8) {
            $dataNoti = [
                'user_id' => 1,
                'client_id' => 1,
                'contractor_id' => $contractor_id,
                'project_id' => $project_id,
                'document_id' => is_null($document_id) ? 0 : $document_id,
                'type' => $type,
                'status' => 1,
                'added_date' => time()
            ];

            Notification::create($dataNoti);
        }

        //send email if need

        \Queue::pushOn(PROJECT_EMAIL_QUEUE,new SendProjectEmail($type, $project_id, $contractor_id, null));
    }

    public static function checkNotification($type, $contractor_id, $project_id, $document_id = null) {
        try {
            $dataWhere = [  'status' => 1,
                            'type' => $type,
                            'contractor_id' => $contractor_id,
                            'project_id' => $project_id];
            !is_null($document_id) ? ($dataWhere['document_id'] = $document_id) : '';

            Notification::where($dataWhere)
                            ->update([
                                'status' => 2,
                                'checked_date' => time(),
                                'checked_user_id' => \Auth::user()->id
                                ]);

        } catch (Exception $e) {
            dd($e);
        }
    }

    public static function checkAvailableNotification ($document_id, $type) {
        return Notification::where('status', 1)->where('document_id', $document_id)->where('type', $type)->get();
    }

    public static function getListExistingNotificationsInProject($project_id, $type) {
        return Notification::where('status', 1)->where('project_id', $project_id)->where('type', $type)->get();
    }
}
