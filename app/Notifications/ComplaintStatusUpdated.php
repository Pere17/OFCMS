<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ComplaintStatusUpdated extends Notification
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
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'reference_number' => $this->complaint->reference_number,
            'message' => "Your complaint #{$this->complaint->reference_number} has been updated to: {$this->complaint->status}",
        ];
    }
}
