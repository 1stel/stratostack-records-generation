<fieldset>
    <legend>Reseller</legend>

    <!-- Name Form Input -->
    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- Address Form Input -->
    <div class="form-group">
        {!! Form::label('address', 'Address', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('address', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- Address2 Form Input -->
    <div class="form-group">
        {!! Form::label('address2', 'Address', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('address2', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- City/State/ZIP Form Group -->
    <div class="form-group">
        {!! Form::label('city', 'City', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('city', null, ['class' => 'form-control']) !!}
        </div>

        {!! Form::label('state', 'State', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-1">
            {!! Form::text('state', null, ['class' => 'form-control']) !!}
        </div>

        {!! Form::label('zip', 'Zip', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-2">
            {!! Form::text('zip', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- Phone Form Input -->
    <div class="form-group">
        {!! Form::label('phone', 'Phone', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- Email Form Input -->
    <div class="form-group">
        {!! Form::label('email', 'Email:', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('email', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <!-- Due Date Input -->
    <div class="form-group">
        {!! Form::label('due_date', 'Due Date:', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('due_date', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('apikey', 'API Key:', ['class' => 'col-sm-1 control-label']) !!}
        <div class="col-sm-11">
            <input type="text" name="apieky" value="{{ $apikey }}" readonly class="form-control">
        </div>
    </div>

    <!-- Domainid Form Input -->
    <div class="form-group">
        {!! Form::label('domainid', 'Domain ID:') !!}
        {!! Form::text('domainid', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Portal_url Form Input -->
    <div class="form-group">
        {!! Form::label('portal_url', 'Portal URL:') !!}
        {!! Form::text('portal_url', null, ['class' => 'form-control']) !!}
    </div>
    
    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-11">
            <!-- Form Submit -->
            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
        </div>
    </div>


</fieldset>