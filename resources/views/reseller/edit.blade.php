@extends('app')

@section('content')
    {!! Form::model($reseller, ['url' => 'reseller/' . $reseller->id, 'method' => 'PUT', 'class' => 'form-horizontal']) !!}

    @include('reseller.resellerform')

    {!! Form::close() !!}
@endsection