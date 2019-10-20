<div class="card">
    <div class="card-block">
        <h6 class="card-title">{{ __('Labels') }}</h6>

        <table class="table">
            <tr>
                <td>
                    @foreach($issue->labels as $label)
                        <span class="badge badge-pill badge-lg"
                              style="background-color: {{ $label->colorAsHex() }};">{{ $label->title }}</span>
                    @endforeach
                </td>
                <td class="text-right">
                    <button type="button" data-toggle="modal"
                            data-target="#label-modal"
                            class="btn btn-outline-success btn-sm">{{ __('Edit labels') }}</button>
                </td>
            </tr>
        </table>
    </div>
</div>

@include('stift::issue-label._modal', [
    'labels' => $issue->project->labels,
    'assignments' => $issue->labels()->get()->keyBy('id'),
])

