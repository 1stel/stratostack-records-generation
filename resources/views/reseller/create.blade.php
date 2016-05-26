@extends('app')

@section('content')

    {!! Form::open(['url' => 'reseller', 'class' => 'form-horizontal']) !!}

    @include('reseller.resellerform')

    {!! Form::close() !!}
@endsection