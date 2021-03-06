@extends('appshell::layouts.default')

@section('title')
    {{ __('Time Report') }}
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br/>
            @endforeach
        </div>
    @endif

    <div class="card card-accent-secondary">

        <div class="card-header">
            {{ $filter->getDisplayText('period') }}

            @if($filter->isNotDefined('projects'))
                <span class="badge badge-dark font-weight-normal">{{ __('All projects') }}</span>
            @else
                <?php $projects = $report ? $report->getProjects() : []; ?>
                @forelse($projects as $project)
                    <span class="badge badge-dark font-weight-normal">{{ $project->name }}</span>
                @empty
                    <span class="badge badge-warning font-weight-normal">{{ __('No projects included in report') }}</span>
                @endforelse
            @endif

            <div class="card-actionbar">
                <form action="{{ route('stift.worklog.index') }}" class="form-inline">
                    {!! Form::select('users[]', $filter->options('users'), $filter->value('users'), ['class' => 'form-control form-control-sm', 'multiple' => 'multiple']) !!}
                    &nbsp;
                    {!! Form::select('projects[]', $filter->options('projects'), $filter->value('projects'), ['class' => 'form-control form-control-sm', 'multiple' => 'multiple']) !!}
                    &nbsp;
                    {!! Form::select('period', $filter->options('period'), $filter->value('period'), ['class' => 'form-control form-control-sm', 'autocomplete' => 'off']) !!}

                    {!! Form::select('billable', $filter->options('billable'), $filter->value('billable'), ['class' => 'form-control form-control-sm']) !!}
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
                        {{ show_duration_in_hours($report ? $report->getDuration() : 0) }}

                        @slot('subtitle')
                            {{ __('Total Work logged') }}
                        @endslot
                    @endcomponent
                </div>
            </div>


            @includeWhen($report, 'stift::worklog._list')

        </div>
    </div>

@stop
