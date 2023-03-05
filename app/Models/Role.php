<?php

namespace App\Models;

use App\Models\User;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, UuidTrait;

    public function users() {

        return $this->belongsToMany(User::class,'users_roles');
            
     }
}
