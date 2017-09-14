@extends('layouts.default')
@section('content')
<div id="content" class="span11">

    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                  <h2><span class="break"></span>Edit Property</h2>
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


              
                {{-- */$url='edit-asset/'.$asset_data->id;/* --}}
                {!! Form::open(array('url' => $url, 'class'=>'form-horizontal')) !!}
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
                                   {!!Form::text('asset_number', isset($asset_data['asset_number']) ? $asset_data['asset_number'] : '' , array('class'=>'span12 typeahead', 'id'=>'asset_number' ))!!}
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
                                       {!!Form::text('property_address', isset($asset_data['property_address']) ? $asset_data['property_address'] : '' , array('class'=>'span12 typeahead', 'id'=>'property_address'))!!}
                                                       </div>
                            </div>


                            <div class="control-group dmState">
                                {!!Form::label('state_id', 'State: *', array('class' => 'control-label '))!!}
                                <div class="controls">

 <?php
                                $states_data = array('0' => 'Select State');
                                foreach ($states as $state) {
                                    $states_data[$state['id']] = $state['name'];
                                }
                                ?>
                            
                            {!! Form::select('state_id',  $states_data,  isset($asset_data['state_id']) ? $asset_data['state_id'] : '0',   array('class'=>'span8 typeahead','id'=>'state_id'))!!}
                              </div>
                            </div>
                            <div class="control-group dmState">
                                {!!Form::label('city_id', 'City: *', array('class' => 'control-label'))!!}
                               <?php
                                $cities_data = array('' => 'Select City');
                                $cities = City::getCitiesByStateId($asset_data['state_id']);
                                foreach ($cities as $city) {
                                    $cities_data[$city['id']] = $city['name'];
                                }
                                ?>
                                <div class="controls">
                                    {!! Form::select('city_id',  $cities_data , isset($asset_data['city_id']) ? $asset_data['city_id'] : '0', array('class'=>'span8 typeahead','id'=>'city_id'))!!}

                                </div>
                            </div>
                            <div class="control-group">
                                {!!Form::label('zip', 'Zip: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('zip', isset($asset_data['zip']) ? $asset_data['zip'] : '', array('class'=>'span6 typeahead', 'id'=> 'zip'))!!}                                     </div>
                       </div>
                            <div class="control-group">
                                {!!Form::label('lock_box', 'Lock Box: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                     {!!Form::text('lock_box', isset($asset_data['lock_box']) ? $asset_data['lock_box'] : '', array('class'=>'span12 typeahead', 'id'=>'lock_box'))!!}
                                 </div>
                            </div>
                            <div class="control-group">
                                {!!Form::label('access_code', 'Gate/ Access Code: ', array('class'=>'control-label'))!!}
                                <div class="controls">
                                     {!!Form::text('access_code', isset($asset_data['access_code']) ? $asset_data['access_code'] : '', array('class'=>'span12 typeahead', 'id'=>'access_code'))!!}                                    </div>
                            </div>

                            <div class="control-group">
                                {!!Form::label('agent', 'Customer Contact: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('agent', isset($asset_data['agent']) ? $asset_data['agent'] : '', array('class'=>'span8 typeahead', 'id'=>'agent'))!!}
                              </div>
                            </div>
                                          <div class="control-group">
                                {!!Form::label('outbuilding_shed', 'Outbuilding/ Shed: *', array('class'=>'control-label'))!!}
                                <div class="controls" style="dis">
                                    <label class="radio">
                                  
                                        {!! Form::radio('outbuilding_shed',  '1',isset($asset_data['outbuilding_shed']) && $asset_data['outbuilding_shed']==1  ? true : '',  array('id'=>'outbuilding_shed'))!!}
                                        Yes&nbsp;&nbsp;&nbsp;&nbsp;
                                        {!! Form::radio('outbuilding_shed',  '0', isset($asset_data['outbuilding_shed']) && $asset_data['outbuilding_shed']==0  ? true : '',  array('id'=>'outbuilding_shed'))!!}
                                        No
                                    </label>

                                     
                                   
                                </div>
                                 <div style=" margin-left: 184px;">
                                     {!!Form::textarea('outbuilding_shed_note', isset($asset_data['outbuilding_shed_note']) ? $asset_data['outbuilding_shed_note'] : '', array('class'=>'span5', 'id'=>'outbuilding_shed_note', 'rows'=>'7'))!!}
                             </div>

                                <div style="clear:both"></div>
                            </div>
                             


                        </div>
                        <!--/span-6-->
                        <div class="span5">
                            <div class="control-group">
                                {!!Form::label('loan_number', 'Loan Number: ', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('loan_number', isset($asset_data['loan_number']) ? $asset_data['loan_number'] : '', array('class'=>'span8 typeahead', 'id'=>'loan_number'))!!}
                                 </div>
                            </div>
                            <div class="control-group">
                                {!!Form::label('property_type', 'Property Type: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                        <?php $option_type = array('0' => 'Select Property type', 'single-family' => 'Single Family', 'condo' => 'Condo', 'multi-family' => 'Multi Family') ?>
                                    {!! Form::select('property_type', $option_type, isset($asset_data['property_type']) ? $asset_data['property_type'] : '0', array('class'=>'span12', 'id'=>'property_type'))!!}
 </div>
                            </div>
                            
                            <div class="control-group">
                                {!!Form::label('property_status', 'Property Status: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                  <?php $option = array('active' => 'Active', 'inactive' => 'Inactive', 'closed' => 'Closed', 'in-rehab' => 'In-Rehab', 'onhold' => 'On Hold') ?>
                                    {!! Form::select('property_status', $option, isset($asset_data['property_status']) ? $asset_data['property_status'] : '', array('class'=>'span12', 'id'=>'property_status'))!!}
                                </div>
                            </div>

                                 <div class="control-group " id="property_status_note" style="display:none;">
                            {!!Form::label('property_status_note', 'Property Status Note: *', array('class'=>'control-label'))!!}
                            <div class="controls">
                                {!!Form::textarea('property_status_note', isset($asset_data['property_status_note']) ? $asset_data['property_status_note'] : '', array('class'=>'span5', 'id'=>'property_status_note', 'rows'=>'7','style'=>'width: 340px;'))!!}
                            </div>
                           </div>


                            <div class="control-group inLog">
                            {!!Form::label('special_direction_note', 'Direction or Special Note: ', array('class'=>'control-label'))!!}
                            <div class="controls">
                                {!!Form::textarea('special_direction_note', isset($asset_data['special_direction_note']) ? $asset_data['special_direction_note'] : '', array('class'=>'span5', 'id'=>'special_direction_note', 'rows'=>'7','style'=>'width: 340px;'))!!}
                            </div>
                           </div>


                            <div class="control-group" >
                            {!!Form::label('occupancy_status', 'Occupancy Status: *', array('class'=>'control-label'))!!}
                            <div class="controls"style="display:inline; margin-left:5px;" >
                                <label class="radio"  >
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                     {!! Form::radio('occupancy_status',  'Vacant',isset($asset_data['occupancy_status']) && $asset_data['occupancy_status']=="Vacant"  ? true : '',  array('id'=>'occupancy_status'))!!}
                                     Vacant&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              
                                    {!! Form::radio('occupancy_status',  'Occupied',isset($asset_data['occupancy_status']) && $asset_data['occupancy_status']=="Occupied"  ? true : '',  array('id'=>'occupancy_status'))!!}
                                    Occupied
                                </label>
                               
                            </div>
                            <div style="clear:both"></div>
                            </div>
                            <div class="control-group inLog">
                              {!!Form::label('utility_note', 'Occupancy Status Note:', array('class'=>'control-label'))!!}
                           
                                 <div class="controls row">
                               {!!Form::textarea('occupancy_status_note', isset($asset_data['occupancy_status_note']) ? $asset_data['occupancy_status_note'] : '', array('class'=>'span5', 'id'=>'occupancy_status_note', 'rows'=>'7'))!!}
                             </div>
                            </div>
                              <div class="control-group">
                                {!!Form::label('UNIT', 'Unit #:', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('UNIT', isset($asset_data['UNIT']) ? $asset_data['UNIT'] : '', array('class'=>'span8 typeahead', 'id'=>'UNIT'))!!}
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
                                    $customers_data = array('' => 'Select Client');
                                    $customers = User::getCustomers();
                                    foreach ($customers as $customer) {
                                        $customers_data[$customer['id']] = $customer['first_name'] . ' ' . $customer['last_name'];
                                    }
                                    ?>
                                    {!! Form::select('customer_id', $customers_data, isset($asset_data['customer_id']) ? $asset_data['customer_id'] : '', array('style'=>'width:150px;', 'class'=>'span12', 'id'=>'customer_id',  'data-rel'=>'chosen', 'onchange'=>'populateCompany(this.value)'))!!}
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

                           
                                       
                                            ?>
                            {!! Form::select('customer_type',   $customer_type ,  isset($asset_data['customer_type']) ? $asset_data['customer_type'] : '',array('style'=>'width:150px;','class'=>'span8 typeahead','id'=>'customer_type', 'data-rel'=>'chosen'))!!}
                                            
                            </div>
                            </div>
                        <div class="control-group lbl">
                                {!!Form::label('brokage', 'Customer Company: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                   {!!Form::text('brokage', isset($asset_data['brokage']) ? $asset_data['brokage'] : '', array('class'=>'span5 typeahead', 'id'=>'brokage'))!!}
                                </div>
                        </div>
                           <div class="control-group lbl">
                                {!!Form::label('customer_email_address', 'Customer Email Address: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                  {!!Form::text('customer_email_address', isset($asset_data['customer_email_address']) ? $asset_data['customer_email_address'] : '', array('class'=>'span5 typeahead', 'id'=>'customer_email_address'))!!}
                                </div>
                            </div>
                        <!--     <div class="control-group lbl">
                                {!!Form::label('carbon_copy_email', 'Send Carbon Copy Email To: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('carbon_copy_email', isset($asset_data['carbon_copy_email']) ? $asset_data['carbon_copy_email'] : '', array('class'=>'span5 typeahead', 'id'=>'carbon_copy_email'))!!}
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
                               {!! Form::radio('electric_status',  '1', isset($asset_data['electric_status']) && $asset_data['electric_status']==1  ? true : '',  array('id'=>'electric_status'))!!}                                    Yes                                    
                            </label>
                            <label class="radio rdBtn">
                                  {!! Form::radio('electric_status',  '0', isset($asset_data['electric_status']) && $asset_data['electric_status']==0  ? true : '',  array('id'=>'electric_status'))!!}                                       No
                            </label>
                        </div>
                        <div style="clear:both"></div>
                        {!!Form::label('water_status', 'Water: *', array('class'=>'control-label'))!!}
                        <div class="controls">
                            <label class="radio">
                                 {!! Form::radio('water_status',  '1', isset($asset_data['water_status']) && $asset_data['water_status']==1  ? true : '',  array('id'=>'water_status'))!!}                                          Yes
                            </label>
                            <label class="radio rdBtn">
                                {!! Form::radio('water_status',  '0', isset($asset_data['water_status']) && $asset_data['water_status']==0  ? true : '',  array('id'=>'water_status'))!!}                                      No
                            </label>
                        </div>
                        <div style="clear:both"></div>
                        {!!Form::label('gas_status', 'Gas: *', array('class'=>'control-label'))!!}
                        <div class="controls">
                            <label class="radio">
                                {!! Form::radio('gas_status',  '1', isset($asset_data['gas_status']) && $asset_data['gas_status']==1  ? true : '',  array('id'=>'gas_status'))!!}                                     Yes
                            </label>
                            <label class="radio rdBtn">
                               {!! Form::radio('gas_status',  '1', isset($asset_data['gas_status']) && $asset_data['gas_status']==0  ? true : '',  array('id'=>'gas_status'))!!}
                                No
                            </label>
                            <div style="clear:both"></div>
                        </div>
                        <div class="control-group hidden-phone">
                            {!!Form::label('utility_note', 'Utility Note:', array('class'=>'control-label'))!!}
                            <div class="controls row">
                                 {!!Form::textarea('utility_note', isset($asset_data['utility_note']) ? $asset_data['utility_note'] : '', array('class'=>'span5', 'id'=>'utility_note', 'rows'=>'7'))!!}                                </div>
                        </div>

                        <div class="control-group">
                            {!!Form::label('swimming_pool', 'Swimming: ', array('class'=>'control-label'))!!}
                            <div class="controls">
                                 <?php $option_pool = array('None'=>'None' ,'pool' => 'Pool', 'spa' => 'Spa', 'koi-pond' => 'Koi Pond', 'pond' => 'Pond') ?>
                            {!! Form::select('swimming_pool', $option_pool, isset($asset_data['swimming_pool']) ? $asset_data['swimming_pool'] : '', array('class'=>'span5', 'id'=>'swimming_pool'))!!}
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