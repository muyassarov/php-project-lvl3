@extends('layouts.app')

@section('title', 'List of available domains')

@section('content')
    <div class="container-md mt-5">
        <h1 class="display-4">Domains</h1>
        @isset($domains)
        <table class="table table-bordered">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Last Check</th>
                <th>Status Code</th>
            </tr>
            @foreach($domains as $domain)
                <tr>
                    <td style="width: 5%">{{$domain->id}}</td>
                    <td>
                        <a href="{{route('domains.show', [$domain->id])}}">{{$domain->name}}</a>
                    </td>
                    <td>{{$lastChecks[$domain->id]->created_at ?? ''}}</td>
                    <td>{{$lastChecks[$domain->id]->status_code ?? ''}}</td>
                </tr>
            @endforeach
        </table>
        @endif
    </div>
@endsection

