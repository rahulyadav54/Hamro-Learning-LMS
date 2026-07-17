@extends('backend.master')

@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Upload Material</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('instructor.materials.index') }}">My Materials</a>
                <a href="#">Upload</a>
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
                    <div class="p-25" style="background: linear-gradient(135deg, #6a4fdb 0%, #a855f7 100%);">
                        <div class="d-flex align-items-center">
                            <div style="background:rgba(255,255,255,0.2); width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-right:16px;">
                                <i class="ti-cloud-up" style="font-size:24px; color:#fff;"></i>
                            </div>
                            <div>
                                <h3 style="color:#fff; margin:0; font-size:20px; font-weight:600;">Upload New Material</h3>
                                <p style="color:rgba(255,255,255,0.8); margin:0; font-size:13px;">Share PDFs, documents, slides, videos or any learning material</p>
                            </div>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('instructor.materials.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
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
                                <input type="text" name="title" value="{{ old('title') }}"
                                       placeholder="e.g. Chapter 3 - Data Structures Notes"
                                       class="primary_input_field"
                                       style="width:100%; padding:12px 16px; border:1px solid #e0e0e0; border-radius:8px; font-size:14px; outline:none; transition:border-color 0.2s;"
                                       onfocus="this.style.borderColor='#6a4fdb'"
                                       onblur="this.style.borderColor='#e0e0e0'"
                                       required>
                            </div>

                            {{-- Description --}}
                            <div class="mb-20">
                                <label style="font-weight:600; color:#333; margin-bottom:8px; display:block;">
                                    Description <span style="color:#aaa; font-weight:400;">(optional)</span>
                                </label>
                                <textarea name="description" rows="3"
                                          placeholder="Brief description of this material..."
                                          style="width:100%; padding:12px 16px; border:1px solid #e0e0e0; border-radius:8px; font-size:14px; outline:none; resize:vertical; transition:border-color 0.2s;"
                                          onfocus="this.style.borderColor='#6a4fdb'"
                                          onblur="this.style.borderColor='#e0e0e0'">{{ old('description') }}</textarea>
                            </div>

                            {{-- File Drop Zone --}}
                            <div class="mb-20">
                                <label style="font-weight:600; color:#333; margin-bottom:8px; display:block;">
                                    File <span style="color:#e74c3c;">*</span>
                                </label>
                                <div id="dropZone"
                                     onclick="document.getElementById('fileInput').click()"
                                     style="border:2px dashed #c4b5fd; border-radius:12px; padding:40px 20px; text-align:center; cursor:pointer; background:#faf5ff; transition:all 0.2s;"
                                     ondragover="event.preventDefault(); this.style.background='#ede9fe'; this.style.borderColor='#6a4fdb';"
                                     ondragleave="this.style.background='#faf5ff'; this.style.borderColor='#c4b5fd';"
                                     ondrop="handleDrop(event)">
                                    <i class="ti-cloud-up" style="font-size:48px; color:#8b5cf6; display:block; margin-bottom:12px;"></i>
                                    <p style="color:#6a4fdb; font-weight:600; margin-bottom:4px; font-size:15px;">Click to browse or drag & drop</p>
                                    <p style="color:#aaa; font-size:12px; margin:0;">
                                        PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, ZIP, RAR, MP4, MP3, JPG, PNG<br>
                                        <strong>Max size: 50 MB</strong>
                                    </p>
                                    <div id="filePreview" class="mt-15" style="display:none;"></div>
                                </div>
                                <input type="file" id="fileInput" name="file" style="display:none;"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.mp4,.mp3,.jpg,.jpeg,.png"
                                       onchange="showFilePreview(this)">
                            </div>

                            {{-- Visibility --}}
                            <div class="mb-30">
                                <label style="font-weight:600; color:#333; margin-bottom:12px; display:block;">Visibility</label>
                                <div class="d-flex" style="gap:12px;">
                                    <label style="flex:1; cursor:pointer;">
                                        <input type="radio" name="visibility_choice" value="public" checked onchange="document.getElementById('isPublicHidden').value=1"
                                               style="display:none;" id="radioPublic">
                                        <div id="publicCard"
                                             style="border:2px solid #6a4fdb; border-radius:10px; padding:16px; text-align:center; background:#f5f3ff; transition:all 0.2s;"
                                             onclick="selectVisibility('public')">
                                            <i class="ti-world" style="font-size:24px; color:#6a4fdb; display:block; margin-bottom:6px;"></i>
                                            <p style="font-weight:600; color:#6a4fdb; margin:0; font-size:14px;">Public</p>
                                            <p style="color:#aaa; font-size:12px; margin:4px 0 0;">Visible to all students</p>
                                        </div>
                                    </label>
                                    <label style="flex:1; cursor:pointer;">
                                        <input type="radio" name="visibility_choice" value="private" onchange="document.getElementById('isPublicHidden').value=0"
                                               style="display:none;" id="radioPrivate">
                                        <div id="privateCard"
                                             style="border:2px solid #e0e0e0; border-radius:10px; padding:16px; text-align:center; background:#fff; transition:all 0.2s;"
                                             onclick="selectVisibility('private')">
                                            <i class="ti-lock" style="font-size:24px; color:#aaa; display:block; margin-bottom:6px;"></i>
                                            <p style="font-weight:600; color:#aaa; margin:0; font-size:14px;">Private</p>
                                            <p style="color:#aaa; font-size:12px; margin:4px 0 0;">Only accessible by link</p>
                                        </div>
                                    </label>
                                </div>
                                <input type="hidden" name="is_public" id="isPublicHidden" value="1">
                            </div>

                            {{-- Upload Progress --}}
                            <div id="uploadProgress" style="display:none; margin-bottom:20px;">
                                <p style="color:#6a4fdb; font-weight:600; margin-bottom:8px;">Uploading...</p>
                                <div style="background:#e0e0e0; border-radius:10px; height:8px; overflow:hidden;">
                                    <div id="progressBar" style="background: linear-gradient(90deg, #6a4fdb, #a855f7); height:100%; width:0%; transition:width 0.3s; border-radius:10px;"></div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('instructor.materials.index') }}"
                                   style="color:#aaa; text-decoration:none; font-size:14px;">
                                    <i class="ti-angle-left mr-4"></i> Back to Materials
                                </a>
                                <button type="submit" id="submitBtn"
                                        class="primary-btn fix-gr-bg"
                                        style="border-radius:25px; padding:10px 32px; font-size:15px; border:none; cursor:pointer;">
                                    <i class="ti-cloud-up mr-6"></i> Upload Material
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
function showFilePreview(input) {
    const file = input.files[0];
    if (!file) return;

    const preview = document.getElementById('filePreview');
    const size = (file.size / (1024 * 1024)).toFixed(2);

    preview.style.display = 'block';
    preview.innerHTML = `
        <div style="background:#fff; border:1px solid #e0e0e0; border-radius:8px; padding:12px 16px; display:inline-flex; align-items:center; gap:10px;">
            <i class="ti-file" style="font-size:20px; color:#6a4fdb;"></i>
            <div style="text-align:left;">
                <p style="margin:0; font-weight:600; color:#333; font-size:13px;">${file.name}</p>
                <p style="margin:0; color:#aaa; font-size:11px;">${size} MB</p>
            </div>
            <span style="background:#d1fae5; color:#065f46; padding:2px 8px; border-radius:10px; font-size:11px;">Ready</span>
        </div>
    `;
}

function handleDrop(event) {
    event.preventDefault();
    const dt = event.dataTransfer;
    const files = dt.files;
    const input = document.getElementById('fileInput');
    input.files = files;
    showFilePreview(input);
    document.getElementById('dropZone').style.background = '#faf5ff';
    document.getElementById('dropZone').style.borderColor = '#c4b5fd';
}

function selectVisibility(type) {
    const publicCard  = document.getElementById('publicCard');
    const privateCard = document.getElementById('privateCard');
    const hidden      = document.getElementById('isPublicHidden');

    if (type === 'public') {
        publicCard.style.border  = '2px solid #6a4fdb';
        publicCard.style.background = '#f5f3ff';
        publicCard.querySelector('i').style.color = '#6a4fdb';
        publicCard.querySelector('p').style.color = '#6a4fdb';
        privateCard.style.border = '2px solid #e0e0e0';
        privateCard.style.background = '#fff';
        privateCard.querySelector('i').style.color = '#aaa';
        privateCard.querySelector('p').style.color = '#aaa';
        hidden.value = 1;
    } else {
        privateCard.style.border = '2px solid #6a4fdb';
        privateCard.style.background = '#f5f3ff';
        privateCard.querySelector('i').style.color = '#6a4fdb';
        privateCard.querySelector('p').style.color = '#6a4fdb';
        publicCard.style.border  = '2px solid #e0e0e0';
        publicCard.style.background = '#fff';
        publicCard.querySelector('i').style.color = '#aaa';
        publicCard.querySelector('p').style.color = '#aaa';
        hidden.value = 0;
    }
}

// Show progress bar on submit
document.getElementById('uploadForm').addEventListener('submit', function() {
    const fileInput = document.getElementById('fileInput');
    if (!fileInput.files.length) return;

    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<i class="ti-loop mr-6"></i> Uploading...';

    let w = 0;
    const interval = setInterval(() => {
        w = Math.min(w + Math.random() * 15, 90);
        document.getElementById('progressBar').style.width = w + '%';
        if (w >= 90) clearInterval(interval);
    }, 300);
});
</script>

@endsection
