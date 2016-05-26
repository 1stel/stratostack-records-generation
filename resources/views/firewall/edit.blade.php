@extends('app')

@section('content')
    @if ($errors->any())
        <div class="flash alert-info">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

    {!! Form::model($rule, ['route' => ['firewall.update', $rule->id], 'method' => 'PATCH']) !!}

    @include('firewall.ruleform')

    <!-- Form Submit -->
    {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}

    {!! Form::close() !!}
@endsection