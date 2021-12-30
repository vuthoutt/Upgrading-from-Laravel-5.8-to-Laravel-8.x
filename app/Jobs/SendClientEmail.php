<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\Role;
use App\RoleUpdate;
use App\Update;
use App\Models\Property;
use App\Models\Survey;
use App\Models\ClientEmailNotification;
use App\Mail\ClientEmail;

class SendClientEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $property_id;
    public $survey_id;
    public $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($property_id, $type, $survey_id = false)
    {
        $this->property_id = $property_id;
        $this->survey_id = $survey_id;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $property_id = $this->property_id;
            $asbestos_email_privs = ASBESTOS_REGISTER_UPDATE_VIEW_PRIV;
            $high_risk_email_privs = HIGH_RISK_ITEM_VIEW_PRIV;
            $remedial_email_privs = REMEDIAL_ACTION_REQUIRED_VIEW_PRIV;

            $asbestos_user_emails = \DB::select("SELECT u.* FROM `tbl_users` u
                                left join tbl_role ur on ur.id = u.role
                                left join tbl_role_update uv on uv.id = u.role
                                where email is not null and email != '' and email REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,63}$'
                                and (FIND_IN_SET($asbestos_email_privs,ur.view_privilege)
                                or ur.is_everything = 1)
                                and (FIND_IN_SET($property_id,uv.update_privilege)
                                or uv.is_everything = 1)
                                GROUP BY email");

            // $high_risk_user_emails = \DB::select("SELECT u.* FROM `tbl_users` u
            //                     left join tbl_role ur on ur.id = u.role
            //                     left join tbl_role_update uv on uv.id = u.role
            //                     where email is not null and email != '' and email REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,63}$'
            //                     and (FIND_IN_SET($high_risk_email_privs,ur.view_privilege)
            //                     or ur.is_everything = 1)
            //                     and (FIND_IN_SET($property_id,uv.update_privilege)
            //                     or uv.is_everything = 1)
            //                     GROUP BY email");
            $high_risk_user_emails = User::whereIn('id', [7,64,131,488,675])->get();

            $remedial_user_emails = \DB::select("SELECT u.* FROM `tbl_users` u
                                left join tbl_role ur on ur.id = u.role
                                left join tbl_role_update uv on uv.id = u.role
                                where email is not null and email != '' and email REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,63}$'
                                and (FIND_IN_SET($remedial_email_privs,ur.view_privilege)
                                or ur.is_everything = 1)
                                and (FIND_IN_SET($property_id,uv.update_privilege)
                                or uv.is_everything = 1)
                                GROUP BY email");

            echo(' ==========>  Get all privilege user emails from update_email_type');

            $property = Property::find($property_id);
            $survey = Survey::find($this->survey_id);

            switch ($this->type) {
                case ASBESTOS_REGISTER_EMAILTYPE:
                    $this->sendEmailClient($asbestos_user_emails, $property, $survey);
                    break;

                case HIGH_RISK_ITEM_EMAILTYPE:
                    $this->sendEmailClient($high_risk_user_emails, $property, $survey);
                    break;

                case REMEDIAL_ACTIONS_REQUIRED_EMAILTYPE:
                    $this->sendEmailClient($remedial_user_emails, $property, $survey);
                    break;

                default:
                    # code...
                    break;
            }
        } catch (\Exception $e) {
            echo($e->getMessage());
        }
    }

    public function sendEmailClient($users, $property, $survey) {

        if (!is_null($users) and (count($users) > 0 )) {
                foreach ($users as $user) {
                    $notification = ClientEmailNotification::where('user_id', $user->id)
                                        ->where('email_type', $this->type)
                                        ->orderBy('last_send','desc')->first();
                    $send = false;
                    echo('==========>  Start send client mail'.$this->type .' to user :'. $user->id);
                    // check notification within 1 day.
                    if (is_null($notification)) {
                        $send = true;
                    } else {
                        $time = (time() - $notification->last_send)/86400;
                        if ($time > 1) {
                            $send = true;
                        }
                    }

                    if ($send) {
                        if ($this->survey_id) {
                            echo(' ==========>  Send mail from survey to '.$user->email);
                            \Mail::to($user->email)
                                ->send(new ClientEmail($property->clients->name,
                                                        $this->type,
                                                        $property->property_reference ?? '',
                                                        $property->name,
                                                        $property->propertyInfo->pblock ?? '',
                                                        $survey->reference,
                                                        $survey->clients->name ?? ''
                                                    ));
                        } else {
                            echo(' ==========>  Send mail from register to '.$user->email);
                            \Mail::to($user->email)
                                ->send(new ClientEmail($property->clients->name,
                                                        $this->type,
                                                        $property->property_reference,
                                                        $property->name,
                                                        $property->propertyInfo->pblock
                                                    ));
                        }
                        echo(' ==========>  Save  '.$user->id.' to database');
                        ClientEmailNotification::create([
                            'user_id' => $user->id,
                            'email_type' => $this->type,
                            'last_send' => time()
                        ]);
                    }
                }
            }
        echo(' ==========>  Send client mail:'. $this->type.' successfully');
    }

    protected function listenForEvents()
    {
        $this->laravel['events']->listen(JobProcessing::class, function ($event) {
            $this->writeOutput($event->job, 'starting');
        });

        $this->laravel['events']->listen(JobProcessed::class, function ($event) {
            $this->writeOutput($event->job, 'success');
        });

        $this->laravel['events']->listen(JobFailed::class, function ($event) {
            $this->writeOutput($event->job, 'failed');

            $this->logFailedJob($event);
        });
    }
}
