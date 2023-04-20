<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index() {
        return response()->json(["data" => Company::all()]);
    }

    public function getCompany ($id) {
        return response()->json(["data" => Company::find($id)]) ;
    }

    public function createCompany (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
        ]);

        $company = Company::create([
            "name" => $request->name,
            "address" => $request->address,
            "city" => $request->city, 
        ]);
        return response()->json(["data" => $company]);
        }
    public function updateCompany (Request $request ,$id) {
        $company = Company::find($id);
        $company->name = $request->name;
        $company->address = $request->address;
        $company->city = $request->city;
        $company->save();
        return response()->json(["data" => Company::all() , "company" => $company]);
    }
    public function deleteCompany ($id) {

        $company = Company::find($id);
        $users =  $company->users()->count();
        if($users == 0){
        $company->delete(); 
        return response()->json(["data" => Company::all()]);
        }  else {
            return response()->json(["message"=> "Not possible to delete" , "status" => 403]);
            
        }
    }

    public function getCurrentcompany($id) {
        $user = User::find($id);
        return response()->json(["data" => $user->company]);
    }
}
