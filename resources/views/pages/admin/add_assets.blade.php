@extends('layouts.default')

@section('content')

<div id="content" class="span11">



    <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2><span class="break"></span>Add Property</h2>

            </div>

            <div class="box-content custome-form">

                @if (Session::has('message'))

                <div class="alert alert-success">{!! Session::get('message') !!}</div>

                @endif



                @if($errors->has())

                @foreach ($errors->all() as $error)

                <div class="alert alert-error">{!! $error !!}</div>

                @endforeach

                @endif





                {!! Form::open(array('action' => 'AssetController@addAdminAsset', 'class'=>'form-horizontal')) !!}

                <fieldset class="fieldBox">

                    <div class="box-header" data-original-title>

                        <h2><span class="break"></span>Property Details</h2>

                    </div>

                    

                    <div class="row-fluid frstStep accrodSlid">

                        <div class="clearfix">

                        <div class="span5">

                            <div class="control-group">

                                {!!Form::label('asset_number', 'Property ID: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('asset_number', '', array('class'=>'span12 typeahead', 'id'=>'asset_number' ))!!}

                                    {!!Form::hidden('one_column','no')!!}

                                </div>

                            </div>



                            <div class="control-group">

                                <div class="controls">

                                    {!!Form::button('Generate Number', array('id'=>'generate_asset_number', 'class'=> 'btn btn-success'))!!}

                                </div>

                            </div>

                           



                            <div class="control-group">

                                {!!Form::label('property_address', 'Property Address: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('property_address', '', array('placeholder'=>'Enter full property address', 'class'=>'span12 typeahead', 'id'=>'property_address'))!!}

                                </div>

                            </div>





                            <div class="control-group dmState">

                                {!!Form::label('state_id', 'State: *', array('class' => 'control-label '))!!}

                                <div class="controls">





                                    <?php

                                    $states_data = array('' => 'Select State');

                                    foreach ($states as $state) {

                                        $states_data[$state['id']] = $state['name'];

                                    }

                                    ?>

                                    {!! Form::select('state_id',  $states_data , '', array('class'=>'span8 typeahead','id'=>'state_id', 'data-rel'=>'chosen'))!!}

                                </div>

                            </div>

                            <div class="control-group dmState">

                                {!!Form::label('city_id', 'City: *', array('class' => 'control-label'))!!}

                                <?php

                                $cities_data = array('' => 'Select City');

                                $cities = City::getCitiesByStateId(Request::old('state_id'));

                                foreach ($cities as $city) {

                                    $cities_data[$city['id']] = $city['name'];

                                }

                                ?>

                                <div class="controls">

                                    {!! Form::select('city_id',  $cities_data ,  '',array('class'=>'span12 typeahead','id'=>'city_id', 'data-rel'=>'chosen'))!!}

                                </div>

                            </div>

                            <div class="control-group">

                                {!!Form::label('zip', 'Zip: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('zip', '', array('class'=>'span12 typeahead', 'id'=> 'zip'))!!}                                     </div>

                            </div>

                            <div class="control-group">

                                {!!Form::label('lock_box', 'Lock Box: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('lock_box', '', array('class'=>'span12 typeahead', 'id'=>'lock_box'))!!}

                                </div>

                            </div>

                            <div class="control-group">

                                {!!Form::label('access_code', 'Gate/ Access Code: ', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('access_code', '', array('class'=>'span12 typeahead', 'id'=>'access_code'))!!}                                    </div>

                            </div>



                            <div class="control-group">

                                {!!Form::label('agent', 'Customer Contact: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('agent', '', array('class'=>'span12 typeahead', 'id'=>'agent'))!!}

                                </div>

                            </div>

                                          <div class="control-group">

                                {!!Form::label('outbuilding_shed', 'Outbuilding/ Shed: *', array('class'=>'control-label'))!!}

                                <div class="controls" style="dis">

                                    <label class="radio">

                                  

                                        {!! Form::radio('outbuilding_shed',  '1', false,  array('id'=>'outbuilding_shed'))!!}

                                        Yes&nbsp;&nbsp;&nbsp;&nbsp;

                                        {!! Form::radio('outbuilding_shed',  '0', false,  array('id'=>'outbuilding_shed'))!!}

                                        No

                                    </label>



                                     

                                   

                                </div>

                                 <div style=" margin-left: 184px;">

                                    {!!Form::textarea('outbuilding_shed_note', '', array('class'=>'span5', 'id'=>'outbuilding_shed_note', 'rows'=>'7','placeholder'=>"Notes" ,'style'=>'width: 264px;' ))!!}

                                </div>



                                <div style="clear:both"></div>

                            </div>

                             





                        </div>

                        <!--/span-6-->

                        <div class="span5">

                            <div class="control-group">

                                {!!Form::label('loan_number', 'Loan Number: ', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('loan_number', '', array('class'=>'span12 typeahead', 'id'=>'loan_number'))!!}

                                    <!--                                    {!!Form::text('loan_number_date', '', array('class'=>'input-xlarge span4 datepicker', 'id'=>'loan_number_date'))!!}-->

                                </div>

                            </div>
                            <div class="control-group">
                                {!!Form::label('lender', 'Lender: ', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('Lender', '', array('class'=>'span12 typeahead', 'id'=>'Lender'))!!}
                                    <!--                                    {!!Form::text('loan_number_date', '', array('class'=>'input-xlarge span4 datepicker', 'id'=>'loan_number_date'))!!}-->
                                </div>
                            </div>

                            <div class="control-group">

                                {!!Form::label('property_type', 'Property Type: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    <?php $option_type = array('single-family' => 'Single Family', 'condo' => 'Condo', 'multi-family' => 'Multi Family','modular'=>'Modular') ?>

                                    {!! Form::select('property_type', $option_type, '', array('class'=>'span12', 'id'=>'property_type', 'data-rel'=>'chosen'))!!}

                                </div>

                            </div>

                            

                            <div class="control-group">

                                {!!Form::label('property_status', 'Property Status: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    <?php $option = array('active' => 'Active', 'inactive' => 'Inactive', 'in-rehab' => 'In-Rehab') ?>

                                    {!! Form::select('property_status', $option, '', array('class'=>'span12', 'id'=>'property_status',  'data-rel'=>'chosen'))!!}

                                </div>

                            </div>



                                 <div class="control-group " id="property_status_note" style="display:none;">

                            {!!Form::label('property_status_note', 'Property Status Note: *', array('class'=>'control-label'))!!}

                            <div class="controls">

                                {!!Form::textarea('property_status_note', '', array('class'=>'span5', 'id'=>'property_status_note', 'rows'=>'7','style'=>'width: 340px;'))!!}

                            </div>

                           </div>





                            <div class="control-group inLog">

                            {!!Form::label('special_direction_note', 'Direction or Special Note: ', array('class'=>'control-label'))!!}

                            <div class="controls">

                                {!!Form::textarea('special_direction_note', '', array('class'=>'span5', 'id'=>'special_direction_note', 'rows'=>'7','style'=>'width: 340px;'))!!}

                            </div>

                           </div>





                            <div class="control-group" >

                            {!!Form::label('occupancy_status', 'Occupancy Status: *', array('class'=>'control-label'))!!}

                            <div class="controls"style="display:inline; margin-left:5px;" >

                                <label class="radio"  >

                                &nbsp;&nbsp;&nbsp;&nbsp;

                                    {!! Form::radio('occupancy_status',  'Vacant', false,  array('id'=>'occupancy_status'))!!}

                                    Vacant&nbsp;&nbsp;&nbsp;&nbsp;

                              

                              

                                    {!! Form::radio('occupancy_status',  'Occupied', false,  array('id'=>'occupancy_status'))!!}

                                    Occupied

                                </label>

                               

                            </div>

                            <div style="clear:both"></div>

                            </div>

                            <div class="control-group inLog">

                              {!!Form::label('utility_note', 'Occupancy Status Note:', array('class'=>'control-label'))!!}

                           

                                 <div class="controls row">

                                {!!Form::textarea('occupancy_status_note', '', array('class'=>'span5', 'id'=>'occupancy_status_note', 'rows'=>'7','placeholder'=>"Please enter tenant contact information" ,'style'=>'width: 300px;'))!!}

                                </div>

                            </div>

                              <div class="control-group">

                                {!!Form::label('UNIT', 'Unit #:', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('UNIT', '', array('class'=>'span12 typeahead', 'id'=>'UNIT'))!!}

                                </div>

                            </div>

                 

                        </div>

                        </div>

                        <div class="break"></div>

                        <div class="control-group" id="maparea">  </div>

                        <!--/span-6-->

                    </div>

                        <div class="box-header" data-original-title>

                            <h2><span class="break"></span>Customer Details</h2>

                        </div>

                        <div class="row-fluid secndStep accrodSlid">



                         <div class="control-group lbl">

                                {!!Form::label('customer', 'Select Customer: ', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    <?php

                                    $customers_data = array('' => 'Select Client Type');

                                    $customers = User::getCustomers();

                                    foreach ($customers as $customer) {

                                        $customers_data[$customer['id']] = $customer['first_name'] . ' ' . $customer['last_name'];

                                    }

                                    ?>

                                    {!! Form::select('customer_id', $customers_data, '', array('style'=>'width:150px;', 'class'=>'span12', 'id'=>'customer_id',  'data-rel'=>'chosen', 'onchange'=>'populateCompany(this.value)'))!!}

                                </div>

                            </div>

                             <div class="control-group lbl">

                                {!!Form::label('customer_type', 'Client Type: ', array('class'=>'control-label'))!!}

                              

                            <div class="controls">

                                        <?php 

                                        $customer_type=array();

                                        foreach ($customerType as $key => $value) {

                                              $customer_type[$value->id]=$value->title; 

                                        }



                           

                                        // $customer_type=array(

                                        //     "Freddie Mac"=>"Freddie Mac",

                                        //     "VRM"=>"VRM",

                                        //     "City of Plano"=>"City of Plano",

                                        //     "City of Allen"=>"City of Allen",

                                        //     "Broker Direct"=>"Broker Direct"

                                        //     );

                                            ?>

                            {!! Form::select('customer_type',   $customer_type ,  '7', array('style'=>'width:150px;','class'=>'span8 typeahead','id'=>'customer_type', 'data-rel'=>'chosen'))!!}

                                            

                            </div>

                            </div>

                        <div class="control-group lbl">

                                {!!Form::label('brokage', 'Customer Company: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('brokage', '', array('class'=>'span5 typeahead', 'id'=>'brokage'))!!}

                                </div>

                        </div>

                           <div class="control-group lbl">

                                {!!Form::label('customer_email_address', 'Customer Email Address: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('customer_email_address', '', array('class'=>'span5 typeahead', 'id'=>'customer_email_address'))!!}

                                </div>

                            </div>

                          <!--   <div class="control-group lbl">

                                {!!Form::label('carbon_copy_email', 'Send Carbon Copy Email To: *', array('class'=>'control-label'))!!}

                                <div class="controls">

                                    {!!Form::text('carbon_copy_email', '', array('class'=>'span5 typeahead', 'id'=>'carbon_copy_email'))!!}

                                </div>

                               

                            </div> -->

              

                        </div>

                        <div class="box-header" data-original-title>

                            <h2><span class="break"></span>Utility - On inside Property</h2>

                        </div>

                    <div class="control-group thirdStep accrodSlid">

                        {!!Form::label('electric_status', 'Electric: *', array('class'=>'control-label'))!!}

                        <div class="controls">

                            <label class="radio">

                                {!! Form::radio('electric_status',  '1', false,  array('id'=>'electric_status'))!!}                                    Yes

                            </label>

                            <label class="radio rdBtn">

                                {!! Form::radio('electric_status',  '0', false,  array('id'=>'electric_status'))!!}                                          No

                            </label>

                        </div>

                        <div style="clear:both"></div>

                        {!!Form::label('water_status', 'Water: *', array('class'=>'control-label'))!!}

                        <div class="controls">

                            <label class="radio">

                                {!! Form::radio('water_status',  '1', false,  array('id'=>'water_status'))!!}                                          Yes

                            </label>

                            <label class="radio rdBtn">

                                {!! Form::radio('water_status',  '0', false,  array('id'=>'water_status'))!!}                                     No

                            </label>

                        </div>

                        <div style="clear:both"></div>

                        {!!Form::label('gas_status', 'Gas: *', array('class'=>'control-label'))!!}

                        <div class="controls">

                            <label class="radio">

                                {!! Form::radio('gas_status',  '1', false,  array('id'=>'gas_status'))!!}                                     Yes

                            </label>

                            <label class="radio rdBtn">

                                {!! Form::radio('gas_status',  '0', false,  array('id'=>'gas_status'))!!}

                                No

                            </label>

                            <div style="clear:both"></div>

                        </div>

                        <div class="control-group hidden-phone">

                            {!!Form::label('utility_note', 'Utility Note:', array('class'=>'control-label'))!!}

                            <div class="controls row">

                                {!!Form::textarea('utility_note', '', array('class'=>'span5', 'id'=>'utility_note', 'rows'=>'7'))!!}                                </div>

                        </div>



                        <div class="control-group">

                            {!!Form::label('swimming_pool', 'Swimming: ', array('class'=>'control-label'))!!}

                            <div class="controls">

                                <?php $option_pool = array('None'=>'None','pool' => 'Pool', 'spa' => 'Spa', 'koi-pond' => 'Koi Pond', 'pond' => 'Pond') ?>

                                {!! Form::select('swimming_pool', $option_pool, '', array('class'=>'span12', 'id'=>'swimming_pool', 'data-rel'=>'chosen' ,'style' => 'width:380px'))!!}

                            </div>

                        </div>



                    </div>





                    <div class="form-actions text-right">

                        {!!Form::hidden('submitted','1')!!}

                        {!!Form::submit('Save Property', array('name'=>'save_continue', 'class'=>'btn btn-success'))!!}

                        {!!Form::button('Cancel', array('class' => 'btn btn-inverse', 'onClick' =>'location.href="'.URL::to('list-assets').'"'))!!}



                    </div>

            </div>



            </fieldset>

            {!! Form::close() !!}



        </div>

    </div><!--/span-->



</div>

@stop