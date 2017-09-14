@extends('layouts.default')
@section('content')
<div id="content" class="span11">
  <div class="row-fluid">
    <div class="box span12">
      <div class="box-header" data-original-title>
        <h2><span class="break"></span>Add Access Level</h2>
      </div>
      	
      <div class="box-content custome-form">
      	
        @if(Session::has('message'))
    		{!!Session::get('message')!!}
		@endif
        
        @if($errors->has())
            @foreach ($errors->all() as $error)
                <div class="alert alert-error">{!! $error !!}</div>
            @endforeach
        @endif
      
        {!! Form::open(array('url' => 'add-access-level', 'class' => 'form-horizontal', 'method' => 'post')) !!}
          <fieldset>
            <div class="control-group">
            {!! Form::label('typeahead', 'Role Name: *', array('class' => 'control-label')) !!}
              <div class="controls">
                <div class="input-append">
                {!! Form::text('role_name', '', 
                array('class' => 'input-xlarge focused','id' => 'focusedInput')) !!}
                </div>
              </div>
            </div>
            <div class="control-group">
              {!! Form::label('typeahead', 'Description:', array('class' => 'control-label')) !!}
              <div class="controls">
                <div class="input-append span4">
                  {!! Form::textarea('description') !!}
                </div>
              </div>
            </div>
            <div class="control-group">
             {!! Form::label('typeahead', 'Status', array('class' => 'control-label', 'for' => 'selectError3')) !!}
              
              <div class="controls">
               {!! Form::select('status', array('1' => 'Enable', '0' => 'Disable'), '1') !!}
              </div>
            </div>
            <div class="form-actions">
            {!!Form::hidden('submitted', 1)!!}
            	{!! Form::submit('Add Access Level', array('class' => 'btn btn-large btn-success')) !!}
              	{!! Form::button('Cancel', array('class' => 'btn btn-large btn-success'
                ,'onClick' => 'location.href="list-access-level"')) !!}              
            </div>
          </fieldset>
        {!!Form::close()!!}
      </div>
    </div>
  </div>
</div>
<!-- end: Content --> 
@stop