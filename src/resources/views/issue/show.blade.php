@extends('appshell::layouts.default')

@section('title')
    {{  $issue->subject }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-3">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'folder-star',
                    'type' => $issue->status == 'done' ? 'success' : null
            ])
                {{ $issue->project->name }}
                @slot('subtitle')
                    {{ $issue->project->customer->name }}
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

    <div class="card">
        <div class="card-block">
            @can('edit issues')
                <a href="{{ route('stift.issue.edit', $issue) }}" class="btn btn-outline-primary">{{ __('Edit issue') }}</a>
            @endcan
        </div>
    </div>

@stop
