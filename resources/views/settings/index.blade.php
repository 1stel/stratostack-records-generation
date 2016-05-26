@extends('app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            {!! Form::open(['route' => 'settings.update', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

            <div class="panel panel-default">
                <div class="panel-heading">Management Server</div>
                <div class="panel-body">

                    <!-- Server Address Input -->
                    <div class="form-group">
                        {!! Form::label('mgmtServer', 'Server Address', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::text('mgmtServer', $mgmtServer->data, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <!-- API Key Input -->
                    <div class="form-group">
                        {!! Form::label('apiKey', 'API Key', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::text('apiKey', $apiKey->data, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <!-- Secret Key Input -->
                    <div class="form-group">
                        {!! Form::label('secretKey', 'Secret Key', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::text('secretKey', $secretKey->data, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-md-6 col-md-offset-3">
                        <a href="#" id="testACS" class="btn btn-primary">Test Configuration</a>
                        <a href="#" id="syncACS" class="btn btn-primary">Sync ACS Settings</a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Billing Options</div>
                <div class="panel-body">
                    <!-- Hours in Month Input -->
                    <div class="form-group">
                        {!! Form::label('hoursInMonth', 'Hours in a Month', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-2">
                            {!! Form::text('hoursInMonth', $hoursInMonth->data, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Submit -->
            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
            
            {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection

@section('js')
$( "#testACS" ).click(function() {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: { 'mgmtServer': $("#mgmtServer").val(), 'apiKey': $("#apiKey").val(), 'secretKey': $("#secretKey").val() },
        url: '{{ route('settings.acstest') }}', //resource
        success: function() {
            location.reload('true');
        }
    });
});

$( "#syncACS" ).click(function() {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        url: '{{ route('settings.syncacs') }}', //resource
        success: function(code) {
            //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
            if (code != -1)
                alert('ACS Settings successfully synchronized.')
            else
                alert('Synchronization failed.')
        }
    });
});
@endsection