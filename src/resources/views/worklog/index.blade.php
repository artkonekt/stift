@extends('appshell::layouts.default')

@section('title')
    {{ __('Time Report') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            {{ $report->getPeriod()->getStartDate()->format('M d') }}
            -
            {{ $report->getPeriod()->getEndDate()->format('M d, Y') }}

            @forelse($report->getProjects() as $project)
                <span class="badge badge-dark font-weight-normal">{{ $project->name }}</span>
            @empty
                <span class="badge badge-dark font-weight-normal">{{ __('All projects') }}</span>
            @endforelse

            <div class="card-actionbar">
                <form action="{{ route('stift.worklog.index') }}" class="form-inline">
                    {!! Form::select('projects[]', $projects, null, ['id' => 'projects', 'multiple'=>'multiple', 'class' => 'form-control form-control-sm']) !!}
                    &nbsp;
                    {!! Form::select('period', $periods, null, ['class' => 'form-control form-control-sm']) !!}
                    &nbsp;
                    <button class="btn btn-sm btn-primary" type="submit">{{ __('Filter') }}</button>
                    &nbsp;
                    <button name="print" class="btn btn-sm btn-primary" type="submit" value="1" >{{ __('Print') }}</button>
                </form>

            </div>

        </div>

        <div class="card-block">

            <div class="row">
                <div class="col-sm-6 col-md-3">
                    @component('appshell::widgets.card_with_icon', [
                            'icon' => 'time-interval',
                            'type' => 'info'
                    ])
                        {{ show_duration_in_hours($report->getDuration()) }}

                        @slot('subtitle')
                            {{ __('Total Work logged') }}
                        @endslot
                    @endcomponent
                </div>
            </div>

            @include('stift::worklog._list')

        </div>
    </div>

@stop

@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet"/>

    <script>
        $(document).ready(function () {
            $('#projects').select2({
                'placeholder': '{{ __('All projects') }}',
                'allowClear': true,
                'theme': 'bootstrap'
            });
        });
    </script>
@stop