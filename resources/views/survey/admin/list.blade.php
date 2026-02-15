@extends('survey.layout')

@section('content')
    <div class="row">

        <!-- ================= LIST SUBMISSION ================= -->
        <div class="col-12">
            @foreach ($submissions as $surveyId => $surveySubmissions)
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light fw-semibold border border-secondary d-flex align-items-center gap-2">
                        <i class="bi bi-clipboard-data text-secondary"></i>
                        {{ $surveySubmissions->first()->survey->survey_name }}
                        <span class="badge bg-secondary ms-2">Survey ID: {{ $surveyId }}</span>
                    </div>

                    <div class="table-responsive px-3 pb-3 pt-2">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="bg-light text-uppercase small">
                                <tr>
                                    <th>Survey</th>
                                    <th class="text-center">Employee ID</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($surveySubmissions as $s)
                                    <tr>
                                        <td>{{ $s->survey->survey_name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                {{ $s->employee_id }}
                                            </span>
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ $s->survey_date_formatted }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.survey.results.detail', $s->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer text-end bg-white">
                        <span class="badge bg-primary-subtle text-primary">
                            Total Responses: {{ $surveySubmissions->count() }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ================= HRD DASHBOARD ================= -->
        <div class="col-12">
            <div class="row g-4">
                @foreach ($surveyCharts as $surveyId => $survey)
                    <div class="col-md-5 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center">

                                <div class="fw-semibold mb-2 small">
                                    {{ $survey['survey_name'] }}
                                </div>

                                <div class="d-flex justify-content-center">
                                    <canvas id="donut-{{ $surveyId }}" style="max-width:150px; max-height:150px;">
                                    </canvas>
                                </div>

                                <div class="mt-3 small">
                                    <span class="badge bg-success">
                                        OK {{ $survey['ok_percent'] }}%
                                    </span>
                                    <span class="badge bg-danger ms-2">
                                        Not OK {{ $survey['not_ok_percent'] }}%
                                    </span>
                                </div>

                                <div class="text-muted small mt-2">
                                    Employee Satisfaction
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('survey.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- ================= CHART JS ================= -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        @foreach ($surveyCharts as $surveyId => $survey)
            new Chart(document.getElementById('donut-{{ $surveyId }}'), {
                type: 'doughnut',
                data: {
                    labels: ['OK', 'Not OK'],
                    datasets: [{
                        data: [
                            {{ $survey['ok'] }},
                            {{ $survey['not_ok'] }}
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        @endforeach
    </script>
@endsection
