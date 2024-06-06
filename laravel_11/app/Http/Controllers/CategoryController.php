<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
// use Dotenv\Exception\ValidationException; // works but interpreter got error
use Illuminate\Validation\ValidationException; // use that
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category);
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
            return response()->json(['errors' => $e->errors()], 406);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to category product"], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json('The category was deleted');
        } catch (Exception $error) {
            return response()->json(['message' => "We can't delete the category"], 500);
        }
    }
}
