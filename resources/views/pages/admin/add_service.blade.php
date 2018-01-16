@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <h2>Add Service</h2>
  <div class="row">
      <div class="col-md-4">
          @if (Session::has('message'))
              {!! Session::get('message') !!}
          @endif

              @if(!$errors->isEmpty())
                  @foreach ($errors->all() as $error)
                      <div class="alert alert-error">{!! $error !!}</div>
                  @endforeach
              @endif

              {!! Form::open(array('action' => 'ServiceController@addAdminService', 'class' => 'form-horizontal', 'method' => 'post')) !!}
              <fieldset>

                  <div class="form-group"> {!! Form::label('typeahead', 'Service Code: *', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append"> {!! Form::text('service_code', '', array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                      </div>
                  </div>

                  <div class="form-group"> {!! Form::label('typeahead', 'Service Type: ', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append">
                              <select name="service_type" id="service_type" class="form-control">
                                  <option value="0" >Standard</option>
                                  <option value="1">OSR</option>
                              </select>

                          </div>
                      </div>
                  </div>

                  <div class="form-group"> {!! Form::label('typeahead', 'Service Category: *', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append">

                              <select name="service_cat_id" id="service_cat_id" class="form-control">
                                  <?php

                                  foreach($ServiceCategory as $scat)
                                  {
                                  ?>
                                  <option value="{!!$scat->id!!}">{!!$scat->title!!}</option>
                                  <?php
                                  }

                                  ?>

                              </select>


                          </div>
                      </div>
                  </div>


                  <div class="form-group"> {!! Form::label('typeahead', 'Client Type: *', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append">

                              <select name="customer_type_id" id="customer_type_id" class="form-control">
                                  <?php

                                  foreach($CustomerType as $scat)
                                  {
                                  ?>
                                  <option value="{!!$scat->id!!}">{!!$scat->title!!}</option>
                                  <?php
                                  }

                                  ?>

                              </select>


                          </div>
                      </div>
                  </div>


                  <div class="form-group"> {!! Form::label('typeahead', 'Job Type: *', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append">

                              <select name="job_type_id" id="job_type_id" class="form-control">
                                  <?php

                                  foreach($JobType as $scat)
                                  {
                                  ?>
                                  <option value="{!!$scat->id!!}">{!!$scat->title!!}</option>
                                  <?php
                                  }

                                  ?>

                              </select>


                          </div>
                      </div>
                  </div>




                  <div class="form-group"> {!! Form::label('typeahead', 'Title: *', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append"> {!! Form::text('title', '', array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('customer_price', 'Customer Price: *', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append"> {!! Form::text('customer_price', '', array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('vendor_price', 'Vendor Price: *', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append"> {!! Form::text('vendor_price', '', array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('desc', 'Description: ', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append"> {!! Form::textarea('desc', '', array('class' => 'form-control',
                    'id' => 'focusedInput')) !!} </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('desc', 'Due Date: ', array('class' => 'control-label')) !!}
                      <div class="controls">
                          <div class="input-append"> {!!Form::text('due_date_val', '', array('class'=> 'form-control datepicker', 'id'=> 'due_date_val' ))!!} </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('vendorCheckLabel', 'Vendor Edit:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('vendor_edit', '1') !!}
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('recurring', 'Recurring:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('recurring', '1') !!}
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('emergency', 'Emergency :', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('emergency', '1') !!}
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('req_date', 'Services Required Date:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('req_date', '1') !!}
                      </div>
                  </div>

                  <div class="form-group"> {!! Form::label('due_date', 'Due Date:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('due_date', '1') !!}
                      </div>
                  </div>


                  <div class="form-group"> {!! Form::label('number_of_men', 'Number Of Men:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('number_of_men', '1',false,array('onclick'=>'showDetails("number_of_men")' ) ) !!}
                          <div id="number_of_men_configuration" style="display:none;">
                              {!! Form::select('number_of_men_type', $typeArray, '', array('class'=>'span12',  'data-rel'=>'chosen'))!!}
                              {!! Form::text('number_of_men_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>

              <!--
               <div class="form-group"> {!! Form::label('cash_for_keys_trash_out', 'Cash For keys Trash Out:', array('class' => 'control-label')) !!}
                      <div class="controls">
{!! Form::checkbox('cash_for_keys_trash_out', '1',false,array('onclick'=>'showDetails("cash_for_keys_trash_out")' )) !!}
                      <div id="cash_for_keys_trash_out_configuration" style="display:none;">
{!! Form::select('cash_for_keys_trash_out_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
              {!! Form::text('cash_for_keys_trash_out_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                      </div>
                    </div>
                  </div> -->
                  <div class="form-group"> {!! Form::label('trash_size', 'Trash Size:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('trash_size', '1',false,array('onclick'=>'showDetails("trash_size")' )) !!}
                          <div id="trash_size_configuration" style="display:none;">
                              {!! Form::select('trash_size_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('trash_size_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>
              <!--
              <div class="form-group"> {!! Form::label('storage_shed', 'Storage Shed:', array('class' => 'control-label')) !!}
                      <div class="controls">
{!! Form::checkbox('storage_shed', '1',false,array('onclick'=>'showDetails("storage_shed")' )) !!}
                      <div id="storage_shed_configuration" style="display:none;">
{!! Form::select('storage_shed_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
              {!! Form::text('storage_shed_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                      </div>
                    </div>
                  </div>
-->
                  <div class="form-group"> {!! Form::label('lot_size', 'Lot Size:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('lot_size', '1',false,array('onclick'=>'showDetails("lot_size")' )) !!}
                          <div id="lot_size_configuration" style="display:none;">
                              {!! Form::select('lot_size_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('lot_size_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>

                  <div class="form-group"> {!! Form::label('set_prinkler_system_type', 'Set Sprinkler System Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('set_prinkler_system_type', '1',false,array('onclick'=>'showDetails("set_prinkler_system_type")' )) !!}
                          <div id="set_prinkler_system_type_configuration" style="display:none;">
                              {!! Form::select('set_prinkler_system_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('set_prinkler_system_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>


                  <div class="form-group"> {!! Form::label('install_temporary_system_type', 'Install Temporary System Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('install_temporary_system_type', '1',false,array('onclick'=>'showDetails("install_temporary_system_type")' )) !!}
                          <div id="install_temporary_system_type_configuration" style="display:none;">
                              {!! Form::select('install_temporary_system_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('install_temporary_system_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>

                  <div class="form-group"> {!! Form::label('carpet_service_type', 'Carpet Service Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('carpet_service_type', '1',false,array('onclick'=>'showDetails("carpet_service_type")' )) !!}
                          <div id="carpet_service_type_configuration" style="display:none;">
                              {!! Form::select('carpet_service_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('carpet_service_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>

                  <div class="form-group"> {!! Form::label('pool_service_type', 'Pool Service Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('pool_service_type', '1',false,array('onclick'=>'showDetails("pool_service_type")' )) !!}
                          <div id="pool_service_type_configuration" style="display:none;">
                              {!! Form::select('pool_service_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('pool_service_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>

                  <div class="form-group"> {!! Form::label('boarding_type', 'Boarding Service Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('boarding_type', '1',false,array('onclick'=>'showDetails("boarding_type")' )) !!}
                          <div id="boarding_type_configuration" style="display:none;">
                              {!! Form::select('boarding_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('boarding_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>

                  <div class="form-group"> {!! Form::label('spruce_up_type', 'Spruce Up Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('spruce_up_type', '1',false,array('onclick'=>'showDetails("spruce_up_type")' )) !!}
                          <div id="spruce_up_type_configuration" style="display:none;">
                              {!! Form::select('spruce_up_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('spruce_up_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('constable_information_type', 'Constable information Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('constable_information_type', '1',false,array('onclick'=>'showDetails("constable_information_type")' )) !!}
                          <div id="constable_information_type_configuration" style="display:none;">
                              {!! Form::select('constable_information_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('constable_information_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('remove_carpe_type', 'Remove Carpet Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('remove_carpe_type', '1',false,array('onclick'=>'showDetails("remove_carpe_type")' )) !!}
                          <div id="remove_carpe_type_configuration" style="display:none;">
                              {!! Form::select('remove_carpe_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('remove_carpe_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('remove_blinds_type', 'Remove Blinds Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('remove_blinds_type', '1',false,array('onclick'=>'showDetails("remove_blinds_type")' )) !!}
                          <div id="remove_blinds_type_configuration" style="display:none;">
                              {!! Form::select('remove_blinds_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('remove_blinds_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>
                  <div class="form-group"> {!! Form::label('remove_appliances_type', 'Remove Appliances Type:', array('class' => 'control-label')) !!}
                      <div class="controls">
                          {!! Form::checkbox('remove_appliances_type', '1',false,array('onclick'=>'showDetails("remove_appliances_type")' )) !!}
                          <div id="remove_appliances_type_configuration" style="display:none;">
                              {!! Form::select('remove_appliances_type_type', $typeArray, '', array('class'=>'span12',   'data-rel'=>'chosen'))!!}
                              {!! Form::text('remove_appliances_type_values', '', array('placeholder' => 'insert comma seperated values')) !!}
                          </div>
                      </div>
                  </div>





      </div>
  </div>
    <div class="form-actions">
        <div class="pull-left" style="margin-bottom:15px;">
            {!! Form::hidden('submitted', 1) !!}
            {!! Form::submit('Add Service', array('class' => 'btn btn-large btn-success')) !!}
            {!! Form::button('Cancel',array('class' => 'btn btn-large btn-info',
                   'onClick' =>'location.href="'.URL::to('list-services').'"')) !!}
        </div>
    </div>
    </fieldset>
    {!!Form::close()!!}
      </div>



      </div>
    </div>
  </div>
</div>
@stop