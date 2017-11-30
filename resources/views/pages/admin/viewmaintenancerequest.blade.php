@extends('layouts.default')
@section('content')

<title>GSS - Maintenance Request #{!! $request_maintenance->id !!}</title>
<div id="content" class="span11">
<?php if($request_maintenance->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>
<p id="message" style="display:none">Saved...


    <h4>{!! $property_details->property_address !!}, {!! $city !!}, {!! $state !!}  {!! $property_details->zip !!}</h4>
    <hr>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">

            <div class="row">
                <div class="col-md-3 col-lg-3 col-sm-12">
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

                <div class="col-md-3 col-lg-3 col-sm-12">
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
                <div class="col-md-3 col-lg-3 col-sm-12">
                    <table class="table table-small">
                        <tbody>


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


                        </tbody>
                    </table>

                </div>
                <div class="col-md-3 col-lg-3 col-sm-12">
                    <div id="map"></div>
                </div>

            </div> <!-- end .row -->
        </div>
    </div>
    <hr>
    <div class="row-fluid">
        <span>
            <h4 style="float:left;">Service Request Details</h4>
            <a style="margin-left: 33px;margin-bottom: 7px;margin-top: -4px;" href="{!! URL::to('asset/'.$property_details->id) !!}" class="btn btn-success">View All Work Orders</a>
        </span>
    </div>
    <div class="row">
        <!-- Left Table -->
        <div class="col-md-5 col-lg-5 col-sm-12">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Request ID</td>
                        <td>{!! $request_maintenance->id !!}</td>
                    </tr>

                    <tr>
                        <td>Asset #</td>
                        <td>{!! $request_maintenance->asset->asset_number !!}  <button class="btn btn-small btn-success" data-target="#showServiceid"  onclick="viewAsset({!! $request_maintenance->asset->id !!})">View Property</button> </td>
                    </tr>

                    <tr>
                        <td>Property Address</td>
                        <td>{!! $request_maintenance->asset->property_address !!}</td>
                    </tr>

                    <tr>
                        <td>Zip</td>
                        <td>{!! $request_maintenance->asset->zip !!}</td>
                    </tr>

                    <tr>
                        <td>City</td>
                        <td>{!! $request_maintenance->asset->city->name !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Right Table -->
        <div class="col-md-5 col-lg-5 col-sm-12">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Request Date</td>
                        <td>{!! date('m/d/Y', strtotime($request_maintenance->created_at)) !!}</td>
                    </tr>
                    <tr>
                        <td>Customer Name</td>
                        <td>@if(isset($request_maintenance->user->first_name)) {!! $request_maintenance->user->first_name!!} @endif  @if(isset($request_maintenance->user->last_name)){!!$request_maintenance->user->last_name !!} @endif</td>
                    </tr>
                    <tr>
                        <td>State</td>
                        <td>{!! $request_maintenance->asset->state->name !!}</td>
                    </tr>

                    <tr>
                        <td>Email</td>
                        <td>@if(isset( $request_maintenance->user->email)){!! $request_maintenance->user->email!!} @endif</td>
                    </tr>

                    <tr>
                        <td>Admin Notes</td>
                        <td> {!!Form::textarea('admin_notes', isset( $request_maintenance->admin_notes) ? $request_maintenance->admin_notes : '' , array('class'=>'form-control', 'id'=>'admin_notes','onChange'=>'adminNotes(this,"'.$request_maintenance->id.'")'))!!} </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row-fluid">
        <h4>Vendor Details</h4>
    </div>
    <hr>
    <div class="row-fluid">
        <button data-toggle="modal" data-target="#assign" class="btn btn-large btn-success assign-vendor" onclick="showMaintenanceServices('{!! $request_maintenance->id !!}')">Assign Vendor</button>
    </div>
    <?php
    $totalPriceCustomer =0;
    $totalPriceVendor   =0;
    ?>
    @foreach($request_maintenance->requestedService as $services)
        <div class="row">
            <div class="col-md-9 col-lg-9 col-sm-12">
                <div class="card" style="width:100%;">
                    <div class="card-header" data-original-title>
                        <div class='float-left'><h4><i class="halflings-icon edit"></i><span class="break"></span>{!!$services->service->title!!}</h4></div>
                        <div class="card-price">
                            <?php
                            $priceData=\App\SpecialPrice::getSpecialCustomerPrice($request_maintenance->user->id,$services->service->id);
                            $servicePrice="";

                            if(!empty($priceData) )
                            {

                                if($services->quantity=="" || $services->quantity==0)
                                {
                                    $servicePrice=$priceData->special_price;
                                    $totalPriceCustomer += $priceData->special_price;
                                }
                                else
                                {
                                    $servicePrice=$priceData->special_price*$services->quantity ;
                                    $totalPriceCustomer += $priceData->special_price*$services->quantity ;
                                }

                            }
                            else
                            {

                                if($services->quantity=="" || $services->quantity==0)
                                {
                                    $servicePrice=$services->service->customer_price;
                                    $totalPriceCustomer += $services->service->customer_price;
                                }
                                else
                                {
                                    $servicePrice=$services->service->customer_price*$services->quantity ;
                                    $totalPriceCustomer += $services->service->customer_price*$services->quantity ;

                                }

                            }

                            $vendorPrice=0;

                            if($services->quantity=="" || $services->quantity==0)
                            {
                                $totalPriceVendor += $services->service->vendor_price;
                                $vendorPrice= $services->service->vendor_price;
                            }
                            else
                            {
                                $totalPriceVendor += $services->service->vendor_price*$services->quantity ;
                                $vendorPrice=  $services->service->vendor_price*$services->quantity ;
                            }
                            ?>
                            Customer Price :  ${!!$servicePrice!!} ::::: Vendor Price: ${!!$vendorPrice!!}
                            <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="card-body">

                        <table>
                            <tr><td> {!!$services->service->desc!!}</td></tr>
                            <tr>
                                <td class="center"><h5>Customer Note:</h5>{!!$services->service_note!!}</td>
                            </tr>
                            <tr>
                                <td class="center span3"><h5>Note for Vendor:</h5>
                                    {!!Form::textarea('public_notes',isset($services->public_notes) ? $services->public_notes : '' , array('class'=>'form-control', 'id'=>'public_notes','onChange'=>'publicNotes(this,"'.$services->id.'")'))!!}
                                </td>

                            </tr>
                            <tr>
                                <div class="row-fluid browse-sec">
                                    @if(count($services->serviceImages)!=0)<h2>Images</h2>@endif


                                    <ul class="media-list ">
                                        @foreach($services->serviceImages as $images)
                                            <?php
                                            $docType=explode(".", $images->image_name);
                                            if( $docType[1]=='jpeg'|| $docType[1]=='jpg'|| $docType[1]=='png'|| $docType[1]=='gif')
                                            {
                                            ?>
                                            <li>{!! Html::image(Config::get('app.request_images').'/'.$images->image_name) !!}</li>


                                            <?php
                                            }  else {
                                            ?>

                                            <li> <a  href="{!!URL::to('/')!!}/{!!Config::get('app.request_images').'/'.$images->image_name!!}" target="_blank" > Download File</a></li>
                                            <?php
                                            }
                                            ?>
                                        @endforeach


                                    </ul>

                                </div>
                            </tr>

                            <tr><td>Service Details</td><td></td></tr>
                            @if($services->required_date!="")
                                <tr><td>Required Date</td>
                                    <td>{!! date('m/d/Y', strtotime($services->required_date)) !!}

                                    </td>
                                </tr>
                            @endif

                            @if( $services->due_date!="")
                                <tr><td>Due Date</td>
                                    <td>
                                        {!! date('m/d/Y', strtotime($services->due_date)) !!}
                                    </td>
                                </tr>
                            @endif

                            @if($services->quantity!="")
                                <tr><td>Quantity</td>
                                    <td>{!! $services->quantity !!}

                                    </td>
                                </tr>
                            @endif



                            @if($services->service_men!="")
                                <tr><td>Service men</td>
                                    <td>{!!$services->service_men !!}

                                    </td>
                                </tr>
                            @endif
                            @if($services->service_note!="")
                                <tr><td>Service note</td>
                                    <td>{!!$services->service_note !!}

                                    </td>
                                </tr>
                            @endif

                            @if($services->verified_vacancy!="")
                                <tr><td>Verified vacancy</td>
                                    <td>{!!$services->verified_vacancy !!}

                                    </td>
                                </tr>
                            @endif
                            @if($services->cash_for_keys!="")
                                <tr><td>Cash for keys</td>
                                    <td>{!!$services->cash_for_keys !!}

                                    </td>
                                </tr>
                            @endif

                            @if($services->cash_for_keys_trash_out!="")
                                <tr><td>Cash for keys Trash Out</td>
                                    <td>{!!$services->cash_for_keys_trash_out !!}

                                    </td>
                                </tr>
                            @endif

                            @if($services->trash_size!="")
                                <tr><td>trash size</td>
                                    <td>{!!$services->trash_size !!}

                                    </td>
                                </tr>
                            @endif


                            @if($services->storage_shed!="")
                                <tr><td>storage shed</td>
                                    <td>{!!$services->storage_shed !!}

                                    </td>
                                </tr>
                            @endif


                            @if($services->lot_size!="")
                                <tr><td>lot size</td>
                                    <td>{!!$services->lot_size !!}

                                    </td>
                                </tr>
                            @endif

                            @if($services->set_prinkler_system_type!="")
                                <tr><td>set prinkler system type</td>
                                    <td>{!!$services->set_prinkler_system_type !!}

                                    </td>
                                </tr>
                            @endif


                            @if($services->install_temporary_system_type!="")
                                <tr><td>install temporary system type</td>
                                    <td>{!!$services->install_temporary_system_type !!}

                                    </td>
                                </tr>
                            @endif



                            @if($services->pool_service_type!="")
                                <tr><td>pool service type</td>
                                    <td>{!!$services->pool_service_type !!}

                                    </td>
                                </tr>
                            @endif


                            @if($services->carpet_service_type!="")
                                <tr><td>carpet service type</td>
                                    <td>{!!$services->carpet_service_type !!}

                                    </td>
                                </tr>
                            @endif

                            @if($services->boarding_type!="")
                                <tr><td>boarding type</td>
                                    <td>{!!$services->boarding_type !!}

                                    </td>
                                </tr>
                            @endif



                            @if($services->spruce_up_type!="")
                                <tr><td>spruce up type</td>
                                    <td>{!!$services->spruce_up_type !!}

                                    </td>
                                </tr>
                            @endif



                            @if($services->constable_information_type!="")
                                <tr><td>constable information type</td>
                                    <td>{!!$services->constable_information_type !!}

                                    </td>
                                </tr>
                            @endif


                            @if($services->remove_carpe_type!="")
                                <tr><td>remove carpe type</td>
                                    <td>{!!$services->remove_carpe_type !!}

                                    </td>
                                </tr>
                            @endif


                            @if($services->remove_blinds_type!="")
                                <tr><td>remove blinds type</td>
                                    <td>{!!$services->remove_blinds_type !!}

                                    </td>
                                </tr>
                            @endif

                            @if($services->remove_appliances_type!="")
                                <tr><td>remove appliances type</td>
                                    <td>{!!$services->remove_appliances_type !!}

                                    </td>
                                </tr>
                            @endif

                        </table>

                    </div>
                </div>
            </div>
        </div>
</div>

            @endforeach





        <div class="row" style="margin-bottom:30px;">
            <div class="col-md-10 col-lg-10 col-sm-12">
                <hr>
                <div class="pull-right">
                    <h5>Total Customer Price: ${!!$totalPriceCustomer!!} Total Vendor Price: ${!!$totalPriceVendor!!} </h5>
                </div>
            </div>
        </div>
  </div>

<div class="modal fade" id="showVendorList" tabindex='-1' role='dialog' aria-hidden='true'>
  <div class='modal-dialog' role='document'>
    <div class='modal-content'>
      <div class="modal-body" style="min-height:400px !important;"></div>
      <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
        <a href="#" class="btn btn-primary" onclick="assign_request()">Save</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="showServiceid" tabindex='-1' role='dialog' aria-hidden='true'>
          <div class="modal-dialog" role="document">
        		<div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" data-target="#showServiceid" aria-label="Close">x</button>
                        <h1>Property Detail</h1>
                    </div>
                    <div class="modal-body">
                     </div>
                    <div class="modal-footer">
                        <div class="text-right">
                          <button type="button" class="btn btn-large btn-inverse" data-dismiss="modal">Close</button>
                        </div>
                    </div>
             	</div>
             </div>   
</div>




</div>
@parent
@include('common.delete_alert')

<script>

    function initMap() {
        var lat = parseFloat('<?=$geolocation['latitude']?>');
        var lng = parseFloat('<?=$geolocation['longitude']?>');
        var uluru = {lat: lat, lng: lng};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 18,
            center: uluru
        });
        var marker = new google.maps.Marker({
            position: uluru,
            map: map
        });
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtaE4nNK7Wdb19dUeCMitdhFv4Wy7eb30&callback=initMap">
</script>
@stop
