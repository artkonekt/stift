@extends('appshell::layouts.default')

@section('title')
    {{ __('Issues') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            {{ __('Open Issues') }}

            <div class="card-actionbar">
                @can('create issues')
                    <a href="{{ route('stift.issue.create') }}" class="btn btn-sm btn-outline-success float-right">
                        <i class="zmdi zmdi-plus"></i>
                        {{ __('New Issue') }}
                    </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('Subject') }}</th>
                    <th>{{ __('Project') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Assigned to') }}</th>
                    <th>{{ __('Created at') }}</th>
                    <th>{{ __('Worklogs') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach($issues as $issue)
                    <tr>
                        <td>
                            @can('view issues')
                                <a href="{{ route('stift.issue.show', $issue) }}">{{ $issue->subject }}</a>
                            @else
                                {{ $issue->subject }}
                            @endcan
                        </td>
                        <td>{{ $issue->project->name }}</td>
                        <td>{{ $issue->status }}</td>
                        <td>{{ $issue->assignedTo ? $issue->assignedTo->name : '-' }}</td>
                        <td>{{ $issue->created_at->diffForHumans() }}</td>
                        <td>{{ duration_secs_to_human_readable($issue->worklogsTotalDuration()) }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop
