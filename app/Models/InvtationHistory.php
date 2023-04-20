<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvtationHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        "status" ,
        "userSender_id",
        "userRece_id" ,
        "company_id" ,
        "invitaion_id",
    ];
    public function userSender(){
        return $this->belongsTo(User::class , 'userSender_id', 'id');
    }
    public function userRece(){
        return $this->belongsTo(User::class , 'userRece_id', 'id');
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function invitation () {
        return $this->belongsTo(Invitatio::class);
    }
}
