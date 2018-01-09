@extends('layouts.default')
@section('content')
<div id="content" class="span11">	

			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><span class="break"></span>Edit User</h2>
					</div>
                    	@if(Session::has('message'))
                            {!!Session::get('message')!!}
                        @endif
                     @if(!$errors->isEmpty())
                    <div class="alert alert-error alert-danger form-element">
                         {!! $errors->first('first_name') !!}
                         {!! $errors->first('last_name') !!}
                         {!! $errors->first('email') !!}
                    </div>
                	@endif
                    {{-- */$url='edit-user/'.$user->id;/* --}}
					<div class="box-content custome-form">
						{!! Form::open(array('url' => $url, 'class' => 'form-horizontal', 'method' => 'post')) !!}
						  <fieldset>
                                                      <div class="row-fluid">
                                                      <div class="span6 offset3 centered">
							<div class="control-group">
							{!! Form::label('typeahead', 'First Name: *', array('class' => 'control-label')) !!}                                
								<div class="controls">
									<div class="input-append">
										{!! Form::text('first_name', $user->first_name, array('class' => 'input-xlarge focused','id' => 'focusedInput')) !!} 
									</div>
								</div>
							</div>
							<div class="control-group">
                            {!! Form::label('typeahead', 'Last Name: *', array('class' => 'control-label')) !!}
								<div class="controls">
									<div class="input-append">
										{!! Form::text('last_name', $user->last_name, array('class' => 'input-xlarge focused','id' => 'focusedInput')) !!}                                      
									</div>
								</div>
							</div>
							<div class="control-group">
                              {!! Form::label('typeahead', 'Email Address: *', array('class' => 'control-label')) !!}
								<div class="controls">
									<div class="input-append">
                                    	{!! Form::text('email', $user->email, array('id' => 'focusedInput',
                                         'class' => 'input-xlarge focused')) !!} 
									</div>
								</div>
							</div>
							<div class="control-group">
							{!! Form::label('user_role', 'User Role *', array('class' => 'control-label')) !!}
                                
                                
								<div class="controls">
								  {!! Form::select('role_id', $user_roles, $role_id,
                                    array('data-rel' => 'chosen')); !!}                                  
								</div>
							</div>
                                                      </div>
                                                      </div>
							<div class="form-actions">
                                                            
                                                            <div class="pull-right">
                                {!! Form::hidden('update_user', '1') !!} 
                                {!! Form::hidden('user_id', $user->id) !!}                       
								{!! Form::submit('Update User', array('class' => 'btn btn-large btn-success'))!!}
                                
								{!!Form::button('Cancel', array('class' => 'btn btn-large btn-info'))!!}
                                
                                                            </div>
							</div>   
						  </fieldset>
						{!!Form::close()!!}
					</div>
				</div>
			
				
			
       
					
			</div>
					
			</div>
@stop