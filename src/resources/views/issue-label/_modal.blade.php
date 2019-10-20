<div id="label-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="label-modal-title" aria-hidden="true">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open([
                    'url'  => route('stift.issue.labels', [$issue]),
                    'method' => 'PUT'
                ])
            !!}

            <div class="modal-header">
                <h5 class="modal-title" id="label-modal-title">{{ __('Issue Labels') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{ Form::hidden('issue_id', $issue->id) }}

                @foreach($labels as $label)
                    <input type="checkbox" id="issue-label-{{ $label->id }}"
                           name="labels[]" value="{{ $label->id }}"
                           @if($assignments->has($label->id))checked="checked" @endif
                    />
                    <label for="issue-label-{{ $label->id }}">{{ $label->title }}</label>

                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('Close') }}</button>
                <button class="btn btn-primary">{{ __('Change labels') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
