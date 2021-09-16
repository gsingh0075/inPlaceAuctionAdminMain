<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFmvNotification extends Notification
{
    use Queueable;

    protected $fmv;
    protected $client;
    protected $user;
    protected $type;

    public function __construct($fmv, $client, $user, $type)
    {
        //
        $this->fmv = $fmv;
        $this->client = $client;
        $this->user = $user;
        $this->type = $type;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'by'           => $this->user,
            'id'           => $this->fmv->fmv_id,
            'type'         => $this->type,
            'resource'     => $this->client->COMPANY,
            'model'        => 'fmv'
         ];
    }
}
