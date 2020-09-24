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
        <form method="post" action="{{route('domains.checks', [$domain->id])}}">
            @csrf
            <button class="btn btn-primary" type="submit">Run check</button>
        </form>

        @isset($domainChecks)
        <table class="table mt-5">
            <tr>
                <th>Id</th>
                <th>Status Code</th>
                <th>h1</th>
                <th>Keywords</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
            @foreach($domainChecks as $domainCheck)
            <tr>
                <td>{{ $domainCheck->id }}</td>
                <td>{{ $domainCheck->status_code }}</td>
                <td>{{ $domainCheck->h1 }}</td>
                <td>{{ $domainCheck->keywords }}</td>
                <td>{{ $domainCheck->description }}</td>
                <td>{{ $domainCheck->created_at }}</td>
            </tr>
            @endforeach
        </table>
        @endif

    </div>
@endsection

