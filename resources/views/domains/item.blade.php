@extends('layouts.app')

@section('content')
    <div class="container-md mt-5">
        <h1 class="display-4">Site: {{$domain->name}}</h1>
        @include('flash::message')
        <table class="table table-bordered">
            <tr>
                <td style="width: 200px">Id</td>
                <th>{{$domain->id}}</th>
            </tr>
            <tr>
                <td>Name</td>
                <td>{{$domain->name}}</td>
            </tr>
        </table>

        <h3 class="mt-5">Checks</h3>
        <form method="post" action="{{route('domains.check', [$domain->id])}}">
            @csrf
            <button class="btn btn-primary" type="submit">Check</button>
        </form>

    </div>
@endsection

