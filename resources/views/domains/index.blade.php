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
            </tr>
            @foreach($domains as $domain)
                <tr>
                    <td style="width: 5%">{{$domain->id}}</td>
                    <td>
                        <a href="{{route('domains.show', [$domain->id])}}">
                            {{$domain->name}}
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        @endif
    </div>
@endsection

