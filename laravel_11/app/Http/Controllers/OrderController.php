<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::all();
            foreach ($orders as $order) {
                $order["user_name"] = User::find($order->user_id)->name;
                $order["address"] = Address::find($order->address_id);
                $order["product_image"] = Product::find($order->product_id)->image;
            }
            return response()->json($orders);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 400);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $quantity = 1;
            $data_validation = $request->validate([
                "product_id" => "required|array",
                "full_name" => "required",
                "street" => "required",
                "city" => "required",
                "province" => "required",
                "country" => "required",
                "phone" => "required",
                "zip_code" => "required",
            ]);
            $address = Address::create([
                "full_name" => $data_validation['full_name'],
                "street" => $data_validation['street'],
                "city" => $data_validation['city'],
                "province" => $data_validation['province'],
                "country" => $data_validation['country'],
                "phone" => $data_validation['phone'],
                "zip_code" => $data_validation['zip_code'],
                "user_id" => Auth::user()->id,
            ]);
            $transaction_id = (string)Auth::user()->id . (string)Str::uuid();
            $products = $request->product_id;
            $orders = [];
            foreach ($products as $productId) {
                $product = Product::where('id', $productId)->lockForUpdate()->first();
                if (!(($product->quantity - $quantity) >= 0)) {
                    DB::rollBack();
                    return response()->json(['message' => 'Not enough stock'], 400);
                }
                $product->quantity -= $quantity;
                // dd($product->quantity);
                $product->save();
                $orders[] = Order::create([
                    "total_price" => Product::findOrFail($productId)->price * $quantity,
                    "product_id" => $productId,
                    "user_id" => Auth::user()->id,
                    "transaction_id" => $transaction_id,
                    "address_id" => $address->id,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'order created successfully', "orders" => $orders], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([$e->errors()], 400);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create order' . $e->getMessage()], 500);
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
