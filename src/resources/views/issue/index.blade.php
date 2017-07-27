@extends('appshell::layouts.default')

@section('title')
    {{ __('Issues') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

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
                    <th>{{ __('What') }}</th>
                    <th>{{ __('Who') }}</th>
                    <th>{{ __('When') }}</th>
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
                        <td>{{ $issue->createdBy->name }}</td>
                        <td>{{ $issue->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop