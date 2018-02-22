@extends('layouts.default')
@section('content')



    <!-- start: Content -->
    <title>GSS - Edit Work Order</title>
    <style>
        .dz-preview{
            display:none !important;
        }
    </style>
    <div class="bg-underlay"></div>
    <div id="content">

        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">


                <div class="row" style="box-shadow: 1px 1px 1px black;">
                    <div class="col-md-3 col-lg-2 col-sm-12">
                        @if (isset($property_details->property_photo))
                            <div class="property-photo">
                                <img src="{!! URL::to(\Illuminate\Support\Facades\Config::get('app.upload_path').$property_details->property_photo) !!}">
                                <button type="button" data-title="Upload Image" data-toggle='tooltip' class="btn btn-primary btn-sm property-photo-upload" id="property-photo-upload"><i class="fa fa-upload"></i></button>
                                <button type="button" data-title="Select Primary Photo" data-toggle='tooltip' class="btn btn-primary btn-sm property-photo-select" id="property-photo-select"><i class="fa fa-edit"></i></button>
                            </div>
                        @else
                            <div class="property-photo" style="display:none;">
                                <img>
                                <button type="button" data-title="Upload Image" data-toggle='tooltip' class="btn btn-primary btn-sm property-photo-upload" id="property-photo-upload"><i class="fa fa-upload"></i></button>
                                <button type="button" data-title="Select Primary Photo" data-toggle='tooltip' class="btn btn-primary btn-sm property-photo-select" id="property-photo-select"><i class="fa fa-edit"></i></button>
                            </div>
                            <div class="property-photo-placeholder text-center">
                                <i class="fa fa-photo fa-fullsize"></i>

                                <button type="button" data-title="Upload Image" data-toggle='tooltip' class="btn btn-primary btn-sm property-photo-upload" id="property-photo-upload"><i class="fa fa-upload"></i></button>
                                <button type="button" data-title="Select Primary Photo" data-toggle='tooltip' class="btn btn-primary btn-sm property-photo-select" id="property-photo-select"><i class="fa fa-edit"></i></button>

                            </div>
                        @endif

                    </div>
                    <div class="col-md-9 col-lg-10 col-sm-12 property-header-info">
                        <div class="property-header">
                            <p style="margin-bottom: 0px !important;">{!! $property_details->property_address !!}, {!! $city !!}, {!! $state !!}  {!! $property_details->zip !!}</p>
                        </div>
                        <div class="row" style="margin-left:0px !important;">
                            <div class="col-md-4 col-lg-4 col-sm-12">
                                <table class="table table-sm">
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

                                            <span class="pull-right asset-details-values">
                                               <span id="edit-property-details">
                                                   <i class="fa fa-edit"></i>
                                               </span>
                                           </span>
                                        </td>
                                    </tr>

                                    <tr class="asset-details-values">
                                        <td>Property Address:</td>
                                        <td id="property_address_value">
                                            <a href="{!! URL::to("asset/".$property_details->id) !!}">
                                                {!! $property_details->property_address !!}
                                                <br>{!! $city !!}, {!! $state !!}  {!! $property_details->zip !!}
                                            </a>
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
                                        <td>Google Map</td>
                                        <td><a href="javascript:void(0)" data-target="#gmap-modal" data-toggle="modal">Click here for Map</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12">
                                <table class="table table-sm">
                                    <tbody>

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




                                    </tbody>
                                </table>

                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12">
                                <table class="table table-sm">
                                    <tbody>
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
                        </div>
                    </div>



                </div> <!-- end .row -->
            </div>
        </div>

        <div class="row" style="margin-top:29px !important">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <h1 class="text-center">Requested Services</h1>
            </div>
        </div>
        <?php

        $totalPriceCustomer=0;
        $total = 0;
        $totalPriceVendor=0;
        $totalPrice=0;
        $totalRequestedServices=0;
        $RecurringFlag=0;
        ?>

        @foreach($order_details as $order_detail)
            @foreach($customData as $custom)
                @if(isset($order_detail->requestedService->service->title))


                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between;">
                                        <div>
                                            <label class="table-label">Work Order::#{!! $order->id!!}</label>

                                        </div>
                                        <label class="table-label">{!! $order_detail->requestedService->service->title !!}</label>
                                        <div>
                                            <span data-toggle="modal" data-target="#edit_request_service" style="cursor:pointer;">
                                                <i class="fa fa-edit"></i>
                                            </span>
                                            <?php

                                            if ($order_detail->requestedService->recurring == 1)
                                            {
                                                $RecurringFlag = 1;
                                            }

                                            $totalRequestedServices++;




                                            if (Auth::user()->type_id == 3)
                                            {
                                                $SpecialPriceVendor = \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                                                    ->where('customer_id', Auth::user()->id)
                                                    ->where('type_id', 3)
                                                    ->get();

                                                $vendor_priceFIND = 0;

                                                if (isset($SpecialPriceVendor[0]) && !empty($SpecialPriceVendor[0]))
                                                {
                                                    if (isset($custom->vendors_price) && isset($custom->quantity))
                                                    {
                                                        echo "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                                    }
                                                    else
                                                    {
                                                        echo "Price: $".$SpecialPriceVendor[0]->special_price * $order_detail->requestedService->quantity;
                                                    }
                                                }
                                                else
                                                {
                                                    if (isset($custom->vendors_price) && isset($custom->quantity))
                                                    {
                                                        if( $custom->vendors_price !== NULL && $custom->quantity !== NULL)
                                                        {
                                                            $vendor_priceFIND += $custom->vendors_price * $custom->quantity;
                                                            echo "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                                        }
                                                    }

                                                    else
                                                    {
                                                        $vendor_priceFIND = $order_detail->requestedService->service->vendor_price * $order_detail->requestedService->quantity;
                                                        echo "Vendor Price: ".$order_detail->requestedService->service->vendor_price * $order_detail->requestedService->quantity;
                                                    }
                                                }

                                                $totalPrice += $vendor_priceFIND;

                                            }
                                            else if (Auth::user()->type_id==2)
                                            {

                                                $SpecialPriceVendor=  \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                                                    ->where('customer_id', Auth::user()->id)
                                                    ->where('type_id', 2)
                                                    ->get();

                                                $vendor_priceFIND = 0;
                                                if (!empty($SpecialPriceVendor[0]))
                                                {
                                                    if ($custom->vendors_price !== NULL && $custom->quantity !== NULL)
                                                    {
                                                        $vendor_priceFIND = $custom->vendors_price * $custom->quantity;
                                                        echo "Price: $".$vendor_priceFIND;
                                                    }
                                                    else
                                                    {
                                                        $vendor_priceFIND = $SpecialPriceVendor[0]->special_price * $order_detail->requestedService->quantity;
                                                        echo "Price: $".$vendor_priceFIND;
                                                    }
                                                }
                                                else
                                                {
                                                    if (isset($custom->vendors_price) && isset($custom->quantity))
                                                    {
                                                        if( $custom->vendors_price !== NULL && $custom->quantity !== NULL)
                                                        {
                                                            $vendor_priceFIND += $custom->vendors_price * $custom->quantity;
                                                            echo "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                                        }
                                                    }
                                                }

                                                $totalPrice += $vendor_priceFIND;

                                            }
                                            else
                                            {



                                                $SpecialPriceCustomer=  \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                                                    ->where('customer_id', $order->customer->id)
                                                    ->where('type_id', 2)
                                                    ->get();

                                                $customer_priceFIND = 0;

                                                if (!empty($SpecialPriceCustomer[0]))
                                                {
                                                    if (!empty($custom->customer_price))
                                                    {
                                                        $totalPriceCustomer += $custom->customer_price * $custom->admin_quantity;
                                                        echo "Customer Price: $".$custom->customer_price * $custom->admin_quantity;
                                                    }
                                                    else
                                                    {
                                                        $totalPriceCustomer += $SpecialPriceCustomer[0]->special_price;
                                                        echo "Customer Price: $".$SpecialPriceCustomer[0]->special_price;
                                                    }
                                                }
                                                else
                                                {
                                                    if (isset($custom->customer_price) && $custom->customer_price !== NULL)
                                                    {
                                                        $totalPriceCustomer += $custom->customer_price * $custom->admin_quantity;
                                                        echo "Customer Price: $".$custom->customer_price * $custom->admin_quantity;
                                                    }
                                                    else
                                                    {
                                                        $totalPriceCustomer = $order_detail->requestedService->service->customer_price;
                                                        echo "Customer Price: $".$order_detail->requestedService->service->customer_price;
                                                    }
                                                }

                                                $SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                                                    ->where('customer_id','=',$order->vendor->id)
                                                    ->where('type_id','=',3)
                                                    ->get();

                                                if (!empty($SpecialPriceVendor[0]))
                                                {
                                                    if ($custom->vendors_price !== null && $custom->quantity !== null)
                                                    {
                                                        $totalPriceVendor += $custom->vendors_price * $custom->quantity;
                                                        echo " | Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                                    }
                                                    else
                                                    {
                                                        $totalPriceVendor += $SpecialPriceVendor[0]->special_price;
                                                        echo " | Vendor Pri mystatusclassce: $".$SpecialPriceVendor[0]->special_price;
                                                    }
                                                }
                                                else
                                                {
                                                    if (isset($custom->vendors_price) && $custom->vendors_price !== null && $custom->quantity !== NULL)
                                                    {
                                                        $totalPriceVendor += $custom->vendors_price * $custom->quantity;
                                                        echo " | Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                                    }
                                                    else
                                                    {
                                                        $totalPriceVendor += $order_detail->requestedService->service->vendor_price;
                                                        echo " | Vendor Price: $".$order_detail->requestedService->service->vendor_price;
                                                    }
                                                }

                                            }


                                            ?>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-body">



                                    <div id="vendor-note-empty-error-{!!$order_detail->id!!}" class="hide">
                                        <div class="alert alert-error">Vendor Note Can not be Empty</div>
                                    </div>
                                    <div id="vendor-note-empty-success-{!!$order_detail->id!!}" class="hide">
                                        <div class="alert alert-success">Saved Successful</div>
                                    </div>

                                    <div id="billing-note-empty-success" class="hide">
                                        <div class="alert alert-success">Saved Successful</div>
                                    </div>

                                    <div id="billing-note-empty-error" class="hide">
                                        <div class="alert alert-success">Billing Note Can not be Empty</div>
                                    </div>

                                    <table class="table table-bordered">

                                        <?php
                                        if( Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {
                                        ?>
                                        <tr>
                                            <td style="width:200px; background-color:#f7f7f7;">
                                                <label class="table-label">Vendor:</label>
                                            </td>
                                            <td>
                                                <span style="font-weight:bold;">
                                                    @foreach($vendorsDATA as $vendor)
                                                        @if ($vendor->id == $order->vendor_id)
                                                            @if (isset($vendor['first_name']))
                                                                {!! $vendor['first_name']." " !!}
                                                            @endif
                                                            @if (isset($vendor['last_name']))
                                                                {!! $vendor['last_name']." " !!}
                                                            @endif

                                                            @if (isset($vendor['company']))
                                                                @ {!! $vendor['company'] !!}
                                                            @endif

                                                            <span class="edit-vendor" data-toggle="modal" data-target="#change-vendor-modal">
                                                                <i class="fa fa-edit"></i>
                                                            </span>
                                                        @endif
                                                    @endforeach

                                                </span>
                                            </td>

                                        </tr>
                                        <tr>
                                            @if(isset($custom->customers_notes))
                                                <td style="width:200px; background-color:#f7f7f7;"><label class="table-label">Customer Note: </label></td>
                                                <td>{!!$custom->customers_notes!!}</td>
                                            @else
                                                <td style="width:200px; background-color:#f7f7f7;"><label class="table-label">Customer Note: </label>
                                                <td>{!!$order_detail->requestedService->customer_note!!}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if(isset($custom->notes_for_vendors))
                                                <td style="width:200px; background-color:#f7f7f7;"><label class="table-label">Note for Vendor: </label></td>
                                                <td>{!!$custom->notes_for_vendors!!}</td>

                                            @else
                                                <td style="width:200px; background-color:#f7f7f7;"><label class="table-label">Note for Vendor: </label></td>
                                                <td>{!!$order_detail->requestedService->public_notes!!}</td>
                                            @endif
                                        </tr>

                                        <tr>
                                            @if(isset($custom->vendors_notes))
                                                <td style="width:200px; background-color:#f7f7f7;"><label class="table-label">Vendor Note: </label></td>
                                                <td>{!!$custom->vendors_notes!!}</td>
                                            @else
                                                <td style="width:200px; background-color:#f7f7f7;"><label class="table-label">Vendor Note: </label></td>
                                                <td>{!!$order_detail->requestedService->vendor_note!!}</td>
                                            @endif
                                        </tr>

                                        <?php }elseif( Auth::user()->type_id == 3 ) {
                                        ?>


                                        <tr>
                                            <td colspan="2" class="center"><label class="table-label">Note for Vendor:</label>

                                                @if(isset($custom->notes_for_vendors))
                                                    <p>{!!$custom->notes_for_vendors!!}</p >
                                                @else
                                                    <p>{!!$order_detail->requestedService->public_notes!!}</p >
                                            @endif
                                        </tr>
                                        <?php
                                        }elseif( Auth::user()->type_id == 2 ) {
                                            ?>


                                        <?php }?>
                                            <tr>
                                                <td colspan="9">
                                                    <div class="row" style="margin-bottom:30px;">
                                                        <div class="col-md-12">
                                                            <div style="display:inline-block;">
                                                                <label class="table-label" style="display:inline;">Order Images: </label>
                                                                <input type="hidden" id="order_id" value="{!! $order->id !!}">
                                                                <span>
                                                                     <button class="btn btn-primary export-all-photos" type="button">Export All Images</button>
                                                                </span>
                                                                <span>
                                                                    <button class="btn btn-primary export-selected-photos" type="button">Export Selected Images</button>
                                                                </span>
                                                                <span>
                                                                     <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-confirmation">Delete Selected</button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 col-lg-4 col-sm-12">


                                                            <div class="center-div">
                                                                <label class="table-label">Before Images</label>
                                                            </div>
                                                            <hr>
                                                            <div class="order-photo-div center-div">
                                                                <div class="order-photo-item upload-order-photo upload-before">
                                                                    <i class="fa fa-2x fa-upload"></i>
                                                                    <p>Drag Or Click To Upload</p>
                                                                </div>
                                                                <?php
                                                                    $images = \App\OrderImage::where('order_id', $order->id)->where('type', 'before')->orderBy('id', 'desc')->get();
                                                                    foreach($images as $image)
                                                                    {
                                                                        $check = config('app.order_images_before').$image->address;
                                                                        if (file_exists($check))
                                                                        {
                                                                            echo '<div class="order-photo-item" id="photo-id-'.$image->id.'"><span class="photo-export-checkbox"><input data-id="'.$image->id.'" type="checkbox" class="form-control export-before-checkbox"></span><img data-id="'.$image->id.'" data-image-type="before" class="order-photo-img" src="'.config('app.url').'/'.config('app.order_images_before').$image->address.'"></div>';
                                                                        }
                                                                     }
                                                                ?>
                                                            </div>
                                                            <div class="center-div export-button-group">
                                                                <button type="button" class="btn btn-primary export-before-all">Export All Before Images</button>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-4 col-lg-4 col-sm-12">
                                                            <div class="center-div">
                                                                <label class="table-label">During Images</label>
                                                            </div>
                                                            <hr>
                                                            <div class="order-photo-div center-div">
                                                                <div class="order-photo-item upload-order-photo upload-during">
                                                                    <i class="fa fa-2x fa-upload"></i>
                                                                    <p>Drag Or Click To Upload</p>
                                                                </div>
                                                                <?php
                                                                $images = \App\OrderImage::where('order_id', $order->id)->where('type', 'during')->orderBy('id', 'desc')->get();

                                                                foreach($images as $image)
                                                                {
                                                                    $check = config('app.order_images_during').$image->address;
                                                                    if (file_exists($check))
                                                                    {
                                                                        echo '<div class="order-photo-item" id="photo-id-'.$image->id.'"><span class="photo-export-checkbox"><input data-id="'.$image->id.'" type="checkbox" class="form-control export-during-checkbox"></span><img data-id="'.$image->id.'" class="order-photo-img" data-image-type="during" src="'.config('app.url').'/'.config('app.order_images_during').$image->address.'"></div>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="center-div export-button-group">
                                                                <button type="button" class="btn btn-primary export-during-all">Export All During Images</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-lg-4 col-sm-12">
                                                            <div class="center-div">
                                                                <label class="table-label">After Images</label>
                                                            </div>
                                                            <hr>
                                                            <div class="order-photo-div center-div">
                                                                <div class="order-photo-item upload-order-photo upload-after">
                                                                    <i class="fa fa-2x fa-upload"></i>
                                                                    <p>Drag Or Click To Upload</p>
                                                                </div>

                                                                <?php
                                                                $images = \App\OrderImage::where('order_id', $order->id)->where('type', 'after')->orderBy('id', 'desc')->get();
                                                                foreach($images as $image)
                                                                {
                                                                    $check = config('app.order_images_after').$image->address;
                                                                    if (file_exists($check))
                                                                    {
                                                                        echo '<div class="order-photo-item" id="photo-id-'.$image->id.'"><span class="photo-export-checkbox"><input data-id="'.$image->id.'" type="checkbox" class="form-control export-after-checkbox"></span><img data-id="'.$image->id.'" class="order-photo-img" data-image-type="before" src="'.config('app.url').'/'.config('app.order_images_after').$image->address.'"></div>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="center-div export-button-group">
                                                                <button type="button" class="btn btn-primary export-after-all">Export All After Images</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>



                                        <!-- <tr>
                                          <td colspan="2" class="center"><label class="control-label" for="typeahead">Vendor Note:</label><textarea style="width: 100%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 139px;" rows="6" id="limit"></textarea></td>
                                      </tr> -->

                                        <?php
                                        if( Auth::user()->type_id == 3 ) {
                                        ?>
                                        <tr>
                                            <td colspan="2" class="center"><label class="table-label">Vendor Note:</label>
                                                @if($order_detail->requestedService->vendor_note)
                                                    <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!$order_detail->requestedService->vendor_note!!}<br><button class="btn btn-primary" id="edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}" onclick="editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})"> Edit Note </button> </span >
                                                    <span class="hide" id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('vendor_note', $order_detail->requestedService->vendor_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @else
                                                <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}"></span >
                                                <span id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('vendor_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right"  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif onclick="saveVendorNote({!!$order->id!!}, {!!$order_detail->id!!})">Save {!!$order_detail->requestedService->service->title!!}</button></td>

                                        </tr>
                                        <?php } else if( Auth::user()->type_id == 2 ) {
                                        ?>

                                        <tr>
                                            <td colspan="2" class="center"><label class="table-label">Customers Note:</label>
                                                @if($order_detail->requestedService->customer_note)
                                                    <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!$order_detail->requestedService->custumer_note!!}<br><button class="btn btn-primary" id="edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}" onclick="editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})"> Edit Note </button> </span >
                                                    <span class="hide" id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('custumer_note', $order_detail->requestedService->customer_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @else
                                                <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}"></span >
                                                <span id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('custumer_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right"  onclick="saveCustomerNote({!!$order->id!!}, {!!$order_detail->id!!})">Save {!!$order_detail->requestedService->service->title!!}</button></td>

                                        </tr>

                                        <?php } else if( Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ||Auth::user()->user_role_id == 5 ||Auth::user()->user_role_id == 6||Auth::user()->user_role_id == 8 ) {
                                        ?>

                                        <tr>
                                            <td colspan="2" class="center"><label class="table-label">Admin Note:</label>
                                                @if($order_detail->requestedService->admin_note)

                                                    <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}"><p>{!!$order_detail->requestedService->admin_note!!}</p><br><button class="btn btn-primary" id="edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}" onclick="editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})"> Edit Note </button> </span >
                                                    <span class="hide" id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('admin_note', $order_detail->requestedService->admin_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @else
                                                <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}"></span >
                                                <span id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('admin_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right" onclick="saveAdminNote({!!$order->id!!}, {!!$order_detail->id!!})">Save Admin Note</button></td>

                                        </tr>
                                        <?php } ?>
                                        <?php if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ) { ?>
                                        <tr>


                                            <td colspan="2" class="center"><label class="table-label">Billing Note:</label>
                                                @if($order->billing_note)
                                                    <span id="show-billing-note-{!!$order->id!!}">{!!$order->billing_note!!}<br>
                                                        <button class="btn btn-primary" id="edit-billing-note-button-{!!$order->id!!}" onclick="editBillingNoteButton({!!$order->id!!})"> Edit Note </button>
                                                            </span >
                                                    <span class="hide" id="textarea-billing-note-{!!$order->id!!}">{!!Form::textarea('admin_note', $order->billing_note ,array('class'=>'span','id'=>'billing-note-'.$order->id))!!}
                                                        <button class="btn btn-large btn-warning pull-right " id="bill-btn" onclick="saveBillingNote({!!$order->id!!})">Save Billing Note</button></span>
                                            </td>
                                            @else
                                                <span id="show-billing-note-{!!$order->id!!}"></span >
                                                <span id="textarea-billing-note-{!!$order->id!!}">{!!Form::textarea('admin_note','',array('class'=>'span','id'=>'billing-note-'.$order->id))!!}
                                                    <button class="btn btn-large btn-warning pull-right" onclick="saveBillingNote({!!$order->id!!})">Save Billing Note</button></span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="center" colspan="2"><!-- <button class="btn btn-large btn-warning pull-right" onclick="saveBillingNote({!!$order->id!!})">Save Billing Note</button> --></td>

                                        </tr>
                                        <?php  } ?>

                                        <tr><td><label class="table-lable">Service Details</label></td><td></td></tr>
                                        @if($order_detail->requestedService->required_date!="")
                                            <tr><td><span>Required Date</span></td>
                                                <td><span>{!! date('m/d/Y', strtotime($order_detail->requestedService->required_date)) !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if( $order_detail->requestedService->due_date!="")
                                            <tr><td><span>Due Date</span></td>
                                                <td>
                                                    <span> {!! date('m/d/Y', strtotime($order_detail->requestedService->due_date)) !!}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->quantity!="")
                                            <tr><td><span>Quantity</span></td>
                                                <td>
                                                    <span id="show-vendor-qty">{!! $order_detail->requestedService->quantity !!}</span>
                                                </td>
                                            </tr>
                                        @endif



                                        @if($order_detail->requestedService->service_men!="")
                                            <tr><td><span>Service men</span></td>
                                                <td><span>{!!$order_detail->requestedService->service_men !!}</span>

                                                </td>
                                            </tr>
                                        @endif
                                        @if($order_detail->requestedService->service_note!="")
                                            <tr><td><span>Service note</span></td>
                                                <td><span>{!!$order_detail->requestedService->service_note !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->verified_vacancy!="")
                                            <tr><td><span>Verified vacancy</span></td>
                                                <td><span>{!!$order_detail->requestedService->verified_vacancy !!}</span>

                                                </td>
                                            </tr>
                                        @endif
                                        @if($order_detail->requestedService->cash_for_keys!="")
                                            <tr><td><span>Cash for keys</span></td>
                                                <td><span>{!!$order_detail->requestedService->cash_for_keys !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->cash_for_keys_trash_out!="")
                                            <tr><td><span>Cash for keys Trash Out</span></td>
                                                <td><span>{!!$order_detail->requestedService->cash_for_keys_trash_out !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->trash_size!="")
                                            <tr><td><span>trash size</span></td>
                                                <td><span>{!!$order_detail->requestedService->trash_size !!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->storage_shed!="")
                                            <tr><td><span>storage shed</span></td>
                                                <td><span>{!!$order_detail->requestedService->storage_shed !!}</span></td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->lot_size!="")
                                            <tr><td><span>lot size</span></td>
                                                <td><span>{!!$order_detail->requestedService->lot_size !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->set_prinkler_system_type!="")
                                            <tr><td><span>set prinkler system type</span></td>
                                                <td><span>{!!$order_detail->requestedService->set_prinkler_system_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->install_temporary_system_type!="")
                                            <tr><td><span>install temporary system type</span></td>
                                                <td><span>{!!$order_detail->requestedService->install_temporary_system_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif



                                        @if($order_detail->requestedService->pool_service_type!="")
                                            <tr><td><span>pool service type</span></td>
                                                <td><span>{!!$order_detail->requestedService->pool_service_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->carpet_service_type!="")
                                            <tr><td><span>carpet service type</span></td>
                                                <td><span>{!!$order_detail->requestedService->carpet_service_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->boarding_type!="")
                                            <tr><td><span>boarding type</span></td>
                                                <td><span>{!!$order_detail->requestedService->boarding_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif



                                        @if($order_detail->requestedService->spruce_up_type!="")
                                            <tr><td><span>spruce up type</span></td>
                                                <td><span>{!!$order_detail->requestedService->spruce_up_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif



                                        @if($order_detail->requestedService->constable_information_type!="")
                                            <tr><td><span>constable information type</span></td>
                                                <td><span>{!!$order_detail->requestedService->constable_information_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->remove_carpe_type!="")
                                            <tr><td><span>remove carpe type<span></td>
                                                <td><span>{!!$order_detail->requestedService->remove_carpe_type!!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->remove_blinds_type!="")
                                            <tr><td><span>remove blinds type</span></td>
                                                <td><span>{!!$order_detail->requestedService->remove_blinds_type !!}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->remove_appliances_type!="")
                                            <tr><td><span>remove appliances type</span></td>
                                                <td><span>{!!$order_detail->requestedService->remove_appliances_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif



                                    </table>
                                </div>
                            </div><!--/span-->

                        </div><!--/row-->
                        <!-- Edit Service request pop modal Starts
                          1 = admin
                          2 = customers
                          3 = vendors -->

                    </div>
                @endif
            @endforeach
        @endforeach
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <h1 class="text-center">Order Status</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="mystatusclass"  id="btn-group-unique-mobile">
                    <label class="table-label">Current Status: </label>
                    <div class="form-group orderstatus">
                        @if($order->status==1) In-Process @else {!!$order->status_text!!} @endif</button>
                    </div>
                </div>

                <div>
                    <label class="table-label">Completion Date: </label>
                    <div class="form-group">
                        {!!Form::text('completion_date', $order->completion_date, array('class'=> 'datepicker', 'id'=> 'completion_date' ))!!}
                        <button id="completion_date_div" class="btn btn-small" onclick="completionDate()" >Save</button>
                    </div>
                </div>
                <div>
                    <label class="table-label">Change Status: </label>
                    <div class="form-group">
                        <button class="dropdown-toggle" data-toggle="dropdown">Status Options <span class="caret"></span></button>
                        <ul class="dropdown-menu"  @if(Auth::user()->type_id==3 && $order->status==4) style="display:none;padding:1px;" @endif  >
                            <li><a href="javascript:void(0)" class="completion-status-dropdown">In-Process</a></li>

                            <li><a href="javascript:void(0)" class="completion-status-dropdown">Completed</a></li>

                            @if(Auth::user()->type_id==4||Auth::user()->type_id==1||Auth::user()->type_id==2)
                                <li><a href="javascript:void(0)" class="completion-status-dropdown">Approved</a></li>
                                <li><a href="javascript:void(0)" class="underreview completion-status-dropdown">Under Review</a></li>

                                <li><a href="javascript:void(0)" class="completion-status-dropdown">Cancelled</a></li>


                            @endif

                        </ul>
                    </div>
                </div>

                <?php


                if($RecurringFlag==1)
                {
                ?>

                {!! Form::hidden('recurring_id', $RecurringFlag,array("id"=>"recurring_id"))!!}
                <?php
                }
                ?>







                            <div style="display:none;" id="under_review_notes_section">
                                <label class="table-label">Under Review Notes:</label>
                                <div class="form-group">
                                    {!! Form::hidden('lol', "$order->vendor_id",array("id"=>"vendor_id"))!!}
                                    {!!Form::textarea('under_review_notes', '' ,array('class'=>'span','id'=>'under_review_notes'))!!}
                                    <a class="btn btn-info" href="#" onclick="under_review_notes('<?php echo $order->vendor_id;?>')" > Save</a>
                                </div>
                            </div>
                            @if((Auth::user()->type_id==3||Auth::user()->type_id==1||Auth::user()->type_id==4||Auth::user()->type_id==5||Auth::user()->type_id==6) &&(count($OrderReviewNote))>0)

                                <div class="reviews_note_history">

                                    <label class="table-label">Under Review Notes History</label>
                                    <div>
                                        <ul>
                                            <?php
                                            foreach ($OrderReviewNote as  $value) {
                                            ?>
                                            <li><?php echo $value->review_notes."---".date('m/d/Y h:i:s A',strtotime( $value->created_at )) ;?></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>

                                </div>
                            @endif


                        </tr>
                        <tr>
                            <td class="center" colspan="2"></td>
                        </tr>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade" id="edit_request_service">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="pull-right" data-dismiss="modal" aria-label="Close">X</span>
                </div>

                <div class="modal-body">
                    <div class="imageviewPop">
                        <div class="well text-center"><h1>Edit Requested Service </h1></div>

                        <div class="row-fluid">
                            <div class="alert alert-success" id="flash_modal" style="display: none;">Added Successfully</div>
                                <div class="alert alert-danger" id="additional_flash_danger" style="display: none;">
                                    Please Fill All the Fields!
                                    </div>
                                <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==4){ ?>
                                <?php if (isset($custom->customer_price)) {
                                if ( $custom->customer_price != 0){ ?>
                                <!-- <span><?php echo $totalPriceCustomer; ?></span> -->
                                    {!!Form::label('customer_price', 'Customer Price')!!}

                                    {!!Form::text('customer_price',$custom->customer_price,array('class' => 'form-control', 'id'=>'customer_price','onkeypress'=>'return isNumberKey(event)','required'))!!}
                                    <?php }else{ ?>
                                    {!!Form::label('customer_price', 'Customer Price')!!}

                                    {!!Form::text('customer_price',$totalPriceCustomer,array('class' => 'form-control', 'id'=>'customer_price','onkeypress'=>'return isNumberKey(event)','required'))!!}
                                    <?php
                                    }
                                    }else{ ?>
                                    {!!Form::label('customer_price', 'Customer Price')!!}

                                    {!!Form::text('customer_price',$totalPriceCustomer,array('class' => 'form-control', 'id'=>'customer_price','onkeypress'=>'return isNumberKey(event)','required'))!!}
                                    <?php
                                    }
                                    ?>
                                    <?php if (isset($custom->customers_notes)  ){ ?>
                                    {!!Form::label('customers_notes', 'Customers Notes')!!}

                                    {!!Form::textarea('customers_notes',$custom->customers_notes ,array('class' => 'form-control', 'id' => 'customers_notes' ) )!!}
                                    <?php }else{ ?>
                                    {!!Form::label('customers_notes', 'Customers Notes')!!}

                                    {!!Form::textarea('customers_notes', $order_detail->requestedService->customer_note ,array('class' => 'form-control', 'id' => 'customers_notes') )!!}
                                    <?php } ?>


                                    <?php } ?>
                                    <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==4){ ?>
                                    <?php if (!empty($custom->admin_quantity)  ){ ?>

                                    {!!Form::label('admin_quantity', 'Admin Quantity')!!}

                                    {!!Form::number('admin_quantity',$custom->admin_quantity,array('class' => 'form-control', 'id' => 'adminquantity_edit' ,'onkeypress'=>'return isNumberKey(event)'))!!}
                                    <?php }else{?>
                                    {!!Form::label('admin_quantity', 'Admin Quantity')!!}

                                    {!!Form::number('admin_quantity','',array('class' => 'form-control', 'id' => 'adminquantity_edit' ,'onkeypress'=>'return isNumberKey(event)'))!!}
                                    <?php } ?>
                                    <?php } ?>
                                    <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==4 || $order_detail->requestedService->service->vendor_edit == 1){ ?>

                                    <?php if (isset($custom->quantity)  ){ ?>
                                    {!!Form::label('quantity', 'Vendor Quantity')!!}

                                    {!!Form::number('quantity',$custom->quantity,array('class' => 'form-control', 'id' => 'quantity_edit' ,'onkeypress'=>'return isNumberKey(event)'))!!}
                                    <?php }else{ ?>
                                    {!!Form::label('quantity', 'Vendor Quantity')!!}

                                    {!!Form::number('quantity','' ,array('class' => 'form-control', 'id' => 'quantity_edit','onkeypress'=>'return isNumberKey(event)','required'))!!}
                                    <?php } ?>
                                    <?php if (isset($custom->vendors_price)  ){ ?>
                                    {!!Form::label('vendor_price', 'Vendor Price')!!}

                                    {!!Form::text('vendor_price',$custom->vendors_price,array('class' => 'form-control', 'id' => 'vendor_price','onkeypress'=>'return isNumberKey(event)','required'))!!}

                                    <?php }else{?>
                                    {!!Form::label('vendor_price', 'Vendor Price')!!}

                                    {!!Form::text('vendor_price',$totalPriceVendor,array('class' => 'form-control', 'id' => 'vendor_price','onkeypress'=>'return isNumberKey(event)','required'))!!}
                                    <?php } ?>

                                    <?php } ?>
                                    <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==4){
                                    ?>
                                    <?php if (isset($custom->vendors_notes) ){ ?>

                                    {!!Form::label('vendor_note', 'Vendors Notes')!!}

                                    {!!Form::textarea('vendor_note', $custom->vendors_notes ,array('class' => 'form-control', 'id' => 'vendors_note') )!!}

                                    <?php }else{ ?>

                                    {!!Form::label('vendor_note', 'Vendors Notes')!!}

                                    {!!Form::textarea('vendor_note', $order_detail->requestedService->vendor_note ,array('class' => 'form-control', 'id' => 'vendors_note') )!!}
                                    <?php } ?>
                                    <?php if (isset($custom->notes_for_vendors)){ ?>
                                    {!!Form::label('notes_for_vendors', 'Notes For Vendors')!!}

                                    {!!Form::textarea('notes_for_vendors',$custom->notes_for_vendors,array('class' => 'form-control', 'id' => 'notes_for_vendors') )!!}

                                    <?php }else{?>

                                    {!!Form::label('notes_for_vendors', 'Notes For Vendors')!!}

                                    {!!Form::textarea('notes_for_vendors',$order_detail->requestedService->public_notes,array('class' => 'form-control', 'id' => 'notes_for_vendors') )!!}

                                    <?php }?>
                                    <?php }?>




                                </div>


                                <div class="row-fluid">
                                    <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

                                    <button  style="margin:40px 0 25px 0;" onclick="updateRequestedService({!!$order->id!!})" class="btn btn-large btn-success">Update </button>

                                </div>
                            </div>

                        </div>
                        <!-- Edit Service request pop modal Ends -->

                    @if(isset($items))
                        <!--     <span><h1 class="text-center">Additional Services</h1></span> -->
                        @endif

                        <div class=" " id="additionalserviceform">
                            <div class="row-fluid">
                                <div class="box span12">
                                    <?php
                                    $total_rate = array();
                                    $vendor_additional_totaled_price = '';
                                    $totalPriceCustomerFinal = "";
                                    //$totalPriceCustomerFinal += $totalPriceCustomer;
                                    ?>


                                    @foreach($items as $item)
                                        <?php
                                        $total_rate[] = $item->rate * $item->quantity;
                                        $vendor_additional_totaled_price = $item->rate * $item->quantity;
                                        ?>

                                        @if(isset($item->customer_price))

                                            <?php $totalPriceCustomerFinal += $item->customer_price * $item->admin_quantity; ?>
                                        @else

                                            <?php $totalPriceCustomerFinal += $totalPriceCustomer; ?>
                                        @endif


                                        <div class="box-header" data-original-title="">
                                            <h2>
                                                <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==3 || Auth::user()->type_id==4){ ?>
                                                <button data-toggle="modal" data-target="#edit_additional_item_{!!$item->id!!}" data-backdrop="static" ><i class="halflings-icon edit" ></i> Edit Service</button>
                                                <?php }else{ ?><i class="halflings-icon edit" ></i> <?php } ?><span class="break"></span>{!!$item->title!!}</h2>
                                            <div class="box-icon">
                                                <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==2){ ?>
                                                @if(isset($item->customer_price))

                                                    Customer Price:${!!$item->customer_price * $item->admin_quantity!!}

                                                @else

                                                    Customer Price:${!!$totalPriceCustomer!!}

                                                @endif
                                                <?php } if ( Auth::user()->type_id==3) { ?>

                                                Price:$<span id="vendor_price">{!!$vendor_additional_totaled_price!!}</span>
                                                <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                                <?php }else{ ?>

                                                Vendor Price:$<span id="vendor_price">{!!$vendor_additional_totaled_price!!}</span>
                                                <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>

                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="box-content">

                                            <table class="table">
                                                <tbody>

                                                <tr><th>Description</th>
                                                    <td style="width: 200px;">
                                                        <textarea readonly="readonly">{!! $item->description !!}</textarea>
                                                    </td>
                                                </tr>

                                                @if(isset($item->quantity ))
                                                    <tr><th>Quantity</th>
                                                        <td style="width: 200px;">
                                                            <span >{!! $item->quantity !!}</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if(Auth::user()->type_id == 3  )





                                                    <tr>
                                                        <td class="center" colspan="2">
                                                            <a href="#"  onclick="popModalAdditionalItemExport({!!$item->id!!}, 'before')" > <button   data-toggle="modal" data-backdrop="static" data-target="#export_additional_view_images_{!!$item->id!!}" class="btn btn-large btn-warning pull-right"style=" margin: 0 15px 0 0; border-radius: 2px; font-size: 18px;"> Export Images
                                                                </button></a>
                                                        </td>
                                                        <td class="center" colspan="2">

                                                                        <span class="pull-left">
                                                                            <button data-toggle="modal" data-backdrop="static" data-target="#before_{!!$item->id!!}" class="btn btn-large btn-success myBtnImg">Upload Before Images</button>
                                                                            <button data-toggle="modal" data-backdrop="static" data-target="#before_view_image_{!!$item->id!!}" onclick="popModalAdditionalItem({!!$item->id!!}, 'before')" class="btn myBtnImg">View Before Images</button>
                                                                        </span>



                                                        </td>
                                                        <td class="center" colspan="2">

                                                                        <span class="pull-left">
                                                                            <button data-toggle="modal" data-backdrop="static" data-target="#during_{!!$item->id!!}" class="btn btn-large btn-success myBtnImg">Upload During Images</button>
                                                                            <button data-toggle="modal" data-backdrop="static" data-target="#during_view_image_{!!$item->id!!}" onclick="popModalAdditionalItem({!!$item->id!!}, 'during')" class="btn myBtnImg">View During Images</button>
                                                                        </span>



                                                        </td>
                                                        <td class="center" colspan="2">

                                                        <span class="pull-left">
                                                            <button data-toggle="modal" data-backdrop="static" data-target="#after_{!!$item->id!!}" class="btn btn-large btn-success myBtnImg">Upload After Images</button>
                                                            <button data-toggle="modal" data-backdrop="static" data-target="#after_view_image_{!!$item->id!!}" onclick="popModalAdditionalItem({!!$item->id!!}, 'after')" class="btn myBtnImg">View After Images</button>
                                                        </span>



                                                        </td>
                                                    </tr>
                                                @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    {!! Form::hidden('order_image_id', "",array("id"=>"order_image_id"))!!}
    <div class="modal fade"  id="before_{!!$order->id!!}">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="row-fluid dragImage">
                        {!! Form::open(array('url' => 'add-before-images', 'class'=>'dropzone', 'id'=>'form-before-'.$order_detail->id)) !!}
                        {!! Form::hidden('order_id', $order->id,array("id"=>"order_id_for_change"))!!}
                        {!! Form::hidden('order_details_id', $order_detail->id)!!}
                        {!! Form::hidden('type', 'before')!!}
                        <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/   Modal-Section Add Before Images End   -->


    <!--/   Modal-Section Start   -->
    <!--/   Modal-Section Add Before Images Start   -->
    <div class="modal fade"  id="during_{!!$order->id!!}">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="row-fluid dragImage">
                        {!! Form::open(array('url' => 'add-during-images', 'class'=>'dropzone', 'id'=>'form-during-'.$order_detail->id)) !!}
                        {!! Form::hidden('order_id', $order->id,array("id"=>"order_id_for_change"))!!}

                        {!! Form::hidden('order_details_id', $order_detail->id)!!}
                        {!! Form::hidden('type', 'during')!!}
                        <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>
                        {!! Form::close() !!}
                    </div>
                    <!--        <div class="row-fluid">

                    </div>-->
                </div>
            </div>
        </div>
    </div>


    {!! Form::hidden('order_id', $order->id,array("id"=>"order_id_custom"))!!}


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



    <!--/   Modal-Section Add After Images Start   -->
    <div class="modal fade"  id="after_{!!$order->id!!}">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="row-fluid dragImage">
                        {!! Form::open(array('url' => 'add-after-images', 'class'=>'dropzone', 'id'=>'form-after-'.$order_detail->id)) !!}
                        {!! Form::hidden('order_id', $order->id)!!}
                        {!! Form::hidden('order_details_id', $order_detail->id)!!}
                        {!! Form::hidden('type', 'after')!!}
                        <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/   Modal-Section Add After Images End   -->

    <style>
        #export_view_images{
            overflow: auto;
        }
    </style>

    <div class="modal fade" id="view_image">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 image_prev_div">
                            </div>
                            <div class="col-md-6 image_next_div">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="view_image_div">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="change-vendor-modal">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="pull-right" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></span>
                    <label class="table-label">Change Assigned Vendor</label>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-3">New Vendor: </div>
                        <div class="col-md-6">
                            <select id="change-vendor-select" class="form-control" onchange="changeVendor()">
                                <?php
                                    $vendors = \App\User::getVendors();
                                    foreach($vendors as $vendor)
                                        {
                                            echo "<option value='".$vendor->id."'>".$vendor->first_name." ".$vendor->last_name."</option>";
                                        }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="gmap-modal">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="pull-right" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></span>
                </div>

                <div class="modal-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    <!--/   Modal-Section Show Export Images Start   -->
    <div class="modal fade" id="export_view_images">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="well text-center"><h1>Export Image</h1></div>
                    <div class="row-fluid" id="export_modal_image">
                    </div>
                    <div class="row-fluid">
                        <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

                        <button  style="margin:40px 0 25px 0;" id="save_value" class="btn btn-large btn-success" onclick="Exportpdf()">Export To PDF</button>
                        <button onclick="doit()" style="margin:40px 0 25px 0;" class="btn btn-large btn-primary">Download All Photos</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--/   Modal-Section Show Export Images End   -->


    <!--/   Modal-Section Show Before Images Start   -->
    <div class="modal fade" id="before_view_image_{!!$order->id!!}">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="well text-center"><h1>View Before Image</h1></div>
                    <div class="row-fluid" id="before_view_modal_image_{!!$order->id!!}">
                    </div>
                    <div class="row-fluid">
                        <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--/   Modal-Section Show Before Images End   -->



    <!--/   Modal-Section Show During Images Start   -->
    <div class="modal fade" id="during_view_image_{!!$order->id!!}">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="well text-center"><h1>View During Image</h1></div>
                    <div class="row-fluid" id="during_view_modal_image_{!!$order->id!!}">
                    </div>
                    <div class="row-fluid">
                        <button data-dismiss="modal" style="margin: 40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="printdata" class="clearfix"  style="display:none;  @page { size: landscape; }" >

        <div class="leftPnl" >
            <img class="inLogo" width="250px" src="{!!URL::to('/')!!}/assets/images/GSS-Logo.jpg">
            <!-- <p><strong>Good Scents Services, LP </strong> 118 National Dr <br>Rockwall TX 75032</p> -->
        </div>

        <h2>Property Address:  </h2> <span >{!!$order->maintenanceRequest->asset->property_address!!}</span> <br>
        <div id="printdata1">

        </div>
    </div>
    <!--/   Modal-Section Show Before Images End   -->


    <!--/   Modal-Section Show After Images Start   -->
    <div class="modal fade"  id="after_view_image_{!!$order->id!!}">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="well text-center"><h1>View After Image</h1></div>
                    <div class="row-fluid" id="after_view_modal_image_{!!$order->id!!}">
                    </div>
                    <div class="row-fluid">
                        <button data-dismiss="modal" style="margin: 40px 0 25px 0;" class="btn btn-large btn-success">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-confirmation">
        <div class="modal-dialog" role="dialog" style="top:33% !important">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12" style="text-align:center;">
                                <h3 class="delete-confirm-header">Delete selected photos?</h3>
                                <button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary delete-photos">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/   Modal-Section Show After Images End   -->
    <!--/   Modal-Section End   -->
    <?php if ($RecurringFlag == 1) { ?>
    <div class="modal fade"  id="recurringpopup">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="row-fluid dragImage">
                    <div>
                        <h2>Recurring Reminder</h2>
                        This is recurring service</div>
                    <br/>
                    <button class="btn btn-large btn-success" data-dismiss="modal"> Close</button>

                </div>
            </div>
        </div>
    </div>
    <?php }  ?>
    <script>

        function initMap() {
            setTimeout(function(){
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
            }, 300);
            ;
        }


    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtaE4nNK7Wdb19dUeCMitdhFv4Wy7eb30">
    </script>



@stop

