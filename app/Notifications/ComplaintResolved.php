<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintResolved extends Notification
{
    use Queueable;

    public function __construct(public Complaint $complaint)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Complaint Resolved: '.$this->complaint->reference_number)
            ->line("Your complaint #{$this->complaint->reference_number} has been resolved.")
            ->action('View Complaint', route('complainant.complaints.show', $this->complaint))
            ->line('Thank you for your patience.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'reference_number' => $this->complaint->reference_number,
            'message' => "Your complaint #{$this->complaint->reference_number} has been resolved.",
        ];
    }
}
