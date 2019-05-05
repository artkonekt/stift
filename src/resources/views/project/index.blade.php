@extends('appshell::layouts.default')

@section('title')
    {{ __('Projects') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                <form action="{{ route('stift.project.index') }}" class="form-inline">

                    {!! Form::select('active', $actives, $active, ['class' => 'form-control form-control-sm', 'placeholder' => __('--')]) !!}
                    &nbsp;
                    <button class="btn btn-sm btn-primary" type="submit">{{ __('Filter') }}</button>

                    @can('create projects')
                        <a href="{{ route('stift.project.create') }}" class="btn btn-sm btn-outline-success float-right">
                            <i class="zmdi zmdi-plus"></i>
                            {{ __('New Project') }}
                        </a>
                    @endcan
                </form>

            </div>

        </div>

        <div class="card-block">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Billable') }}</th>
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
                            <span class="font-lg mb-3 font-weight-bold">
                            @can('view projects')
                                <a href="{{ route('stift.project.show', $project) }}">{{ $project->name }}</a>
                            @else
                                {{ $project->name }}
                            @endcan
                            </span>
                            @unless($project->is_active)
                                <span class="badge badge-warning">{{ __('Inactive') }}</span>
                            @endunless
                            <div class="text-muted">
                                @can('view customers')
                                    <a href="{{ route('appshell.customer.show', $project->customer) }}">
                                        {{ $project->customer->name }}
                                    </a>
                                @else
                                    {{ $project->customer->name }}
                                @endcan
                            </div>
                        </td>
                        <td class="text-{{ $project->is_billable ? 'success' : 'danger' }}">
                            <i class="zmdi zmdi-{{ $project->is_billable ? 'check' : 'close' }}"></i>
                        </td>
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
