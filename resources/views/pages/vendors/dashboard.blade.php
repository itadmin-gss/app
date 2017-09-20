@if(!Request::ajax())


@extends('layouts.vendordashboard')


@section('content')


<div id="content" class="span11">


@endif


<!-- start: Content -->








    <div class="row-fluid">





        <div class="box span12 main-title">


            <h1>Vendor Dashboard</h1>


        </div><!--/span-->





    </div><!--/row-->








    <div class="row-fluid">





        <div class="span">





            <div class="row-fluid">


                <div class="box span12">





                    <h1>Quick View</h1>





                    <div class="box-content main-button">





                        <a href="{!!URL::to('vendor-bid-requests')!!}" class="quick-button span4">


                            <i class="fa-icon-briefcase"></i>


                            <p>OSR</p>


                        </a>


                        <a href="{!!URL::to('vendor-list-orders')!!}" class="quick-button span4">


                            <i class="fa-icon-cog"></i>


                            <p>Work Orders</p>


                        </a>


                        <a href="{!!URL::to('vendor-list-invoice')!!}" class="quick-button span4">


                            <i class="fa-icon-tasks"></i>


                            <p>Invoices</p>


                        </a>





                        <div class="clearfix"></div>


                    </div>


                </div>


            </div>


<!-- <div class="row-fluid">


        <div class="box span12">


            <div class="box-header" data-original-title>


                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Summary</h2>





            </div>


            <div class="box-content">


                <table class="table table-striped table-bordered bootstrap-datatable datatable">





                    <thead>





                            <tr>


                                              <th id="dashboardclick">Req No</th>


                                              <th>Property #</th>


                                              <th>Property Address </th>


                                              <th>Request Date</th>


                                              <th>No. of Services</th>


                                              <th>Service </th>





                                              <th>Work Order #</th>





                                              <th>Invoice#</th>


                                              <th>Vendor Price</th>





                                              <th>Action</th>








                                          </tr>


                    </thead>


                    <tbody>





                        @foreach ($assign_requests as $assign_request)


                        <tr>





                           <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['request_id'] !!}</td>


                           <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['asset_number'] !!}</td>


                                                        <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['property_address'] !!}</td>                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['request_date'] !!}</td>


                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['no_of_services'] !!}</td>





                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['service_name'] !!}</td>





                             <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['order_id'] !!}</td>





                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['invoiceNo'] !!}</td>


                           <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif> {!! $assign_request['vendor_price'] !!}


            </td>


                            <td class="center popover-examples" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif><a class="btn btn-success" href="view-vendor-maintenance-request/{!!  $assign_request['request_id'] !!}"> <i class="halflings-icon zoom-in halflings-icon"></i> </a></td>


                        </tr>





                        @endforeach





                    </tbody>


                </table>


            </div>


        </div><!--/span





    </div> -->














        </div><!--/span-->





    </div><!--/row-->


                        <div class="row-fluid">





                        <div class="box span6">


                            <div class="box-header">


                                <h2>Recent Service Requests <a class="btn btn-info" href="{!!URL::to('vendor-assigned-requests')!!}" title="View All">View All</a></h2>


                                <div class="box-icon">


                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>


                                </div>


                            </div>


                            <div class="box-content">


                                <table class="table table-condensed">


                                      <thead>


                                          <tr>


                                              <th>Request ID</th>


                                              <th> Property Address</th>


                                              <th>Services/Due Date</th>





                                          </tr>


                                      </thead>


                                      <tbody>


                                        <tr>





                                        </tr>





                                         @foreach ($assign_requests as $assign_request)


                                        <tr>


                                            <td @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['request_id'] !!}</td>


                                            <td @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['property_address'] !!}</td>





                                             <td @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['service_name'] !!}</td>








                                        </tr>





                                        @endforeach


                                      </tbody>


                                 </table>


                            </div>


                        </div>











                        <div class="box span6">


                            <div class="box-header">


                                <h2>Recent Work Orders  <a class="btn btn-info" href="{!!URL::to('vendor-list-orders')!!}" title="View All">View All</a> </h2>


                                <div class="box-icon">


                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>


                                </div>


                            </div>


                            <div class="box-content">


                                <table class="table table-condensed">


                                      <thead>


                                          <tr>


                                              <th>Order ID</th>


                                              <th> Property Address</th>


                                              <th>Services/Due Date</th>


                                              <th>Status</th>


                                          </tr>


                                      </thead>


                                      <tbody>


                                        <tr>





                                        </tr>





                                        @foreach ($recent_orders as $order)


                                        <tr>


                                            <td>{!! $order->id !!}</td>


                                            <td>{!! $order->maintenanceRequest->asset->property_address !!}</td>


                                             <?php


                                              $service_name = '';


                                              $order_details = ($order->orderDetail);


                                              foreach ($order_details as $order_detail) {





                                                if($order_detail->requestedService->due_date!=""){


                                               $service_name.=$order_detail->requestedService->service->title ." /". $order_detail->requestedService->due_date. ', <br>';


                                             }


                                             else


                                             {


                                               $service_name.=$order_detail->requestedService->service->title ." / Not Set " . ', <br>';





                                             }





                                             }





                                             ?>


                                             <td>{!! $service_name!!}</td>





                                            <td>


                                             <span class="label label-@if($order->status==1){!!'warning'!!}@else{!!$order->status_class!!}@endif">@if($order->status==1) In-Progress @else {!!$order->status_text!!} @endif</span>





                                            </td>


                                        </tr>





                                        @endforeach


                                      </tbody>


                                 </table>


                            </div>


                        </div>














                              <div class="box span6" style="margin-left: 0px;">


                            <div class="box-header">


                                <h2>Recent Invoices  <a class="btn btn-info" href="{!!URL::to('vendor-list-invoice')!!}" title="View All">View All</a></h2>


                                <div class="box-icon">


                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>


                                </div>


                            </div>


                            <div class="box-content">


                                <table class="table table-condensed">


                                      <thead>


                                          <tr>


                                              <th>Order ID</th>


                                              <th>Service</th>


                                              <th>Status</th>


                                              <th>Price</th>


                                          </tr>


                                      </thead>


                                      <tbody>


                                        <tr>





                                        </tr>





                                      @foreach ($list_orders as $order)





                                     <tr>


                                            <td>{!! $order['order_id'] !!}</td>


                                       <td>{!! $order['service_name'] !!}</td>


                                         <td class="center"> <span class="label label-gray">@if($order['status']==1) Approved @else Deactive @endif </span> </td>


                                             <td>{!! $order['price'] !!}</td>








                                        </tr>





                                        @endforeach


                                      </tbody>


                                 </table>


                            </div>


                        </div>








     <div class="box span6">


                            <div class="box-header">


                                <h2>Recent Bids  <a class="btn btn-info" href="{!!URL::to('vendor-bid-requests')!!}" title="View All">View All</a></h2>


                                <div class="box-icon">


                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>


                                </div>


                            </div>


                            <div class="box-content">


                                <table class="table table-condensed">


                                      <thead>


                                          <tr>


                                              <th>Bid ID</th>


                                              <th> Property Address</th>


                                              <th>Services</th>





                                          </tr>


                                      </thead>


                                      <tbody>


                                        <tr>





                                        </tr>





                                     @foreach ($assign_requests_bids as $assign_request)





                                       <tr>


                                            <td>{!! $assign_request['request_id'] !!}</td>


                                            <td>{!! $assign_request['property_address'] !!}</td>





                                             <td>{!! $assign_request['service_name'] !!}</td>








                                        </tr>





                                        @endforeach


                                      </tbody>


                                 </table>


                            </div>


                        </div>


                        <div class="box span6">


                            <div class="box-header">


                                <h2>Recent New Work Orders  <a class="btn btn-info" href="{!!URL::to('vendor-list-orders')!!}" title="View All">View All</a> </h2>


                                <div class="box-icon">


                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>


                                </div>


                            </div>


                            <div class="box-content">


                                <table class="table table-condensed">


                                      <thead>


                                          <tr>


                                              <th>Order ID</th>


                                              <th> Property Address</th>


                                              <th>Services/Due Date</th>


                                              <th>Status</th>


                                          </tr>


                                      </thead>


                                      <tbody>


                                        <tr>





                                        </tr>





                                        @foreach ($new_work_orders as $order)


                                        <tr>


                                            <td>{!! $order->id !!}</td>


                                            <td>{!! $order->maintenanceRequest->asset->property_address !!}</td>


                                             <?php


                                              $service_name = '';


                                              $order_details = ($order->orderDetail);


                                              foreach ($order_details as $order_detail) {





                                                if($order_detail->requestedService->due_date!=""){


                                               $service_name.=$order_detail->requestedService->service->title ." /". $order_detail->requestedService->due_date. ', <br>';


                                             }


                                             else


                                             {


                                               $service_name.=$order_detail->requestedService->service->title ." / Not Set " . ', <br>';





                                             }





                                             }





                                             ?>


                                             <td>{!! $service_name!!}</td>





                                            <td>


                                             <span class="label label-@if($order->status==1){!!'warning'!!}@else{!!$order->status_class!!}@endif">@if($order->status==0) New Work Order @endif</span>





                                            </td>


                                        </tr>





                                        @endforeach


                                      </tbody>


                                 </table>


                            </div>


                        </div>








<!-- end: Content -->


@if(!Request::ajax())


</div>


@stop


@endif


