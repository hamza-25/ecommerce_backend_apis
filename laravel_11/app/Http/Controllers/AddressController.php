<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Exception;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    function index()
    {
       try{
        $addresses = Address::all();
        return response()->json($addresses);
       }catch (Exception $e) {
        return response()->json(["error" => "Failed to get addresses"], 500);
    }
    }

    function store(Request $request)
    {
        try {
            $data_validation = $request->validate([
                "full_name" => "required",
                "street" => "required",
                "city" => "required",
                "province" => "required",
                "country" => "required",
                "phone" => "required",
                "zip_code" => "required",
                "user_id" => "required",
            ]);
            $address = Address::create($data_validation);
            return response()->json($address, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 406);
        }catch (Exception $e) {
            return response()->json(["error" => "Failed to show address"], 500);
        }
    }

    public function show($id)
    {
        try {
            $address = Address::findOrFail($id);
            return response()->json($address);
        } catch (Exception $e) {
            return response()->json(["error" => "Failed to show address"], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data_validation = $request->validate([
                "full_name" => "required",
                "street" => "required",
                "city" => "required",
                "province" => "required",
                "country" => "required",
                "phone" => "required",
                "zip_code" => "required",
                "user_id" => "required",
            ]);
            $address = Address::findOrFail($id);
            $address->update($data_validation);
            return response()->json($address);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 406);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to update address"], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $address = Address::findOrFail($id);
            $address->delete();
            return response()->json(['message' => 'address deleted succesfully']);
        } catch (Exception $e) {
            return response()->json(['errors' => "Failed to delete address"], 500);
        }
    }
}
