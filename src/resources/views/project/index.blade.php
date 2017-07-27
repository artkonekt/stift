@extends('appshell::layouts.default')

@section('title')
    {{ __('Projects') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create projects')
                    <a href="{{ route('stift.project.create') }}" class="btn btn-sm btn-outline-success float-right">
                        <i class="zmdi zmdi-plus"></i>
                        {{ __('New Project') }}
                    </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Client') }}</th>
                        <th>{{ __('Since') }}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>
                            @can('view projects')
                                <a href="{{ route('stift.project.show', $project) }}">{{ $project->name }}</a>
                            @else
                                {{ $project->name }}
                            @endcan
                            @unless($project->is_active)
                                <span class="badge badge-warning">{{ __('Inactive') }}</span>
                            @endunless
                        </td>
                        <td>{{ $project->client->name() }}</td>
                        <td>{{ $project->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop