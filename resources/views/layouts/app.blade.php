<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/app.css">

    <title>Analyzer - @yield('title')</title>
</head>
<body>

@include('navbar')
@include('flash::message')
<div class="container-fluid">

    @yield('content')
</div>
<footer class="fixed-bottom">
    <hr/>
    <div class="container text-center mt-5 pb-3">
        created by <a target="_blank" href="https://github.com/muyassarov">Muyassarov Behruz</a>
    </div>
</footer>
<script src="/js/app.js"></script>
</body>
</html>
