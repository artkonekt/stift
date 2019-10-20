<div class="card card-accent-secondary">

    <div class="card-header">{{ __('Project Labels') }}</div>

    <div class="card-block">
        <p class="h5">
        @foreach($labels as $label)
            <span class="badge badge-pill" style="background-color: {{ $label->colorAsHex() }}">{{ $label->title }}</span>
        @endforeach
        </p>
    </div>

</div>
