@extends('appshell::layouts.default')

@section('title')
    {{ __('Projects') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create projects')
                    <a href="{{ route('stift.project.create') }}" class="btn btn-sm btn-outline-success float-right">
                        <i class="zmdi zmdi-plus"></i>
                        {{ __('New Project') }}
                    </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Client') }}</th>
                        <th>{{ __('Hours This Month') }}</th>
                        <th>{{ __('Open Issues') }}</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                    $totalHours = 0;
                    $totalOpenIssues = 0;
                ?>
                @foreach($projects as $project)
                    <?php $hours = \Konekt\Stift\Reports\ProjectWorkingHours::create(
                            \Konekt\Stift\Models\PredefinedPeriodProxy::CURRENT_MONTH(),
                            $project)->getWorkingHours();

                        $totalHours += $hours;
                        $openIssues = $project->issues()->open()->count();
                        $totalOpenIssues += $openIssues;
                    ?>
                    <tr>
                        <td>
                            @can('view projects')
                                <a href="{{ route('stift.project.show', $project) }}">{{ $project->name }}</a>
                            @else
                                {{ $project->name }}
                            @endcan
                            @unless($project->is_active)
                                <span class="badge badge-warning">{{ __('Inactive') }}</span>
                            @endunless
                        </td>
                        <td>{{ $project->customer->name }}</td>
                        <td>{{ $hours }}h</td>
                        <td>{{ $project->issues()->open()->count() }}</td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">{{ __('Total') }}:</th>
                        <th>{{ $totalHours }}h</th>
                        <th>{{ $totalOpenIssues }}</th>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>

@stop
