@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Revenue Reports</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="#">Reports</a>
                <a href="#">Revenue</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Chart card --}}
            <div class="col-xl-8 col-12 mb-4">
                <div class="white-box" style="border-radius:12px;">
                    <h4 class="mb-30" style="font-weight:600;">Earnings Analysis</h4>
                    <div style="height:350px;">
                        <canvas id="revenueAnalysisChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Summary cards --}}
            <div class="col-xl-4 col-12">
                <div class="white-box mb-4" style="border-radius:12px; border-left:4px solid #6a4fdb;">
                    <h6 class="text-muted text-uppercase mb-2" style="font-weight:600; font-size:12px;">Total Accumulated Revenue</h6>
                    <h2 style="font-weight:700; color:#6a4fdb;">{{ getSetting()->currency->symbol }}{{ number_format(collect($revenueData)->sum('revenue'), 2) }}</h2>
                </div>
                <div class="white-box" style="border-radius:12px; border-left:4px solid #10b981;">
                    <h6 class="text-muted text-uppercase mb-2" style="font-weight:600; font-size:12px;">Total Units Sold</h6>
                    <h2 style="font-weight:700; color:#10b981;">{{ collect($revenueData)->sum('sales') }} Units</h2>
                </div>
            </div>
        </div>

        {{-- Breakdown Table --}}
        <div class="row">
            <div class="col-12">
                <div class="white-box" style="border-radius:12px;">
                    <h4 class="mb-30" style="font-weight:600;">Course-wise Revenue Breakdown</h4>
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:14px;">
                            <thead style="background:#fafafa;">
                                <tr>
                                    <th>Course Title</th>
                                    <th>Units Sold</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenueData as $data)
                                <tr style="border-top:1px solid #f0f0f0;">
                                    <td style="font-weight:600; color:#333;">{{ $data['title'] }}</td>
                                    <td>{{ $data['sales'] }} sales</td>
                                    <td style="font-weight:600; color:#22c55e;">{{ getSetting()->currency->symbol }}{{ number_format($data['revenue'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueAnalysisChart').getContext('2d');
    
    const titles = @json(collect($revenueData)->pluck('title'));
    const revenues = @json(collect($revenueData)->pluck('revenue'));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: titles,
            datasets: [{
                label: 'Course Revenue ({{ getSetting()->currency->symbol }})',
                data: revenues,
                backgroundColor: 'rgba(106, 79, 219, 0.75)',
                borderColor: '#6a4fdb',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endsection
