<div class="card">
    <div class="card-header" id="asd">
        {{ __('Worklogs') }}
        <div class="card-actionbar">
            @can('create worklogs')
                {!! Form::open(['route' => 'stift.worklog.store', 'style' => 'display: inline;']) !!}
                {{ Form::hidden('issue_id', $issue->id) }}
                <button class="btn btn-sm btn-primary float-right">
                    <i class="zmdi zmdi-play"></i>
                    {{ __('Start work') }}
                </button>
                {!! Form::close() !!}
                <button onclick="alert('Not yet dude');" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Log work') }}
                </button>
            @endcan
        </div>
    </div>
    <div class="card-block">
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('State') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Duration') }}</th>
                <th>{{ __('Description') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($issue->worklogs as $worklog)
                <tr>
                    <td>
                        {{ $worklog->state->label() }}
                        @if ($worklog->state->value() == 'running')
                            {!! Form::model($worklog, ['route' => ['stift.worklog.update', $worklog], 'method' => 'PUT', 'style' => 'display: inline;']) !!}
                            {{ Form::hidden('state', 'finished') }}
                            {{ Form::text('duration', $worklog->started_at->diffInSeconds(), [
                                    'data-running' => '1', 'data-worklog_id' => $worklog->id,
                                    'autocomplete' => 'off',
                                    'id' => 'worklog_' . $worklog->id
                            ]) }}
                            <button class="btn btn-xs btn-primary" title="{{ __('Stop work') }}">
                                <i class="zmdi zmdi-stop"></i>
                            </button>
                            {!! Form::close() !!}
                        @endif
                    </td>
                    <td>
                        {{ $worklog->started_at }}
                    </td>
                    <td>{{ is_null($worklog->duration) ? '-' : duration_secs_to_human_readable($worklog->duration) }}</td>
                    <td>{!! nl2br($worklog->description) !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">{{ __('No work has been logged yet') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script>
    /* I know I know, jquery sucks in 2018, I promise it will be removed in 48 hours */
    $('document').ready(function () {
        setInterval(function() {
            $('[data-running=1]').each(function(index, item) {
                $(item).val(parseInt($(item).val()) + 1);
            });
        }, 1000);


    });
</script>
@stop
