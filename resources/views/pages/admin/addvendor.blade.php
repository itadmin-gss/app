@extends('layouts.default')
@section('content')

<div id="content" class="span11">

    <div class="row-fluid ">
        <div class="box span12">
            <div class="box-header" data-original-title>
                
            </div>
            <div class="box-content custome-form">
                
                <div class="row-fluid">
                    <div id="addVendorSuccessMessage" class="">                        
                    </div>
                    <div id="addVendorValidationErrorMessage" class="">
                    </div>
                    <div id="addVendorErrorMessage" class="hide">
                        <div class="alert alert-error">Can not Updated Profile</h4>
                    </div>
                    
                </div>
                

                        <div class='row'>
                            <div class='col-md-4 col-lg-3'></div>
                            
                            <div class="form-group col-md-4 col-sm-12 col-lg-3">
                                <h4>Add Vendor</h4>
                                    {!! Form::open(array('url' => 'admin-create-vendor', 'id'=>'addVendorForm' , 'class'=>'form-horizontal')) !!}
                                    {!!Form::label('firstName', 'First Name *:', array('class' => 'control-label'))!!}
                                    <div class="controls">
                                        {!! Form::text('first_name',  isset($user_data['first_name']) ? $user_data['first_name'] : '' , array('class'=>'form-control','id'=>'firstName'))!!}
                                    </div>
                                
                                    {!!Form::label('lastName', 'Last Name *:', array('class' => 'control-label'))!!}
                                    <div class="controls">
                                        {!! Form::text('last_name',  isset($user_data['last_name']) ? $user_data['last_name'] : '' , array('class'=>'form-control','id'=>'lastName'))!!}
                                    </div>
                                
                               
                                    {!!Form::label('email', 'Email *:', array('class' => 'control-label'))!!}
                                    <div class="controls">
                                        {!! Form::email('email',  isset($user_data['email']) ? $user_data['email'] : '' , array('class'=>'form-control','id'=>'email'))!!}
                                    </div>
                                
                                    {!! Form::submit('Create', array('class'=>'btn btn-large btn-success text-left','id'=>'createVendorSaveButton'))!!}
                                    {!! Form::button('Cancel', array('class'=>'btn btn-large btn-info text-right','id'=>'createVendorCancelButton'))!!}
                            </div>

                            </div>
                        </div> 
               
                </form>
            </div>
        </div>





    </div>

</div>
<!-- end: Content -->
@stop