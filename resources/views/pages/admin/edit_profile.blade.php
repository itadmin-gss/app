@extends('layouts.default')
@section('content')
<div id="content" class="span11">
{{--  
        <div id="profileSuccessMessage" class="">
        </div>
        <div id="profileValidationErrorMessage" class="">
        </div>
        <div id="profileErrorMessage" class="hide">
            <div class="alert alert-error">Can not Updated Profile</h4>
        </div>

        </div>
        @if($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-error">{!! $error !!}</div>
        @endforeach
        @endif  --}}


    <h4>Edit Profile</h4>
    {!! Form::open(array('url' => 'save-profile-admin', 'files'=>true, 'id'=>'UserEditForm' , 'class'=>'form-horizontal')) !!}
    <div class='row'>
        <div class='col-sm-12 col-md-4 col-lg-4 left-margin-10'>
            {!!Form::label('first_name', 'First Name', array('class' => 'control-label'))!!}    
            <div class='form-inline'> 
                    {!! Form::text('first_name',  isset($user_data['first_name']) ? $user_data['first_name'] : '' , array('class'=>'form-control','id'=>'firstName','readonly'=>'true'))!!}
                    {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editFirstName'))!!}
            </div>
           
            {!!Form::label('last_name', 'Last Name', array('class' => 'control-label'))!!}
            <div class='form-inline'>
                    {!! Form::text('last_name',  isset($user_data['last_name']) ? $user_data['last_name'] : '' , array('class'=>'form-control','id'=>'lastName','readonly'=>'true'))!!}
                    {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editLastName'))!!}
            </div>

            {!!Form::label('company', 'Company', array('class' => 'control-label'))!!}
            <div class='form-inline'>
                {!! Form::text('company',  isset($user_data['company']) ? $user_data['company'] : '' ,  array('class'=>'form-control','id'=>'company','readonly'=>'true'))!!}
                {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editCompany'))!!}
            </div>
            
            {!!Form::label('phone', 'Phone', array('class' => 'control-label'))!!}
            <div class='form-inline'>
                {!! Form::text('phone', isset($user_data['phone']) ? $user_data['phone'] : '' , array('class'=>'form-control','id'=>'phone','readonly'=>'true'))!!}
                {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editPhone'))!!}
            </div>

            {!!Form::label('address_1', 'Address 1', array('class' => 'control-label'))!!}
            <div class='form-inline'>
                {!! Form::text('address_1', isset($user_data['address_1']) ? $user_data['address_1'] : ''  , array('class'=>'form-control','id'=>'address1','readonly'=>'true'))!!}
                {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editAddress1'))!!}
            </div>

            {!!Form::label('address_2', 'Address 2', array('class' => 'control-label'))!!}
            <div class='form-inline'>
                {!! Form::text('address_2', isset($user_data['address_2']) ? $user_data['address_2'] : '' , array('class'=>'form-control','id'=>'address2','readonly'=>'true'))!!}
                {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editAddress2'))!!}
            </div>

                <?php
                    $states_data = array('0' => 'Select State');
                    foreach ($states as $state) {
                        $states_data[$state['id']] = $state['name'];
                    }
                ?>                                

            {!!Form::label('state_id', 'State', array('class' => 'control-label '))!!}    
            <div class='form-inline'>                                
                {!! Form::select('state_id', $states_data , isset($user_data['state_id']) ? $user_data['state_id'] : '0', array('class'=>'form-control','id'=>'state_id', 'data-rel'=>'chosen'))!!}
            </div>

                <?php
                    $city_data = array('' => 'Select City');
                    $cities = \App\City::getCitiesByStateId($user_data['state_id']);
                    foreach ($cities as $city) {
                        $city_data[$city['id']] = $city['name'];
                    }
                ?>
                {!!Form::label('city_id', 'City', array('class' => 'control-label first-label'))!!}
                <div class="form-inline">
                    {!! Form::select('city_id', $city_data , isset($user_data['city_id']) ? $user_data['city_id'] : '0' , array('class'=>'form-control','id'=>'city_id', 'data-rel'=>'chosen'))!!}
                </div>       
                                    
                {!!Form::label('zipcode', 'Zip ', array('class' => 'control-label '))!!}
                <div class="form-inline">
                    {!! Form::text('zipcode', isset($user_data['zipcode']) ? $user_data['zipcode'] : '' , array('class'=>'form-control','id'=>'zipcode','readonly'=>'true'))!!}
                    {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editZipcode'))!!}
                </div>
                
                @if(Auth::user()->type_id == 1 || Auth::user()->type_id == 4)
                    <label>Change Password</label>
                    <div class='form-inline checkbox'>
                        {!!Form::checkbox('change_password','1',false,array('id'=>'changePassword', 'class' => 'form-control'))!!}
                    </div>



                    <div id="changePasswordControll" class="box hide">
                        <div class="form-group">
                            {!!Form::label('password', 'New Password: *', array('class'=>'control-label', 'for'=>'typeahead'))!!}
                            <div class="controls">
                                {!! Form::password('password', array('class'=>'form-control','id'=>'password'))!!}
                            </div>
                        </div>
                        <div class="control-group">
                            {!!Form::label('password_confirmation', 'Confirm New password: *', array('class'=>'control-label', 'for'=>'typeahead'))!!}
                            <div class="controls">
                                {!! Form::password('password_confirmation', array('class'=>'form-control','id'=>'password_confirmation'))!!}
                            </div>
                        </div>
                    </div>
                    @endif



            
        </div>
        <div class='col-sm-12 col-md-4 col-lg-4'>

            {!!Form::label('username', 'Username', array('class' => 'control-label'))!!}
            <div class='form-inline'>
                {!! Form::text('username',  isset($user_data['username']) ? $user_data['username'] : '' , array('class'=>'form-control','id'=>'username', 'readonly'=>'true'))!!}
                {!!Form::hidden('user_id', $user_data['id'], array('id' => 'user_id'))!!}
				{!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editUsername'))!!}
            </div>

            {!!Form::label('email', 'Email', array('class' => 'control-label'))!!}
            <div class='form-inline'>
                {!! Form::email('email',  isset($user_data['email']) ? $user_data['email'] : '' , array('class'=>'form-control','id'=>'email', 'readonly'=>'true'))!!}
                {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editEmail'))!!}
            </div>

            <div>
                {!!Form::label('fileInput', 'File', array('class' => 'control-label'))!!} 
                <span class="form-inline">
                    {!!Form::file('profile_picture', array('value'=>$user_data['profile_picture'],'class'=>'form-control', 'id'=>'fileInput'))!!}
                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                </span>
                
            </div>

            @if($user_data['type_id']==3)            
                {!!Form::label('office_notes', 'Office Notes', array('class' => 'control-label'))!!}
                {!! Form::textarea('office_notes',  isset($user_data['office_notes']) ? $user_data['office_notes'] : '' , array('class'=>'form-control','id'=>'office_notes'))!!}
            @endif

             @if($user_data->type_id==3 )
                
                <label>Vendor Services</label>
                <select class="form-control chosen" id="vendor_services" data-rel="chosen" multiple="true" name="vendor_services[]" >
                    {!! $servicesDATAoption!!}
                </select>
            @endif

            <label>Service Zipcodes(Comma seprated)</label>
            {!! Form::textarea('available_zipcodes', isset($user_data['available_zipcodes']) ? $user_data['available_zipcodes'] : '' , array('class'=>'form-control','id'=>'available_zipcodes'))!!}
            <div class="form-actions text-right top-margin-15">
                {!! Form::submit('Save', array('class'=>'btn btn-large btn-success text-left','id'=>'profileSaveButton'))!!}
                {!! Form::button('Cancel', array('class'=>'btn btn-large btn-inverse text-right','id'=>'profileCancelButton' ,'onClick' => 'location.href="'.URL::to('admin').'"'))!!}
            </div>

        </div>
    </div>





                        @if($user_data->type_id==3)
                        <div class="clearfix"></div>
                               <div class="controls control-group">

                              </div>
                              @endif


                            </div>




                        </fieldset>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div><!--/span-->

    </div><!--/row-->


</div>
@stop
