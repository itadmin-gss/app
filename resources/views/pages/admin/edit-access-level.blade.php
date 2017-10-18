@extends('layouts.default')
@section('content')
<div id="content" class="span11">
  <div class="row-fluid">
    <div class="box span12">
      <div class="box-header" data-original-title>
        <h2><span class="break"></span>Edit Access Level</h2>
      </div>
      	@if(Session::has('message'))
    		{!!Session::get('message')!!}
		@endif
         {{-- */$url='edit-access-level/'.$user_role->id;/* --}}
      <div class="box-content custome-form">
        {!! Form::open(array('url' => $url, 'class' => 'form-horizontal', 'method' => 'post')) !!}
          <fieldset>
            <div class="control-group">
            {!! Form::label('typeahead', 'Role Name: *', array('class' => 'control-label')) !!}
              <div class="controls">
                <div class="input-append">
                {!! Form::text('role_name', $user_role->role_name, 
                array('class' => 'input-xlarge focused','id' => 'focusedInput')) !!}
                </div>
              </div>
            </div>
            <div class="control-group">
              {!! Form::label('typeahead', 'Description:', array('class' => 'control-label')) !!}
              <div class="controls">
                <div class="input-append span4">
                  {!! Form::textarea('description', $user_role->description) !!}
                </div>
              </div>
            </div>
            <div class="control-group">
             {!! Form::label('typeahead', 'Status', array('class' => 'control-label', 'for' => 'selectError3')) !!}
              
              <div class="controls">
               {!! Form::select('status', array('1' => 'Enable', '0' => 'Disable'), $user_role->status) !!}
              </div>
            </div>
            <div class="form-actions">
            	{!! Form::hidden('role_id',  $user_role->id) !!}
                {!! Form::hidden('update',  '1') !!}
            	{!! Form::submit('Update', array('class' => 'btn btn-large btn-success')) !!}
              	{!! Form::button('Cancel', array('class' => 'btn btn-large btn-success'
                ,'onClick' => 'location.href="'.URL::to('list-access-level').'"')) !!}              
            </div>
          </fieldset>
        {!!Form::close()!!}
      </div>
    </div>
  </div>
</div>
<!-- end: Content --> 
@stop