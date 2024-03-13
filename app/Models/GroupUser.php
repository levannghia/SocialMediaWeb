<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'status',
        'role',
        'user_id',
        'group_id',
        'created_by',
        'token',
        'token_expire_date',
    ];

    public function adminUser(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function groups(){
        return $this->belongsTo(Group::class,'group_id');
    }
}
