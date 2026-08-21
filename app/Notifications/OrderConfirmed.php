<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;

        return (new MailMessage)
            ->subject(__('Your order has been confirmed') . ' - ' . $order->order_number)
            ->greeting(__('Your order has been confirmed') . '!')
            ->line(__('Order number') . ': ' . $order->order_number)
            ->line(__('Total') . ': ' . $order->formatted_total)
            ->line(__('You can now download your photos.'))
            ->action(__('Download your photos'), route('downloads.index', $order))
            ->line(__('Thank you for your purchase!'));
    }
}
