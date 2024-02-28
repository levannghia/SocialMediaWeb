<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    
    protected $fillable = [
        'object_id',
        'type_id',
        'type',
        'user_id',
    ];

    public function object()
    {
        return $this->morphTo();
    }
}
