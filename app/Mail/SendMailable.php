<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from, $fromName, $subject,$emailData, $cc, $bcc) 
    { 
        $this->fromEmail = $from;
        $this->fromName = $fromName;
        $this->emailSubject = $subject;
        $this->emailData = $emailData;
        $this->ccEmails = $cc;
        $this->bccEmails = $bcc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        
        $fromName = $this->fromName; 
        $email = $this->from($this->fromEmail, $fromName)
        ->subject($this->emailSubject);
        if(!empty($this->ccEmails)){ 
            $email->cc($this->ccEmails);
        }
        if(!empty($this->bccEmails)){
            $email->bcc($this->bccEmails);
        }
        
        $email->view('emails.default')
        ->with([
            'emailData' => $this->emailData
        ]);

    }
}
