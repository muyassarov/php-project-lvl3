@extends('layouts.app')

@section('content')
    <div class="container-md mt-5">
        <h1 class="display-4">Domains</h1>
        @include('flash::message')
        @isset($domains)
        <table class="table table-bordered">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Last Check</th>
                <th>Status Code</th>
            </tr>
            @foreach($domains as $domain)
                @php
                    $domainCheck = null;
                @endphp
                @foreach($lastChecks as $lastCheck)
                    @if($lastCheck->domain_id == $domain->id)
                        @php
                        $domainCheck = $lastCheck;
                        @endphp
                        @break
                    @endif
                @endforeach
                <tr>
                    <td style="width: 5%">{{$domain->id}}</td>
                    <td>
                        <a href="{{route('domains.show', [$domain->id])}}">{{$domain->name}}</a>
                    </td>
                    <td>{{$domainCheck ? $domainCheck->created_at : ''}}</td>
                    <td>{{$domainCheck ? $domainCheck->status_code : ''}}</td>
                </tr>
            @endforeach
        </table>
        @endif
    </div>
@endsection

