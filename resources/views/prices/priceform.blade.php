<!-- Element Listing -->
<div class="form-group">
    {!! Form::label('element', 'Element', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('element', $elements, null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Quantity -->
<div class="form-group">
    {!! Form::label('quantity', 'Quantity', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-2">
        {!! Form::text('quantity', null, ['class' => 'form-control']) !!}
    </div>
    <div class="col-sm-3">
        {!! Form::select('quantity_type', ['MB' => 'Megabytes', 'GB' => 'Gigabytes', 'TB' => 'Terabytes'], 'GB', ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Quantity -->
<div class="form-group">
    {!! Form::label('price', 'Price', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-3">
        {!! Form::text('price', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Submit Button -->
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
        <!-- Form Submit -->
        {!! Form::submit('Add', ['class' => 'btn btn-success']) !!}
    </div>
</div>