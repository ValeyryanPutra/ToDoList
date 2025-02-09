<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{

    protected $fillable = ['name', 'user_id', 'is_default', 'is_default_for_users'];

    public function tasks()
    {   
        return $this->hasMany(Task::class);
    }
}
