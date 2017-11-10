@extends('layouts.default')
@section('content')
    <title>GSS - Property Details for {!! $property_details->property_address !!}</title>
    <div id="content">
        <h4>{!! $property_details->property_address !!}, {!! $city !!}, {!! $state !!}  {!! $property_details->zip !!}</h4>
        <hr>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">

                <div class="row">
                    <div class="col-md-5 col-lg-5 col-sm-12">
                        @if (isset($property_details->property_photo))
                            <div class="property-photo">
                                <img src="{!! URL::to(\Illuminate\Support\Facades\Config::get('app.upload_path').$property_details->property_photo) !!}">
                            </div>
                        @else
                            <div class="property-photo" style="display:none;">
                                <img>
                            </div>
                        <div class="property-photo-placeholder text-center">
                            <div class="vertical-center">
                                <div class="house-photo-brand">
                                    <i class="fa fa-photo"></i>
                                    <p class="no-photo">No Property Photo Selected</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="photo-button-group">
                            <button type="button" class="btn btn-success" id="property-photo-upload">Upload</button>
                            <button type="button" class="btn btn-info" id="property-photo-select">Select From Available Photos</button>
                        </div>
                    </div>
                       <div class="col-md-5 col-lg-5 col-sm-12">
                           <h5 class="">Property Information</h5>
                           <table class="table table-small">
                               <tbody>

                                   <tr>
                                       <td>Property Number:</td>
                                       <td>
                                           <span class="asset-details-values" id="property_number_value">
                                             @if (isset($property_details->asset_number)) {!! $property_details->asset_number !!} @endif
                                           </span>

                                           <span class="asset-details-inputs">
                                               <input type="text" class="form-control" id="asset_number" value="@if (isset($property_details->asset_number)) {!! $property_details->asset_number !!}@endif">
                                           </span>

                                           <span class="pull-right asset-details-values"><button class="btn btn-success btn-sm" id="edit-property-details">Edit Details</button></span>
                                       </td>
                                   </tr>

                                   <tr class="asset-details-values">
                                      <td>Property Address:</td>
                                      <td id="property_address_value">
                                              {!! $property_details->property_address !!}
                                              <br>{!! $city !!}, {!! $state !!}  {!! $property_details->zip !!}

                                      </td>
                                   </tr>

                                   <tr class="asset-details-inputs">
                                       <td>Property Address</td>
                                       <td>
                                           <input type="text" class="form-control" id="property_address" value="{!! $property_details->property_address !!}">
                                       </td>
                                   </tr>

                                   <tr class="asset-details-inputs">
                                       <td>Property State</td>
                                       <td>
                                           <?php

                                           $states_data = array('' => 'Select State');
                                           foreach ($states as $state) {
                                               $states_data[$state['id']] = $state['name'];
                                           }
                                           ?>
                                           {!! Form::select('state_id',  $states_data , $property_details->state_id, array('class'=>'form-control','id'=>'state_id', 'data-rel'=>'chosen'))!!}
                                       </td>

                                   </tr>

                                   <tr class="asset-details-inputs">
                                       <td>Property City</td>
                                       <td>
                                           <?php

                                           $cities_data = array('' => 'Select City');
                                           $cities = \App\City::getCitiesByStateId($property_details->state_id);
                                           foreach ($cities as $city) {
                                               $cities_data[$city['id']] = $city['name'];
                                           }

                                           ?>
                                           {!! Form::select('city_id',  $cities_data ,  $property_details->city_id ,array('class'=>'form-control','id'=>'city_id', 'data-rel'=>'chosen'))!!}

                                       </td>
                                   </tr>



                                   <tr class="asset-details-inputs">
                                       <td>Property Zip Code</td>
                                       <td>
                                           <input type="text" class="form-control" id="property_zip" value="{!! $property_details->zip !!}">

                                       </td>
                                   </tr>

                                   <tr>
                                       <td>Property Type:</td>
                                       <td>
                                           <span class="asset-details-values">
                                                @if (isset($property_details->property_type)) {!! $property_details->property_type !!} @endif
                                           </span>

                                           <span class="asset-details-inputs">
                                                <?php $option_type = array('0' => 'Select Property type', 'single-family' => 'Single Family', 'condo' => 'Condo', 'multi-family' => 'Multi Family') ?>
                                               {!! Form::select('property_type', $option_type, isset($property_details->property_type) ? $property_details->property_type : '0', array('class'=>'form-control', 'id'=>'property_type'))!!}
                                           </span>
                                       </td>
                                   </tr>
                                   <tr class="asset-details-values">
                                       <td>Customer:</td>
                                       <td id="property_customer_value">
                                           @if (isset($customer_info->first_name)) {!! $customer_info->first_name !!} @endif @if (isset($customer_info->last_name)){!! $customer_info->last_name !!} @endif
                                       </td>
                                   </tr>
                                   <tr class="asset-details-inputs">
                                       <td>Customer First Name:</td>
                                       <td>
                                           <input type="text" class="form-control" id="first_name" value="@if (isset($customer_info->first_name)) {!! $customer_info->first_name !!} @endif">
                                       </td>
                                   </tr>

                                   <tr class="asset-details-inputs">
                                       <td>Customer Last Name:</td>
                                       <td>
                                           <input type="text" class="form-control" id="last_name" value="@if (isset($customer_info->last_name)){!! $customer_info->last_name !!} @endif">
                                       </td>
                                   </tr>


                                       <tr>
                                           <td>Customer Email:</td>
                                           <td>
                                               <span class="asset-details-values" id="property_email_value">
                                                   @if (isset($customer_info->email)) {!! $customer_info->email !!} @endif
                                               </span>

                                               <span class="asset-details-inputs">
                                                   <input type="email" class="form-control" id="email" value="@if (isset($customer_info->email)) {!! $customer_info->email !!} @endif">
                                               </span>
                                           </td>
                                       </tr>

                                       <tr>
                                           <td>Customer Company:</td>
                                           <td>
                                               <span class="asset-details-values" id="property_company_value">
                                                     @if (isset($customer_info->company)) {!! $customer_info->company !!} @endif
                                               </span>

                                               <span class="asset-details-inputs">
                                                   <input type="text" class="form-control" id="company" value="@if (isset($customer_info->company)) {!! $customer_info->company !!} @endif">
                                               </span>
                                           </td>
                                       </tr>


                                       <tr>
                                           <td>Lock Box:</td>
                                           <td>
                                               <span class="asset-details-values" id="property_lock_value">
                                                    @if (isset($property_details->lock_box)) {!! $property_details->lock_box !!} @endif
                                               </span>

                                               <span class="asset-details-inputs">
                                                   <input type="text" class="form-control" id="lock_box" value="@if (isset($property_details->lock_box)) {!! $property_details->lock_box !!} @endif">
                                               </span>
                                           </td>
                                       </tr>

                                       <tr>
                                           <td>Access Code:</td>
                                           <td>
                                               <span class="asset-details-values" id="property_access_value">
                                                   @if (isset($property_details->access_code)) {!! $property_details->access_code !!} @endif
                                               </span>

                                               <span class="asset-details-inputs">
                                                   <input type="text" class="form-control" id="access_code" value="@if (isset($property_details->access_code)) {!! $property_details->access_code !!} @endif">
                                               </span>
                                           </td>
                                       </tr>

                                        <tr>
                                            <td>Loan Number:</td>
                                            <td>
                                                <span class="asset-details-values" id="property_loan_value">
                                                    @if (isset($property_details->loan_number)) {!! $property_details->loan_number !!} @endif
                                                </span>

                                                <span class="asset-details-inputs">
                                                    <input type="text" class="form-control" id="loan_number" value="@if (isset($property_details->loan_number)) {!! $property_details->loan_number !!} @endif">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Property Status</td>
                                            <td>
                                                <span class="asset-details-values" id="property_status_value">
                                                    @if (isset($property_details->property_status)) {!! ucwords($property_details->property_status) !!} @endif
                                                </span>

                                                <span class="asset-details-inputs">
                                                    <?php $option = array('active' => 'Active', 'inactive' => 'Inactive', 'closed' => 'Closed', 'in-rehab' => 'In-Rehab', 'onhold' => 'On Hold') ?>
                                                    {!! Form::select('property_status', $option, isset($property_details->property_status) ? $property_details->property_status : '', array('class'=>'form-control', 'id'=>'property_status'))!!}
                                                    <span class="pull-right">
                                                        <button type="button" class="btn btn-success" id="save_asset_changes">Save Changes</button>
                                                    </span>
                                                </span>

                                            </td>
                                        </tr>
                                </tbody>
                           </table>

                       </div>
                        <div class="col-md-1 col-lg-1 col-sm-12"></div>

                </div> <!-- end .row -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <h5>Work Orders</h5>
                <hr>
                <a href="{!! URL::to('/admin-add-new-service-request') !!}">
                    <button type="button" class="btn btn-success">Add Work Order</button>
                </a>
                <table class="table table-striped table-small dt-responsive datatabledashboard" style="width:100%;">
                    <thead>
                        <th>ID #</th>
                        <th>Vendor Name</th>
                        <th>Vendor Company</th>
                        <th>Service(s)</th>
                        <th>Status</th>
                        <th>Completed</th>
                        <th>Approved</th>
                    </thead>
                    <tbody>
                        @foreach($order_details as $key => $detail)
                            @foreach($order_details[$key]["order_details"] as $id => $order_detail)
                                <tr>
                                    <td>{!! $id !!}</td>
                                    <td>@if (isset($order_detail["vendor_name"])) {!! $order_detail["vendor_name"] !!} @else Not Set @endif</td>
                                    <td>@if (isset($order_detail["vendor_company"])) {!! $order_detail["vendor_company"] !!} @else Not Set @endif</td>
                                    <td>
                                        @foreach($order_detail["requested_services"] as $services)
                                            {!! $services->title !!} <br>
                                        @endforeach
                                    </td>
                                    <td>{!! $order_detail["status"] !!}</td>
                                    <td>{!! $order_detail["completed"] !!}</td>
                                    <td>{!! $order_detail["approved"] !!}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> <!-- End Row -->

        <!-- Select Existing Photos Modal -->
        <div class="modal fade" id="addt-photo-modal">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Select A Photo
                        <span class="pull-right" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></span>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-wrap addt-photos"></div>

                        <div class="pull-right">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="button" class="btn btn-success" id="save-available-photo">Save Photo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Upload Photo Modal -->
        <div class="modal fade" id="photo-upload-modal">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Upload Photos
                        <span class="pull-right" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div id="tpl" class="dropzone-previews">

                                </div>
                                <form id="dropzone-upload" class="dropzone"></form>
                                <div class="dropzone-control-buttons text-center">
                                    <button type="button" class="btn btn-danger" id="dropzone-cancel">Cancel</button>
                                    <button type="button" class="btn btn-success" id="dropzone-process-upload">Save Photo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display:none;" id="dropzone-preview-template">
        <div class="dz-preview dz-file-preview">
            <div class="dz-details">
                <img data-dz-thumbnail />
                {{--<div class="dz-filename">Filename: <span data-dz-name></span></div>--}}
            </div>
            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
            <div class="dz-error-message"><span data-dz-errormessage></span></div>
        </div>
    </div>
    <input type="hidden" id="property_id" value="{!! $property_details->id !!}">
@stop