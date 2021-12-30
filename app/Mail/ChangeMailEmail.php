<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeMailEmail extends Mailable
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
                    ->view('emails.confirm_email')
                    ->with([
                        'username' => $this->dataEmail['username'],
                        'domain' => $this->dataEmail['domain'],
                        'company_name' => $this->dataEmail['companyName'],
                        'subject' => $this->dataEmail['subject'],
                        'token' => $this->dataEmail['token'],
                        'type' => $this->dataEmail['type'],
                        'user_id' => $this->dataEmail['user_id'],
                    ]);
    }
}
