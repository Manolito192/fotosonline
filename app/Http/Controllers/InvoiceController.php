<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        if (auth()->guest() || auth()->id() !== $order->user_id) {
            if ($order->user_id !== null) {
                abort(403);
            }
        }

        $order->load('items');

        $pdf = Pdf::loadView('invoices.order', compact('order'))
            ->setPaper('a4')
            ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }
}
