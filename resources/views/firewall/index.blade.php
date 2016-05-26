@extends('app')

@section('content')
<div>
    <table class="table">
        <thead>
        <tr>
            <th>Source</th>
            <th>Local Port</th>
            <th>Protocol</th>
            <th>Reseller</th>
            <th><a href="{{ route('firewall.create') }}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></th>
        </tr>
        </thead>
        <tbody>
        @if ($rules->count() > 0)
            @foreach ($rules as $rule)
                <tr>
                    <td>{{ $rule->src }}{{ $rule->src_cidr }}</td>
                    <td>{{ $rule->dst_port }}</td>
                    <td>{{ $rule->protocol }}</td>
                    <td>{{ $rule->reseller->name or '' }}</td>
                    <td>
                        <a href="{{ route('firewall.edit', $rule->id) }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                        <a href="javascript:deleteRule({{ $rule->id }})" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">No firewall rules active.</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection

@section('js')
function deleteRule(id) {
    if (confirm('Delete this reseller?')) {
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            url: '/reseller/' + id, //resource
            success: function(affectedRows) {
                //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                if (affectedRows > 0) window.location = '/reseller/';
            }
        });
    }
}
@endsection