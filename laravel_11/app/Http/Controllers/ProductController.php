<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Exception;
use Illuminate\Validation\ValidationException;


class ProductController extends Controller
{
    function index()
    {
        try {
            $products = Product::all();
            return response()->json($products);
        } catch (Exception $e) {
            return response()->json(["error" => "Failed to get products"], 500);
        }
    }

    function store(Request $request)
    {
        try {
            $data_validation = $request->validate([
                "title" => "required|min:5",
                "description" => "required|min:5",
                "price" => "required|numeric|min:0",
                "image" => "url",
                "category_id" => "required|numeric",
            ]);
            $category = Category::findOrFail($data_validation['category_id']);
            $product = Product::create([
                "title" => $data_validation['title'],
                "description" => $data_validation['description'],
                "price" => $data_validation['price'],
                "image" => $data_validation['image'],
                "category_id" => $data_validation['category_id'],
                "category_name" => $category->name
            ]);
            return response()->json($product, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 406);
        } catch (Exception $e) {
            return response()->json(["error" => "Failed to store product"], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json(["error" => "Failed to show product"], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data_validation = $request->validate([
                "title" => "required|min:5",
                "description" => "required|min:5",
                "price" => "required|numeric|min:0",
                "image" => "url",
                "category_id" => "required",
            ]);
            $product = Product::findOrFail($id);
            $product->update($data_validation);
            return response()->json($product);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 406);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to update product"], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'product deleted succesfully']);
        } catch (Exception $e) {
            return response()->json(['errors' => "Failed to delete product"], 500);
        }
    }
}
