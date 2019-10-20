<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-label"></i>
        </span>
        {{ Form::text('title', null,
                [
                    'class' => 'form-control form-control-lg' . ($errors->has('title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ]
        ) }}
    </div>
    @if ($errors->has('title'))
        <input hidden class="form-control is-invalid"/>
        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
    @endif
</div>

<div class="form-group row">
    <label class="col-md-2 form-col-label">{{ __('Color') }}</label>
    <div class="col-md-2">
        {{ Form::color('color', null, [
                'class' => 'formd-control' . ($errors->has('color') ? ' is-invalid': ''),
                'style' => 'padding:0;',
                'id'    => 'label-color'
           ])
        }}
        @if ($errors->has('color'))
            <div class="invalid-feedback">{{ $errors->first('color') }}</div>
        @endif
    </div>
    <div class="col-md-8">
        @foreach($presetColors as $name => $hexColor)
            <button type="button" class="btn btn-xs btn-{{ $name }}" title="{{ $name }}"
                    style="background-color: {{ $hexColor }};"
                    onclick="document.getElementById('label-color').value = '{{ $hexColor }}'"><i
                        class="zmdi zmdi-invert-colors"></i>
            </button>
        @endforeach
    </div>
</div>

<hr>
