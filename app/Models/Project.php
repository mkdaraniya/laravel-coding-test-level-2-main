<?php

namespace App\Models;

use App\Models\Task;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, UuidTrait;
    
    protected $fillable = [
        'name',
    ];
    
    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function scopeHasTasks($q){
        return $q->whereHas('tasks');
    }
}
