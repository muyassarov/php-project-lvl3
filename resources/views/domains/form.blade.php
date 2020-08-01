@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Page Analyzer</h1>
        <p class="lead">Check web page for free</p>
        <hr class="my-4">

        <form method="post" action="<?=route('domains.store')?>" class="form-inline">
            <input type="url" name="domain" id="domain" class="form-control form-control-lg">
            <button class="ml-2 btn btn-lg btn-primary">CHECK</button>
        </form>
    </div>
@endsection
