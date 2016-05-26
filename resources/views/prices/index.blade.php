@extends('app')

@section('content')

    <div class="row">
        <div class="col-sm-5 col-sm-offset-3">
            Pricing Method
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary">
                    <input type="radio" name="priceMethod" id="fixedRatio" autocomplete="off"> Fixed Ratio
                </label>
                <label class="btn btn-primary">
                    <input type="radio" name="priceMethod" id="elementPrice" autocomplete="off"> Price per Element
                </label>
            </div>
        </div>
    </div>

    <div id="fixedRatioContent" class="hidden">
        {!! Form::open(['route' => 'prices.updateFixed', 'method' => 'POST']) !!}

        <fieldset>
            <legend>Hourly Prices</legend>

            <!-- Price per Core (CPU) Form Input -->
            <div class="form-group">
                {!! Form::label('corePrice', 'CPU', ['class' => 'col-sm-3 control-label']) !!}
                <div class="input-group col-sm-3">
                    <div class="input-group-addon">$</div>
                    {!! Form::text('corePrice', $ratioPrices['corePrice'], ['class' => 'form-control']) !!}
                    <div class="input-group-addon">per Core</div>
                </div>
            </div>

            <!-- Price per Gb (RAM) Form Input -->
            <div class="form-group">
                {!! Form::label('ramPrice', 'RAM', ['class' => 'col-sm-3 control-label']) !!}
                <div class="input-group col-sm-3">
                    <div class="input-group-addon">$</div>
                    {!! Form::text('ramPrice', $ratioPrices['ramPrice'], ['class' => 'form-control']) !!}
                    <div class="input-group-addon">per Gb</div>
                </div>
            </div>

            @foreach ($storageTypes as $st)
                <!-- Price per Gb (Storage) Form Input -->
                <div class="form-group">
                    {!! Form::label($st->tag . 'Price', "$st->storage_type - $st->tag", ['class' => 'col-sm-3 control-label']) !!}
                    <div class="input-group col-sm-3">
                        <div class="input-group-addon">$</div>
                        {!! Form::text($st->tag . 'Price', $ratioPrices[$st->tag . 'Price'], ['class' => 'form-control']) !!}
                        <div class="input-group-addon">per Gb</div>
                    </div>
                </div>
            @endforeach
        </fieldset>

        <!-- Form Submit -->
        {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}

        <!-- Form Reset -->
        {!! Form::reset('Reset', ['class' => 'btn btn-default']) !!}

        {!! Form::close() !!}
    </div>
    <div id="elementPriceContent" class="hidden">
        <h4>Hourly Prices</h4>
        <table class="table">
            <thead>
            <tr>
                <th>Element</th>
                <th>Quantity</th>
                <th>Price</th>
                <th><a href="{{ route('prices.create') }}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></th>
            </tr>
            </thead>
            <tbody>

            @foreach ($elementPrices as $ep)
            <tr>
                <td>{{ $ep->element }}</td>
                <td>{{ $ep->quantity }} {{ $ep->quantity_type }}</td>
                <td>{{ $ep->price }}</td>
                <td>
                    <a href="{{ route('prices.edit', $ep->id) }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="javascript:deletePrice({{ $ep->id }})" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('js') 
$( "input[name='priceMethod']" ).change(function() {
    var divName = $( this ).attr('id') + 'Content';

    $("div[id*='Content']").each(function( index ) {
        if (!($(this).hasClass('hidden'))) {
            $(this).addClass('hidden');
        }
    });

    $( "#" + divName).toggleClass('hidden');

    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: { 'priceMethod': $( this ).attr('id') },
        url: '/prices/updateMethod',
        success: function(affectedRows) {
            console.log(affectedRows);
            //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
            if (affectedRows > 0) window.location = '/reseller/';
        }
    });

});

$( document ).ready(function() {
    $( "#{{ $priceMethod->data }}Content" ).toggleClass('hidden');
    var radioInput = $( "#{{ $priceMethod->data }}" );
    radioInput.attr('checked', 'checked');
    radioInput.parent().addClass('active');
});

function deletePrice(id) {
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            url: '/prices/' + id, //resource
            success: function(affectedRows) {
                //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                if (affectedRows > 0) window.location = '/prices/';
            }
        });
}
 @endsection
