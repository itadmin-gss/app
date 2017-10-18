@extends('layouts.default')
@section('content')

<div id="content" class="span11">



  <?php
  $unassigned=0;
  $assigned=0;
  $i=1;
  foreach ($requests as $rm) {
    $request_service_ids=array();
    foreach ($rm->assignRequest as $rqdata) {
        $request_service_ids[] = $rqdata->status;
    }
    if($numberofrequestids['requested_services_count'][$rm->id]!=$numberofrequestids['assigned_services_count'][$rm->id])
    {
        if($rm->status==2)
            $unassigned++;

        } else{

        if($rm->status==0 && in_array(1,$request_service_ids) )
        $assigned++;

    }

}

          ?>
    <div class='table-padding'>
        <ol class="breadcrumb ">
            <li class="breadcrumb-item">
                <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">My Dashboard</li>
        </ol>
    </div>

    <div class="row table-padding">
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="mr-5">
              @if (isset($orderCounterDashboard['1']))
                {!! $orderCounterDashboard['1']." In-Process" !!}
              @else
                {!! "0 In-Process" !!}
             @endif
            
            </div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#" onclick="ajaxDashboardGridOrders('1', 'inprocess')">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="mr-5">
                @if (isset($orderCounterDashboard['3']))
                    {!! $orderCounterDashboard['3']." Under Review" !!}
                @else
                    {!! "0 Under Review" !!}
                @endif
              </div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#" onclick="ajaxDashboardGridOrders('3', 'underreview')">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card o-hidden text-white bg-success h-100">
              <div class="card-body">

                <div class="mr-5">
                    @if (isset($orderCounterDashboard['4']))
                        {!! $orderCounterDashboard['4']." Approved" !!}
                    @else
                        {!! "0 Approved" !!}
                    @endif
                </div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#" onclick="ajaxDashboardGridOrders('4', 'approved')">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fa fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card  o-hidden h-100">
                <div class="card-body">
                  <div class="mr-5">
                    @if (isset($orderCounterDashboard['2']))
                        {!! $orderCounterDashboard['2']." Completed" !!}
                    @else
                        {!! "0 Completed" !!}
                    @endif
                  </div>
                </div>
                <a class="card-footer clearfix small z-1" href="#" onclick="ajaxDashboardGridOrders('2', 'complete')">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
      </div>

            <div class="row-fluid">

        <div class="span">
                 @if(Session::has('message'))
            {!!Session::get('message')!!}
         @endif


        @if( isset($access_roles['Summary Grid']['view']) && $access_roles['Summary Grid']['view'] == 1)
                  <div class="row-fluid">

                   

                              <div class="admtableInr" id="datatabledashboard">


                                <div class='table-container'>
                                 
                                <div class='table-responsive'>
                                <div class='table-padding table-heading'>
                                    <h4>Summary</h4>
                                </div>
                                
                                 <table class="table table-striped table-bordered table-sm dt-responsive datatabledashboard"  id='dataTable' width='100%' cellspacing='0' >


          <thead>
            <tr>

              <th>Request ID</th>

              <th>Request Date</th>
              <th>Client Type</th>

              <th>Submitter</th>

              <th>Customer</th>

              <th>Email</th>

              <th>Property Address</th>

              <th>City</th>

              <th>State</th>

              <th>Zip</th>

              <th>Job Types</th>

              <th>Services Type</th>

              <th>Due Date</th>

              <th>Status</th>

              <th>Action</th>



            </tr>

          </thead>
          <tbody>

          @foreach ($requestsNew as $rm)

          @if($numberofrequestids['requested_services_count'][$rm->id]!=$numberofrequestids['assigned_services_count'][$rm->id])

        <tr>

            

            <td @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $rm->id !!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!!date('m/d/Y h:i:s A',strtotime( $rm->created_at )) ;!!} </td>

                      <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->asset->customerType->title)) {!!  $rm->asset->customerType->title !!} @endif</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user2->first_name)) {!! $rm->user2->last_name !!} @endif</td>

             <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user->first_name)) {!! $rm->user->first_name !!} @endif   @if(isset($rm->user->last_name)) {!! $rm->user->last_name !!}@endif</td>

            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->user->email)) {!! $rm->user->email !!} @endif  </td>

           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->asset->property_addresss)) {!! $rm->asset->property_address !!} @endif </td>



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
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
            @if(isset($rm->jobType->id))
                {!!$rm->jobType->title!!}

            @endif
            </td>
  <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $servicedate !!} </td>
   <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $due_date !!} </td>

            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
            @if($rm->status==1)
            <span class="badge badge-success badge-summary">New Request</span>
            @elseif($rm->status==2)
            <span class="badge badge-warning badge-summary">Un Assigned</span>
            @elseif($rm->status==3)
            <span class="badge badge-warning badge-summary">Un Assigned</span>
            @elseif($rm->status==4)
            <span class="badge badge-danger badge-summary">Cancelled</span>
            @endif

           </td>

            <td class="center popover-examples" @if($rm->emergency_request==1) style="background-color:red;" @endif>
                <div class='action-button-group'>
                    <a class="btn btn-xs btn-success action-button"  @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View"> 
                        <i class="fa fa-search-plus" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-xs btn-danger action-button" href="cancel-maintenance-request/{!! $rm->id !!}" title="Cancel" onclick="return confirm('Are you sure you want to delete?')"> 
                        <i class='fa fa-trash' aria-hidden='true'></i>
                    </a>
                </div>
            </td>
          </tr>


          @elseif(1==2)

            <tr>


            <td @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $rm->id !!}</td>
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
            @if(isset($rm->jobType->id))
                {!!$rm->jobType->title!!}

            @endif
            </td>
          <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $servicedate !!} </td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
             @if($rm->status==4)
            <span class="badge badge-danger badge-summary">Cancelled</span>
            @else
             <span class="badge badge-default badge-summary">Assigned</span>
            @endif
           </td>

            <td class="center popover-examples" @if($rm->emergency_request==1) style="background-color:red;" @endif>
                <a class="btn btn-success"  @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View"> 
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                </a>
                <a class="btn btn-danger" href="cancel-maintenance-request/{!! $rm->id !!}" title="Cancel" onclick="return confirm('Are you sure you want to delete?')"> 
                    <i class='fa fa-trash' aria-hidden='true'></i>
                </a>
            </td>
          </tr>



          @endif
          @endforeach
            </tbody>

        </table>
        </div>
                                 </div>
                            </div>
                        </div><!--/span-->
                      </div>

@endif






                     @if( isset($access_roles['Work Order in Proces']['view']) && $access_roles['Work Order in Proces']['view'] == 1)
                          <div class="row-fluid">
                        <div class="box span12 table-padding">
                            <div class="box-header">
                                <h4>Work Order in Process</h4>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                
                                <table class="table table-condensed table-bordered">
                                      <thead>
                                          <tr>
                                              <th>Sr#</th>
                                              <th>Order ID</th>
                                              <th><i class="fa-icon-caret-up"></i>Customer Name</th>
                                              <th>Property #</th>
                                              <th>Vendor Name</th>
                                              <th>Order Date</th>
                                          </tr>
                                      </thead>
                                      <tbody>

                                        @foreach ($orders_process as $order)
                                       <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{!! $order->id !!}</td>
                                            <td class="middle">
                                               @if(isset($order->customer->first_name)) {!! $order->customer->first_name !!} @endif   @if(isset($order->customer->last_name)){!! $order->customer->last_name !!}  @endif
                                            </td>
                                            <td >@if(isset($order->maintenanceRequest->asset->asset_number)){!! $order->maintenanceRequest->asset->asset_number !!} @endif</td>
                                            <td>   @if(isset($order->vendor->first_name)){!! $order->vendor->first_name !!} @endif    @if(isset($order->vendor->first_name)) {!! $order->vendor->last_name !!} @endif</td>
                                            <td>{!! date("d F Y",strtotime($order->created_at)) !!}</td>
                                        </tr>

                                        @endforeach
                                      </tbody>
                                 </table>
                            </div>
                        </div><!--/span-->
                          </div>
                          @endif


                            @if( isset($access_roles['Work Order Approval']['view']) && $access_roles['Work Order Approval']['view'] == 1)
                               <div class="row-fluid">
                        <div class="box span12 table-padding">
                            <div class="box-header">
                                <h4>Work Order Approval Request</h4>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <table class="table table-condensed table-bordered">
                                      <thead>
                                          <tr>
                                              <th>Sr#</th>
                                              <th>Order ID</th>
                                              <th><i class="fa-icon-caret-up"></i>Customer Name</th>
                                              <th>Property #</th>
                                              <th>Vendor Name</th>
                                              <th>Order Date</th>
                                          </tr>
                                      </thead>
                                      <tbody>

                                        @foreach ($orders_completed as $order)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>
                                            <td>{!! $order->id !!}</td>
                                            <td class="middle">
                                                @if(isset($order->customer->first_name)) {!! $order->customer->first_name !!} @endif   @if(isset($order->customer->last_name)){!! $order->customer->last_name !!}  @endif
                                            </td>

                                             <td >@if(isset($order->maintenanceRequest->asset->asset_number)){!! $order->maintenanceRequest->asset->asset_number !!} @endif</td>

                                            <td>@if(isset($order->vendor->first_name)){!! $order->vendor->first_name !!} @endif @if(isset($order->vendor->last_name)){!! $order->vendor->last_name !!} @endif</td>
                                            <td>{!! date("d F Y",strtotime($order->created_at)) !!}</td>
                                        </tr>

                                        @endforeach

                                      </tbody>
                                 </table>
                            </div>
                        </div><!--/span-->
                          </div>
                          @endif

                    <div class="row-fluid">
     @if((@isset($access_roles['Recent Workorders']['view'])) && ($access_roles['Recent Workorders']['view'] == 1))
                        <div class="box span6 table-padding">
                            <div class="box-header">
                                <h4>Recent Work Orders</h4>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <table class="table table-condensed table-bordered">
                                      <thead>
                                          <tr>
                                              <th>Req No</th>
                                              <th><i class="fa-icon-caret-up"></i> Property No</th>
                                              <th>Customer</th>
                                              <th>Status</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <tr>

                                        </tr>

                                        @foreach ($recent_orders as $order)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                             <td >@if(isset($order->maintenanceRequest->asset->asset_number)){!! $order->maintenanceRequest->asset->asset_number !!} @endif</td>

                                            <td>@if(isset($order->customer->first_name)){!! $order->customer->first_name !!} @endif @if(isset($order->customer->last_name)){!! $order->customer->last_name !!}@endif</td>
                                            <td>
                                             <span class="label label-@if($order->status==1){!!'warning'!!}@else{!!$order->status_class!!}@endif">@if($order->status==1) In-Progress @else {!!$order->status_text!!} @endif</span>

                                            </td>
                                        </tr>

                                        @endforeach
                                      </tbody>
                                 </table>
                            </div>
                        </div>
                        @endif


               @if((@isset($access_roles['Recent Properties']['view'])) && ($access_roles['Recent Properties']['view'] == 1))
                        <div class="box span6 table-padding">
                            <div class="box-header">
                                <h4>Recent Properties</h4>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <table class="table table-condensed table-bordered">
                                      <thead>
                                          <tr>
                                              <th>Req No</th>
                                              <th><i class="fa-icon-caret-up"></i> Property No</th>
                                              <th>Loan</th>
                                              <th>Status</th>
                                          </tr>
                                      </thead>
                                      <tbody>

                                            @foreach ($recent_assets as $recent_asset)
                                              <tr>

                                                <td>@if(isset($recent_asset->asset_number)){!!$recent_asset->id!!}@endif</td>
                                                <td class="middle">@if(isset($recent_asset->asset_number)){!!$recent_asset->asset_number!!} @endif</td>
                                                <td>@if(isset($recent_asset->loan_number)){!!$recent_asset->loan_number!!} @endif</td>
                                                <td>
                                                    @if($recent_asset->status == 1)
                                                       <span class="label label-success">Active</span>
                                                    @else
                                                        <span class="label label-error">InActive</span>
                                                    @endif
                                                </td>
                                            </tr>

                                        @endforeach
                                      </tbody>
                                 </table>
                            </div>
                        </div>
                            @endif

                    </div>

        </div><!--/span-->

      </div><!--/row-->

      </div>
@stop