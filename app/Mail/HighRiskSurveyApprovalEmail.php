<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class HighRiskSurveyApprovalEmail extends Mailable
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
        return $this->subject('Survey Approved Notification')
                    ->view('emails.high_risk_item_survey_approval_email')
                    ->with([
                        'domain' => \Config::get('app.url'),
                        'company_name' => env('APP_DOMAIN') ?? 'GSK' ,
                        'subject' => 'High Risk Item',
                        'survey' => $this->dataEmail
                    ]);
    }
}
