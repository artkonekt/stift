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
    @forelse($report->getWorklogs() as $worklog)
        @if (!$project || $project->id != $worklog->issue->project->id)
            <tr class="table-dark">
                <th colspan="3">{{ $worklog->issue->project->name }}</th>
                <th class="text-right">{{ show_duration_in_hours($report->projectTotal($worklog->issue->project)) }}</th>
            </tr>
        @endif
        @if (!$issue || $issue->id != $worklog->issue->id)
            <tr>
                <th colspan="4">{{ $worklog->issue->issueType->name }}: {{ $worklog->issue->subject }}</th>
            </tr>
        @endif
        <tr>
            <td>{{ $worklog->started_at->format('Y-m-d') }}</td>
            <td>{!! nl2br($worklog->description) !!}</td>
            <td>{{ $worklog->user->name }}</td>
            <td class="text-right">{{ show_duration_in_hours($worklog->duration) }}</td>
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
    @if (count($report->getUsers()))
        <tr>
            <th colspan="4"><hr></th>
        </tr>
        <tr class="table-dark">
            <th colspan="4">{{ __('By User') }}</th>
        </tr>
        @foreach($report->getUsers() as $user)
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>{{ $user->name }}:</td>
                <td class="text-right">{{ show_duration_in_hours($report->userTotal($user)) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <th colspan="4"><hr></th>
        </tr>
    @endif

    <tr class="table-dark">
        <th colspan="4">{{ __('All work') }}</th>
    </tr>
    <tr>
        <th colspan="2">&nbsp;</th>
        <th class="text-uppercase">{{ __('Total hours') }}:</th>
        <th class="text-right">{{ show_duration_in_hours($report->getDuration()) }}</th>
    </tr>
    </tfoot>

</table>
