<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintSubmitted extends Notification
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
            ->subject('New Complaint Submitted: '.$this->complaint->reference_number)
            ->line("A new complaint #{$this->complaint->reference_number} has been submitted: \"{$this->complaint->subject}\".")
            ->action('View Complaint', route('admin.complaints.show', $this->complaint))
            ->line('Please review it at your earliest convenience.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'reference_number' => $this->complaint->reference_number,
            'message' => "New complaint #{$this->complaint->reference_number}: {$this->complaint->subject}",
        ];
    }
}
