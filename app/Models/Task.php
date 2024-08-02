<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = ['name', 'content', 'project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getContentAttribute($value)
    {
        return Str::limit($value, 100);
    }
    // public function getNameAttribute($value)
    // {
    //     return Str::limit($value, 20);
    // }
}
