@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <div class="container-md">
            <h1 class="display-4">Page Analyzer</h1>
            <p class="lead">Check web page for free</p>
            <hr class="my-4">
            @include('flash::message')
            <form method="post" action="<?=route('domains.store')?>" class="form-inline">
                @csrf
                <input type="text" name="name" id="domain" value="{{ old('name') }}" class="form-control w-25 form-control-lg">
                <button class="ml-2 btn btn-lg btn-primary">CHECK</button>
            </form>
        </div>
    </div>
@endsection
