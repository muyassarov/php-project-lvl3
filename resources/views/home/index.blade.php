@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
    <div class="jumbotron">
        <div class="container-md">
            <h1 class="display-4">Page Analyzer</h1>
            <p class="lead">Check web page for free</p>
            <hr class="my-4">
            <form method="post" action="{{ route('domains.store') }}" class="form-inline">
                @csrf
                <label class="sr-only" for="domain">Domain</label>
                <input type="text" name="name" id="domain" value="{{ old('name') }}" placeholder="Domain"
                       class="form-control w-25 form-control-lg">
                <button type="submit" class="ml-2 btn btn-lg btn-primary text-uppercase">check</button>
            </form>
        </div>
    </div>
@endsection
