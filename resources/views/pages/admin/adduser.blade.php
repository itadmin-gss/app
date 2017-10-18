@extends('layouts.default')
@section('content')
<div id="content" class="span11">




    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>Add User</h2>
            </div>

            <div class="box-content custome-form">
                @if (Session::has('message'))
                {!! Session::get('message') !!}
                @endif

                @if(!$errors->isEmpty())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">{!! $error !!}</div>
                @endforeach
                @endif
                {!! Form::open(array('url' => 'addUser', 'class' => 'form-horizontal', 'method' => 'post')) !!}
                <fieldset>
                    <div class="row-fluid">
                        <div class="span6 offset3 centered">
                            <div class="control-group">
                                {!! Form::label('typeahead', 'First Name: *', array('class' => 'control-label')) !!}
                                <div class="controls">
                                    <div class="input-append">
                                        {!! Form::text('first_name', '', array('class' => 'input-xlarge focused',
                                         'id' => 'focusedInput')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                {!! Form::label('typeahead', 'Last Name: *', array('class' => 'control-label')) !!}
                                <div class="controls">
                                    <div class="input-append">
                                        {!! Form::text('last_name', '', array('class' => 'input-xlarge focused',
                                         'id' => 'focusedInput')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                {!! Form::label('typeahead', 'Email Address: *', array('class' => 'control-label')) !!}
                                <div class="controls">
                                    <div class="input-append">
                                        {!! Form::text('email', '', array('id' => 'focusedInput',
                                         'class' => 'input-xlarge focused')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                {!! Form::label('user_role', 'User Role *', array('class' => 'control-label')) !!}

                                <div class="controls">
                                    {!! Form::select('role_id', $user_roles, '01',
                                    array('data-rel' => 'chosen')); !!}
                                </div>
                            </div>

                               <div class="control-group">
                                {!! Form::label('typeahead', 'Password *', array('class' => 'control-label')) !!}
                                <div class="controls">
                                    <div class="input-append">
                                        {!! Form::text('password', '', array('id' => 'password','class' => 'input-xlarge focused')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">

                        <div class="pull-right">
            				 {!! Form::submit('Add User', array('class' => 'btn btn-large btn-success')) !!}
                            {!! Form::button('Cancel', 
                           		 array('class' => 'btn btn-large btn-info', 
                            'onClick' =>'location.href="'.URL::to('list-user').'"')) !!}
                           
                            
                        </div>
                    </div>
                </fieldset>
                {!!Form::close()!!}
            </div>
        </div>





    </div>

</div>
@stop