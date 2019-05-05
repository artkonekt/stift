<div id="worklog_form--create" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="invoice-settings-title" aria-hidden="true">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open(['route' => 'stift.worklog.store']) !!}

            <div class="modal-header">
                <h5 class="modal-title" id="invoice-settings-title">{{ __('Log Work') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{ Form::hidden('state', 'finished') }}

                <div class="form-group row">
                    <label class="col-form-label col-md-4">{{ __('Issue') }}</label>
                    <div class="col-md-8">
                        <div class="{{ $errors->has('issue_id') ? ' has-danger' : '' }}">
                            {{ Form::select(
                                        'issue_id',
                                        stift_open_issues($issue)->pluck('subject', 'id'),
                                        $issue->id,
                                        ['class' => 'form-control', 'autocomplete' => 'off']
                            ) }}
                            @if ($errors->has('issue_id'))
                                <div class="form-control-feedback">{{ $errors->first('issue_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-4">{{ __('Start date') }}</label>
                    <div class="col-md-8">
                        <div class="{{ $errors->has('started_at') ? ' has-danger' : '' }}">
                            {{ Form::datetime('started_at', Carbon\Carbon::now(), [ 'class' => 'form-control', 'type' => 'datetime-local']) }}
                            @if ($errors->has('started_at'))
                                <div class="form-control-feedback">{{ $errors->first('duration') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-4">{{ __('Duration') }}</label>
                    <div class="col-md-8">
                        <div class="{{ $errors->has('duration') ? ' has-danger' : '' }}">
                            {{ Form::text('duration', null, [ 'class' => 'form-control', 'placeholder' => __('Eg: 1h 15m')]) }}
                            @if ($errors->has('duration'))
                                <div class="form-control-feedback">{{ $errors->first('duration') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="form-control-label col-md-4">{{ __('Billable') }}</label>
                    <div class="col-md-8">
                        {{ Form::hidden('is_billable', 0) }}
                        <label class="switch switch-icon switch-pill switch-primary">
                            {{ Form::checkbox('is_billable', 1, $issue->project->is_billable, ['class' => 'switch-input']) }}
                            <span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>
                            <span class="switch-handle"></span>
                        </label>

                        @if ($errors->has('is_billable'))
                            <input type="text" hidden class="form-control is-invalid">
                            <div class="invalid-feedback">{{ $errors->first('is_billable') }}</div>
                        @endif

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-form-label">{{ __('Description') }}</label>
                    <div class="{{ $errors->has('description') ? ' has-danger' : '' }}">
                        {{ Form::textarea('description', null, [ 'class' => 'form-control', 'placeholder' => __('Type worklog description...')]) }}
                        @if ($errors->has('description'))
                            <div class="form-control-feedback">{{ $errors->first('description') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">{{ __('Log work') }}</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
