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
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="{{ asset('/css/print.css') }}" rel="stylesheet" media="all">
</head>
<body class="has-toolbar">
    <div class="noprint toolbar">
        <button onclick="window.print()" class="btn btn-primary">{{ __('Print') }}</button>
        <button onclick="window.history.back()" class="btn btn-dark">{{ __('Back') }}</button>
    </div>
    <h1>{{ __('Time Report') }}</h1>
    <p>
        @forelse($report->getProjects() as $project)
            <span class="badge">{{ $project->name }}</span>
        @empty
            <span class="badge">{{ __('All projects') }}</span>
        @endforelse
    </p>
    <h2>{{ $report->getPeriod()->getStartDate()->format('M d') }}
    -
        {{ $report->getPeriod()->getEndDate()->format('M d, Y') }}</h2>
    @include('stift::worklog._list')
</body>
</html>




