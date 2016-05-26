<!-- Src Form Input -->
<div class="form-group">
    {!! Form::label('src', 'Source:') !!}
    {!! Form::text('src', null, ['class' => 'form-control']) !!}
</div>

<!-- Src_cidr Form Input -->
<div class="form-group">
    {!! Form::label('src_cidr', 'Source CIDR:') !!}
    {!! Form::text('src_cidr', null, ['class' => 'form-control']) !!}
</div>

<!-- Dst_port Form Input -->
<div class="form-group">
    {!! Form::label('dst_port', 'Local Port:') !!}
    {!! Form::text('dst_port', null, ['class' => 'form-control']) !!}
</div>

<!-- Protocol Form Input -->
<div class="form-group">
    {!! Form::label('protocol', 'Protocol:') !!}
    {!! Form::select('protocol', ['tcp' => 'TCP', 'udp' => 'UDP'], null, ['class' => 'form-control']) !!}
</div>

<!-- Reseller Form Input -->
<div class="form-group">
    {!! Form::label('reseller_id', 'Reseller:') !!}
    {!! Form::select('reseller_id', $resellerList, null, ['class' => 'form-control']) !!}
</div>