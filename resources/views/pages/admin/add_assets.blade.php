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
                <div class='col-md-8 col-lg-7 col-sm-12'>

                    <table class="table table-small">
                        <tbody>
                            <tr>
                                <td>Property ID *</td>
                                <td>
                                    <div class='form-group'>
                                        {!!Form::text('asset_number', '', array('class'=>'form-control', 'id'=>'asset_number' ))!!}
                                        {!!Form::hidden('one_column','no')!!}
                                        {!!Form::button('Generate Number', array('id'=>'generate_asset_number', 'class'=> 'btn btn-success'))!!}
                                    </div>
                                </td>
                            </tr>
                            <tr>

                                <td>Property Address *</td>
                                <td>
                                    {!!Form::text('property_address', '', array('placeholder'=>'Enter full property address', 'class'=>'form-control', 'id'=>'property_address'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Unit #</td>
                                <td>
                                    {!!Form::text('UNIT', '', array('class'=>'form-control', 'id'=>'UNIT'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>State *</td>
                                <td>
                                    <?php

                                    $states_data = array('' => 'Select State');
                                    foreach ($states as $state) {
                                        $states_data[$state['id']] = $state['name'];
                                    }
                                    ?>
                                    {!! Form::select('state_id',  $states_data , '', array('class'=>'form-control','id'=>'state_id', 'data-rel'=>'chosen'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>City *</td>
                                <td>
                                    <?php

                                    $cities_data = array('' => 'Select City');
                                    $cities = \App\City::getCitiesByStateId(Request::old('state_id'));
                                    foreach ($cities as $city) {
                                        $cities_data[$city['id']] = $city['name'];
                                    }

                                    ?>
                                    {!! Form::select('city_id',  $cities_data ,  '',array('class'=>'form-control','id'=>'city_id', 'data-rel'=>'chosen'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Zip *</td>
                                <td>
                                    {!!Form::text('zip', '', array('class'=>'form-control', 'id'=> 'zip'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Lock Box *</td>
                                <td>
                                    {!!Form::text('lock_box', '', array('class'=>'form-control', 'id'=>'lock_box'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Access Code</td>
                                <td>
                                    {!!Form::text('access_code', '', array('class'=>'form-control', 'id'=>'access_code'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Loan Number</td>
                                <td>
                                    {!!Form::text('loan_number', '', array('class'=>'form-control', 'id'=>'loan_number'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Lender</td>
                                <td>
                                    {!!Form::text('Lender', '', array('class'=>'form-control', 'id'=>'Lender'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Property Type *</td>
                                <td>
                                    <?php $option_type = array('single-family' => 'Single Family', 'condo' => 'Condo', 'multi-family' => 'Multi Family','modular'=>'Modular') ?>
                                    {!! Form::select('property_type', $option_type, '', array('class'=>'form-control', 'id'=>'property_type', 'data-rel'=>'chosen'))!!}
                                </td>
                            </tr>

                            <tr>
                                <td>Select Customer</td>
                                <td>
                                    <?php
                                    $customers_data = array('' => 'Select Client Type');
                                    $customers = \App\User::getCustomers();
                                    foreach ($customers as $customer) {
                                        $customers_data[$customer['id']] = $customer['first_name'] . ' ' . $customer['last_name'];
                                    }
                                    ?>
                                    {!! Form::select('customer_id', $customers_data, '', array('class'=>'form-control', 'id'=>'customer_id',  'data-rel'=>'chosen', 'onchange'=>'populateCompany(this.value)'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Client Type</td>
                                <td>
                                    <?php

                                    $customer_type=array();
                                    foreach ($customerType as $key => $value) {
                                        $customer_type[$value->id]=$value->title;
                                    }
                                    ?>

                                    {!! Form::select('customer_type',   $customer_type ,  '7', array('class'=>'form-control','id'=>'customer_type', 'data-rel'=>'chosen'))!!}

                                </td>
                            </tr>
                            <tr>
                                <td>Customer Contact *</td>
                                <td>
                                    {!!Form::text('agent', '', array('class'=>'form-control', 'id'=>'agent'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Customer Company *</td>
                                <td>
                                    {!!Form::text('brokage', '', array('class'=>'form-control', 'id'=>'brokage'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Customer Email Address *</td>
                                <td>
                                    {!!Form::text('customer_email_address', '', array('class'=>'form-control', 'id'=>'customer_email_address'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Swimming</td>
                                <td>
                                    <?php $option_pool = array('None'=>'None','pool' => 'Pool', 'spa' => 'Spa', 'koi-pond' => 'Koi Pond', 'pond' => 'Pond') ?>
                                    {!! Form::select('swimming_pool', $option_pool, '', array('class'=>'form-control', 'id'=>'swimming_pool'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Outbuilding / Shed *</td>
                                <td>
                                    <div class="form-group">
                                        <label class="radio">

                                            {!! Form::radio('outbuilding_shed',  '1', false,  array('id'=>'outbuilding_shed'))!!}
                                            Yes
                                        </label>

                                        <label class="radio">
                                            {!! Form::radio('outbuilding_shed',  '0', false,  array('id'=>'outbuilding_shed'))!!}
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Outbuilding / Shed Note</td>
                                <td>
                                    {!!Form::textarea('outbuilding_shed_note', '', array('class'=>'form-control', 'id'=>'outbuilding_shed_note', 'rows'=>'7','placeholder'=>"Notes" ))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Property Status</td>
                                <td>
                                    <?php $option = array('active' => 'Active', 'inactive' => 'Inactive', 'in-rehab' => 'In-Rehab') ?>
                                    {!! Form::select('property_status', $option, '', array('class'=>'form-control', 'id'=>'property_status',  'data-rel'=>'chosen'))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Property Status Note *</td>
                                <td>
                                    {!!Form::textarea('property_status_note', '', array('class'=>'form-control', 'id'=>'property_status_note', 'rows'=>'7', 'placeholder'=>"Please enter any property status notes"))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Direction or Special Note:</td>
                                <td>
                                    {!!Form::textarea('special_direction_note', '', array('class'=>'form-control', 'id'=>'special_direction_note', 'rows'=>'7', 'placeholder'=>"Please enter any special notes or directions"))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Electric *</td>
                                <td>
                                    <div class="form-group">
                                        <label class="radio">
                                            {!! Form::radio('electric_status',  '1', false,  array('id'=>'electric_status'))!!}                                    Yes
                                        </label>
                                        <label class="radio rdBtn">
                                            {!! Form::radio('electric_status',  '0', false,  array('id'=>'electric_status'))!!}                                          No
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Water *</td>
                                <td>
                                    <div class="form-group">

                                        <label class="radio">
                                            {!! Form::radio('water_status',  '1', false,  array('id'=>'water_status'))!!}                                          Yes
                                        </label>

                                        <label class="radio rdBtn">
                                            {!! Form::radio('water_status',  '0', false,  array('id'=>'water_status'))!!}                                     No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Gas *</td>
                                <td>
                                    <div class="form-group">

                                        <label class="radio">
                                            {!! Form::radio('gas_status',  '1', false,  array('id'=>'gas_status'))!!}                                     Yes
                                        </label>

                                        <label class="radio rdBtn">
                                            {!! Form::radio('gas_status',  '0', false,  array('id'=>'gas_status'))!!}
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Utility Note</td>
                                <td>
                                    {!!Form::textarea('utility_note', '', array('class'=>'form-control', 'id'=>'utility_note', 'rows'=>'7', 'placeholder'=>"Enter utility information"))!!}
                                </td>
                            </tr>
                            <tr>
                                <td>Occupancy Status *</td>
                                <td>
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
                                </td>
                            </tr>
                            <tr>
                                <td>Occupancy Status Note</td>
                                <td>
                                    {!!Form::textarea('occupancy_status_note', '', array('class'=>'form-control', 'id'=>'occupancy_status_note', 'rows'=>'7','placeholder'=>"Please enter tenant contact information" ))!!}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    {!!Form::hidden('submitted','1')!!}

                                    {!!Form::submit('Save Property', array('name'=>'save_continue', 'class'=>'btn btn-success'))!!}

                                    {!!Form::button('Cancel', array('class' => 'btn btn-inverse', 'onClick' =>'location.href="'.URL::to('list-assets').'"'))!!}

                                </td>
                            </tr>
                        </tbody>
                    </table>


            {!! Form::close() !!}



        </div>

    </div><!--/span-->



</div>

@stop