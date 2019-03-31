<div class="card card-accent-secondary">

    <div class="card-header">
        {{ $title }}

        <div class="card-actionbar">
            @can('create issues')
                <a href="{{ route('stift.issue.create') }}?forProject={{ $project->id }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('New Issue') }}
                </a>
            @endcan
        </div>

    </div>

    <div class="card-block">
        @include('stift::issue._list')
    </div>

</div>
