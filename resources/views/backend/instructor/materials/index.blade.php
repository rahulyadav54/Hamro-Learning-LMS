@extends('backend.master')

@section('mainContent')

{{-- Breadcrumb --}}
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>My Materials</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="#">My Materials</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">

        {{-- Stats Row --}}
        <div class="row mb-20">
            <div class="col-xl-3 col-md-6 mb-20">
                <div class="white-box p-20" style="border-radius:12px; border-left:4px solid #6a4fdb;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p style="color:#aaa; font-size:12px; margin-bottom:4px; text-transform:uppercase;">Total Materials</p>
                            <h3 style="color:#6a4fdb; font-size:28px; margin:0; font-weight:700;">{{ $materials->total() }}</h3>
                        </div>
                        <div style="background:rgba(106,79,219,0.1); width:50px; height:50px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                            <i class="ti-files" style="font-size:22px; color:#6a4fdb;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-20">
                <div class="white-box p-20" style="border-radius:12px; border-left:4px solid #22c55e;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p style="color:#aaa; font-size:12px; margin-bottom:4px; text-transform:uppercase;">Public Files</p>
                            <h3 style="color:#22c55e; font-size:28px; margin:0; font-weight:700;">{{ $materials->where('is_public', 1)->count() }}</h3>
                        </div>
                        <div style="background:rgba(34,197,94,0.1); width:50px; height:50px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                            <i class="ti-world" style="font-size:22px; color:#22c55e;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-20">
                <div class="white-box p-20" style="border-radius:12px; border-left:4px solid #f59e0b;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p style="color:#aaa; font-size:12px; margin-bottom:4px; text-transform:uppercase;">Total Downloads</p>
                            <h3 style="color:#f59e0b; font-size:28px; margin:0; font-weight:700;">{{ $materials->sum('download_count') }}</h3>
                        </div>
                        <div style="background:rgba(245,158,11,0.1); width:50px; height:50px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                            <i class="ti-download" style="font-size:22px; color:#f59e0b;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-20">
                <div class="white-box p-20 d-flex align-items-center justify-content-center" style="border-radius:12px; background: linear-gradient(135deg, #6a4fdb, #a855f7); cursor:pointer;"
                     onclick="window.location='{{ route('instructor.materials.create') }}'">
                    <div class="text-center">
                        <i class="ti-cloud-up" style="font-size:28px; color:#fff; display:block; margin-bottom:6px;"></i>
                        <span style="color:#fff; font-weight:600; font-size:15px;">Upload New Material</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Materials List --}}
        <div class="row">
            <div class="col-12">
                <div class="white-box" style="border-radius:12px; overflow:hidden;">
                    <div class="p-20 d-flex justify-content-between align-items-center" style="border-bottom:1px solid #f0f0f0;">
                        <h4 style="margin:0; font-size:16px; font-weight:600; color:#333;">All Materials</h4>
                        <a href="{{ route('instructor.materials.create') }}"
                           class="primary-btn fix-gr-bg"
                           style="border-radius:25px; padding:8px 20px; font-size:13px;">
                            <i class="ti-cloud-up mr-5"></i> Upload Material
                        </a>
                    </div>

                    @if($materials->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:14px;">
                            <thead style="background:#fafafa;">
                                <tr>
                                    <th style="padding:14px 20px; color:#666; font-weight:600; border:none; width:50px;">#</th>
                                    <th style="padding:14px 20px; color:#666; font-weight:600; border:none;">File</th>
                                    <th style="padding:14px 20px; color:#666; font-weight:600; border:none;">Title</th>
                                    <th style="padding:14px 20px; color:#666; font-weight:600; border:none;">Size</th>
                                    <th style="padding:14px 20px; color:#666; font-weight:600; border:none;">Visibility</th>
                                    <th style="padding:14px 20px; color:#666; font-weight:600; border:none;">Downloads</th>
                                    <th style="padding:14px 20px; color:#666; font-weight:600; border:none;">Status</th>
                                    <th style="padding:14px 20px; color:#666; font-weight:600; border:none; text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materials as $key => $material)
                                @php
                                    $icon = $material->file_icon;
                                @endphp
                                <tr style="border-top:1px solid #f0f0f0;">
                                    <td style="padding:14px 20px; border:none; color:#aaa;">{{ $materials->firstItem() + $key }}</td>
                                    <td style="padding:14px 20px; border:none;">
                                        <div style="width:42px; height:42px; background:{{ $icon[1] }}1a; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                                            <i class="{{ $icon[0] }}" style="font-size:20px; color:{{ $icon[1] }};"></i>
                                        </div>
                                    </td>
                                    <td style="padding:14px 20px; border:none;">
                                        <p style="margin:0; font-weight:600; color:#333;">{{ $material->title }}</p>
                                        <small style="color:#aaa;">{{ $material->file_name }}</small>
                                        @if($material->description)
                                        <p style="margin:4px 0 0; color:#888; font-size:12px;">{{ Str::limit($material->description, 60) }}</p>
                                        @endif
                                    </td>
                                    <td style="padding:14px 20px; border:none; color:#666;">{{ $material->file_size_human }}</td>
                                    <td style="padding:14px 20px; border:none;">
                                        @if($material->is_public)
                                            <span style="background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500;">
                                                <i class="ti-world mr-2"></i> Public
                                            </span>
                                        @else
                                            <span style="background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500;">
                                                <i class="ti-lock mr-2"></i> Private
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 20px; border:none;">
                                        <span style="background:#ede9fe; color:#6a4fdb; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                                            {{ $material->download_count }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 20px; border:none;">
                                        <a href="{{ route('instructor.materials.toggleStatus', $material->id) }}"
                                           title="{{ $material->status ? 'Click to disable' : 'Click to enable' }}">
                                            @if($material->status)
                                                <span style="background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500;">Active</span>
                                            @else
                                                <span style="background:#f3f4f6; color:#999; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500;">Inactive</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td style="padding:14px 20px; border:none; text-align:right; white-space:nowrap;">
                                        <a href="{{ route('instructor.materials.download', $material->id) }}"
                                           title="Download"
                                           style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#ede9fe; color:#6a4fdb; margin-right:4px; text-decoration:none;">
                                            <i class="ti-download" style="font-size:13px;"></i>
                                        </a>
                                        <a href="{{ route('instructor.materials.edit', $material->id) }}"
                                           title="Edit"
                                           style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#dbeafe; color:#2563eb; margin-right:4px; text-decoration:none;">
                                            <i class="ti-pencil" style="font-size:13px;"></i>
                                        </a>
                                        <a href="{{ route('instructor.materials.delete', $material->id) }}"
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this material?')"
                                           style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#fee2e2; color:#dc2626; text-decoration:none;">
                                            <i class="ti-trash" style="font-size:13px;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($materials->hasPages())
                    <div class="p-20" style="border-top:1px solid #f0f0f0;">
                        {{ $materials->links() }}
                    </div>
                    @endif

                    @else
                    <div class="text-center p-60">
                        <div style="width:80px; height:80px; border-radius:50%; background:#f3f0ff; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                            <i class="ti-files" style="font-size:36px; color:#6a4fdb;"></i>
                        </div>
                        <h4 style="color:#333; margin-bottom:8px;">No materials yet</h4>
                        <p style="color:#aaa; margin-bottom:20px;">Upload your first PDF, document, or any learning material for your students.</p>
                        <a href="{{ route('instructor.materials.create') }}"
                           class="primary-btn fix-gr-bg"
                           style="border-radius:25px; padding:10px 28px;">
                            <i class="ti-cloud-up mr-5"></i> Upload First Material
                        </a>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</section>

@endsection
