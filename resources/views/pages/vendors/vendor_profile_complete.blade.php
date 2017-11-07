@extends('layouts.onecolumn')
@section('content')
<title>GSS - Complete Profile - Step 1</title>
<div id="content">

    {!! Form::open(array('url' => 'vendor-profile-add', 'files'=>true,  'class'=>'form-horizontal')) !!}

    <div class="complete-profile-step-1">
        <div class="row">
            <div class="col-md-5 col-lg-5 col-sm-12">
                <h2>
                    @if(isset($user_detail['first_name']))
                        Welcome, {!!  $user_detail['first_name'] !!}
                    @endif
                    @if(isset($user_detail['last_name']))
                        @if(!isset($user_detail['first_name']))
                           Welcome,
                        @endif
                        {!! $user_detail['last_name'] !!}
                    @endif
                </h2>

                <p>Before you can begin, please complete your profile. To begin, enter your desired username and password below, then click "Next"</p>

                <div class="row">
                    <div class="col-md-8 col-lg-8 col-sm-12">
                        {!!Form::label('username', 'Username', array('class' => 'control-label'))!!}
                        <div class="form-group">
                            @if($user_detail['username'])
                                {!! Form::text('username',  isset($user_detail['username']) ? $user_detail['username'] : '' , array('class'=>'form-control','id'=>'username', 'readonly'=>'true'))!!}
                                {!! Form::hidden('create_by_admin', 'no')!!}
                            @else
                                {!! Form::text('username',  isset($user_detail['username']) ? $user_detail['username'] : '' , array('class'=>'form-control','id'=>'username'))!!}
                                {!! Form::hidden('create_by_admin', 'yes')!!}
                            @endif
                        </div>

                        {!!Form::label('password', 'Password', array('class'=>'control-label', 'for'=>'typeahead'))!!}
                        <div class="form-group">
                            {!! Form::password('password',  array('class'=>'form-control','id'=>'password'))!!}
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-success pull-right" id="vendor-step-1-next">Next</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="complete-profile-step-2">
        <div class="row">
            <div class="col-md-5 col-lg-5 col-sm-12">
                <h2>Vendor Profile Completion - Step 2</h2>

                {!!Form::label('address_1', 'Address 1', array('class' => 'control-label'))!!}
                <div class="form-group">
                    {!! Form::text('address_1', isset($user_detail['address_1']) ? $user_detail['address_1'] : ''  , array('class'=>'form-control','id'=>'address_1'))!!}
                </div>

                {!!Form::label('address_2', 'Address 2', array('class' => 'control-label'))!!}
                <div class="form-group">
                    {!! Form::text('address_2', isset($user_detail['address_2']) ? $user_detail['address_2'] : '' , array('class'=>'form-control','id'=>'address_2'))!!}
                </div>

                <?php
                    $states_data = array('0' => 'Select State');
                    foreach ($states as $state) {
                        $states_data[$state['id']] = $state['name'];
                    }
                ?>

                {!!Form::label('state_id', 'State', array('class' => 'control-label '))!!}
                <div class="form-group">
                    {!! Form::select('state_id', $states_data , isset($user_detail['state_id']) ? $user_detail['state_id'] : '0', array('class'=>'form-control','id'=>'state_id', 'data-rel'=>'chosen'))!!}
                </div>

                <?php
                    $city_data = array('' => 'Select City');
                    $cities = \App\City::getCitiesByStateId(Request::old('state_id'));
                    foreach ($cities as $city) {
                        $city_data[$city['id']] = $city['name'];
                    }
                ?>

                {!!Form::label('city_id', 'City', array('class' => 'control-label first-label'))!!}
                <div class="form-group">
                    {!! Form::select('city_id', $city_data , isset($user_detail['city_id']) ? $user_detail['city_id'] : '0' , array('class'=>'form-control','id'=>'city_id', 'data-rel'=>'chosen' ))!!}
                </div>

                {!!Form::label('zipcode', 'Zip ', array('class' => 'control-label '))!!}
                <div class="form-group">
                    {!! Form::text('zipcode', isset($user_detail['zipcode']) ? $user_detail['zipcode'] : '' , array('class'=>'form-control','id'=>'zipcode'))!!}
                </div>

                {!!Form::label('company', 'Company', array('class' => 'control-label'))!!}
                <div class="form-group">
                    {!! Form::text('company',  isset($user_detail['company']) ? $user_detail['company'] : '' ,  array('class'=>'form-control','id'=>'company'))!!}
                </div>

                {!!Form::label('phone', 'Phone', array('class' => 'control-label'))!!}
                <div class="form-group">
                    {!! Form::text('phone', isset($user_detail['phone']) ? $user_detail['phone'] : '' , array('class'=>'form-control','id'=>'phone'))!!}
                </div>

                {!!Form::label('email', 'Email', array('class' => 'control-label'))!!}
                <div class="form-group">
                    {!! Form::email('email',  isset($user_detail['email']) ? $user_detail['email'] : '' , array('class'=>'form-control','id'=>'email', 'readonly'=>'true'))!!}
                </div>

                <div class="form-group pull-right">
                    <button type="button" class="btn btn-info" id="vendor-step-2-back">Back</button>
                    <button type="button" class="btn btn-success" id="vendor-step-2-next">Next</button>
                </div>
            </div>
        </div>
    </div>

    <div class="complete-profile-step-3">
        <div class="row">
            <div class="col-md-5 col-lg-5 col-sm-12">
                <h2>Vendor Profile Completion - Step 3</h2>
                <div class="form-group">
                    {!! Form::label('zip_list', 'Service Zip Codes (Comma Seperated)') !!}
                    {!! Form::text('zip_list', '', array('class' => 'form-control', 'id' => 'zip_list')) !!}
                </div>


                <div class="form-group">
                    {!! Form::label('vendor_services', 'Vendor Services') !!}
                    <select class="chosen" id="vendor_services" data-rel="chosen" multiple="true" name="vendor_services[]" >
                        {!! $vendor_services !!}
                    </select>
                </div>

            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>


@stop