<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Time Report') }}</title>

    <!-- Styles -->
    <link href="{{ asset('/css/print.css') }}" rel="stylesheet" media="all">
</head>
<body>
    <h1>{{ __('Time Report') }}</h1>
    <h2>{{ $report->getPeriod()->getStartDate()->format('M d') }}
    -
        {{ $report->getPeriod()->getEndDate()->format('M d, Y') }}

    @forelse($report->getProjects() as $project)
        <span class="badge badge-dark font-weight-normal">{{ $project->name }}</span>
    @empty
        <span class="badge badge-dark font-weight-normal">{{ __('All projects') }}</span>
    @endforelse
    </h2>
    @include('stift::worklog._list')
</body>
</html>




