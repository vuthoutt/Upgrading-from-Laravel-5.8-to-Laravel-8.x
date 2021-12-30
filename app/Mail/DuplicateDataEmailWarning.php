<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class DuplicateDataEmailWarning extends Mailable
{
    use Queueable, SerializesModels;
    public $survey;
    public $areas;
    public $locations;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($survey, $areas, $locations)
    {
        $this->survey = $survey;
        $this->areas = $areas;
        $this->locations = $locations;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Survey Approved Duplicate Data Warning')
                    ->view('emails.duplicate_data_warning_email')
                    ->with([
                        'company_name' => env('APP_DOMAIN') ?? 'GSK',
                        'survey' => $this->survey,
                        'locations' => $this->locations,
                        'areas' => $this->areas,
                        'domain' => \Config::get('app.url')
                    ]);
    }
}
