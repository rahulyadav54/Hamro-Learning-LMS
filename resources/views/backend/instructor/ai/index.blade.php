@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>AI Assistant Creator</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="#">AI Assistant</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-12">
                <div class="white-box" style="border-radius:12px; border-top:4px solid #8b5cf6;">
                    
                    {{-- Header --}}
                    <div class="d-flex align-items-center mb-4">
                        <div style="background:rgba(139,92,246,0.1); width:50px; height:50px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:16px;">
                            <i class="ti-wand" style="font-size:24px; color:#8b5cf6;"></i>
                        </div>
                        <div>
                            <h3 style="margin:0; font-weight:700; color:#333;">AI Generator Control Workspace</h3>
                            <p class="text-muted m-0">Generate structurally complete courses, quiz assessments, or announcements instantly.</p>
                        </div>
                    </div>

                    <form action="#" id="aiForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">What do you want to generate?</label>
                            <select name="type" id="aiType" class="form-control" style="border-radius:8px;" required>
                                <option value="course">Course Structure & Outline (Creates Draft Course)</option>
                                <option value="quiz">Question Bank & Quiz Set (Creates Course Quiz)</option>
                                <option value="assignment">Assignment Task Outline (Creates Course Assignment)</option>
                                <option value="announcement">Global Course Announcement (Sends Student Update)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-weight:600;">Specify Subject / Core Topic</label>
                            <input type="text" name="topic" id="aiTopic" placeholder="e.g. Advanced Flutter State Management with BLoC" class="form-control" style="border-radius:8px;" required>
                        </div>

                        <button type="submit" id="aiBtn" class="primary-btn fix-gr-bg w-100" style="border-radius:25px; padding:12px;">
                            <i class="ti-wand mr-1"></i> Generate AI Content
                        </button>
                    </form>

                    {{-- AI Output Container --}}
                    <div id="aiOutputContainer" class="mt-4 p-4 bg-light" style="border-radius:12px; display:none; border:1px solid #e2e8f0;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="font-weight:700; margin:0; color:#8b5cf6;"><i class="ti-wand"></i> AI Generated Result</h5>
                            <button class="btn btn-sm btn-outline-secondary" onclick="copyAiOutput()" style="border-radius:20px;">Copy Output</button>
                        </div>
                        <pre id="aiOutputText" style="white-space:pre-wrap; font-family:'Courier New', monospace; font-size:14px; color:#333; margin:0; line-height:1.5;"></pre>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('aiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const type = document.getElementById('aiType').value;
    const topic = document.getElementById('aiTopic').value;
    const btn = document.getElementById('aiBtn');
    const container = document.getElementById('aiOutputContainer');
    const outputText = document.getElementById('aiOutputText');

    btn.disabled = true;
    btn.innerHTML = '<i class="ti-reload mr-2" style="animation: spin 1s linear infinite;"></i> Generating... Please Wait...';
    container.style.display = 'none';

    fetch('{{ route("instructor.ai.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type, topic: topic })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti-wand mr-1"></i> Generate AI Content';
        if(data.success) {
            container.style.display = 'block';
            outputText.innerHTML = data.output;
        } else {
            alert('Generation failed: ' + data.message);
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti-wand mr-1"></i> Generate AI Content';
        alert('Something went wrong.');
    });
});

function copyAiOutput() {
    const text = document.getElementById('aiOutputText').innerText;
    navigator.clipboard.writeText(text);
    alert('Copied to clipboard!');
}
</script>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection
