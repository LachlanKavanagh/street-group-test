<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Street group technical test</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    </head>
    <body class="antialiased">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <ul>
                @foreach($message as $entry) 
                    <li>@php print_r($entry); @endphp
                @endforeach
                </ul>
            </div>
        @endif
        @if(count($errors) > 0)
            <div class="alert alert-danger">
                The following errors occured when attempting to parse your file:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            <div>
        @endif
        <form action="{{ route('parseData') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <input type="file" name="csv"/>
                <button type="submit">Upload File</button>
            </div>
    </body>
</html>
