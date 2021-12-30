<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkRequestApprovalEmail extends Mailable
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
                    ->view('emails.work_request_email')
                    ->with([
                        'subject' => $this->dataEmail['subject'] ?? '',
                        'username' => $this->dataEmail['work_requester'] ?? '',
                        'work_requester_type' => $this->dataEmail['work_requester_type'] ?? '',
                        'block_reference' => $this->dataEmail['block_reference'] ?? '',
                        'property_reference' => $this->dataEmail['property_reference'] ?? '',
                        'property_name' => $this->dataEmail['property_name'] ?? '',
                        'work_request_reference' => $this->dataEmail['work_request_reference'] ?? '',
                        'company_name' => env('APP_DOMAIN') ?? 'Westminster',
                        'property_postcode' => $this->dataEmail['property_postcode'] ?? '',
                        'domain' => $this->dataEmail['domain']
                    ]);
    }
}
