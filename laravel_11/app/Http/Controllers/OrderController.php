<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // use that
use Exception;

class OrderController extends Controller
{
    function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    function store(Request $request)
    {
        $order = Order::create($request->all());
        return response()->json($order);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
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
