<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Exception;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            // redis cahing 
            return response()->json($categories);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to get categories'], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $data_validation = $request->validate([
                "name" => "required|unique:categories|min:2",
                "description" => "required|min:4",
                "image" => "url",
            ]);
            $category = Category::create($data_validation);
            return response()->json($category);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 400);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to create category"], 500);
        }
    }
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            // caching redis $set viwes = +1
            // views += 1;
            return response()->json($category);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to get category"], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data_validation = $request->validate([ // $request->validation XXXXX
                "name" => "required",
                "description" => "required",
            ]);

            $category = Category::findOrFail($id);
            $category->update($data_validation);
            return response()->json($category);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 406);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to update category"], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'category deleted successfuly']);
        } catch (Exception $error) {
            return response()->json(['errors' => "Failed to delete category"], 500);
        }
    }

    public function get_products_by_category($id){
        try {
            $category = Category::findOrFail($id);
            return response()->json($category->products);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to get products by category"], 500);
        }
    }
}
