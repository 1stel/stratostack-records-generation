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

                {!! Form::model($st, ['route' => ['storagetypes.update', $st->id], 'method' => 'PATCH', 'class' => 'form-horizontal']) !!}

                <div class="panel panel-default">
                    <div class="panel-heading">Storage Type</div>
                    <div class="panel-body">

                        <!-- Tag Display (read only) -->
                        <div class="form-group">
                            {!! Form::label('tag', 'Tag', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-7">
                                <p class="form-control-static">{{ $st->tag }}</p>
                            </div>
                        </div>

                        <!-- Storage Type Input -->
                        <div class="form-group">
                            {!! Form::label('storage_type', 'Type', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::select('storage_type', ['Local HDD' => 'Local HDD','Network HDD' => 'Network HDD','Local SSD' => 'Local SSD','Network SSD' => 'Network SSD'], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <!-- Form Submit -->
                                {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection