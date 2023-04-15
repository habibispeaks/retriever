<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\UploadItem;

class ClaimNotification extends Notification
{
    use Queueable;
protected $itemId;
protected $claimerName;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UploadItem $item, $claimerName)
    {
        $this->item = $item;

        $this->claimerName = $claimerName;

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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('A user has claimed an item.')
    //                 ->action('View Item', url('/items/'.$this->itemId))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'item_id' => $this->item->id,
            'claimer_name' => $this->claimerName,
            'message' => 'A user has claimed your item',
        ];
    }
}
