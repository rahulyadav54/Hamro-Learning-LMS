<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Course;

class InstructorAnnouncement extends Model
{
    protected $table = 'instructor_announcements';

    protected $fillable = [
        'instructor_id',
        'course_id',
        'title',
        'content',
        'is_published',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
