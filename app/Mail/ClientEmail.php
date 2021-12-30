<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class ClientEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $type;
    public $property_ref;
    public $property_name;
    public $property_block;
    public $survey_ref;
    public $contractor_name;
    public $survey_name;
    public $project_ref;
    public $client_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client_name, $type, $property_ref, $property_name, $property_block, $survey_ref = '', $contractor_name = '', $survey_name = '' , $project_ref = '')
    {
        $this->client_name  = $client_name;
        $this->type  = $type;
        $this->property_ref  = $property_ref;
        $this->property_name  = $property_name;
        $this->property_block  = $property_block;
        $this->survey_ref  = $survey_ref;
        $this->contractor_name  = $contractor_name;
        $this->survey_name  = $survey_name;
        $this->project_ref  = $project_ref;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->type) {
            case ASBESTOS_REGISTER_EMAILTYPE:
            return $this->subject('Asbestos Register Update')
                    ->view('emails.asbestos_register_email')
                    ->with([
                        'property_ref' => $this->property_ref,
                        'property_name' => $this->property_name,
                        'property_block' => $this->property_block,
                        'company_name' => $this->client_name,
                        'domain' => \Config::get('app.url'),
                    ]);
                break;

            case HIGH_RISK_ITEM_EMAILTYPE:
                return $this->subject('High Risk Item')
                    ->view('emails.high_risk_item_email')
                    ->with([
                        'property_ref' => $this->property_ref,
                        'property_name' => $this->property_name,
                        'property_block' => $this->property_block,
                        'survey_ref' => $this->survey_ref,
                        'contractor_name' => $this->contractor_name,
                        'company_name' => $this->client_name,
                        'domain' => \Config::get('app.url'),
                    ]);
                break;

            case REMEDIAL_ACTIONS_REQUIRED_EMAILTYPE:
                return $this->subject('Remedial Actions Required')
                    ->view('emails.remedial_action_item_email')
                    ->with([
                        'property_ref' => $this->property_ref,
                        'property_name' => $this->property_name,
                        'property_block' => $this->property_block,
                        'company_name' => $this->client_name,
                        'domain' => \Config::get('app.url'),
                    ]);
                break;

            case SURVEY_APPROVED_EMAILTYPE:
                return $this->subject('Survey Ready for Approval Notification')
                    ->view('emails.survey_approved_email')
                    ->with([
                        'property_ref' => $this->property_ref,
                        'property_name' => $this->property_name,
                        'property_block' => $this->property_block,
                        'survey_ref' => $this->survey_ref,
                        'contractor_name' => $this->contractor_name,
                        'company_name' => $this->client_name,
                        'domain' => \Config::get('app.url'),
                    ]);
                break;

            case SURVEY_REJECTED_EMAILTYPE:
                return $this->subject('Survey Rejected Notification')
                    ->view('emails.survey_rejected_email')
                    ->with([
                        'property_ref' => $this->property_ref,
                        'property_name' => $this->property_name,
                        'property_block' => $this->property_block,
                        'survey_ref' => $this->survey_ref,
                        'contractor_name' => $this->contractor_name,
                        'company_name' => env('APP_DOMAIN') ?? 'Westminster',
                        'domain' => \Config::get('app.url'),
                    ]);
                break;

            case CONTRACTOR_PROJECT_EMAILTYPE:
                return $this->subject('Project Document Upload Notification')
                    ->view('emails.contractor_table_project_email')
                    ->with([
                        'property_ref' => $this->property_ref,
                        'property_name' => $this->property_name,
                        'property_block' => $this->property_block,
                        'survey_ref' => $this->survey_ref,
                        'contractor_name' => $this->contractor_name,
                        'survey_name' => $this->survey_name,
                        'project_ref' => $this->project_ref,
                        'company_name' => $this->client_name,
                        'domain' => \Config::get('app.url'),
                    ]);
                break;

            case CONTRACTOR_APPROVED_EMAILTYPE:
                    return $this->subject('Project Document Approved Notification')
                        ->view('emails.contractor_table_approved_email')
                        ->with([
                            'property_ref' => $this->property_ref,
                            'property_name' => $this->property_name,
                            'property_block' => $this->property_block,
                            'survey_ref' => $this->survey_ref,
                            'contractor_name' => $this->contractor_name,
                            'survey_name' => $this->survey_name,
                            'project' => $this->project_ref,
                            'company_name' => $this->client_name,
                            'domain' => \Config::get('app.url'),
                        ]);
                break;
            case CONTRACTOR_REJECT_EMAILTYPE:
                    return $this->subject('Project Document Rejected Notification')
                        ->view('emails.contractor_table_reject_email')
                        ->with([
                            'property_ref' => $this->property_ref,
                            'property_name' => $this->property_name,
                            'property_block' => $this->property_block,
                            'survey_ref' => $this->survey_ref,
                            'contractor_name' => $this->contractor_name,
                            'survey_name' => $this->survey_name,
                            'project' => $this->project_ref,
                            'company_name' => $this->client_name,
                            'domain' => \Config::get('app.url'),
                        ]);
                break;

            case DOCUMENT_APPROVED_EMAILTYPE:
                return $this->subject('Project Document Ready for Approval Notification')
                    ->view('emails.document_approved_email')
                    ->with([
                        'property_ref' => $this->property_ref,
                        'property_name' => $this->property_name,
                        'property_block' => $this->property_block,
                        'survey_ref' => $this->survey_ref,
                        'contractor_name' => $this->contractor_name,
                        'survey_name' => $this->survey_name,
                        'project_ref' => $this->project_ref,
                        'company_name' => $this->client_name,
                        'domain' => \Config::get('app.url'),
                    ]);
                break;

            default:
                # code...
                break;
        }
    }
}
