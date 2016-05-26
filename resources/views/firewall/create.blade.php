@extends('app')

@section('content')
    @if ($errors->any())
        <div class="flash alert-info">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

    {!! Form::open(['route' => 'firewall.store', 'method' => 'POST']) !!}

    @include('firewall.ruleform')

    <!-- Form Submit -->
    {!! Form::submit('Add', ['class' => 'btn btn-success']) !!}

    {!! Form::close() !!}
@endsection