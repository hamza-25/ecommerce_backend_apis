<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Dotenv\Exception\ValidationException;
use Exception;

class OrderController extends Controller
{
    function index() {
        $orders = Order::all();
        return response()->json($orders);
    }

    function store(Request $request) {
        $order = Order::create($request->all());
        return response()->json($order);
    }

    public function show($id){
        $order = Order::findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id){
        try {
            $data_validation = $request->validation([
                "transactions_id" => "required",
                "total_price" => "required",
                "product_id" => "required",
                "user_id" => "required",
            ]);
        
        $order = Order::findOrFail($id);
        $order ->update($data_validation);
        return response()->json($order);
        } catch (ValidationException $error){
            return response()->json(['errors' => $error()], 406);
        }
    }

    public function destroy($id){
        try{
        $order = Order::findOrFail($id);
        $order ->delete();
        return response()->json('The order was deleted');
    }catch (Exception $error){
        return response()->json(['message' => "We can't delete the order"],500);
    }
    }
}
