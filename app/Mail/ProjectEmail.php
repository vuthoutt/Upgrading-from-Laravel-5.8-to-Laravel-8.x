<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class ProjectEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $type;
    public $project_ref;
    public $project_title;
    public $company_name;
    public $project_id;
    public $property;
    public $project_type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type, $project_ref, $project_title,$company_name, $project_id, $property,$project_type)
    {
        $this->type  = $type;
        $this->project_ref  = $project_ref;
        $this->project_title  = $project_title;
        $this->company_name  = $company_name;
        $this->project_id  = $project_id;
        $this->property  = $property;
        $this->project_type  = $project_type;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->type) {
            case PROJECT_OPPORTUNITY_EMAILTYPE:
            return $this->subject('Project Opportunity')
                    ->view('emails.project_opportunity_email')
                    ->with([
                        'project_ref' => $this->project_ref,
                        'project_name' => $this->project_title,
                        'project_id' => $this->project_id,
                        'company_name' => $this->company_name,
                        'domain' => \Config::get('app.url'),
                        'property' => $this->property
                    ]);
                break;
            case PROJECT_INVITATION_EMAILTYPE:
                return $this->subject('Project Invitation')
                            ->view('emails.project_invitation_email')
                            ->with([
                                'project_ref' => $this->project_ref,
                                'project_name' => $this->project_title,
                                'project_id' => $this->project_id,
                                'company_name' => $this->company_name,
                                'domain' => \Config::get('app.url'),
                                'property' => $this->property,
                                'project_type' => $this->project_type
                            ]);
                break;

            case PROJECT_TENDER_SUCCESSFUL_EMAILTYPE:
            return $this->subject('Tender Successful')
                    ->view('emails.tender_successful_email')
                    ->with([
                        'project_ref' => $this->project_ref,
                        'project_name' => $this->project_title,
                        'project_id' => $this->project_id,
                        'company_name' => $this->company_name,
                        'domain' => \Config::get('app.url'),
                        'property' => $this->property
                    ]);
                break;


            case PROJECT_TENDER_UNSUCCESSFUL_EMAILTYPE:
            return $this->subject('Tender Unsuccessful')
                    ->view('emails.tender_unsuccessful_email')
                    ->with([
                        'project_ref' => $this->project_ref,
                        'project_name' => $this->project_title,
                        'project_id' => $this->project_id,
                        'company_name' => $this->company_name,
                        'domain' => \Config::get('app.url'),
                        'property' => $this->property
                    ]);
                break;

            case PROJECT_ARCHIVED_EMAILTYPE:
            return $this->subject('Project Archived')
                    ->view('emails.project_archived_email')
                    ->with([
                        'project_ref' => $this->project_ref,
                        'project_name' => $this->project_title,
                        'project_id' => $this->project_id,
                        'company_name' => $this->company_name,
                        'domain' => \Config::get('app.url'),
                        'property' => $this->property
                    ]);
                break;

            default:
                return false;
                break;
        }
    }
}
