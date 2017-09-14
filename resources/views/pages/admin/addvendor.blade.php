@extends('layouts.default')
@section('content')

<div id="content" class="span11">

    <div class="row-fluid ">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>Add Vendor</h2>
            </div>
            <div class="box-content custome-form">
                
                <div class="row-fluid">
                    <div id="addVendorSuccessMessage" class="">                        
                    </div>
                    <div id="addVendorValidationErrorMessage" class="">
                    </div>
                    <div id="addVendorErrorMessage" class="hide">
                        <h4 class="alert alert-error">Can not Updated Profile</h4>
                    </div>
                    
                </div>
                
                {{ Form::open(array('url' => 'admin-create-vendor', 'id'=>'addVendorForm' , 'class'=>'form-horizontal')) }}
                    <fieldset>
                        <div class="row-fluid">
                            <div class="span6 offset3 centered">
                                
                                <div class="control-group">
                                    {{Form::label('firstName', 'First Name *:', array('class' => 'control-label'))}}
                                    <div class="controls">
                                        {{ Form::text('first_name',  isset($user_data['first_name']) ? $user_data['first_name'] : '' , array('class'=>'span10 typeahead','id'=>'firstName'))}}
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    {{Form::label('lastName', 'Last Name *:', array('class' => 'control-label'))}}
                                    <div class="controls">
                                        {{ Form::text('last_name',  isset($user_data['last_name']) ? $user_data['last_name'] : '' , array('class'=>'span10 typeahead','id'=>'lastName'))}}
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    {{Form::label('email', 'Email *:', array('class' => 'control-label'))}}
                                    <div class="controls">
                                        {{ Form::email('email',  isset($user_data['email']) ? $user_data['email'] : '' , array('class'=>'span10 typeahead','id'=>'email'))}}
                                    </div>
                                </div>

                                   <div class="control-group">
                                {{ Form::label('typeahead', 'Password *', array('class' => 'control-label')) }}
                                <div class="controls">
                                    <div class="input-append">
                                        {{ Form::text('password', '', array('id' => 'password','class' => 'input-xlarge focused')) }}
                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="pull-right">
                                {{ Form::submit('Create', array('class'=>'btn btn-large btn-success text-left','id'=>'createVendorSaveButton'))}}
                                {{ Form::button('Cancel', array('class'=>'btn btn-large btn-info text-right','id'=>'createVendorCancelButton'))}}
                            </div>
                        </div>   
                    </fieldset>
                </form>
            </div>
        </div>





    </div>

</div>
<!-- end: Content -->
@stop