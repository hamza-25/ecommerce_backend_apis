<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    function index(){
        $addresses = Address::all();
        return response()->json($addresses);
    }

    function store(Request $request){
        $address = Address::create($request->all());
        return response()->json($address);
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
