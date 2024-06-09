<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; 
use Exception;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::all();
            return response()->json($orders);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 400);
        }
    }
    public function store(Request $request)
    {
        try {
            $data_validation = $request->validate([
                "transaction_id" => "required",
                "total_price" => "required",
                "product_id" => "required",
                "user_id" => "required",
            ]);

            $order = Order::create($data_validation);
            return response()->json($order);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create order'], 500);
        }
    }
    public function show($id)
    {
        try {
            $order = Order::findOrFail($id);
            return response()->json($order);
        } catch (Exception $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data_validation = $request->validate([
                "transaction_id" => "required",
                "total_price" => "required",
                "product_id" => "required",
                "user_id" => "required",
            ]);

            $order = Order::findOrFail($id);
            $order->update($data_validation);
            return response()->json($order);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 406);
        } catch (Exception $e) {
            return response()->json(['message'=> 'failed to update order'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['message' => 'order deleted succesfully']);
        } catch (Exception $e) {
            return response()->json(['errors' => "Failed to delete order"], 500);
        }
    }
}
