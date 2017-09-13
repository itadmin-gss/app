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
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif

                @if($errors->has())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">{{ $error }}</div>
                @endforeach
                @endif

                {{-- */$url='edit-asset/'.$asset_data['id'];/* --}}
                {{ Form::open(array('url' => $url, 'class'=>'form-horizontal')) }}
                <fieldset>

                    <div class="row-fluid">

                        <div class="span5">

                            <div class="control-group">
                                {{Form::label('asset_number', 'Property ID: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    {{Form::text('asset_number', isset($asset_data['asset_number']) ? $asset_data['asset_number'] : '' , array('class'=>'span12 typeahead', 'id'=>'asset_number', 'readonly' ))}}
                                </div>
                            </div>

                            <div class="control-group">
                                {{Form::label('customer', 'Customer: ', array('class'=>'control-label'))}}
                                <div class="controls">
                                    <?php $option_pool = array('pool' => 'Pool', 'spa' => 'Spa', 'koi-pand' => 'Koi Pand', 'pand' => 'Pand') ?>
                                    {{ Form::select('customer_id', $customers, $asset_data->customer_id, array('class'=>'span12', 'id'=>'swimming_pool'))}}
                                </div>
                            </div>

                            <div class="control-group">
                                {{Form::label('property_address', 'Property Address: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    {{Form::text('property_address', isset($asset_data['property_address']) ? $asset_data['property_address'] : '' , array('class'=>'span12 typeahead', 'id'=>'property_address'))}}
                                </div>
                            </div>
                            <div class="control-group">
                                {{Form::label('state_id', 'State', array('class' => 'control-label '))}}
                                <?php
                                $states_data = array('0' => 'Select State');
                                foreach ($states as $state) {
                                    $states_data[$state['id']] = $state['name'];
                                }
                                ?>
                                <div class="controls">
                                    {{ Form::select('state_id',  $states_data,  isset($asset_data['state_id']) ? $asset_data['state_id'] : '0',   array('class'=>'span8 typeahead','id'=>'state_id'))}}
                                </div>
                            </div>
                            <div class="control-group">
                                {{Form::label('city_id', 'City', array('class' => 'control-label'))}}
                                <?php
                                $cities_data = array('' => 'Select City');
                                $cities = City::getCitiesByStateId($asset_data['state_id']);
                                foreach ($cities as $city) {
                                    $cities_data[$city['id']] = $city['name'];
                                }
                                ?>
                                <div class="controls">
                                    {{ Form::select('city_id',  $cities_data , isset($asset_data['city_id']) ? $asset_data['city_id'] : '0', array('class'=>'span8 typeahead','id'=>'city_id'))}}

                                </div>
                            </div>
                            <div class="control-group">
                                {{Form::label('zip', 'Zip: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    {{Form::text('zip', isset($asset_data['zip']) ? $asset_data['zip'] : '', array('class'=>'span6 typeahead', 'id'=> 'zip'))}}                                     </div>
                            </div>

                            <div class="control-group">
                                {{Form::label('lock_box', 'Lock Box: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    {{Form::text('lock_box', isset($asset_data['lock_box']) ? $asset_data['lock_box'] : '', array('class'=>'span12 typeahead', 'id'=>'lock_box'))}}
                                </div>
                            </div>

                            <div class="control-group">
                                {{Form::label('access_code', 'Gate/ Access Code: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    {{Form::text('access_code', isset($asset_data['access_code']) ? $asset_data['access_code'] : '', array('class'=>'span12 typeahead', 'id'=>'access_code'))}}                                    </div>
                            </div>


                            <div class="control-group">
                                {{Form::label('property_status', 'Property Status: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    <?php $option = array('active' => 'Active', 'inactive' => 'Inactive', 'closed' => 'Closed', 'in-rehab' => 'In-Rehab', 'onhold' => 'On Hold') ?>
                                    {{ Form::select('property_status', $option, array('class'=>'span12', 'id'=>'property_status'))}}

                                </div>
                            </div>

                            <div class="control-group">
                                {{Form::label('brokage', 'Customer Name: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    {{Form::text('brokage', isset($asset_data['brokage']) ? $asset_data['brokage'] : '', array('class'=>'span12 typeahead', 'id'=>'brokage'))}}
                                </div>
                            </div>


                                

                        </div>
                        <!--/span-6-->

                        <div class="span5">

                            <div class="control-group">

                                {{Form::label('loan_number', 'Loan Number: ', array('class'=>'control-label'))}}
                                <div class="controls">
                                    {{Form::text('loan_number', isset($asset_data['loan_number']) ? $asset_data['loan_number'] : '', array('class'=>'span8 typeahead', 'id'=>'loan_number'))}}
                                   
                                </div>
                            </div>

                            <div class="control-group">
                                {{Form::label('property_type', 'Property Type: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    <?php $option_type = array('0' => 'Select Property type', 'single-family' => 'Single Family', 'condo' => 'Condo', 'multi-family' => 'Multi Family') ?>
                                    {{ Form::select('property_type', $option_type, isset($asset_data['property_type']) ? $asset_data['property_type'] : '0', array('class'=>'span12', 'id'=>'property_type'))}}

                                </div>
                            </div>


                            <div class="control-group">
                                {{Form::label('agent', 'Customer Company: *', array('class'=>'control-label'))}}
                                <div class="controls">
                                    {{Form::text('agent', isset($asset_data['agent']) ? $asset_data['agent'] : '', array('class'=>'span8 typeahead', 'id'=>'agent'))}}
                                </div>
                            </div>


                            <div class="control-group">
                            {{Form::label('occupancy_status', 'Occupancy Status: *', array('class'=>'control-label'))}}
                            <div class="controls" style="float: left;margin-left: 20px;">
                                <label class="radio">
                                 {{ Form::radio('occupancy_status',  'Vacant',isset($asset_data['occupancy_status']) && $asset_data['occupancy_status']=="Vacant"  ? true : '',  array('id'=>'occupancy_status'))}}
                               
                                    Vacant
                                </label>
                                 <div style="clear:both"></div>
                                <label class="radio">
                                  {{ Form::radio('occupancy_status',  'Occupied',isset($asset_data['occupancy_status']) && $asset_data['occupancy_status']=="Occupied"  ? true : '',  array('id'=>'occupancy_status'))}}
                                  Occupied
                                </label>
                               
                            </div>
                            <div style="clear:both"></div>
                            </div>
                            <div class="control-group">
                              {{Form::label('utility_note', 'Occupancy Status Note:', array('class'=>'control-label'))}}
                           
                                 <div class="controls row" >
                                 {{Form::textarea('occupancy_status_note', isset($asset_data['occupancy_status_note']) ? $asset_data['occupancy_status_note'] : '', array('class'=>'span5', 'id'=>'occupancy_status_note', 'rows'=>'7'))}}
                            
                                </div>
                            </div>
                             <div class="control-group">
                                {{Form::label('UNIT', 'Unit #:', array('class'=>'control-label'))}}
                                <div class="controls">

                                      {{Form::text('UNIT', isset($asset_data['UNIT']) ? $asset_data['UNIT'] : '', array('class'=>'span8 typeahead', 'id'=>'UNIT'))}}
                              
                                </div>
                            </div>
                            <div class="control-group" id="maparea"> 

                                
<div id="map_canvas" style="height:250px"></div>

<script type="text/javascript">
    function loadScript(src,callback){
  
    var script = document.createElement("script");
    script.type = "text/javascript";
    if(callback)script.onload=callback;
    document.getElementsByTagName("head")[0].appendChild(script);
    script.src = src;
  }
  
  
  loadScript('http://maps.googleapis.com/maps/api/js?v=3&sensor=false&callback=initialize',
              function(){});


function initialize() {
     
    var myLatlng = new google.maps.LatLng({{$asset_data['latitude']}},{{$asset_data['longitude']}});
   
    var mapOptions = {
          zoom: 17,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map_canvas'),
            mapOptions);
    var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: ''
  });
  }

function log(str){
  document.getElementsByTagName('pre')[0].appendChild(document.createTextNode('['+new Date().getTime()+']\n'+str+'\n\n'));
}


</script>

                         </div>
                        </div>
                        <!--/span-6-->

                    </div>

                    <div class="row-fluid">
                        <div class="control-group">
                            {{Form::label('customer_email_address', 'Customer Email Address: *', array('class'=>'control-label'))}}
                            <div class="controls">
                                {{Form::text('customer_email_address', isset($asset_data['customer_email_address']) ? $asset_data['customer_email_address'] : '', array('class'=>'span5 typeahead', 'id'=>'customer_email_address'))}}
                            </div>
                        </div>

                                            <div class="control-group">
                        <?php
//                            if(isset($asset_data['carbon_copy_email'])){
//                            $explode_emails = explode(',', $asset_data['carbon_copy_email']);
//                            $carbon_copy_email_1 = $explode_emails[0];
//                            $carbon_copy_email_2 = $explode_emails[1];
//                            $carbon_copy_email_3 = $explode_emails[2];
//                            }
                        ?>
                                                    {{Form::label('carbon_copy_email', 'Send Carbon Copy Email To: *', array('class'=>'control-label'))}}
                                                    <div class="controls">
                                                        {{Form::text('carbon_copy_email', isset($asset_data['carbon_copy_email']) ? $asset_data['carbon_copy_email'] : '', array('class'=>'span5 typeahead', 'id'=>'carbon_copy_email_1'))}}
                                                    </div>
                                                  
                                                </div>

                        <div class="control-group">
                            {{Form::label('outbuilding_shed', 'Outbuilding/ Shed: *', array('class'=>'control-label'))}}
                            <div class="controls">
                                <label class="radio">
                                    {{ Form::radio('outbuilding_shed',  '1',isset($asset_data['outbuilding_shed']) && $asset_data['outbuilding_shed']==1  ? true : '',  array('id'=>'outbuilding_shed'))}}
                                    Yes
                                </label>

                                <label class="radio">
                                    {{ Form::radio('outbuilding_shed',  '0', isset($asset_data['outbuilding_shed']) && $asset_data['outbuilding_shed']==0  ? true : '',  array('id'=>'outbuilding_shed'))}}
                                    No
                                </label>
                            </div>
                            <div style="clear:both"></div>
                        </div>

                        <div class="control-group hidden-phone">
                            {{Form::label('outbuilding_shed_note', 'Notes: *', array('class'=>'control-label'))}}
                            <div class="controls row">
                                {{Form::textarea('outbuilding_shed_note', isset($asset_data['outbuilding_shed_note']) ? $asset_data['outbuilding_shed_note'] : '', array('class'=>'span5', 'id'=>'outbuilding_shed_note', 'rows'=>'7'))}}
                            </div>
                        </div>
                        <div class="control-group hidden-phone">
                            {{Form::label('special_direction_note', 'Direction or Special Note: *', array('class'=>'control-label'))}}
                            <div class="controls">
                                {{Form::textarea('special_direction_note', isset($asset_data['special_direction_note']) ? $asset_data['special_direction_note'] : '', array('class'=>'span5', 'id'=>'special_direction_note', 'rows'=>'7'))}}
                            </div>
                        </div>
                    </div>

                    <h4>Utility - On inside Property</h4>
                    <div class="control-group">
                        {{Form::label('electric_status', 'Electric: *', array('class'=>'control-label'))}}
                        <div class="controls">
                            <label class="radio">
                                {{ Form::radio('electric_status',  '1', isset($asset_data['electric_status']) && $asset_data['electric_status']==1  ? true : '',  array('id'=>'electric_status'))}}                                    Yes
                            </label>

                            <label class="radio">
                                {{ Form::radio('electric_status',  '0', isset($asset_data['electric_status']) && $asset_data['electric_status']==0  ? true : '',  array('id'=>'electric_status'))}}                                          No
                            </label>
                        </div>
                        <div style="clear:both"></div>
                        {{Form::label('water_status', 'Water: *', array('class'=>'control-label'))}}
                        <div class="controls">
                            <label class="radio">
                                {{ Form::radio('water_status',  '1', isset($asset_data['water_status']) && $asset_data['water_status']==1  ? true : '',  array('id'=>'water_status'))}}                                          Yes
                            </label>

                            <label class="radio">
                                {{ Form::radio('water_status',  '0', isset($asset_data['water_status']) && $asset_data['water_status']==0  ? true : '',  array('id'=>'water_status'))}}                                     No
                            </label>
                        </div>
                        <div style="clear:both"></div>
                        {{Form::label('gas_status', 'Gas: *', array('class'=>'control-label'))}}
                        <div class="controls">
                            <label class="radio">
                                {{ Form::radio('gas_status',  '1', isset($asset_data['gas_status']) && $asset_data['gas_status']==1  ? true : '',  array('id'=>'gas_status'))}}                                     Yes
                            </label>

                            <label class="radio">
                                {{ Form::radio('gas_status',  '0', isset($asset_data['gas_status']) && $asset_data['gas_status']==0  ? true : '',  array('id'=>'gas_status'))}}
                                No
                            </label>
                            <div style="clear:both"></div>
                        </div>

                        <div class="control-group hidden-phone">
                            {{Form::label('utility_note', 'Utility Note:', array('class'=>'control-label'))}}
                            <div class="controls row">
                                {{Form::textarea('utility_note', isset($asset_data['utility_note']) ? $asset_data['utility_note'] : '', array('class'=>'span5', 'id'=>'utility_note', 'rows'=>'7'))}}                                </div>
                        </div>
                    </div>

                    <div class="control-group">
                        {{Form::label('swimming_pool', 'Swimming: ', array('class'=>'control-label'))}}
                        <div class="controls">
                            <?php $option_pool = array('None'=>'None' ,'pool' => 'Pool', 'spa' => 'Spa', 'koi-pond' => 'Koi Pond', 'pond' => 'Pond') ?>
                            {{ Form::select('swimming_pool', $option_pool,  array('class'=>'span12', 'id'=>'swimming_pool'))}}
                        </div>
                    </div>





                    <div class="form-actions text-right">
                        {{Form::hidden('submitted','1')}}
                        {{Form::submit('Save & Continue', array('name'=>'save_continue', 'class'=>'btn btn-success'))}}
                       <a class="list-assets" href="{{URL::to('list-assets')}}"  >
                    <button type="button" class="btn btn-inverse">Cancel</button>
                        </a>
                    </div>
            </div>

            </fieldset>
            {{ Form::close() }}

        </div>
    </div><!--/span-->

</div>
@stop