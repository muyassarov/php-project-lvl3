@extends('layouts.app')

@section('title', "Domain {$domain->name} SEO checks")

@section('content')
    <div class="container-md mt-5">
        <h1 class="display-4">Site: {{$domain->name}}</h1>
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
        <form method="post" action="{{route('domains.checks.store', [$domain->id])}}">
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
                <td>{{ Str::limit($domainCheck->h1, 10) }}</td>
                <td>{{ Str::limit($domainCheck->keywords, 10) }}</td>
                <td>{{ Str::limit($domainCheck->description, 10) }}</td>
                <td>{{ $domainCheck->created_at }}</td>
            </tr>
            @endforeach
        </table>
        @endif

    </div>
@endsection

