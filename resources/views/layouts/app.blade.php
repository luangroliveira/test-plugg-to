<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Marvel</title>
    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.0.js"></script>

    <!-- Icon -->
    <link rel="icon" href="https://insidepulse.com/wp-content/uploads/2019/07/Marvel-logo-circle.png" type="image/png"/>

{{--    <link rel="icon" href="images/marvel.png" type="image/png">--}}
</head>
<body>
<nav class="navbar navbar-dark fixed-top nav-menu d-flex justify-content-between">
    <div class="container">
        <a class="navbar-brand" href="{{ route('characters.index') }}">Marvel</a>
        <form action="{{ route('characters.index')}}" method="GET" class="form-inline input-group-sm">
            <input class="form-control input-search mx-2" type="text" placeholder="Search" aria-label="Search"
                   name="name"
                   value="{{ $name ?? null}}">
            <button class="btn btn-secondary btn-sm" type="submit">Search</button>
        </form>
    </div>
</nav>
<main class="py-5 container">
    @yield('content')
</main>
</body>
</html>
