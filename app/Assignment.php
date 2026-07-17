<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Course;

class Assignment extends Model
{
    protected $table = 'assignments';

    protected $fillable = [
        'instructor_id',
        'course_id',
        'title',
        'description',
        'file_path',
        'max_marks',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id');
    }
}
