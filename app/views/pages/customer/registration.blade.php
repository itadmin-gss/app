@extends('layouts.onecolumn')

@section('content')

<div id="content" class="span12">



    <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2><span class="break"></span>Registration</h2>

            </div>



            <div class="box-content custome-form clearfix">



                @if($errors->has())



                @foreach ($errors->all() as $error)

                <div class="alert alert-error">

                    <button type="button" class="close" data-dismiss="alert">×</button>

                    <strong>{{ $error }}</strong>

                </div>



                @endforeach

                @endif





                {{ Form::open(array('action' => 'UserController@createUser', 'class'=>'form-horizontal')) }}

                <fieldset>

                    <div class="row-fluid">

                        <div class="span6">

                            <div class="control-group">



                                {{Form::label('first_name', 'First name:*', array('class'=>'control-label', 'for'=>'typeahead'))}}



                                <div class="controls">

                                    {{ Form::text('first_name', '', array('class'=>'span10 typeahead','id'=>'first_name'))}}

                                </div>

                            </div>





                            <div class="control-group">

                                {{Form::label('last_name', 'Last name:*', array('class'=>'control-label', 'for'=>'typeahead'))}}



                                <div class="controls">

                                    {{ Form::text('last_name', '', array('class'=>'span10 typeahead','id'=>'last_name'))}}



                                </div>

                            </div>





                            <div class="control-group">

                                {{Form::label('company', 'Company:', array('class'=>'control-label', 'for'=>'typeahead'))}}

                                <div class="controls">

                                    {{ Form::text('company', '', array('class'=>'span10 typeahead','id'=>'company'))}}

                                </div>

                            </div>



                            <div class="control-group">

                                {{Form::label('email', 'Email: *', array('class'=>'control-label', 'for'=>'typeahead'))}}

                                <div class="controls">

                                    {{Form::email('email', '', array('class'=>'span10 typeahead', 'id'=>'email'))}}

                                </div>

                            </div>

                            <div class="control-group">

                                {{Form::label('username', 'Username: *', array('class'=>'control-label', 'for'=>'typeahead'))}}

                                <div class="controls">

                                    {{ Form::text('username', '', array('class'=>'span10 typeahead','id'=>'username', 'data-rel'=>'tooltip', 'data-original-title'=>'Username must be at least 8 Characters'))}}

                                </div>

                            </div>





                            <div class="control-group">

                                {{Form::label('password', 'Password: *', array('class'=>'control-label', 'for'=>'typeahead'))}}

                                <div class="controls">

                                    {{ Form::password('password', '', array('class'=>'span10 typeahead','id'=>'password'))}} <a title='Suggestion' rel='popover' data-placement='right'>Password Hint</a>

                                </div>



                            </div>



                            <div class="control-group">

                                {{Form::label('password_confirmation', 'Confirm password: *', array('class'=>'control-label', 'for'=>'typeahead'))}}

                                <div class="controls">

                                    {{ Form::password('password_confirmation', '', array('class'=>'span10 typeahead','id'=>'password_confirmation'))}}

                                </div>

                            </div>

                            <div class="control-group">



                                {{Form::label('type_id', 'User Type: *', array('class'=>'control-label', 'for'=>'typeahead'))}}

                                <div class="controls">

                                    <label class="radio">



                                        {{ Form::radio('type_id', $vendor, false,  array('id'=>'vendor'))}}

                                        Vendor

                                    </label>



                                    <div style="clear:both"></div>



                                    <label class="radio">

                                        {{ Form::radio('type_id', $customer, false, array('id'=>'customer'))}}

                                        Customer

                                    </label>

                                </div>

                                <div style="clear:both"></div>

                            </div>

                        </div>

                        <!--/span-4-->





                        <!--/span-4-->



                    </div>

                    <!--/row-fluid-->







                    <div class="form-actions text-right clearfix">

                        {{Form::submit('Create Account', array('class'=>'btn btn-info'));}}

                        <button type="button" class="btn btn-inverse">Cancel</button>

                    </div>

                </fieldset>

                {{ Form::close() }}



            </div>

        </div><!--/span-->



    </div><!--/row-->





</div>



@stop