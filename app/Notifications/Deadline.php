<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class Deadline extends Notification
{
    use Queueable;

    private $intervention;
    private $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($intervention, $status)
    {
        $this->intervention = $intervention;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return ['Deadline ' . $this->status => $this->intervention];
    }
}
