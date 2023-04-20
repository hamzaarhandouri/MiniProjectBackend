<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Invitation;
use App\Mail\mailValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    
/*
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
*/
    public function index () {
        return response()->json([
         "data" => User::with('company')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company_id' => $user->company_id,
                'company_name' => $user->company ? $user->company->name : null,
                'invitation' => Invitation::find($user->invitation_id),
                'type'=>$user->type ,

            ];
        })
    ]);
    }

    public function getuser($id) {
        return response()->json(['user' => User::find($id)]);
    }
   public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        
        $token = auth::attempt($credentials);
       // dd($token);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function registerAdmin(Request $request){

        if($request->role == "administrateur"){
            $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);
     

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type'=> $request->role ,
        ]);
        }else if($request->role == "normal"){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'companies_id' => 'required'
         //   'password' => 'required|string|min:6',
      //      'adress' => 'required|string',
        //    'phoneNumber' => 'required|string',
          //  'dateBirth' => 'required|date',
        ]);
     

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'type'=> $request->role ,

            'company_id' => $request->companies_id , 
        ]);
    }
      //  Mail::to($user->email)->send(new mailValidation($user));
        return response()->json([
            'status' => 'success',
            'data' => $user , 
            'message' => 'User created successfully',
           
        ]);
    }

    public function registerNormal(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
           
        ]);

    }

    public function test($ancien , $new , $id){

    }
    public function updatedUser($id, Request $request) {
        return response()->json(["address" => $request->adress , "Birthdate" => $request->birthDate]);
        $user = User::find($id); // Find the user by their ID
        
    
        if(!$user) {
            return response()->json(['error' => 'User not found'], 404); // If the user is not found, return an error response
        }
    
        // Update the user's information with the data from the request
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->birthDate = $request->birthDate;
        $user->phoneNumber = $request->phone ;
        $user->adress = $request->address;
        $user->email_verified_at = new DateTime();

        $user->save();
        return response()->json(["message"=> "Success" , "user"=> $user]);
        }

    public function updateUser (Request $request ,$id){
     //dd($request->name);
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255',
        //     'previous_password' => [
        //         'required',
              
        //     ],
        //     'password' => 'required|string|min:6',
        //     'adress' => 'required|string',
        //     'phone' => 'required|string',
        // //    'dateBirth' => 'required|date',
        // ]);
        $user = User::findOrFail($id);
        // if (Hash::check($request->previous_password , $user->password)) {
        //     return response()->json(["message"=> "Invalid previous password"]);
        // }
        // $user->update([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'adress' => $request->address,
        //     'phoneNumber' => $request->phone,
        //     'birthDate' => $request->birthDate,
        // ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->birthDate = $request->birthday;
        $user->phoneNumber = $request->phone ;
        $user->adress = $request->adress;
        $user->email_verified_at = new DateTime();
        $user->save();
        return response()->json(["message"=> "Success" , "user"=> $user]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function usersCompany($id) {
        $users = User::whereHas('company', function ($query) use ($id) {
            $query->where('id', $id);
        })->get();
        return response()->json(["data" => $users]);
    }
}
