<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class mailValidation extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $userSender ;
    public $userRece;
    public $invitation ;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id , $userSender, $userRece , $invitation)
    {
        $this->user = $id ;
        $this->userSender = $userSender ;
        $this->userRece = $userRece ;
        $this->invitation = $invitation;

    }
    public function build()
    {
        return $this->markdown('emails.usersValidation')
                    ->subject('You have been added to a company ')
                    ->with(['name' => $this->user->name , 'usersender' => $this->userSender , 'useRece' => $this->userRece , 'invitation'=> $this->invitation]);
    }
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Mail Validation',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
  /*  public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
