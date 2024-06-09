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
        try{
            $categories = Category::all();
            return response()->json($categories);
        }
        catch(Exception $e){
            return response()->json(['error' => 'something goes wrong'], 400);
        }
    }

    public function store(Request $request)
    {
        try{
            $data_validation = $request->validate([
                "name" => "required|unique:categories|min:2",
                "description" => "required|min:4",
                "image" => "url",
            ]);
            $category = Category::create($data_validation);
            return response()->json($category);
        } catch (ValidationException $e){
            return response()->json($e->errors() ,400);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to create category"], 500);
        }
    }
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
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
}
