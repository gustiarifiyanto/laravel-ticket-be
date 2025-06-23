<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //create order
    public function create(Request $request)
    {
        // Validate the request data
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'sku_id' => 'required|exists:skus,id',
            'quantity' => 'required|integer',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:255',
        ]);

        // Create the order
        $order = Order::create([
            'event_id' => $request->event_id,
            'sku_id' => $request->sku_id,
            'quantity' => $request->quantity,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
        ]); 

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => $order,
        ], 201);
    }
}
