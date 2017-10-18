@extends('layouts.default')

@section('content')

<div id="content" class="span11">



    <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2><span class="break"></span>Profile Edit</h2>

            </div>

            <div class="box-content custome-form clearfix">



                <div class="row-fluid">

                    <div id="profileSuccessMessage" class="">

                    </div>

                    <div id="profileValidationErrorMessage" class="">

                    </div>

                    <div id="profileErrorMessage" class="hide">

                        <h4 class="alert alert-error">Error Updating Profile</h4>

                    </div>



                </div>

                @if($errors->any())

                @foreach ($errors->all() as $error)

                <div class="alert alert-error">{!! $error !!}</div>

                @endforeach

                @endif

                <div class="row-fluid">

                    <div class="span12">

                        {!! Form::open(array('url' => 'save-profile', 'files'=>true, 'id'=>'profileEditForm' , 'class'=>'form-horizontal')) !!}

                        <fieldset>

                            <div class="row-fluid">

                                <div class="span6">



                                    <div class="control-group">

                                        {!!Form::label('username', 'Username', array('class' => 'control-label'))!!}

                                        <div class="controls">

                                            @if($user_data['username'] == '' || !isset($user_data['username']))

                                            {!! Form::text('username',  isset($user_data['username']) ? $user_data['username'] : '' , array('class'=>'span10 typeahead','id'=>'username'))!!} 

                                             {!!Form::hidden('check_user_name', 'yes')!!}  



                                            @else

                                            {!! Form::text('username',  isset($user_data['username']) ? $user_data['username'] : '' , array('class'=>'span10 typeahead','id'=>'username',  'readonly'=>'true'))!!}

                                            {!!Form::hidden('check_user_name', 'no')!!}



                                            @endif



                                        </div>

                                    </div>



                                    <div class="control-group">

                                        {!!Form::label('email', 'Email', array('class' => 'control-label'))!!}

                                        <div class="controls">

                                            {!! Form::email('email',  isset($user_data['email']) ? $user_data['email'] : '' , array('class'=>'span10 typeahead','id'=>'email', 'readonly'=>'true'))!!}

                                        </div>

                                    </div>

                                    <div class="control-group">

                                        {!!Form::label('first_name', 'First Name', array('class' => 'control-label'))!!}

                                        <div class="controls">

                                            {!! Form::text('first_name',  isset($user_data['first_name']) ? $user_data['first_name'] : '' , array('class'=>'span10 typeahead','id'=>'firstName','readonly'=>'true'))!!}

                                            {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editFirstName'))!!}

                                        </div>

                                    </div>

                                    <div class="control-group">

                                        {!!Form::label('last_name', 'Last Name', array('class' => 'control-label'))!!}

                                        <div class="controls">

                                            {!! Form::text('last_name',  isset($user_data['last_name']) ? $user_data['last_name'] : '' , array('class'=>'span10 typeahead','id'=>'lastName','readonly'=>'true'))!!}

                                            {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editLastName'))!!}

                                        </div>

                                    </div>
                                                       @if($user_data['type_id']==2)
                               <!--      <div class="control-group">
                                        {{--  {!!Form::label('customer_type_id', 'Customer Type', array('class' => 'control-label'))!!}  --}}
                                        <div class="controls">
                                           <select name="customer_type_id" id="customer_type_id">
                                               <?php

                                               foreach($CustomerType as $scat)
                                               {
                                                  ?>
                                                  <option value="{!!$scat->id!!}" @if($user_data['customer_type_id']==$scat->id) selected="selected" @endif>{!!$scat->title!!}</option>
                                                  <?php
                                              }

                                              ?>

                                          </select>
                                      </div>
                                    </div>
 -->                                    @endif



                                    <div class="control-group">

                                        {!!Form::label('company', 'Company', array('class' => 'control-label'))!!}

                                        <div class="controls">

                                            {!! Form::text('company',  isset($user_data['company']) ? $user_data['company'] : '' ,  array('class'=>'span10 typeahead','id'=>'company','readonly'=>'true'))!!}

                                            {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editCompany'))!!}

                                        </div>

                                    </div>



                                    <div class="control-group">

                                        {!!Form::label('phone', 'Phone', array('class' => 'control-label'))!!}

                                        <div class="controls">

                                            {!! Form::text('phone', isset($user_data['phone']) ? $user_data['phone'] : '' , array('class'=>'span10 typeahead','id'=>'phone','readonly'=>'true'))!!}

                                            {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editPhone'))!!}

                                        </div>

                                    </div>



                                    <div class="control-group">



                                        {!!Form::label('address_1', 'Address 1', array('class' => 'control-label'))!!}

                                        <div class="controls">

                                            {!! Form::text('address_1', isset($user_data['address_1']) ? $user_data['address_1'] : ''  , array('class'=>'span10 typeahead','id'=>'address1','readonly'=>'true'))!!}

                                            {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editAddress1'))!!}

                                        </div>

                                    </div>



                                    <div class="control-group">

                                        {!!Form::label('address_2', 'Address 2', array('class' => 'control-label'))!!}

                                        <div class="controls">

                                            {!! Form::text('address_2', isset($user_data['address_2']) ? $user_data['address_2'] : '' , array('class'=>'span10 typeahead','id'=>'address2','readonly'=>'true'))!!}

                                            {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editAddress2'))!!}

                                        </div>

                                    </div>







                                </div>

                                <!--/span-4-->



                                <div class="span6 text-center">

                                    <div class="control-group">

                                        <div class="fileinput fileinput-new" data-provides="fileinput">

                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">

                                                @if($user_data['profile_picture'])

                                                {!! Html::image(Config::get('app.upload_path').$user_data['profile_picture']) !!}

                                                @endif

                                            </div>

                                            <div>

                                                <span class="btn btn-default btn-file">

                                                    {!!Form::file('profile_picture', array('value'=>$user_data['profile_picture'],'class'=>'input-file uniform_on', 'id'=>'fileInput'))!!}

                                                </span>

                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <!--/span-4-->



                            </div>

                            <!--/row-fluid-->



                            <div class="row-fluid custome-input">

                                <?php

                                $states_data = array('0' => 'Select State');

                                foreach ($states as $state) {

                                    $states_data[$state['id']] = $state['name'];

                                }

                                ?>

                                <div class="control-group  span4">



                                    {!!Form::label('state_id', 'State', array('class' => 'control-label '))!!}



                                    <div class="controls">

                                        {!! Form::select('state_id', $states_data , isset($user_data['state_id']) ? $user_data['state_id'] : '0', array('class'=>'span8 typeahead','id'=>'state_id', 'data-rel'=>'chosen'))!!}

                                    </div>

                                </div>



                                <div class="control-group span3">

                                    <?php

                                    $city_data = array('' => 'Select City');

                                    $cities = \App\City::getCitiesByStateId($user_data['state_id']);

                                    foreach ($cities as $city) {

                                        $city_data[$city['id']] = $city['name'];

                                    }

                                    ?>



                                    {!!Form::label('city_id', 'City', array('class' => 'control-label first-label'))!!}

                                    <div class="controls">

                                        {!! Form::select('city_id', $city_data , isset($user_data['city_id']) ? $user_data['city_id'] : '0' , array('style'=>'width:200px;' ,'class'=>'span7 typeahead','id'=>'city_id', 'data-rel'=>'chosen'))!!}



                                    </div>

                                </div>



                                <div class="control-group span3">

                                    {!!Form::label('zipcode', 'Zip ', array('class' => 'control-label '))!!}



                                    <div class="controls">

                                        {!! Form::text('zipcode', isset($user_data['zipcode']) ? $user_data['zipcode'] : '' , array('class'=>'span7 typeahead','id'=>'zipcode','readonly'=>'true'))!!}

                                        {!! Form::button('Edit', array('class'=>'btn btn-small btn-success','id'=>'editZipcode'))!!}

                                    </div>

                                </div>



                            </div>

                            <div class="row-fluid">

                                <div class="box">

                                    <div class="control-group">
                                         <?php if (false) {?>
                                        <label class="checkbox noPadding">

                                            <div class="checker" id="uniform-inlineCheckbox1"><span class="checked">

                                                    <div class="checker" id="uniform-inlineCheckbox1"><span>

                                                           {!!Form::checkbox('change_password','1',false,array('id'=>'changePassword'))!!}


                                                        </span></div>

                                                </span>

                                            </div>

                                            Change Password

                                        </label>
                                         <?php }else{

                                                            } ?>

                                    </div>

                                </div>

                            </div>





                            <div id="changePasswordControll" class="box hide">

                                <div class="control-group">

                                    {!!Form::label('current_password', 'Current Password: *', array('class'=>'control-label', 'for'=>'typeahead'))!!}

                                    <div class="controls">

                                        {!! Form::password('current_password', array('class'=>'span10 typeahead','id'=>'password'))!!}

                                    </div>

                                </div>

                                <div class="control-group">

                                    {!!Form::label('password', 'New Password: *', array('class'=>'control-label', 'for'=>'typeahead'))!!}

                                    <div class="controls">

                                        {!! Form::password('password',  array('class'=>'span10 typeahead','id'=>'password'))!!}

                                    </div>

                                </div>

                                <div class="control-group">

                                    {!!Form::label('password_confirmation', 'Confirm New password: *', array('class'=>'control-label', 'for'=>'typeahead'))!!}

                                    <div class="controls">

                                        {!! Form::password('password_confirmation',  array('class'=>'span10 typeahead','id'=>'password_confirmation'))!!}

                                    </div>

                                </div>

                            </div>


@if(Auth::user()->type_id==3)
                            <div class="row-fluid">
                            <h3>Vendor Services</h3>
                                <div class="box">


     <select class="span8 typeahead " id="vendor_services" data-rel="chosen" multiple="true" name="vendor_services[]" >
     {!! $servicesDATAoption!!}
     </select>

                                </div>
                            </div>
                            @endif
                      @if(Auth::user()->type_id==3)
                        <div class="clearfix"></div>
                               <div class="controls control-group" style="margin-left:0px;">
                                        Service Zipcodes(Comma seprated)
                                        <div class="control-group">
                                            {!! Form::textarea('available_zipcodes', isset($user_data['available_zipcodes']) ? $user_data['available_zipcodes'] : '' , array('class'=>'span10 typeahead','id'=>'available_zipcodes'))!!}

                                        </div>
                              </div>
                              @endif


                            <div class="form-actions text-right clearfix">

                                {!! Form::submit('Save', array('class'=>'btn btn-large btn-success text-left','id'=>'profileSaveButton'))!!}

                                {!! Form::button('Cancel', array('class'=>'btn btn-large btn-inverse text-right','id'=>'profileCancelButton'))!!}

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



