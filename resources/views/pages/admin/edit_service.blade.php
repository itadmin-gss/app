@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <h2>Add Service</h2>
  <div class="row">
        <div class="col-md-5">

      	@if (Session::has('message'))
        	{!! Session::get('message') !!}
        @endif

        @if(!$errors->isEmpty())
            @foreach ($errors->all() as $error)
            	<div class="alert alert-error">{!! $error !!}</div>
            @endforeach
        @endif

        {!! Form::open(array('url' => 'edit-service/'.$service->id, 'class' => 'form-horizontal', 'method' => 'post')) !!}
        <fieldset>


                 <div class="form-group" > {!! Form::label('typeahead', 'Service Type: ', array('class' => 'control-label')) !!}
                <div class="controls">
                  <div class="input-append"> 
                      <select name="service_type" id="service_type" class="form-control">
                      <option value="0" @if($service->service_type==0) selected="selected" @endif>Standard</option>
                      <option value="1" @if($service->service_type==1) selected="selected" @endif>OSR</option>
                      </select>

                   </div>
                </div>
              </div>

                <div class="form-group"> {!! Form::label('typeahead', 'Service Category: *', array('class' => 'control-label')) !!}
                <div class="controls">
                  <div class="input-append">  <select name="service_cat_id" id="service_cat_id" class="form-control">
         <?php
              
                foreach($ServiceCategory as $scat)
                {
                  ?>
                <option value="{!!$scat->id!!}" @if($service->service_cat_id==$scat->id) selected="selected" @endif>{!!$scat->title!!}</option>
                  <?php
                }
                
        ?>
         
        </select></div>
                </div>
              </div>


                <div class="form-group"> {!! Form::label('typeahead', 'Client Type: *', array('class' => 'control-label')) !!}
                <div class="controls">
                  <div class="input-append">  <select name="customer_type_id" id="customer_type_id" class="form-control">
         <?php
              
                foreach($CustomerType as $scat)
                {
                  ?>
                <option value="{!!$scat->id!!}" @if($service->customer_type_id==$scat->id) selected="selected" @endif>{!!$scat->title!!}</option>
                  <?php
                }
                
        ?>
         
        </select></div>
                </div>
              </div>




                <div class="form-group"> {!! Form::label('typeahead', 'Job Type: *', array('class' => 'control-label')) !!}
                <div class="controls">
                  <div class="input-append">  <select name="job_type_id" id="job_type_id" class="form-control">
         <?php
              
                foreach($JobType as $scat)
                {
                  ?>
                <option value="{!!$scat->id!!}" @if($service->job_type_id==$scat->id) selected="selected" @endif>{!!$scat->title!!}</option>
                  <?php
                }
                
        ?>
         
        </select></div>
                </div>
              </div>



                  
              <div class="form-group"> {!! Form::label('typeahead', 'Title: *', array('class' => 'control-label')) !!}
                <div class="controls">
                  <div class="input-append"> {!! Form::text('title', $service->title, array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('customer_price', 'Customer Price: *', array('class' => 'control-label')) !!}
                <div class="controls">
                  <div class="input-append"> {!! Form::text('customer_price', $service->customer_price, array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('vendor_price', 'Vendot Price: *', array('class' => 'control-label')) !!}
                <div class="controls">
                  <div class="input-append"> {!! Form::text('vendor_price', $service->vendor_price, array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('desc', 'Description: ', array('class' => 'control-label')) !!}
                <div class="controls">
                  <div class="input-append"> {!! Form::textarea('desc', $service->desc, array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('desc', 'Due Date: ', array('class' => 'control-label')) !!}
                 <div class="controls">
                   <div class="input-append"> {!!Form::text('due_date_val', $service->due_date_val, array('class'=> 'form-control datepicker', 'id'=> 'due_date_val' ))!!} </div>
                 </div>
              </div>
<div class="form-group"> {!! Form::label('vendorCheckLabel', 'Vendor Edit:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->vendor_edit) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('vendor_edit', '1', $attribute_array) !!}
                </div>
              </div>
 <div class="form-group"> {!! Form::label('recurring', 'Recurring :', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->recurring) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('recurring', '1', $attribute_array) !!}
                </div>
              </div>
              
              <div class="form-group"> {!! Form::label('emergency', 'EMERGENCY :', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
					if($service->emergency) 
						$attribute_array = array('checked' => 'checked');
					else
						$attribute_array = array();
					
				?>
                  {!! Form::checkbox('emergency', '1', $attribute_array) !!}
                </div>
              </div>
              <div class="form-group"> {!! Form::label('req_date', 'Show Request Date:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
					if($service->req_date) 
						$attribute_array = array('checked' => 'checked');
					else
						$attribute_array = array();
					
				?>
                  {!! Form::checkbox('req_date', '1', $attribute_array) !!}
                </div>
              </div>

              <div class="form-group"> {!! Form::label('due_date', 'Due Date:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->due_date) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('due_date', '1', $attribute_array) !!}
                </div>
              </div>



              <div class="form-group"> {!! Form::label('number_of_men', 'Number Of Men:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
					if($service->number_of_men) 
						$attribute_array = array('checked' => 'checked');
					else
						$attribute_array = array();
					
				?>
                  {!! Form::checkbox('number_of_men', '1', $attribute_array,array('onClick'=>'showDetails("number_of_men")')) !!}

                   <div id="number_of_men_configuration" @if(!$service->number_of_men)   style="display:none;" @endif>
                    {!! Form::select('number_of_men_type', $typeArray, $serviceTypeArray['number_of_men'], array('class'=>'span12',  'data-rel'=>'chosen'))!!}
                    {!! Form::text('number_of_men_values', $serviceValueArray['number_of_men'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>
        
               
              <!--
               <div class="form-group"> {!! Form::label('cash_for_keys_trash_out', 'Cash For keys Trash Out:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
					if($service->cash_for_keys_trash_out) 
						$attribute_array = array('checked' => 'checked');
					else
						$attribute_array = array();
					
				?>
                  {!! Form::checkbox('cash_for_keys_trash_out', '1', $attribute_array,array('onclick'=>'showDetails("cash_for_keys_trash_out")' )) !!}
                   <div id="cash_for_keys_trash_out_configuration" @if(!$service->cash_for_keys_trash_out)   style="display:none;" @endif>
                    {!! Form::select('cash_for_keys_trash_out_type', $typeArray, $serviceTypeArray['cash_for_keys_trash_out'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('cash_for_keys_trash_out_values', $serviceValueArray['cash_for_keys_trash_out'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div> '
              -->
              <div class="form-group"> {!! Form::label('trash_size', 'Trash Size:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
					if($service->trash_size) 
						$attribute_array = array('checked' => 'checked');
					else
						$attribute_array = array();
					
				?>
                  {!! Form::checkbox('trash_size', '1', $attribute_array,array('onclick'=>'showDetails("trash_size")' )) !!}
                   <div id="trash_size_configuration" @if(!$service->trash_size)   style="display:none;" @endif>
                    {!! Form::select('trash_size_type', $typeArray, $serviceTypeArray['trash_size'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('trash_size_values', $serviceValueArray['trash_size'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div> 
              <!--
              <div class="form-group"> {!! Form::label('storage_shed', 'Storage Shed:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
					if($service->storage_shed) 
						$attribute_array = array('checked' => 'checked');
					else
						$attribute_array = array();
					
				?>
                  {!! Form::checkbox('storage_shed', '1', $attribute_array,array('onclick'=>'showDetails("storage_shed")' )) !!}
                     <div id="storage_shed_configuration" @if(!$service->storage_shed)   style="display:none;" @endif>
                    {!! Form::select('storage_shed_type', $typeArray, $serviceTypeArray['storage_shed'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('storage_shed_values', $serviceValueArray['storage_shed'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div> -->
              <div class="form-group"> {!! Form::label('lot_size', 'Lot Size:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
					if($service->lot_size) 
						$attribute_array = array('checked' => 'checked');
					else
						$attribute_array = array();
					
				?>
                  {!! Form::checkbox('lot_size', '1', $attribute_array,array('onclick'=>'showDetails("lot_size")' )) !!}
                  <div id="lot_size_configuration" @if(!$service->lot_size)   style="display:none;" @endif>
                    {!! Form::select('lot_size_type', $typeArray,  $serviceTypeArray['lot_size'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('lot_size_values', $serviceValueArray['lot_size'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>






                      <div class="form-group"> {!! Form::label('set_prinkler_system_type', 'Set Sprinkler System Type:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->set_prinkler_system_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('set_prinkler_system_type', '1', $attribute_array,array('onclick'=>'showDetails("set_prinkler_system_type")' )) !!}
                  <div id="set_prinkler_system_type_configuration" @if(!$service->set_prinkler_system_type)   style="display:none;" @endif>
                    {!! Form::select('set_prinkler_system_type_type', $typeArray,  $serviceTypeArray['set_prinkler_system_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('set_prinkler_system_type_values', $serviceValueArray['set_prinkler_system_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>


                  <div class="form-group"> {!! Form::label('install_temporary_system_type', 'Install Temporary System Type:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->install_temporary_system_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('install_temporary_system_type', '1', $attribute_array,array('onclick'=>'showDetails("install_temporary_system_type")' )) !!}
                  <div id="install_temporary_system_type_configuration" @if(!$service->install_temporary_system_type)   style="display:none;" @endif>
                    {!! Form::select('install_temporary_system_type_type', $typeArray,  $serviceTypeArray['install_temporary_system_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('install_temporary_system_type_values', $serviceValueArray['install_temporary_system_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>



                 <div class="form-group"> {!! Form::label('carpet_service_type', 'Carpet Service Type:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->carpet_service_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('carpet_service_type', '1', $attribute_array,array('onclick'=>'showDetails("carpet_service_type")' )) !!}
                  <div id="carpet_service_type_configuration" @if(!$service->carpet_service_type)   style="display:none;" @endif>
                    {!! Form::select('carpet_service_type_type', $typeArray,  $serviceTypeArray['carpet_service_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('carpet_service_type_values', $serviceValueArray['carpet_service_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>



               <div class="form-group"> {!! Form::label('pool_service_type', 'Pool Service Type:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->pool_service_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('pool_service_type', '1', $attribute_array,array('onclick'=>'showDetails("pool_service_type")' )) !!}
                  <div id="pool_service_type_configuration" @if(!$service->pool_service_type)   style="display:none;" @endif>
                    {!! Form::select('pool_service_type_type', $typeArray,  $serviceTypeArray['pool_service_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('pool_service_type_values', $serviceValueArray['pool_service_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>


               <div class="form-group"> {!! Form::label('boarding_type', 'Boarding Type:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->boarding_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('boarding_type', '1', $attribute_array,array('onclick'=>'showDetails("boarding_type")' )) !!}
                  <div id="boarding_type_configuration" @if(!$service->boarding_type)   style="display:none;" @endif>
                    {!! Form::select('boarding_type_type', $typeArray,  $serviceTypeArray['boarding_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('boarding_type_values', $serviceValueArray['boarding_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>


               <div class="form-group"> {!! Form::label('spruce_up_type', 'Spruce Up Type:', array('class' => 'control-label')) !!}
                <div class="controls">
                <?php 
          if($service->spruce_up_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('spruce_up_type', '1', $attribute_array,array('onclick'=>'showDetails("spruce_up_type")' )) !!}
                  <div id="spruce_up_type_configuration" @if(!$service->spruce_up_type)   style="display:none;" @endif>
                    {!! Form::select('spruce_up_type_type', $typeArray,  $serviceTypeArray['spruce_up_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('spruce_up_type_values', $serviceValueArray['spruce_up_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('constable_information_type', 'Constable Information Type:', array('class' => 'control-label')) !!}
               
                <div class="controls">
                <?php 
          if($service->constable_information_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          
        ?>
                  {!! Form::checkbox('constable_information_type', '1', $attribute_array,array('onclick'=>'showDetails("constable_information_type")' )) !!}
                  <div id="constable_information_type_configuration" @if(!$service->constable_information_type)   style="display:none;" @endif>
                    {!! Form::select('constable_information_type_type', $typeArray,  $serviceTypeArray['constable_information_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('constable_information_type_values', $serviceValueArray['constable_information_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('remove_carpe_type', 'Remove Carpet:', array('class' => 'control-label')) !!}
               <div class="controls">
                <?php 
          if($service->remove_carpe_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          ?>
                  {!! Form::checkbox('remove_carpe_type', '1', $attribute_array,array('onclick'=>'showDetails("remove_carpe_type")' )) !!}
                  <div id="remove_carpe_type_configuration" @if(!$service->remove_carpe_type)   style="display:none;" @endif>
                    {!! Form::select('remove_carpe_type_type', $typeArray,  $serviceTypeArray['remove_carpe_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('remove_carpe_type_values', $serviceValueArray['remove_carpe_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('remove_blinds_type', 'Remove Blinds:', array('class' => 'control-label')) !!}
               <div class="controls">
                <?php 
          if($service->remove_blinds_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          ?>
                  {!! Form::checkbox('remove_blinds_type', '1', $attribute_array,array('onclick'=>'showDetails("remove_blinds_type")' )) !!}
                  <div id="remove_blinds_type_configuration" @if(!$service->remove_blinds_type)   style="display:none;" @endif>
                    {!! Form::select('remove_blinds_type_type', $typeArray,  $serviceTypeArray['remove_blinds_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('remove_blinds_type_values', $serviceValueArray['remove_blinds_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('remove_appliances_type', 'Remove Appliances:', array('class' => 'control-label')) !!}
               <div class="controls">
                <?php 
          if($service->remove_appliances_type) 
            $attribute_array = array('checked' => 'checked');
          else
            $attribute_array = array();
          ?>
                  {!! Form::checkbox('remove_appliances_type', '1', $attribute_array,array('onclick'=>'showDetails("remove_appliances_type")' )) !!}
                  <div id="remove_appliances_type_configuration" @if(!$service->remove_appliances_type)   style="display:none;" @endif>
                    {!! Form::select('remove_appliances_type_type', $typeArray,  $serviceTypeArray['remove_appliances_type'], array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                    {!! Form::text('remove_appliances_type_values', $serviceValueArray['remove_appliances_type'], array('placeholder' => 'insert comma seperated values')) !!}           
                  </div>
                </div>
              </div>


            

              
              
            </div>
          </div>
          <div class="form-actions">
            <div class="pull-left" style="margin-bottom:15px;">
            	{!! Form::hidden('submitted', 1) !!}
                {!! Form::submit('Update Service', array('class' => 'btn btn-large btn-success')) !!}
              
               {!! Form::button('Cancel',array('class' => 'btn btn-large btn-info', 
                     'onClick' =>'location.href="'.URL::to('list-services').'"')) !!}
            </div>
          </div>
        </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
@stop