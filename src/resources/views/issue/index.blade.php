@extends('appshell::layouts.default')

@section('title')
    {{ __('Issues') }}
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-warning">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif


    <div class="card card-accent-secondary">

        <div class="card-header">
            {{ __('Issues') }}

            @if($filteredProjects)
                @foreach($filteredProjects as $project)
                    <span class="badge badge-dark font-weight-normal">{{ $project->name }}</span>
                @endforeach
            @endif

            <div class="card-actionbar">
                <form action="{{ route('stift.issue.index') }}" class="form-inline">

                    {!! Form::select('projects[]', $projects, null, ['class' => 'form-control form-control-sm', 'placeholder' => __('All projects')]) !!}
                    &nbsp;
                    {!! Form::select('status', $statuses, null, ['class' => 'form-control form-control-sm', 'placeholder' => __('Any status')]) !!}
                    &nbsp;
                    <button class="btn btn-sm btn-primary" type="submit">{{ __('Filter') }}</button>

                @can('create issues')
                    &nbsp;
                    <a href="{{ route('stift.issue.create') }}"
                       class="btn btn-sm btn-outline-success float-right">
                        <i class="zmdi zmdi-plus"></i>
                        {{ __('New Issue') }}
                    </a>
                @endcan
                </form>
            </div>

        </div>

        <div class="card-block">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('Subject') }}</th>
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
                            <span class="font-lg mb-3 font-weight-bold">
                            @can('view issues')
                                    <a href="{{ route('stift.issue.show', $issue) }}">{{ $issue->subject }}</a>
                                @else
                                    {{ $issue->subject }}
                                @endcan
                            </span>
                            <span class="text-muted font-sm">#{{$issue->id}}</span>
                            <div class="text-muted">
                                @if( Auth::user()->can('view projects') && $issue->project->visibleFor(Auth::user()) )
                                    <a href="{{ route('stift.project.show', $issue->project) }}">
                                        {{ $issue->project->name }}
                                    </a>
                                @else
                                    {{ $issue->project->name }}
                                @endif
                            </div>
                        </td>
                        <td><i class="zmdi zmdi-{{ enum_icon($issue->status) }}" alt="{{ $issue->status->label() }}" title="{{ $issue->status->label() }}"></i></td>
                        <td>
                            <img src="{{ avatar_image_url($issue->assignedTo, 100) }}"
                                 class="img-avatar img-avatar-50"
                                 title="{{ $issue->assignedTo ? $issue->assignedTo->name : __('Unassigned')}}"
                                 alt="{{ $issue->assignedTo ? $issue->assignedTo->name : __('Unassigned')}}"
                            >
                        </td>
                        <td>{{ $issue->created_at->diffForHumans() }}</td>
                        <td>{{ duration_secs_to_human_readable($issue->worklogsTotalDuration()) }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop
