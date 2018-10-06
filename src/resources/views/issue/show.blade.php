@extends('appshell::layouts.default')

@section('title')
    {{  $issue->subject }}
@stop

@section('content')

    <div class="row">

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-interval',
                    'type' => $issue->worklogsTotalDuration() ? 'info' : 'warning'
            ])
                @if($issue->worklogsTotalDuration())
                    {{ show_duration_in_hours($issue->worklogsTotalDuration()) }}
                @else
                    {{ __('no work yet') }}
                @endif

                @slot('subtitle')
                    {{ __('Total Work Logged') }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon')
                @if ($issue->assignedTo)
                    <span title="{{ __('Assigned to :name', ['name' => $issue->assignedTo->name]) }}">
                        {{ $issue->assignedTo->name }}
                    </span>
                @else
                    {{ __('Unassigned') }}
                @endif

                @slot('iconSlot')
                    <img src="{{ avatar_image_url($issue->assignedTo) }}"
                         alt="{{ $issue->createdBy->name }}"
                         class="img-avatar img-avatar-35"
                    >
                @endslot

                @slot('subtitle')
                    {{ __('Created by') }}
                    {{ $issue->createdBy->name }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => enum_icon($issue->status),
                    'type' => $issue->status->isOpen() ? null : 'success'
            ])
                {{ $issue->status->label() }}
                @slot('subtitle')
                    {{ $issue->project->customer->name }}
                @endslot
            @endcomponent
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            {{ __('Description') }}

            <div class="card-actionbar">
                @can('edit issues')
                    <a href="{{ route('stift.issue.edit', $issue) }}"
                       class="btn btn-outline-primary">{{ __('Edit issue') }}</a>
                @endcan
            </div>

        </div>
        <div class="card-block">
            {!! $issue->getMarkdownDescriptionAsHtml() !!}
        </div>
    </div>

    @include('stift::issue._worklogs')

@stop
