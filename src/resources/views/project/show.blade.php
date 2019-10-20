@extends('appshell::layouts.default')

@section('title')
    {{ $project->name }}
@stop

@section('content')

    <div class="row">

        <div class="col-sm-6 col-md-3">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-interval',
                    'type' => 'info'
            ])
                {{ show_duration_in_hours($durationCurrentMonth) }}

                @slot('subtitle')
                    {{ __('Work logged this month') }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => enum_icon($project->customer->type),
                    'type' => $project->is_active ? 'success' : null
            ])
                {{ $project->customer->name }}
                @if (!$project->is_active)
                    <small>
                        <span class="badge badge-default">
                            {{ __('inactive') }}
                        </span>
                    </small>
                @endif
                @slot('subtitle')
                    {{ $project->customer->type->isOrganization() ? $project->customer->firstname . ' ' . $project->customer->lastname : ''  }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-12 col-md-5">
            <div class="card text-white bg-info">
                <div class="card-body pb-0">
                    <div class="h1 text-muted float-right m-b-2">
                        <i class="zmdi zmdi-timer"></i>
                    </div>
                    <div class="h4 m-b-0 text-uppercase">{{ __('Working Hours - last 12 months') }}</div>
                </div>
                <div class="chart-wrapper mt-3" style="height:60px;">
                    <canvas class="chart" id="worklogChart" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>

    @include('stift::project._issues', ['issues' => $project->issues()->open()->sort()->get(), 'title' => __('Open Issues')])

    @can('view labels')
    <div class="card">
        <div class="card-header">
            <h5>{{ __('Project Labels') }}</h5>
        </div>
        <div class="card-block">
            @include('stift::project._labels', ['labels' => $project->labels])
        </div>
    </div>
    @endcan


    <div class="card">
        <div class="card-block">
            @can('edit projects')
                <a href="{{ route('stift.project.edit', $project) }}" class="btn btn-outline-primary">{{ __('Edit project') }}</a>
            @endcan
        </div>
    </div>

@stop

@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <script>
        $('document').ready(function () {
            var ctx = document.getElementById("worklogChart");
            var worklogChart = new Chart(ctx, {
                "type": "line",
                "data": {
                    "labels": ["{!! $workingHoursInLast12Months->implode('month_name', '","') !!}"],
                    "datasets": [
                        {
                            label: "{{ __('Hours') }}",
                            backgroundColor: 'rgba(255,255,255,.2)',
                            borderColor: 'rgba(255,255,255,.55)',
                            "data": [{{ $workingHoursInLast12Months->implode('hours', ',') }}]
                        }
                    ]
                },
                "options": {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            display: false
                        }],
                        yAxes: [{
                            display: false
                        }]
                    },
                    elements: {
                        line: {
                            borderWidth: 2
                        },
                        point: {
                            radius: 0,
                            hitRadius: 10,
                            hoverRadius: 4
                        }
                    }
                }
            });

        });
    </script>
@stop
