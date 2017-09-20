@extends('layouts.customer_dashboard')
@section('content')
<style>
.main-button .quick-button.reqstBtn {   color: #005580; border: 1px solid #005580; background: #F2FBFF; }
 @if($clientTypeSession=="")
#sidebar-left {display: none;}
@endif
</style>
<div id="content"  @if($clientTypeSession!="") class="span11" @endif>

      <div class="row-fluid">

                <div class="box span12 main-title">
                  <h1>Customer Dashboard</h1>
                </div>

            </div>

            <div class="row-fluid">

        <div class="span">

                    <div class="row-fluid">
                      <div class="box span12">

                            <h1>Quick View</h1>
                            <p>Please select which properties you would like to view based on the billable client type. You may switch views at any time between multiple client types by changing your client type icon in the top right hand corner of your dashboard</p>
                            <div class="box-content main-button quickTab">
 @if($clientTypeSession=="")
                            @foreach($CustomerType as $cDATA)
                                <a href="{!!URL::to('customer-client-type')!!}/{!! $cDATA->id!!}" class="span3">
                                    <span class="quick-button">
                                      <i class="fa-icon-briefcase"></i>
                                      <p>{!! $cDATA->title!!}</p>
                                    </span>
                                </a>
                            @endforeach

@else

                                <a href="{!!URL::to('view-assets-list')!!}" class="quick-button span4">
                                    <i class="fa-icon-briefcase"></i>
                                    <p>View My Properties</p>
                                </a>
                                <a href="{!!URL::to('add-new-service-request')!!}" class="quick-button span4 reqstBtn">
                                    <i class="fa-icon-cog"></i>
                                    <p>Request A Service</p>
                                </a>
                                <a href="{!!URL::to('customer-list-work-orders')!!}" class="quick-button span4">
                                    <i class="fa-icon-tasks"></i>
                                    <p>View Work Orders</p>
                                </a>
@endif
                             
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
   

 @if($clientTypeSession!="")

                    <div class="row-fluid">

                        <div class="box span6">
                            <div class="box-header">
                                <h2>Recent Service Requests</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <table class="table table-condensed">
                                      <thead>
                                          <tr>
                                              <th>Request ID</th>
                                              <th>Customer</th>
                                              <th>Property #</th>
                                              <th>City</th>
                                              <th>Status</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @foreach($maintenanceRequest as $request)
                                        <tr>
                                            <td>{!!$request->id!!}</td>
                                            <td>@if(isset($request->user->first_name)) {!!$request->user->first_name!!}@endif @if(isset($request->user->last_name)) {!!$request->user->last_name!!} @endif </td>
                                            <td>{!!$request->asset->asset_number!!}</td>
                                            <td>
                                             {!!$request->asset->city->name!!}
                                            </td>
                                            <td class="center" @if($request->emergency_request==1) style="background-color:red;" @endif>
                                              @if($request->status==1)
                                              <span class="label label-green">New Request</span>
                                              @elseif($request->status==2)
                                              <span class="label label-warning">Un Assigned</span>
                                              @elseif($request->status==3)
                                              <span class="label label-warning">Un Assigned</span>
                                              @elseif($request->status==4)
                                              <span class="label label-important">Cancelled</span>
                                              @endif
                                            </td>
                                        </tr>
                                        @endforeach

                                      </tbody>
                                 </table>
     
                            </div>
                        </div><!--/span-->


                        <div class="box span6">
                            <div class="box-header">
                                <h2>Recent Completed Request</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <table class="table table-condensed">
                                      <thead>
                                          <tr>
                                              <th>Req No</th>
                                              <th><i class="fa-icon-caret-up"></i> Property #</th>
                                              <th>Services</th>
                                          
                                             
                                          </tr>
                                      </thead>
                                      <tbody>
                                      @foreach($completeorder as $orders)
                                        <tr>
                                            <td>{!! $orders['request_id']!!}</td>
                                            <td class="middle">{!! $orders['asset_number']!!}</td>
                                            <td>{!!$orders['service_name']!!}</td>
                                          
                                                           </tr>
                                        @endforeach
                                      </tbody>
                                 </table>
                               
                            </div>
                        </div>

                    </div>

                    <div class="row-fluid">

                        <div class="box span6">
                            <div class="box-header">
                                <h2>Recent Work Orders</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            
                              <div class="box-content">
                                <table class="table table-condensed">
                                      <thead>
                                          <tr>
                                              <th>Req No</th>
                                              <th><i class="fa-icon-caret-up"></i> Property #</th>
                                              <th>Services</th>
                                      
                                             
                                          </tr>
                                      </thead>
                                      <tbody>
                                      @foreach($list_complete_orders as $orders)
                                        <tr>
                                            <td>{!! $orders['request_id']!!}</td>
                                            <td class="middle">{!! $orders['asset_number']!!}</td>
                                            <td>{!!$orders['service_name']!!}</td>
                                           
                                            <td class="center"> <span class="label label-{!! $orders['status_class'] !!}">{!! $orders['status_text'] !!}</span> </td>
                                                           </tr>
                                        @endforeach
                                      </tbody>
                                 </table>
                               
                            </div>
                        </div>

                        <div class="box span6">
                            <div class="box-header">
                                <h2>Recent Properties</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <table class="table table-condensed">
                                      <thead>
                                          <tr>
                                              <th>Property #</th>
                                              <th>Property Address</th>
                                              <th>Loan #</th>
                                              <th>Status</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                      @foreach($assets as $assetData)
                                        <tr>
                                            <td>{!!$assetData->asset_number!!}</td>
                                            <td class="middle">{!!$assetData->property_address!!}</td>
                                            <td>{!!$assetData->loan_number!!}</td>
                                            <td class="center"> {!! isset($assetData->status) && $assetData->status == 1 ? '<span class="label label-success">Active</span>' : '<span class="label">Inactive</span>' !!}

                                        </tr>
                                        @endforeach
                                        
                                      </tbody>
                                 </table>
                              
                            </div>
                        </div>

                    </div>
@endif
        </div><!--/span-->

      </div><!--/row-->

      </div>
@stop