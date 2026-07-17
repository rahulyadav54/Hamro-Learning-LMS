<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorMaterial extends Model
{
    protected $table = 'instructor_materials';

    protected $fillable = [
        'instructor_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_public',
        'download_count',
        'status',
    ];

    /**
     * Get the instructor who owns this material.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Return human-readable file size.
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    /**
     * Return icon class based on file type.
     */
    public function getFileIconAttribute()
    {
        $ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
        $map = [
            'pdf'  => ['ti-file', '#e74c3c'],
            'doc'  => ['ti-layout-media-left', '#2980b9'],
            'docx' => ['ti-layout-media-left', '#2980b9'],
            'xls'  => ['ti-layout-grid2', '#27ae60'],
            'xlsx' => ['ti-layout-grid2', '#27ae60'],
            'ppt'  => ['ti-layout-media-center', '#e67e22'],
            'pptx' => ['ti-layout-media-center', '#e67e22'],
            'zip'  => ['ti-package', '#8e44ad'],
            'rar'  => ['ti-package', '#8e44ad'],
            'mp4'  => ['ti-video-clapper', '#e74c3c'],
            'mp3'  => ['ti-music', '#16a085'],
            'jpg'  => ['ti-image', '#f39c12'],
            'jpeg' => ['ti-image', '#f39c12'],
            'png'  => ['ti-image', '#f39c12'],
        ];
        return $map[$ext] ?? ['ti-files', '#7f8c8d'];
    }
}
