@extends('layouts.default')
@section('content')

<div id="content" class="span11">

      <div class="row-fluid">

                <div class="box span12 main-title">
                  @if(Auth::user()->type_id==1)

                  <h1>Admin Dashboard</h1>
                  @elseif(Auth::user()->user_role_id==6)
                    <h1>Coordinator Dashboard</h1>
                  @elseif(Auth::user()->user_role_id==4)
                    <h1>Manager Dashboard</h1>
                  @endif
                </div><!--/span-->

            </div><!--/row-->
<style type="text/css">
  .span2 {width: 18% !important;}
  .span2 h1{font-size: 22px !important;}
</style>

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

          }
          else{

                 if($rm->status==0 && in_array(1,$request_service_ids) )
                $assigned++;

            }

         }

          ?>

              <div class="row-fluid adminTab">

                <!-- <div class="box span2 main-title" style="background-color:#ED7D31;cursor:pointer;" onclick="ajaxDashoboardGridRequests('2','unassigned')">
                <h1>Unassigned “{!!$unassigned!!}”</h1>
                </div> -->

                    <!--  <div class="box span2 main-title" style="margin-left:10px !important;background-color:#4472C4;cursor:pointer;" onclick="ajaxDashoboardGridOrders('0','new_work_order')">
                <h1>New Work Order
                “<?php if(isset($orderCounterDashboard['0'])) {?> {!!$orderCounterDashboard['0']!!}  <?php }else { ?> {!!"0"!!} <?php } ?>”</h1>
                </div>  -->
                <div class="box span2 main-title" style="margin-left:10px !important;background-color:#FFC000; cursor:pointer;" onclick="ajaxDashoboardGridOrders('1','inprocess')">
                <h1>In-Process <?php if(isset($orderCounterDashboard['1'])) {?>“{!!$orderCounterDashboard['1']!!}”<?php }else { ?> {!!"0"!!} <?php } ?>”</h1>
                </div>



                <div class="box span2 main-title" style="margin-left:10px !important;background-color:red; cursor:pointer;" onclick="ajaxDashoboardGridOrders('3','underreview')">
                <h1>Under Review <?php if(isset($orderCounterDashboard['3'])) {?>“{!!$orderCounterDashboard['3']!!}”<?php }else { ?> {!!"0"!!} <?php } ?>”</h1>
                 </div>
                <div class="box span2 main-title" style="margin-left:10px !important;background-color:#000000; cursor:pointer;" onclick="ajaxDashoboardGridOrders('2','complete')">
                <h1>Complete “<?php if(isset($orderCounterDashboard['2'])) {?>“{!!$orderCounterDashboard['2']!!}”<?php }else { ?> {!!"0"!!} <?php } ?>”</h1>
                 </div>

                  <div class="box span2 main-title" style="margin-left:10px !important;background-color:#A5A5A5; cursor:pointer;" onclick="ajaxDashoboardGridOrders('4','approved')">
                  <h1>Approved “<?php if(isset($orderCounterDashboard['4'])) {?>“{!!$orderCounterDashboard['4']!!}”<?php }else { ?> {!!"0"!!} <?php } ?>”</h1>
                  </div>





            </div><!--/row-->



            <div class="row-fluid">

        <div class="span">
                 @if(Session::has('message'))
            {!!Session::get('message')!!}
         @endif



 @if($access_roles['Summary Grid']['view'] == 1)
                  <div class="row-fluid">
                     <div class="box span12">
                            <div class="box-header">
                                <h2>Summary</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>

                             <div class="box-content admtable" >
                              <div class="admtableInr" id="datatabledashboard">



                                 <table class="table table-striped table-bordered bootstrap-datatable datatabledashboard" >


          <thead>
            <tr>

              <th>Request ID</th>

              <th>Request Date</th>
              <th>Client Type</th>

              <th>Submitted By</th>

              <th>Customer Name</th>

              <th>Customer Email</th>

            <!--  <th>Vendor Name</th> -->

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
                         $due_date .= "<br><p ".$style."> ". $value->due_date . '</p> <br>';
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
            <span class="label label-green">New Request</span>
            @elseif($rm->status==2)
            <span class="label label-warning">Un Assigned</span>
            @elseif($rm->status==3)
            <span class="label label-warning">Un Assigned</span>
            @elseif($rm->status==4)
            <span class="label label-important">Cancelled</span>
            @endif

           </td>

            <td class="center popover-examples" @if($rm->emergency_request==1) style="background-color:red;" @endif>
            <a class="btn btn-success"  @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a>
            <a class="btn btn-danger" href="cancel-maintenance-request/{!! $rm->id !!}" title="Cancel" onclick="return confirm('Are you sure you want to cancel?')"> <i class="halflings-icon trash halflings-icon"></i> </a>

<!--             <a class="btn btn-danger" href="delete-maintenance-request/{!! $rm->id !!}" title="Delete" onclick="return confirm('Are you sure you want to delete?')"> <i class="halflings-icon remove halflings-icon"></i> </a> -->
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
            <span class="label label-important">Cancelled</span>
            @else
             <span class="label label-grey">Assigned</span>
            @endif
           </td>

            <td class="center popover-examples" @if($rm->emergency_request==1) style="background-color:red;" @endif><a class="btn btn-success"  @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a><a class="btn btn-danger" href="cancel-maintenance-request/{!! $rm->id !!}" title="Cancel" onclick="return confirm('Are you sure you want to delete?')"> <i class="halflings-icon trash halflings-icon"></i> </a></td>
          </tr>



          @endif
          @endforeach
            </tbody>

        </table>

                                 </div>
                            </div>
                        </div><!--/span-->
                      </div>

@endif






                     @if($access_roles['Work Order in Proces']['view'] == 1)
                          <div class="row-fluid">
                        <div class="box span12">
                            <div class="box-header">
                                <h2>Work Order in Process</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <table class="table table-condensed">
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


                            @if($access_roles['Work Order Approval']['view'] == 1)
                               <div class="row-fluid">
                        <div class="box span12">
                            <div class="box-header">
                                <h2>Work Order Approval Request</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <table class="table table-condensed">
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