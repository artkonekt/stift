@extends('appshell::layouts.default')

@section('title')
    {{ __('The :name Project', ['name' => $project->name]) }}
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

        {{--<div class="col-sm-6 col-md-3">--}}
            {{--@component('appshell::widgets.card_with_icon', ['icon' => 'time-countdown'])--}}
                {{--@if ($user->last_login_at)--}}
                    {{--{{ __('Last login') }}--}}
                    {{--{{ $user->last_login_at->diffForHumans() }}--}}
                {{--@else--}}
                    {{--{{ __('never logged in') }}--}}
                {{--@endif--}}

                {{--@slot('subtitle')--}}
                    {{--{{ __('Member since') }}--}}
                    {{--{{ $user->created_at->format(__('Y-m-d H:i')) }}--}}

                {{--@endslot--}}
            {{--@endcomponent--}}
        {{--</div>--}}

        {{--<div class="col-sm-6 col-md-3">--}}
            {{--@component('appshell::widgets.card_with_icon', ['icon' => 'star-circle'])--}}
                {{--{{ $user->login_count }}--}}
                {{--@slot('subtitle')--}}
                    {{--{{ __('Login count') }}--}}
                {{--@endslot--}}
            {{--@endcomponent--}}
        {{--</div>--}}

    </div>

    @include('stift::project._issues', ['issues' => $project->issues()->open()->get(), 'title' => __('Open Issues')])

    <div class="card">
        <div class="card-block">
            @can('edit projects')
                <a href="{{ route('stift.project.edit', $project) }}" class="btn btn-outline-primary">{{ __('Edit project') }}</a>
            @endcan
        </div>
    </div>

@stop
