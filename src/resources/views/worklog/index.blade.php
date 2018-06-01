@extends('appshell::layouts.default')

@section('title')
    {{ __('Time Reports') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            {{ __('Worked Hours') }}

            <div class="card-actionbar">
                <form action="{{ route('stift.worklog.index') }}" class="form-inline">
                    {!! Form::select('projects', $projects, null, ['class' => 'form-control form-control-sm', 'placeholder' => __('All projects')]) !!}
                    &nbsp;
                    {!! Form::select('period', $periods, null, ['class' => 'form-control form-control-sm']) !!}
                    &nbsp;
                    <button class="btn btn-sm btn-primary" type="submit">{{ __('Filter') }}</button>
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
                        {{ show_duration_in_hours($totalDuration) }}

                        @slot('subtitle')
                            {{ __('Total Work logged') }}
                        @endslot
                    @endcomponent
                </div>
            </div>

            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th width="15%">{{ __('Date') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th width="10%">{{ __('Done by') }}</th>
                    <th width="7%" class="text-right">{{ __('Duration') }}</th>
                </tr>
                </thead>

                <tbody>
                <?php
                    $project = null;
                    $issue = null;
                ?>
                @forelse($worklogs as $worklog)
                    @if (!$project || $project->id != $worklog->issue->project->id)
                        <tr>
                            <th colspan="4">{{ $worklog->issue->project->name }}</th>
                        </tr>
                    @endif
                    @if (!$issue || $issue->id != $worklog->issue->id)
                        <tr>
                            <td colspan="4">&nbsp;<em>{{ $worklog->issue->issueType->name }}: {{ $worklog->issue->subject }}</em></td>
                        </tr>
                    @endif
                        <tr>
                            <td>{{ $worklog->started_at }}</td>
                            <td>{!! nl2br($worklog->description) !!}</td>
                            <td>{{ $worklog->user->name }}</td>
                            <td class="text-right">{{ duration_secs_to_human_readable((int)$worklog->duration) }}</td>
                        </tr>
                    <?php
                        $project = $worklog->issue->project;
                        $issue = $worklog->issue;
                    ?>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('No worklogs') }}</td>
                    </tr>
                @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4"><hr></th>
                    </tr>
                    <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="text-uppercase">{{ __('Total hours') }}:</th>
                        <th class="text-right">{{ show_duration_in_hours($totalDuration) }}</th>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>

@stop
