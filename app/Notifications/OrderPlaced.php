<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification implements ShouldQueue
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
            ->subject(__('New order') . ': ' . $order->order_number)
            ->greeting(__('New order received') . '!')
            ->line(__('Order number') . ': ' . $order->order_number)
            ->line(__('Customer') . ': ' . $order->customer_name . ' (' . $order->customer_email . ')')
            ->line(__('Total') . ': ' . $order->formatted_total)
            ->line(__('Items') . ': ' . $order->items->count())
            ->action(__('View order'), route('admin.orders.show', $order))
            ->line(__('Please confirm the payment once received.'));
    }
}
