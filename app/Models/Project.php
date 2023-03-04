<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, UuidTrait, SoftDeletes;
    
    protected $fillable = [
        'title',
        'status',
    ];
    
    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function scopeHasTasks($q){
        return $q->whereHas('tasks');
    }
}
