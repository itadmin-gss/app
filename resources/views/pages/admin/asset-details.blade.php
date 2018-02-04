@extends('layouts.default')
@section('content')

    <?php
        use App\CustomerType;
        ?>

    <title>GSS - Property Details for {!! $property_details->property_address !!}</title>
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
        <div class="row">


            <div class="col-md-12 col-lg-12 col-sm-12">

                <div style="width:100%;margin-top:20px;">
                    <div class="work-orders-tab work-orders-tab-active" data-id="5">New Requests</div>
                    <div class="work-orders-tab" data-id="1">Work Orders</div>
                    {{--<div class="work-orders-tab" data-id="2">Map</div>--}}
                    <div class="work-orders-tab" data-id="3">Vendors</div>
                    {{--<div class="work-orders-tab" data-id="4">Invoicing</div>--}}
                    <a href="{!! URL::to('/admin-add-new-service-request/'.$property_details->id) !!}">
                        <button type="button" class="btn btn-success add-work-order">Add Work Order</button>
                    </a>
                    <hr style="margin-top:0px;">
                </div>

                <div id="property_map" class="hide">
                    <p>Map</p>
                </div>

                <div id="new-requests-table">

                    <?php
                            $requestsNew = \App\MaintenanceRequest::where('asset_id', $property_details->id)->where('status', '=', 1)->orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')->get();
        $requests = \App\MaintenanceRequest::where('asset_id', $property_details->id)->orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')->get();

        $request_ids = [];
        $request_service_ids = [];
        $assigned_request_ids = [];
        $numberofrequestids = [];
        foreach ($requests as $mdata) {
            $request_service_ids = [];
            $request_ids[] = $mdata->id;
            foreach ($mdata->requestedService as $rqdata) {
                $request_service_ids[] = $rqdata->id;
            }
            $assigned_request_ids = [];
            $assign_requests = \App\AssignRequest::where('request_id', '=', $mdata->id)
                ->where('status', "!=", 2)
                ->select('request_id')->get();

            foreach ($assign_requests as $adata) {
                $assigned_request_ids[] = $adata->request_id;
            }

            $numberofrequestids['requested_services_count'][$mdata->id] = count($request_service_ids);
            $numberofrequestids['assigned_services_count'][$mdata->id] = count($assigned_request_ids);
        }
        ?>
                    <table class="table table-striped table-bordered table-sm datatabledashboard" id='dataTable' cellspacing='0' >


                        <thead>
                        <tr>

                            <th>ID #</th>
                            <th>Submitter</th>
                            <th>Client Type</th>
                            <th>Customer</th>
                            <th>Property Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                            <th>Service(s)</th>
                            <th>Requested</th>
                            <th>Due</th>



                        </tr>

                        </thead>
                        <tbody>
                        @if (!isset($grid))

                            @foreach ($requestsNew as $rm)

                                @if($numberofrequestids['requested_services_count'][$rm->id]!=$numberofrequestids['assigned_services_count'][$rm->id])

                                    <tr>



                                        <td @if($rm->emergency_request==1) style="background-color:red;" @endif>
                                            <a @if($rm->status==4) disabled='disabled' @else href="{!! URL::to("view-maintenance-request/".$rm->id) !!}" @endif title="View">{!! $rm->id !!}</a>
                                        </td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user2->first_name)) {!! $rm->user2->first_name !!} {!! substr($rm->user2->last_name,0,1)."." !!} @endif</td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->asset->customerType->title)) {!!  $rm->asset->customerType->title !!} @endif</td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user->first_name)) {!! $rm->user->first_name !!} @endif   @if(isset($rm->user->last_name)) {!! $rm->user->last_name !!}@endif</td>


                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->asset->property_address)) <a href="{!! URL::to("asset/".$rm->asset->id) !!}">{!! $rm->asset->property_address !!}</a> @endif @if (isset($rm->asset->unit)) #{!! $rm->asset->unit !!}@endif</td>



                                        <?php if(isset($rm->asset->city->name)){?>
                                        <td @if($rm->emergency_request==1) style="background-color:red;" @endif>  {!! $rm->asset->city->name !!}</td>
                                        <?php } else {?>
                                        <td></td>

                                        <?php } ?>

                                        <?php if(isset( $rm->asset->state->name)){?>
                                        <td @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $rm->asset->state->name !!}</td>
                                        <?php   } else {?>
                                        <td></td>

                                        <?php } ?>




                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->asset->zip)) {!!  $rm->asset->zip !!} @endif </td>

                                        <?php
                                        $servicedate="";
                                        $due_date="";
                                        foreach ($rm->requestedService as  $value) {
                                            if(isset( $value->service->title))
                                            {
                                                $servicedate .=  $value->service->title;
                                            }


                                            if(isset($value->due_date))
                                            {
                                                $style="";
                                                if( (strtotime(date('m/d/Y'))>strtotime($due_date)))
                                                {
                                                    $style='style="background-color:yellow;"';
                                                }
                                                $due_date .= "<span ".$style."> ". $value->due_date . '</span> <br>';
                                            }
                                            else
                                            {

                                                $due_date .=   'Not Set<br>';



                                            }
                                            $servicedate .=   ' <br>';

                                        }
                                        ?>

                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $servicedate !!} </td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!!date('m/d/Y',strtotime( $rm->created_at )) !!}</td>

                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $due_date !!} </td>



                                    </tr>


                                @elseif(1==2)

                                    <tr>


                                        <td @if($rm->emergency_request==1) style="background-color:red;" @endif>
                                            <a @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View">{!! $rm->id !!}</a>
                                        </td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user2->first_name)) {!! $rm->user2->last_name !!} @endif</td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!! $rm->user->first_name !!} {!! $rm->user->last_name !!}</td>

                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!! $rm->user->email !!}</td>

                                        <?php

                                        $servicedate="";
                                        foreach ($rm->requestedService as  $value) {
                                            if(isset( $value->service->title))
                                                $servicedate .=  $value->service->title  ;

                                            if(isset($value->due_date))
                                            {
                                                $servicedate .= "<br>".    $value->due_date . ', <br>';
                                            }
                                            else
                                            {
                                                $servicedate .=   ', <br>';


                                            }

                                        }
                                        $assignedUsers="";

                                        foreach ($rm->assignRequest as  $assignRequestData) {

                                            $servicetitle="";
                                            if(isset($assignRequestData->requestedService->service->title))
                                            {
                                                $servicetitle=$assignRequestData->requestedService->service->title;
                                            }

                                            $first_name="";
                                            if(isset($assignRequestData->user->first_name))
                                            {
                                                $first_name=$assignRequestData->user->first_name;
                                            }


                                            $last_name="";
                                            if(isset($assignRequestData->user->last_name))
                                            {
                                                $last_name=$assignRequestData->user->last_name;
                                            }
                                            $assignedUsers .= "Service :".   $servicetitle ." <br/>Vendor:". $first_name." ".$last_name."<br/>---------<br/>" ;
                                        }

                                        ?>

                                        <td  @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!$assignedUsers!!}</td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->property_address !!}</td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->city->name !!}</td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->state->name !!}</td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->zip !!}</td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!!date('m/d/Y h:i:s A',strtotime( $rm->created_at )) ;!!} </td>
                                        <?php
                                        $servicedate="";
                                        foreach ($rm->requestedService as  $value) {
                                            if(isset( $value->service->title))
                                                $servicedate .=  $value->service->title  ;

                                            if(isset($value->due_date))
                                            {
                                                $servicedate .= "<br>".    $value->due_date . ', <br>';
                                            }
                                            else
                                            {
                                                $servicedate .=   ', <br>';


                                            }

                                        }
                                        ?>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
                                            @if(isset($rm->jobType->title))
                                                {!!$rm->jobType->title!!}

                                            @endif
                                        </td>
                                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $servicedate !!} </td>



                                    </tr>



                                @endif
                            @endforeach
                        </tbody>
                        @endif
                    </table>
                </div>

                <div id="property_vendors" class="hide">
                    <table class="table table-striped dt-responsive datatabledashboard">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Company</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                foreach($vendors_found as $vendor)
                                    {
                                        echo "<tr>";
                                        echo "<td>".$vendor[0]->id."</td>";
                                        echo "<td>".$vendor[0]->first_name."</td>";
                                        echo "<td>".$vendor[0]->last_name."</td>";
                                        echo "<td>".$vendor[0]->company."</td>";
                                        echo "<td>".$vendor[0]->address_1."<br>".$vendor[0]->address_2."</td>";
                                        echo "<td>".$vendor[0]->city_id."</td>";
                                        echo "<td>".$vendor[0]->state_id."</td>";
                                        echo "<td>".$vendor[0]->zip."</td>";
                                        echo "</tr>";
                                    }
                            ?>

                        </tbody>
                    </table>
                </div>

                <div id="property_invoicing" class="hide">
                    <table class="table table-striped dt-responsive datatabledashboard">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Client Type</th>
                                <th>Vendor Name @ Vendor Company</th>
                                <th>Service(s)</th>
                                <th>Approved Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($order_details as $key => $detail)
                            @if (isset($order_details[$key]["order_details"]))
                                @foreach($order_details[$key]["order_details"] as $id => $order_detail)
                                    @if (strtolower($order_detail['status']) == 'approved' || strtolower($order_detail['status']) == 'exported')
                                    <tr>
                                        <td><a href="{!! URL::to("edit-order/".$id) !!}">{!! $id !!}</a></td>
                                        <td>
                                            <?php
                                            $customer_details = CustomerType::find($customer_info->customer_type_id);
                                            if (isset($customer_details->title))
                                            {
                                                echo $customer_details->title;
                                            }
                                            else
                                            {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            @if (isset($order_detail["vendor_name"])) {!! $order_detail["vendor_name"] !!} @endif
                                            @if (isset($order_detail["vendor_company"]))@ {!! $order_detail["vendor_company"] !!} @endif
                                            @if (!isset($order_details['vendor_name']) && !isset($order_details['vendor_company'])) Not Set @endif
                                        </td>

                                        <td>
                                            @if (isset($order_detail["requested_services"]))
                                            @foreach($order_detail["requested_services"] as $services)
                                                @if (isset($services->title))
                                                {!! $services->title !!} <br>
                                                    @endif
                                            @endforeach
                                            @endif
                                        </td>

                                        <td>{!! $order_detail['approved'] !!}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        </tbody>

                    </table>
                </div>
                <div id="property_work_orders" class="hide">
                    <table class="table table-striped table-small dt-responsive datatabledashboard3" style="width:100%;">
                        <thead>
                        <th>ID #</th>
                        <th>Client Type</th>
                        <th>Vendor Name @ Vendor Company</th>
                        <th>Service(s)</th>
                        <th>Status</th>

                        </thead>
                        <tbody>
                        @foreach($order_details as $key => $detail)
                            @if (isset($order_details[$key]["order_details"]))
                                @foreach($order_details[$key]["order_details"] as $id => $order_detail)
                                    <tr>
                                        <td><a href="{!! URL::to("edit-order/".$id) !!}">{!! $id !!}</a></td>
                                        <td>
                                            <?php
                                            $customer_details = CustomerType::find($customer_info->customer_type_id);
                                            if (isset($customer_details->title))
                                            {
                                                echo $customer_details->title;
                                            }
                                            else
                                            {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            @if (isset($order_detail["vendor_name"])) {!! $order_detail["vendor_name"] !!} @endif
                                            @if (isset($order_detail["vendor_company"]))@ {!! $order_detail["vendor_company"] !!} @endif
                                            @if (!isset($order_details['vendor_name']) && !isset($order_details['vendor_company'])) Not Set @endif
                                        </td>

                                        <td>
                                            @if (isset($order_detail["requested_services"]))
                                                @foreach($order_detail["requested_services"] as $services)
                                                    @if (isset($services->title))
                                                    {!! $services->title !!} <br>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{!! $order_detail["status"] !!}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div> <!-- End Row -->

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