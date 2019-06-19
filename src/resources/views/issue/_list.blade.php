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
                <span class="text-muted font-sm" title="{{__('Priority') }}">
                        &nbsp;
                        <i class="zmdi zmdi-sort-amount-desc" ></i>
                        {{ $issue->priority }}
                    </span>
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
            <td><i class="zmdi zmdi-{{ enum_icon($issue->status) }}"
                   alt="{{ $issue->status->label() }}" title="{{ $issue->status->label() }}"></i>
            </td>
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
