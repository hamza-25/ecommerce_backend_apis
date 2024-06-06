<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Dotenv\Exception\ValidationException;
use Exception;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request){
        $category = Category::create($request->all());
        return response()->json($category);
    }
    public function show($id){
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id){
        try {
            $data_validation = $request->validation([
                "name" => "required",
                "description" => "required",
            ]);
        
        $category = Category::findOrFail($id);
        $category ->update($data_validation);
        return response()->json($category);
        } catch (ValidationException $error){
            return response()->json(['errors' => $error()], 406);
        }
    }

    public function destroy($id){
        try{
        $category = Category::findOrFail($id);
        $category ->delete();
        return response()->json('The category was deleted');
    }catch (Exception $error){
        return response()->json(['message' => "We can't delete the category"],500);
    }
    }
}
