@extends('layouts.default')

@section('content')
<title>GSS - Add Property</title>
<div id="content" class="span11">
    <h4>Add Property</h4>

            </div>

            <div class="box-content custome-form">

                @if (Session::has('message'))

                <div class="alert alert-success">{!! Session::get('message') !!}</div>

                @endif



                @if($errors->any())

                @foreach ($errors->all() as $error)

                <div class="alert alert-error">{!! $error !!}</div>

                @endforeach

                @endif
            </div>

            {!! Form::open(array('action' => 'AssetController@addAdminAsset', 'class'=>'form-horizontal', 'id'=>'add-asset-form')) !!}

            <div class='row'>
                <div class='col-md-4 col-lg-4 col-sm-12'>
                
                    {!!Form::label('asset_number', 'Property ID: *', array('class'=>'control-label'))!!}
                    <div class='form-group'>
                        {!!Form::text('asset_number', '', array('class'=>'form-control', 'id'=>'asset_number' ))!!}
                        {!!Form::hidden('one_column','no')!!}
                        {!!Form::button('Generate Number', array('id'=>'generate_asset_number', 'class'=> 'btn btn-success'))!!}
                    </div>

                    {!!Form::label('property_address', 'Property Address: *', array('class'=>'control-label'))!!}
                    <div class='form-group'>
                        {!!Form::text('property_address', '', array('placeholder'=>'Enter full property address', 'class'=>'form-control', 'id'=>'property_address'))!!}
                    </div>

                    {!!Form::label('UNIT', 'Unit #:', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::text('UNIT', '', array('class'=>'form-control', 'id'=>'UNIT'))!!}
                    </div>

                    {!!Form::label('state_id', 'State: *', array('class' => 'control-label '))!!}
                    <div class='form-group'>
                        <?php

                            $states_data = array('' => 'Select State');
                            foreach ($states as $state) {
                                $states_data[$state['id']] = $state['name'];
                            }
                        ?>
                        {!! Form::select('state_id',  $states_data , '', array('class'=>'form-control','id'=>'state_id', 'data-rel'=>'chosen'))!!}
                    </div>

                    {!!Form::label('city_id', 'City: *', array('class' => 'control-label'))!!}
                    <div class='form-group'>
                        <?php

                            $cities_data = array('' => 'Select City');
                            $cities = \App\City::getCitiesByStateId(Request::old('state_id'));
                            foreach ($cities as $city) {
                                $cities_data[$city['id']] = $city['name'];
                            }

                        ?>
                        {!! Form::select('city_id',  $cities_data ,  '',array('class'=>'form-control','id'=>'city_id', 'data-rel'=>'chosen'))!!}
                    </div>

                    {!!Form::label('zip', 'Zip: *', array('class'=>'control-label'))!!}
                    <div class='form-group'>
                        {!!Form::text('zip', '', array('class'=>'form-control', 'id'=> 'zip'))!!}
                    </div>

                    {!!Form::label('lock_box', 'Lock Box: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::text('lock_box', '', array('class'=>'form-control', 'id'=>'lock_box'))!!}
                    </div>

                    {!!Form::label('access_code', 'Gate/ Access Code: ', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::text('access_code', '', array('class'=>'form-control', 'id'=>'access_code'))!!}  
                    </div>

                    {!!Form::label('agent', 'Customer Contact: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::text('agent', '', array('class'=>'form-control', 'id'=>'agent'))!!}
                    </div>



                    {!!Form::label('loan_number', 'Loan Number: ', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::text('loan_number', '', array('class'=>'form-control', 'id'=>'loan_number'))!!}
                    </div>

                    {!!Form::label('lender', 'Lender: ', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::text('Lender', '', array('class'=>'form-control', 'id'=>'Lender'))!!}
                    </div>

                    {!!Form::label('property_type', 'Property Type: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        <?php $option_type = array('single-family' => 'Single Family', 'condo' => 'Condo', 'multi-family' => 'Multi Family','modular'=>'Modular') ?>
                        {!! Form::select('property_type', $option_type, '', array('class'=>'form-control', 'id'=>'property_type', 'data-rel'=>'chosen'))!!}
                    </div>

                    {!!Form::label('property_status', 'Property Status: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        <?php $option = array('active' => 'Active', 'inactive' => 'Inactive', 'in-rehab' => 'In-Rehab') ?>
                        {!! Form::select('property_status', $option, '', array('class'=>'form-control', 'id'=>'property_status',  'data-rel'=>'chosen'))!!}
                    </div>
                    
                    {!!Form::label('customer', 'Select Customer: ', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        <?php
                            $customers_data = array('' => 'Select Client Type');
                            $customers = \App\User::getCustomers();
                            foreach ($customers as $customer) {
                                $customers_data[$customer['id']] = $customer['first_name'] . ' ' . $customer['last_name'];
                            }
                        ?>
                        {!! Form::select('customer_id', $customers_data, '', array('class'=>'form-control', 'id'=>'customer_id',  'data-rel'=>'chosen', 'onchange'=>'populateCompany(this.value)'))!!}
                    </div>

                    {!!Form::label('customer_type', 'Client Type: ', array('class'=>'control-label'))!!}
                    <div class="form-group">

                        <?php

                            $customer_type=array();
                            foreach ($customerType as $key => $value) {
                                    $customer_type[$value->id]=$value->title;
                            }
                        ?>

                        {!! Form::select('customer_type',   $customer_type ,  '7', array('class'=>'form-control','id'=>'customer_type', 'data-rel'=>'chosen'))!!}

                    </div>

                    {!!Form::label('brokage', 'Customer Company: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::text('brokage', '', array('class'=>'form-control', 'id'=>'brokage'))!!}
                    </div>
                    
                    {!!Form::label('customer_email_address', 'Customer Email Address: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::text('customer_email_address', '', array('class'=>'form-control', 'id'=>'customer_email_address'))!!}
                    </div>



                    {!!Form::label('swimming_pool', 'Swimming: ', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        <?php $option_pool = array('None'=>'None','pool' => 'Pool', 'spa' => 'Spa', 'koi-pond' => 'Koi Pond', 'pond' => 'Pond') ?>
                        {!! Form::select('swimming_pool', $option_pool, '', array('class'=>'form-control', 'id'=>'swimming_pool'))!!}
                    </div>

                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    {!!Form::label('outbuilding_shed', 'Outbuilding/ Shed: *', array('class'=>'control-label'))!!}
                    <div class="form-group">                      
                        <label class="radio">

                            {!! Form::radio('outbuilding_shed',  '1', false,  array('id'=>'outbuilding_shed'))!!}
                            Yes
                        </label>

                        <label class="radio">
                            {!! Form::radio('outbuilding_shed',  '0', false,  array('id'=>'outbuilding_shed'))!!}
                            No
                        </label>

                        {!!Form::textarea('outbuilding_shed_note', '', array('class'=>'form-control', 'id'=>'outbuilding_shed_note', 'rows'=>'7','placeholder'=>"Notes" ))!!}
                    </div>

                    {!!Form::label('property_status_note', 'Property Status Note: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::textarea('property_status_note', '', array('class'=>'form-control', 'id'=>'property_status_note', 'rows'=>'7'))!!}
                    </div>

                    {!!Form::label('special_direction_note', 'Direction or Special Note: ', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::textarea('special_direction_note', '', array('class'=>'form-control', 'id'=>'special_direction_note', 'rows'=>'7','style'=>'width: 340px;'))!!}
                    </div>
                    {!!Form::label('electric_status', 'Electric: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        <label class="radio">
                            {!! Form::radio('electric_status',  '1', false,  array('id'=>'electric_status'))!!}                                    Yes
                        </label>
                        <label class="radio rdBtn">
                            {!! Form::radio('electric_status',  '0', false,  array('id'=>'electric_status'))!!}                                          No
                        </label>
                    </div>

                    {!!Form::label('water_status', 'Water: *', array('class'=>'control-label'))!!}

                    <div class="form-group">

                        <label class="radio">
                            {!! Form::radio('water_status',  '1', false,  array('id'=>'water_status'))!!}                                          Yes
                        </label>

                        <label class="radio rdBtn">
                            {!! Form::radio('water_status',  '0', false,  array('id'=>'water_status'))!!}                                     No
                        </label>

                    </div>

                    {!!Form::label('gas_status', 'Gas: *', array('class'=>'control-label'))!!}
                    <div class="form-group">

                        <label class="radio">
                            {!! Form::radio('gas_status',  '1', false,  array('id'=>'gas_status'))!!}                                     Yes
                        </label>

                        <label class="radio rdBtn">
                            {!! Form::radio('gas_status',  '0', false,  array('id'=>'gas_status'))!!}
                            No
                        </label>

                    </div>

                    {!!Form::label('utility_note', 'Utility Note:', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::textarea('utility_note', '', array('class'=>'form-control', 'id'=>'utility_note', 'rows'=>'7'))!!}
                    </div>


                    {!!Form::label('occupancy_status', 'Occupancy Status: *', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        <label class="radio">
                            {!! Form::radio('occupancy_status',  'Vacant', false,  array('id'=>'occupancy_status'))!!}
                            Vacant
                        </label>
                        <label class="radio">
                            {!! Form::radio('occupancy_status',  'Occupied', false,  array('id'=>'occupancy_status'))!!}
                            Occupied
                        </label>
                    </div>

                    {!!Form::label('utility_note', 'Occupancy Status Note:', array('class'=>'control-label'))!!}
                    <div class="form-group">
                        {!!Form::textarea('occupancy_status_note', '', array('class'=>'form-control', 'id'=>'occupancy_status_note', 'rows'=>'7','placeholder'=>"Please enter tenant contact information" ,'style'=>'width: 300px;'))!!}
                    </div>
                    <div class="form-group">

                        {!!Form::hidden('submitted','1')!!}

                        {!!Form::submit('Save Property', array('name'=>'save_continue', 'class'=>'btn btn-success'))!!}

                        {!!Form::button('Cancel', array('class' => 'btn btn-inverse', 'onClick' =>'location.href="'.URL::to('list-assets').'"'))!!}

                    </div>
                </div>
            </div>






            {!! Form::close() !!}



        </div>

    </div><!--/span-->



</div>

@stop