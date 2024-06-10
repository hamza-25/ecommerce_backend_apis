<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Exception;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        try {
            $carts = Cart::with(['user', 'product'])->get();
            return response()->json($carts);
        } catch (Exception $e) {
            return response()->json(["error" => "Failed to get cart items"], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data_validation = $request->validate([
                "user_id" => "required|exists:users,id",
                "product_id" => "required|exists:products,id",
                "quantity" => "required|integer|min:1"
            ]);
            $cart = Cart::create($data_validation);
            return response()->json($cart, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 406);
        } catch (Exception $e) {
            return response()->json(["error" => "Failed to add product to cart"], 500);
        }
    }

    public function show($id)
    {
        try {
            $cart = Cart::with(['user', 'product'])->findOrFail($id);
            return response()->json($cart);
        } catch (Exception $e) {
            return response()->json(["error" => "Failed to show cart item"], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data_validation = $request->validate([
                "user_id" => "required|exists:users,id",
                "product_id" => "required|exists:products,id",
                "quantity" => "required|integer|min:1"
            ]);
            $cart = Cart::findOrFail($id);
            $cart->update($data_validation);
            return response()->json($cart);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 406);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to update cart item"], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $cart->delete();
            return response()->json(['message' => 'Cart item deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['errors' => "Failed to delete cart item"], 500);
        }
    }
}
