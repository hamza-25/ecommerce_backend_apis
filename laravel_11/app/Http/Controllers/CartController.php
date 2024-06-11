<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Brick\Math\BigNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index(){
       try{
        $user = User::findOrFail(Auth::user()->id);
        $carts = $user->cart ? $user->cart : [];
        $products = [];
        foreach($carts as $cart){
            $products [] = Product::findOrFail($cart); 
        }
        return response()->json($products, 200);
       }catch(\Exception $e){
        return response()->json(["error"=> 'Failed to get cart item']);
       }
    }

    public function store(Request $request){
        try{
            $data_validation = $request->validate([
                "product_id" => "required|exists:products,id",
            ]);
            $user = User::findOrFail(Auth::user()->id);
            $cart = $user->cart ? $user->cart : [];
            if (in_array($request->product_id, $cart)) {
                return response()->json(["message"=> "item Already Exists"]);
            }
            array_push($cart, (int)$request->product_id);
            $user->cart = $cart; 
            $user->save();
            return response()->json(["message" => "item added to cart"], 201);
           }catch(ValidationException $e){
            return response()->json($e->errors());
           }
           catch(\Exception $e){
            // return response()->json(["error"=> $e->getMessage()]);
            return response()->json(["error"=> 'Failed to get store cart item']);
           }
    }

    public function destroy($id){
        try{
            $user = User::findOrFail(Auth::user()->id);
            $cart = $user->cart ? $user->cart : [];
            if (!(in_array($id, $cart))) {
                return response()->json(["message"=> "item doesnt Exists on Carts"], 406);
            }
            $index = null;
            for( $i = 0; $i < count($cart); $i++ ){
                if ($cart[$i] == $id){
                    $index = $i;
                    break;
                }
            }
            unset($cart[$index]);
            $user->cart = $cart;
            $user->save();
            return response()->json(["message" => "item deleted from cart"], 200);
           }catch(ValidationException $e){
            return response()->json($e->errors());
           }
           catch(\Exception $e){
            // return response()->json(["error"=> $e->getMessage()]);
            return response()->json(["error"=> 'Failed to delete item from cart']);
           }
    }

    public function clear(){
        try{
            
            $user = User::findOrFail(Auth::user()->id);
            $user->cart = null;
            $user->save();
            return response()->json(["message" => "cart is clear"], 200);
           }catch(ValidationException $e){
            return response()->json($e->errors());
           }
           catch(\Exception $e){
            // return response()->json(["error"=> $e->getMessage()]);
            return response()->json(["error"=> 'Failed to clear cart']);
           }
    }
}
