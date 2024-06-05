<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    function index (){
        $products = Product::all();
        return response()->json($products);
    }

    function store(Request $request){
        $product = Product::create($request->all());
        return response()->json($product);
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
