@foreach($labels as $label)
    <?php $labelStyle = "background-color: {$label->colorAsHex()};"; ?>
    <div class="btn-group btn-group-sm mb-1" role="group">
        @can('edit labels')
            <a href="{{ route('stift.label.edit', [$project, $label]) }}"
               class="btn btn-secondary" style="{{ $labelStyle }}">{{ $label->title }}</a>
        @else
            <button class="btn btn-secondary" type="button" style="{{ $labelStyle }}">{{ $label->title }}</button>
        @endcan
        <button type="button" class="btn btn-secondary" style="{{ $labelStyle }}" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-more-vert"></i>
        </button>
        <div class="dropdown-menu">
            @can('delete labels')
                {{ Form::open([
                            'url' => route('stift.label.destroy', [$project, $label]),
                            'style' => 'display: inline',
                            'data-confirmation-text' => __('Delete :title?', ['title' => $label->title]),
                            'method' => 'DELETE'
                        ])
                }}
                <button class="dropdown-item" type="submit">
                    <i class="zmdi zmdi-close text-danger"></i>
                    {{ __('Delete ":title"', ['title' => $label->title]) }}
                </button>
                {{ Form::close() }}

            @endcan
        </div>
    </div>
@endforeach
@can('create labels')
    <a href="{{ route('stift.label.create', $project) }}"
       class="btn btn-success btn-sm mb-1"
       title="{{ __('Add label') }}"><i class="zmdi zmdi-plus"></i></a>
@endcan
