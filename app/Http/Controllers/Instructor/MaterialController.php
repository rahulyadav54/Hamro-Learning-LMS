<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\InstructorMaterial;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    /**
     * Allowed file extensions for upload.
     */
    private $allowedExtensions = [
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        'txt', 'zip', 'rar', 'mp4', 'mp3', 'jpg', 'jpeg', 'png',
    ];

    /**
     * Max file size: 50 MB
     */
    private $maxSize = 51200;

    /**
     * List all materials for the logged-in instructor.
     */
    public function index()
    {
        $materials = InstructorMaterial::where('instructor_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('backend.instructor.materials.index', compact('materials'));
    }

    /**
     * Show upload form.
     */
    public function create()
    {
        return view('backend.instructor.materials.create');
    }

    /**
     * Store uploaded material.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file'        => 'required|file|max:' . $this->maxSize . '|mimes:' . implode(',', $this->allowedExtensions),
            'is_public'   => 'nullable|boolean',
        ]);

        try {
            $file     = $request->file('file');
            $origName = $file->getClientOriginalName();
            $ext      = strtolower($file->getClientOriginalExtension());
            $mime     = $file->getMimeType();
            $size     = $file->getSize();

            // Generate unique filename
            $fileName = md5(Auth::id() . time() . $origName) . '.' . $ext;
            $uploadDir = 'public/uploads/materials/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $file->move($uploadDir, $fileName);
            $filePath = $uploadDir . $fileName;

            InstructorMaterial::create([
                'instructor_id' => Auth::id(),
                'title'         => $request->title,
                'description'   => $request->description,
                'file_path'     => $filePath,
                'file_name'     => $origName,
                'file_type'     => $mime,
                'file_size'     => $size,
                'is_public'     => $request->has('is_public') ? 1 : 0,
                'status'        => 1,
            ]);

            Toastr::success('Material uploaded successfully!', 'Success');
            return redirect()->route('instructor.materials.index');
        } catch (\Exception $e) {
            Toastr::error('Upload failed: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $material = InstructorMaterial::where('id', $id)
            ->where('instructor_id', Auth::id())
            ->firstOrFail();

        return view('backend.instructor.materials.edit', compact('material'));
    }

    /**
     * Update material details (title, description, visibility).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public'   => 'nullable|boolean',
        ]);

        try {
            $material = InstructorMaterial::where('id', $id)
                ->where('instructor_id', Auth::id())
                ->firstOrFail();

            // Optionally replace file
            if ($request->hasFile('file') && $request->file('file') instanceof \Illuminate\Http\UploadedFile) {
                $request->validate([
                    'file' => 'file|max:' . $this->maxSize . '|mimes:' . implode(',', $this->allowedExtensions),
                ]);

                // Delete old file
                if (file_exists($material->file_path)) {
                    unlink($material->file_path);
                }

                $file     = $request->file('file');
                $origName = $file->getClientOriginalName();
                $ext      = strtolower($file->getClientOriginalExtension());
                $mime     = $file->getMimeType();
                $size     = $file->getSize();
                $fileName = md5(Auth::id() . time() . $origName) . '.' . $ext;
                $uploadDir = 'public/uploads/materials/';
                $file->move($uploadDir, $fileName);

                $material->file_path = $uploadDir . $fileName;
                $material->file_name = $origName;
                $material->file_type = $mime;
                $material->file_size = $size;
            }

            $material->title       = $request->title;
            $material->description = $request->description;
            $material->is_public   = $request->has('is_public') ? 1 : 0;
            $material->save();

            Toastr::success('Material updated successfully!', 'Success');
            return redirect()->route('instructor.materials.index');
        } catch (\Exception $e) {
            Toastr::error('Update failed: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a material.
     */
    public function destroy($id)
    {
        try {
            $material = InstructorMaterial::where('id', $id)
                ->where('instructor_id', Auth::id())
                ->firstOrFail();

            if (file_exists($material->file_path)) {
                unlink($material->file_path);
            }

            $material->delete();

            Toastr::success('Material deleted successfully!', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Delete failed.', 'Error');
        }

        return redirect()->route('instructor.materials.index');
    }

    /**
     * Download a material (admin & any user).
     */
    public function download($id)
    {
        try {
            $material = InstructorMaterial::where('id', $id)->firstOrFail();

            // Only allow download if public or own material
            if (!$material->is_public && $material->instructor_id !== Auth::id()) {
                Toastr::error('You do not have access to this file.', 'Error');
                return redirect()->back();
            }

            if (!file_exists($material->file_path)) {
                Toastr::error('File not found on server.', 'Error');
                return redirect()->back();
            }

            $material->increment('download_count');

            return response()->download(
                public_path('../' . ltrim($material->file_path, '/')),
                $material->file_name
            );
        } catch (\Exception $e) {
            Toastr::error('Download failed.', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Toggle status (active/inactive).
     */
    public function toggleStatus($id)
    {
        try {
            $material = InstructorMaterial::where('id', $id)
                ->where('instructor_id', Auth::id())
                ->firstOrFail();

            $material->status = $material->status ? 0 : 1;
            $material->save();

            Toastr::success('Status updated!', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to update status.', 'Error');
        }

        return redirect()->back();
    }
}
