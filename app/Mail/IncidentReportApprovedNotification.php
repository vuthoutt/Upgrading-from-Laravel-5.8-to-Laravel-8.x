<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncidentReportApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $dataEmail;

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
        return $this->subject($this->dataEmail['subject'])
            ->view('emails.incident_report_approved')
            ->with([
                'subject' => $this->dataEmail['subject'] ?? '',
                'username' => $this->dataEmail['username'] ?? '',
                'incident_report_reference' => $this->dataEmail['incident_report_reference'] ?? '',
                'property_uprn' => $this->dataEmail['property_uprn'] ?? '',
                'property_name' => $this->dataEmail['property_name'] ?? '',
                'link_incident_report_detail' => $this->dataEmail['link_incident_report_detail'] ?? '',
                'link_incident_report_pdf' => $this->dataEmail['link_incident_report_pdf'] ?? '',
                'company_name' => $this->dataEmail['company_name'] ?? '',
                'domain' => $this->dataEmail['domain']
            ]);
    }
}
