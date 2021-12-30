<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SurveyEmail extends Mailable
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
        return $this->subject($this->dataEmail['subject'])
                    ->view('emails.survey_rejected_email')
                    ->with([
                        'subject' => 'Survey Reject',
                        'contractor_name' => $this->dataEmail['contractor_name'],
                        'survey_ref' => $this->dataEmail['survey_reference'],
                        'property_block' => $this->dataEmail['property_block'],
                        'property_ref' =>$this->dataEmail['property_reference'],
                        'property_name' => $this->dataEmail['property_name'],
                        'company_name' => $this->dataEmail['company_name'],
                        'domain' => \Config::get('app.url'),
                    ]);
    }
}
