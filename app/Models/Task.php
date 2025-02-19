<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'category_id', 'priority', 'deadline', 'is_completed', 'status'];


    protected $casts = [
        'deadline' => 'datetime', // Pastikan kolom deadline menjadi objek Carbon
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
