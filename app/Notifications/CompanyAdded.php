<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;
use App\Company;
use App\Mail\NewCompanyEmail;

class CompanyAdded extends Notification
{
    use Queueable;

    protected $company;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return view('mail.new_company', $notifiable->toArray());
        // return (new MailMessage)
        //     ->view('mail.new_company', $this->company->toArray());
        return ((new NewCompanyEmail($notifiable))
            ->subject(strtoupper($notifiable->name) . ' Company Added!')
            ->to($notifiable->email)
            ->cc('fb9adfc439-d26dcb@inbox.mailtrap.io'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'company_added' => Carbon::now(),
            'company_name'  => $this->company->name,
        ];
    }
}
