<?php

namespace App\Models;

use App\Models\User;
use App\Models\Project;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'title',
        'status',
        'user_id',
        'project_id'
    ];
    
    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public function scopeWhereProject($q, $id){
        $q->where('project_id', $id);
    }
}
