<?php

namespace App\Service\Midtrans;

use App\Service\Midtrans\Midtrans;
use Illuminate\Database\Eloquent\Collection;
use Midtrans\Snap;
use App\Models\Sku;


class CreatePaymentUrlService extends Midtrans
{
    protected $order;

    public function __construct()
    {
        parent::__construct();
        
    }

    public function getPaymentUrl($order)
    {

        $item_details = new Collection();

        foreach ($order->orderItems as $item) {
            $sku = Sku::find($item->sku_id);
            $item_details->push([
                'id' => $sku->id,
                'price' => $sku->price,
                'quantity' => $item->quantity,
                'name' => $sku->name,
            ]);
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->number,
                'gross_amount' => $order->total,
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
        ];

        $paymentUrl = Snap::createTransaction($params)->redirect_url;

        return $paymentUrl;
    }
}