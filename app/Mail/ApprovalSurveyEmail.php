<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalSurveyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $dataEmail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->dataEmail = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->dataEmail['subject'] . (isset($this->dataEmail['property_name']) ? " – ". $this->dataEmail['property_name'] : ''))
                    ->view('emails.approval_survey_email')
                    ->with([
                        'subject' => $this->dataEmail['subject'] ?? '',
                        'username' => $this->dataEmail['work_requester'] ?? '',
                        'survey_reference' => $this->dataEmail['survey_reference'] ?? '',
                        'survey_type' => $this->dataEmail['survey_type'] ?? '',
                        'project_reference' => $this->dataEmail['project_reference'] ?? '',
                        'work_request_type' => $this->dataEmail['work_request_type'] ?? '',
                        'property_reference' => $this->dataEmail['property_reference'] ?? '',
                        'property_name' => $this->dataEmail['property_name'] ?? '',
                        'work_request_reference' => $this->dataEmail['work_request_reference'] ?? '',
                        'company_name' => env('APP_DOMAIN') ?? 'Westminster',
                        'domain' => $this->dataEmail['domain'],
                        'link_pdf' =>  $this->dataEmail['link_pdf']
                    ]);
    }
}
