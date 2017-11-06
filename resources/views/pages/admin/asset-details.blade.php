@extends('layouts.default')
@section('content')
    <title>GSS - Property Details for {!! $property_details->property_address !!}</title>
    <div id="content">
        <h4>{!! $property_details->property_address !!} {!! $city !!}, {!! $state !!}  {!! $property_details->zip !!}</h4>
        <hr>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">

                <div class="row">
                    <div class="col-md-5 col-lg-5 col-sm-12">
                        <div class="property-photo">

                        </div>
                    </div>
                       <div class="col-md-5 col-lg-5 col-sm-12">



                           <h5 class="">Property Information</h5>
                           <table class="table table-small">
                               <tbody>

                                   <tr>
                                       <td>Property Number:</td>
                                       <td>
                                           @if (isset($property_details->asset_number)) {!! $property_details->asset_number !!} @endif
                                           <span class="pull-right"><button class="btn btn-success btn-sm">Edit</button></span>
                                       </td>
                                   </tr>

                                   <tr>
                                      <td>Property Address:</td>
                                      <td>
                                          {!! $property_details->property_address !!}
                                          <br>{!! $city !!}, {!! $state !!}  {!! $property_details->zip !!}
                                      </td>
                                   </tr>

                                   <tr>
                                       <td>Property Type:</td>
                                       <td>
                                           @if (isset($property_details->property_type)) {!! $property_details->property_type !!} @endif
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>Customer:</td>
                                       <td>
                                           @if (isset($customer_info->first_name)) {!! $customer_info->first_name !!} @endif @if (isset($customer_info->last_name)){!! $customer_info->last_name !!} @endif
                                       </td>
                                   </tr>


                                       <tr>
                                           <td>Customer Email:</td>
                                           <td>
                                               @if (isset($customer_info->email)) {!! $customer_info->email !!} @endif
                                           </td>
                                       </tr>

                                       <tr>
                                           <td>Customer Company:</td>
                                           <td>
                                               @if (isset($customer_info->company)) {!! $customer_info->company !!} @endif
                                           </td>
                                       </tr>


                                       <tr>
                                           <td>Lock Box:</td>
                                           <td>
                                               @if (isset($property_details->lock_box)) {!! $property_details->lock_box !!} @endif
                                           </td>
                                       </tr>

                                       <tr>
                                           <td>Access Code:</td>
                                           <td>
                                               @if (isset($property_details->access_code)) {!! $property_details->access_code !!} @endif
                                           </td>
                                       </tr>

                                        <tr>
                                            <td>Loan Number:</td>
                                            <td>
                                                @if (isset($property_details->loan_number)) {!! $property_details->loan_number !!} @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Property Status</td>
                                            <td>
                                                @if (isset($property_details->property_status)) {!! ucwords($property_details->property_status) !!} @endif
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
        </div>
    </div>
@stop