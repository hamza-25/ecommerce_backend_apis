<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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

    }

    public function edit($id){
        
    }

    public function update(Request $request, $id){
        
    }
    public function destroy($id){
        
    }
}
