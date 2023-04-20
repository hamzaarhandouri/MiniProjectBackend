<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invitation;
use App\Models\InvtationHistory;
use App\Mail\mailValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    public function CreateInvitation(Request $request ) {
        if($request->status === "SendInvitation") {
            $invitation = Invitation::create([
                "status" => $request->status,
                "userSender_id" => $request->userSender_id ,
                "userRece_id" => $request->userRece_id , 
                "company_id" => $request->company_id
            ]);
            $userSender = User::find($request->userSender_id);
            $userRecep = User::find($request->userRece_id);
            $userRecep->invitation_id = $invitation->id ;
            $userRecep->save();
            $history =  InvtationHistory::create([  
            "status" => $request->status,
            "userSender_id" => $request->userSender_id ,
            "userRece_id" => $request->userRece_id , 
            "company_id" => $request->company_id , 
            "invitation_id" => $invitation->id,
        ]);
          Mail::to($userRecep->email)->send(new mailValidation($userRecep , $userRecep , $userSender , $invitation));
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
           })]);
        }
    }


    public function checkInvitation ($id) 
    {
        $invitation = Invitation::find($id);
        if ($invitation === null || $invitation->status === "ValidationInvitation" || $invitation->status === "cancelled"|| $invitation->status === "ProfileComfiramtion" ){
           return response()->json(['status' => 'expired']);
        } else if ($invitation->status === "SendInvitation" ){
           return  response()->json(['status'=>'success']);
        }
    }
    public function modifyInvitation($id) {
        $invitation = Invitation::find($id);
        if ($invitation === null ){
           return response()->json(['status' => 'expired']);
        } else if ($invitation->status === "SendInvitation" ){
            $invitation->status = 'ValidationInvitation';
            $invitation->update();
            $history =  InvtationHistory::create([  
                "status" => "ValidationInvitation",
                "userSender_id" => $invitation->userSender_id ,
                "userRece_id" => $invitation->userRece_id , 
                "company_id" => $invitation->company_id , 
                "invitation_id" => $id,
            ]);
           return  response()->json(['status'=>'success' , "invitation" => $invitation]);
        }
    }

    public function cancelinvitation(Request $request) {
        $invitation = Invitation::find($request->id);
        $invitation->status = 'cancelled';
        $invitation->save();
        $history =  InvtationHistory::create([  
            "status" => "cancelled",
            "userSender_id" => $invitation->userSender_id ,
            "userRece_id" => $invitation->userRece_id , 
            "company_id" => $invitation->company_id , 
            "invitation_id" => $request->id,
        ]);
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
           })]);

    }


    public function getHistory(){

       


        return response()->json([
            "invitations" => InvtationHistory::with( 'userSender'  ,'company' ,'userRece')->get()->map(function($history){
                return [
                    'id' => $history->id ,
                    'status' => $history->status,
                    'company' => $history->company->name ,
                    'userSender' => $history->userSender->name ,
                    'userRece' => $history->userRece->name ,
                    'created_at' => $history->created_at 
                ];
            })
          ]);

    }
}
