@extends('app')

@section('content')
    <div class="panel panel-default col-sm-8 col-sm-offset-2">
        <div class="panel-heading">Element Price (hourly)</div>
        <div class="panel-body">
            {!! Form::model($element, ['route' => ['prices.update', $element->id], 'method' => 'PATCH', 'class' => 'form-horizontal']) !!}

            @include('prices/priceform')

            {!! Form::close() !!}
        </div>

    </div>

@endsection

@section('js')
$( "select[name='element']" ).change(function() {
    var priceElement = $( "select[name='element']" ).val();
    var quantityType = $( "select[name='quantity_type']" );
    if (priceElement == 'CPU')
    {
        quantityType.addClass('hidden');
    }
    else
    {
        quantityType.removeClass('hidden');
    }
});

$( document ).ready(function() {
    if ($( "select[name='element']" ).val() == 'CPU')
        $( "select[name='quantity_type']" ).addClass('hidden');
});
@endsection