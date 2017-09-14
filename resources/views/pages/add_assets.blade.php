@extends('layouts.onecolumn')
@section('content')
<div id="content" class="span12">
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span> Add Properties<h2>
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
                {!! Form::open(array('url' => 'create-customer-asset', 'class'=>'form-horizontal')) !!}
                <fieldset>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                {!!Form::label('asset_number', 'Property Number: *', array('class'=>'control-label'))!!}


                                <div class="controls">
                                    {!!Form::text('asset_number', '', array('class'=>'span12 typeahead', 'id'=>'asset_number' ))!!}

                                    {!!Form::hidden('one_column','yes')!!}
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
                                    {!!Form::text('property_address', '', array('class'=>'span12 typeahead', 'id'=>'property_address'))!!}
                                </div>
                            </div>


                            <div class="control-group">
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
                            <div class="control-group">
                                {!!Form::label('city_id', 'City: *', array('class' => 'control-label'))!!}
                                 <?php
                                $cities_data = array('' => 'Select City');
                                $cities = City::getCitiesByStateId(Input::old('state_id'));
                                foreach ($cities as $city) {
                                    $cities_data[$city['id']] = $city['name'];
                                }
                                 ?>
                                <div class="controls">
                                    {!! Form::select('city_id',  $cities_data , '', array('class'=>'span8 typeahead','id'=>'city_id', 'data-rel'=>'chosen'))!!}
                                </div>
                            </div>
                            <div class="control-group">
                                {!!Form::label('zip', 'Zip: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('zip', '', array('class'=>'span6 typeahead', 'id'=> 'zip'))!!}                                     </div>
                            </div>
                            <div class="control-group">
                                {!!Form::label('lock_box', 'Lock Box: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('lock_box', '', array('class'=>'span12 typeahead', 'id'=>'lock_box'))!!}
                                </div>
                            </div>
                            <div class="control-group">
                                {!!Form::label('access_code', 'Gate/ Access Code: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('access_code', '', array('class'=>'span12 typeahead', 'id'=>'access_code'))!!}                                    </div>
                            </div>

                            <div class="control-group">
                                {!!Form::label('brokage', 'Brokage: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('brokage', '', array('class'=>'span12 typeahead', 'id'=>'brokage'))!!}
                                </div>
                            </div>
                        </div>
                        <!--/span-6-->
                        <div class="span5">
                            <div class="control-group">
                                {!!Form::label('loan_number', 'Loan Number: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('loan_number', '', array('class'=>'span8 typeahead', 'id'=>'loan_number'))!!}
<!--                                    {!!Form::text('loan_number_date', '', array('class'=>'input-xlarge span4 datepicker', 'id'=>'loan_number_date'))!!}-->
                                </div>
                            </div>
                            <div class="control-group">
                                {!!Form::label('property_type', 'Property Type: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    <?php $option_type = array('single-family' => 'Single Family', 'condo' => 'Condo', 'multi-family' => 'Multi Family') ?>
                                    {!! Form::select('property_type', $option_type, '', array('class'=>'span12', 'id'=>'property_type', 'data-rel'=>'chosen'))!!}
                                </div>
                            </div>
                            <div class="control-group">
                                {!!Form::label('agent', 'Agent: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    {!!Form::text('agent', '', array('class'=>'span8 typeahead', 'id'=>'agent'))!!}
                                </div>
                            </div>

                             <div class="control-group">
                                {!!Form::label('property_status', 'Property Status: *', array('class'=>'control-label'))!!}
                                <div class="controls">
                                    <?php $option = array('active' => 'Active', 'inactive' => 'Inactive', 'closed' => 'Closed', 'in-rehab' => 'In-Rehab', 'onhold' => 'On Hold') ?>
                                    {!! Form::select('property_status', $option, '', array('class'=>'span12', 'id'=>'property_status', 'data-rel'=>'chosen'))!!}
                                </div>
                            </div>
                              <div class="control-group" id="maparea">  </div>
                        </div>
                        <!--/span-6-->
                    </div>
                    <div class="row-fluid">
<!--                        <div class="control-group">
                            {!!Form::label('customer_email_address', 'Customer Email Address: *', array('class'=>'control-label'))!!}
                            <div class="controls">
                                {!!Form::text('customer_email_address', '', array('class'=>'span5 typeahead', 'id'=>'customer_email_address'))!!}
                            </div>
                        </div>-->
                        <!--                        <div class="control-group">
                                                    {!!Form::label('carbon_copy_email', 'Send Carbon Copy Email To: *', array('class'=>'control-label'))!!}
                                                    <div class="controls">
                                                        {!!Form::text('carbon_copy_email_1', '', array('class'=>'span5 typeahead', 'id'=>'carbon_copy_email_1'))!!}
                                                    </div>
                                                    <div class="controls">
                                                        {!!Form::text('carbon_copy_email_2', '', array('class'=>'span5 typeahead', 'id'=>'carbon_copy_email_2'))!!}
                                                    </div>
                                                    <div class="controls">
                                                        {!!Form::text('carbon_copy_email_3', '', array('class'=>'span5 typeahead', 'id'=>'carbon_copy_email_3'))!!}
                                                    </div>
                                                </div>-->
                        <div class="control-group">
                            {!!Form::label('outbuilding_shed', 'Outbuilding/ Shed: *', array('class'=>'control-label'))!!}
                            <div class="controls">
                                <label class="radio">
                                    {!! Form::radio('outbuilding_shed',  '1', false,  array('id'=>'outbuilding_shed'))!!}
                                    Yes
                                </label>
                                  <div style="clear:both"></div>
                                <label class="radio">
                                    {!! Form::radio('outbuilding_shed',  '0', false,  array('id'=>'outbuilding_shed'))!!}
                                    No
                                </label>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <div class="control-group hidden-phone">
                            {!!Form::label('outbuilding_shed_note', 'Notes: *', array('class'=>'control-label'))!!}
                            <div class="controls row">
                                {!!Form::textarea('outbuilding_shed_note', '', array('class'=>'span5', 'id'=>'outbuilding_shed_note', 'rows'=>'7'))!!}
                            </div>
                        </div>
                        <div class="control-group hidden-phone">
                            {!!Form::label('special_direction_note', 'Direction or Special Note: *', array('class'=>'control-label'))!!}
                            <div class="controls">
                                {!!Form::textarea('special_direction_note', '', array('class'=>'span5', 'id'=>'special_direction_note', 'rows'=>'7'))!!}
                            </div>
                        </div>
                    </div>
                    <h4>Utility - On inside Property</h4>
                    <div class="control-group">
                        {!!Form::label('electric_status', 'Electric: *', array('class'=>'control-label'))!!}
                        <div class="controls">
                            <label class="radio">
                                {!! Form::radio('electric_status',  '1', false,  array('id'=>'electric_status'))!!}                                    Yes
                            </label>
                              <div style="clear:both"></div>
                            <label class="radio">
                                {!! Form::radio('electric_status',  '0', false,  array('id'=>'electric_status'))!!}                                          No
                            </label>
                        </div>
                        <div style="clear:both"></div>
                        {!!Form::label('water_status', 'Water: *', array('class'=>'control-label'))!!}
                        <div class="controls">
                            <label class="radio">
                                {!! Form::radio('water_status',  '1', false,  array('id'=>'water_status'))!!}                                          Yes
                            </label>
                              <div style="clear:both"></div>
                            <label class="radio">
                                {!! Form::radio('water_status',  '0', false,  array('id'=>'water_status'))!!}                                     No
                            </label>
                        </div>
                        <div style="clear:both"></div>
                        {!!Form::label('gas_status', 'Gas: *', array('class'=>'control-label'))!!}
                        <div class="controls">
                            <label class="radio">
                                {!! Form::radio('gas_status',  '1', false,  array('id'=>'gas_status'))!!}                                     Yes
                            </label>
                              <div style="clear:both"></div>
                            <label class="radio">
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
                    </div>
                     <div class="control-group">
                        {!!Form::label('swimming_pool', 'Swimming: ', array('class'=>'control-label'))!!}
                        <div class="controls">
                            <?php $option_pool = array('pool' => 'Pool', 'spa' => 'Spa', 'koi-pand' => 'Koi Pand', 'pand' => 'Pand') ?>
                            {!! Form::select('swimming_pool', $option_pool, '', array('class'=>'span12', 'id'=>'swimming_pool', 'data-rel'=>'chosen' ,'style' => 'width:380px'))!!}
                        </div>
                    </div>
                    <div class="form-actions text-right">
                        {!!Form::submit('Save & Continue', array('name'=>'save_continue', 'class'=>'btn btn-success'))!!}
                        <button type="button" class="btn btn-inverse">Cancel</button>
                    </div>
            </div>
            </fieldset>
            {!! Form::close() !!}
        </div>
    </div><!--/span-->
</div>
@stop