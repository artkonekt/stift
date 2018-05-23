@extends('appshell::layouts.default')

@section('title')
    {{ __('The :name Project', ['name' => $project->name]) }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-3">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'folder-star',
                    'type' => $project->is_active ? 'success' : null
            ])
                {{ $project->name }}
                @if (!$project->is_active)
                    <small>
                        <span class="badge badge-default">
                            {{ __('inactive') }}
                        </span>
                    </small>
                @endif
                @slot('subtitle')
                    {{ $project->customer->name }}
                @endslot
            @endcomponent
        </div>

        {{--<div class="col-sm-6 col-md-3">--}}
            {{--@component('appshell::widgets.card_with_icon', [--}}
                    {{--'icon' => 'shield-security',--}}
                    {{--'type' => 'info'--}}
            {{--])--}}
                {{--{{ $user->type }}--}}

                {{--@slot('subtitle')--}}
                    {{--@if($user->roles->count())--}}
                        {{--{{ __('Roles') }}:--}}
                        {{--{{ $user->roles->take(3)->implode('name', ' | ') }}--}}
                    {{--@else--}}
                        {{--{{ __('no roles') }}--}}
                    {{--@endif--}}

                    {{--@if($user->roles->count() > 3)--}}
                        {{--| {{ __('+ :num more...', ['num' => $user->roles->count() - 3]) }}--}}
                    {{--@endif--}}
                {{--@endslot--}}
            {{--@endcomponent--}}
        {{--</div>--}}

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
