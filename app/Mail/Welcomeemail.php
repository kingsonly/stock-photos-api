<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcomeemail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public function __construct($user)
    {
        $this->user =  $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
     {
        
         return $this->from('Boffins@gmail.com',  'Bartum Energy')->subject("Welcome to Bartum Energy")->view('emails.welcome')->with('user', $this->user);
     }
}
