{!! Form::model($issue, ['route' => ['stift.issue.update', $issue], 'method' => 'PUT', 'style' => 'display:inline;']) !!}
{!! Form::hidden('status', $toStatus) !!}
<button type="submit" class="btn btn-sm btn-outline-{{$color}}">{{ $btnText }}</button>
{!! Form::close() !!}
