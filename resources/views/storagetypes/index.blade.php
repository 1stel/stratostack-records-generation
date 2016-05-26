@extends('app')

@section('content')
<div>
    <table class="table">
        <thead>
        <tr>
            <th>Tag</th>
            <th>Type</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if ($storageTypes->count() > 0)
            @foreach ($storageTypes as $st)
                <tr>
                    <td>{{ $st->tag }}</td>
                    <td>{{ $st->storage_type }}</td>
                    <td>
                        <a href="{{ route('storagetypes.edit', $st->id) }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                        <a href="javascript:deleteType({{ $st->id }})" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3">There are no storage types available, did you complete Setup?</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection

@section('js')
function deleteType(id) {
    if (confirm('Delete this storage option?')) {
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            url: '/storagetypes/' + id, //resource
            success: function(affectedRows) {
                //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                if (affectedRows > 0) window.location = '/storagetypes/';
            }
        });
    }
}
@endsection