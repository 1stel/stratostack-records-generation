@extends('app')

@section('content')
<div>
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th><a href="{{ route('reseller.create') }}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></th>
        </tr>
        </thead>
        <tbody>
        @if ($resellers->count() > 0)
            @foreach ($resellers as $reseller)
                <tr>
                    <td><a href="{{ route('reseller.show', $reseller->id) }}">{{ $reseller->name }}</a></td>
                    <td>
                        <a href="{{ route('reseller.edit', $reseller->id) }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                        <a href="javascript:deleteReseller({{ $reseller->id }})" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="2">There are no resellers available.</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection

@section('js')
function deleteReseller(id) {
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