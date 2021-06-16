<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        //return ['mail'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

//    public function get()
//    {
//        $interventions = DB::table('course_edition_user')->where('course_edition_id', '=', 1)->get()
//            ->flatMap(function ($editionUser) {
//                $userInterventions = DB::table('interventions_individual')
//                    ->where('user_id', '=', $editionUser->user_id)->get();
//                $currentDate = Carbon::now();
//                $passed = $userInterventions->map(function ($intervention) use ($currentDate) {
//                    if ($currentDate->gt($intervention->end_day)) {
//                        return $intervention;
//                    }
//                    return null;
//                })->filter(function ($intervention) {
//                    return $intervention != null;
//                })->unique();
//                return $passed;
//            })->filter(function ($intervention) {
//                return $intervention != null;
//            })->unique();
//
//        return $interventions;
//    }

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
