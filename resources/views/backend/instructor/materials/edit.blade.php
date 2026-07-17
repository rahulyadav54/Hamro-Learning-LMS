@extends('backend.master')

@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Edit Material</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('instructor.materials.index') }}">My Materials</a>
                <a href="#">Edit</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-12">
                <div class="white-box" style="border-radius:14px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <div class="p-25" style="background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);">
                        <div class="d-flex align-items-center">
                            <div style="background:rgba(255,255,255,0.2); width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-right:16px;">
                                <i class="ti-pencil" style="font-size:22px; color:#fff;"></i>
                            </div>
                            <div>
                                <h3 style="color:#fff; margin:0; font-size:20px; font-weight:600;">Edit Material</h3>
                                <p style="color:rgba(255,255,255,0.8); margin:0; font-size:13px;">Update title, description, or replace the file</p>
                            </div>
                        </div>
                    </div>

                    {{-- Current File Info --}}
                    <div class="p-20" style="background:#f8f8f8; border-bottom:1px solid #eee;">
                        @php $icon = $material->file_icon; @endphp
                        <div class="d-flex align-items-center">
                            <div style="width:48px; height:48px; background:{{ $icon[1] }}1a; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-right:14px; flex-shrink:0;">
                                <i class="{{ $icon[0] }}" style="font-size:22px; color:{{ $icon[1] }};"></i>
                            </div>
                            <div>
                                <p style="margin:0; font-weight:600; color:#333; font-size:14px;">{{ $material->file_name }}</p>
                                <p style="margin:0; color:#aaa; font-size:12px;">Current file &bull; {{ $material->file_size_human }} &bull; {{ $material->download_count }} downloads</p>
                            </div>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('instructor.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="p-30">

                            @if($errors->any())
                            <div class="alert alert-danger mb-20" style="border-radius:8px;">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            {{-- Title --}}
                            <div class="mb-20">
                                <label style="font-weight:600; color:#333; margin-bottom:8px; display:block;">
                                    Material Title <span style="color:#e74c3c;">*</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title', $material->title) }}"
                                       class="primary_input_field"
                                       style="width:100%; padding:12px 16px; border:1px solid #e0e0e0; border-radius:8px; font-size:14px; outline:none;"
                                       onfocus="this.style.borderColor='#6a4fdb'"
                                       onblur="this.style.borderColor='#e0e0e0'"
                                       required>
                            </div>

                            {{-- Description --}}
                            <div class="mb-20">
                                <label style="font-weight:600; color:#333; margin-bottom:8px; display:block;">Description</label>
                                <textarea name="description" rows="3"
                                          style="width:100%; padding:12px 16px; border:1px solid #e0e0e0; border-radius:8px; font-size:14px; outline:none; resize:vertical;"
                                          onfocus="this.style.borderColor='#6a4fdb'"
                                          onblur="this.style.borderColor='#e0e0e0'">{{ old('description', $material->description) }}</textarea>
                            </div>

                            {{-- Replace File --}}
                            <div class="mb-20">
                                <label style="font-weight:600; color:#333; margin-bottom:8px; display:block;">
                                    Replace File <span style="color:#aaa; font-weight:400;">(optional — leave empty to keep current)</span>
                                </label>
                                <div style="border:2px dashed #e0e0e0; border-radius:10px; padding:24px; text-align:center; cursor:pointer; background:#fafafa;"
                                     onclick="document.getElementById('editFileInput').click()">
                                    <i class="ti-cloud-up" style="font-size:32px; color:#aaa; display:block; margin-bottom:8px;"></i>
                                    <p style="color:#aaa; margin:0; font-size:13px;">Click to select replacement file (Max 50MB)</p>
                                    <div id="editFilePreview" class="mt-10" style="display:none;"></div>
                                </div>
                                <input type="file" id="editFileInput" name="file" style="display:none;"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.mp4,.mp3,.jpg,.jpeg,.png"
                                       onchange="showEditPreview(this)">
                            </div>

                            {{-- Visibility --}}
                            <div class="mb-30">
                                <label style="font-weight:600; color:#333; margin-bottom:12px; display:block;">Visibility</label>
                                <div class="d-flex" style="gap:12px;">
                                    <label style="flex:1; cursor:pointer;" onclick="document.getElementById('isPublicHidden').value=1; setActiveCard('publicEditCard','privateEditCard')">
                                        <div id="publicEditCard"
                                             style="border:2px solid {{ $material->is_public ? '#6a4fdb' : '#e0e0e0' }}; border-radius:10px; padding:14px; text-align:center; background:{{ $material->is_public ? '#f5f3ff' : '#fff' }}; transition:all 0.2s;">
                                            <i class="ti-world" style="font-size:22px; color:{{ $material->is_public ? '#6a4fdb' : '#aaa' }}; display:block; margin-bottom:6px;"></i>
                                            <p style="font-weight:600; color:{{ $material->is_public ? '#6a4fdb' : '#aaa' }}; margin:0; font-size:14px;">Public</p>
                                        </div>
                                    </label>
                                    <label style="flex:1; cursor:pointer;" onclick="document.getElementById('isPublicHidden').value=0; setActiveCard('privateEditCard','publicEditCard')">
                                        <div id="privateEditCard"
                                             style="border:2px solid {{ !$material->is_public ? '#6a4fdb' : '#e0e0e0' }}; border-radius:10px; padding:14px; text-align:center; background:{{ !$material->is_public ? '#f5f3ff' : '#fff' }}; transition:all 0.2s;">
                                            <i class="ti-lock" style="font-size:22px; color:{{ !$material->is_public ? '#6a4fdb' : '#aaa' }}; display:block; margin-bottom:6px;"></i>
                                            <p style="font-weight:600; color:{{ !$material->is_public ? '#6a4fdb' : '#aaa' }}; margin:0; font-size:14px;">Private</p>
                                        </div>
                                    </label>
                                </div>
                                <input type="hidden" name="is_public" id="isPublicHidden" value="{{ $material->is_public }}">
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('instructor.materials.index') }}"
                                   style="color:#aaa; text-decoration:none; font-size:14px;">
                                    <i class="ti-angle-left mr-4"></i> Back to Materials
                                </a>
                                <button type="submit"
                                        class="primary-btn fix-gr-bg"
                                        style="border-radius:25px; padding:10px 32px; font-size:15px; border:none; cursor:pointer;">
                                    <i class="ti-save mr-6"></i> Save Changes
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
function showEditPreview(input) {
    const file = input.files[0];
    if (!file) return;
    const preview = document.getElementById('editFilePreview');
    const size = (file.size / (1024 * 1024)).toFixed(2);
    preview.style.display = 'block';
    preview.innerHTML = `<span style="background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:20px; font-size:12px;">✓ ${file.name} (${size} MB)</span>`;
}

function setActiveCard(activeId, inactiveId) {
    const active   = document.getElementById(activeId);
    const inactive = document.getElementById(inactiveId);
    active.style.border = '2px solid #6a4fdb';
    active.style.background = '#f5f3ff';
    active.querySelector('i').style.color = '#6a4fdb';
    active.querySelector('p').style.color = '#6a4fdb';
    inactive.style.border = '2px solid #e0e0e0';
    inactive.style.background = '#fff';
    inactive.querySelector('i').style.color = '#aaa';
    inactive.querySelector('p').style.color = '#aaa';
}
</script>

@endsection
