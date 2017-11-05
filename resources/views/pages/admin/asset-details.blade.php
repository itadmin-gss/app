@extends('layouts.default')
@section('content')
    <title>GSS - Property Details for {!! $property_details->property_address !!}</title>
    <div id="content">
        <h4 class="text-center">Details for {!! $property_details->property_address !!}</h4>
        <hr>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">

                <div class="row">
                       <div class="col-md-5 col-lg-5 col-sm-12">
                           <h5 class="">Property Information</h5>
                           <table class="table table-small">
                               <tbody>
                                   <tr>
                                      <td>Property Address:</td>
                                      <td>
                                          {!! $property_details->property_address !!}
                                          <br>{!! $city !!}, {!! $state !!}  {!! $property_details->zip !!}
                                          <span class="pull-right"><button class="btn btn-success btn-sm">Edit</button></span>
                                      </td>
                                   </tr>
                                   <tr>
                                       <td>Customer:</td>
                                       <td>
                                           {!! $customer_info->first_name !!} {!! $customer_info->last_name !!}
                                           <span class="pull-right"><button class="btn btn-success btn-sm">Edit</button></span>
                                       </td>
                                   </tr>

                                   @if (isset($customer_info->email))
                                       <tr>
                                           <td>Customer Email:</td>
                                           <td>
                                               {!! $customer_info->email !!}
                                               <span class="pull-right"><button class="btn btn-success btn-sm">Edit</button></span>
                                           </td>
                                       </tr>
                                   @endif

                                   @if (isset($customer_info->company))
                                       <tr>
                                           <td>Customer Company:</td>
                                           <td>
                                               {!! $customer_info->company !!}
                                               <span class="pull-right"><button class="btn btn-success btn-sm">Edit</button></span>
                                           </td>
                                       </tr>
                                   @endif

                                   @if (isset($property_details->lock_box) && $property_details->lock_box !== '')
                                       <tr>
                                           <td>Lock Box:</td>
                                           <td>
                                               {!! $property_details->lock_box !!}
                                               <span class="pull-right"><button class="btn btn-success btn-sm">Edit</button></span>
                                           </td>
                                       </tr>
                                   @endif

                                   @if (isset($property_details->access_code) && $property_details->access_code !== '')
                                       <tr>
                                           <td>Access Code:</td>
                                           <td>
                                               {!! $property_details->access_code !!}
                                               <span class="pull-right"><button class="btn btn-success btn-sm">Edit</button></span>
                                           </td>
                                       </tr>
                                   @endif


                                </tbody>
                           </table>

                       </div>
                        <div class="col-md-1 col-lg-1 col-sm-12"></div>
                        <div class="col-md-5 col-lg-5 col-sm-12">
                            <h5>Property Photo</h5>

                        </div>
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