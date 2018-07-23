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

            @if($reportsAllProjects)
                <span class="badge badge-dark font-weight-normal">{{ __('All projects') }}</span>
            @else
                @forelse($report->getProjects() as $project)
                    <span class="badge badge-dark font-weight-normal">{{ $project->name }}</span>
                @empty
                    <span class="badge badge-warning font-weight-normal">{{ __('No projects included in report') }}</span>
                @endforelse
            @endif

            <div class="card-actionbar">
                <form action="{{ route('stift.worklog.index') }}" class="form-inline">
                    {!! Form::select('projects[]', $projects, null, ['class' => 'form-control form-control-sm', 'placeholder' => __('All projects')]) !!}
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
                <div class="col-sm-6 col-md-4">
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
