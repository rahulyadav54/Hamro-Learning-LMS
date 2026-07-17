<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorTodo extends Model
{
    protected $table = 'instructor_todo';

    protected $fillable = [
        'user_id',
        'title',
        'is_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
