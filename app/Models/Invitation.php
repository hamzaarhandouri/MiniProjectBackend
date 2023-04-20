<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        "status" ,
        "userSender_id",
        "userRece_id" ,
        "company_id" ,
    ];
    public function userSender(){
        return $this->belongsTo(User::class);
    }
    public function userRece(){
        return $this->belongsTo(User::class);
    }
}
