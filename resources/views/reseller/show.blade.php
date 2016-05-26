@extends('app')

@section('content')
<div>
    <a href="/reseller/{{ $reseller->id }}/edit">Edit</a>

    <table class="table">
        <tr>
            <td>Name</td>
            <td>{{ $reseller->name }}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td>
                {{ $reseller->address }}<br>
                {{ $reseller->city }}, {{ $reseller->state }} {{ $reseller->zip }}
            </td>
        </tr>
        <tr>
            <td>Phone</td>
            <td>{{ $reseller->phone }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $reseller->email }}</td>
        </tr>

        <tr>
            <td>Domain</td>
            <td>{{ $reseller->domainid }}</td>
        </tr>

        <tr>
            <td>API Key</td>
            <td>{{ $reseller->apikey }}</td>
        </tr>

        <tr>
            <td>Portal URL</td>
            <td>{{ $reseller->portal_url }}</td>
        </tr>
    </table>
</div>
@endsection