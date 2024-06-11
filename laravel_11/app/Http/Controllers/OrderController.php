<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
                "product_id" => "required|array",
                // "full_name" => "required",
                // "user_id" => "numeric|exists:users,id|min:1",
            ]);
            // $address = Address::create([]);
            $transaction_id = (string)Auth::user()->id . (string)Str::uuid();
            $products = $request->product_id;
            $orders = [];
            foreach ($products as $productId) {
                $orders [] = Order::create([
                    "total_price" => Product::findOrFail($productId)->price,
                    "product_id" =>$productId,
                    "user_id" => Auth::user()->id,
                    "transaction_id" => $transaction_id,
                    // "address_id" => $address->id,
                ]);
            }
            return response()->json($orders, 201);

        } catch (ValidationException $e) {
            return response()->json([$e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create order'/* . $e->getMessage() */], 500);
        }
    }
    public function show($id)
    {
        try {
            $order = Order::findOrFail($id)->where("user_id", Auth::user()->id)->first();
            return response()->json($order);
        } catch (Exception $e) {
            return response()->json(['error' => 'failed to get order'], 404);
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
            return response()->json(['message' => 'failed to update order'], 500);
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
