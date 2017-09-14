@extends('layouts.default')
@section('content')
<div id="content" class="span11">
<a class="btn btn-info" href="{!!URL::to('admin-add-new-service-request')!!}" style="float:right" >
 Add Service Request
</a>
<p id="message" style="display:none">Saved...</p>
  <div class="row-fluid">
    <div class="box span12">
      <div class="box-header" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Assigned Service Request List</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>
      					@if(Session::has('message'))
                            {!!Session::get('message')!!}
                        @endif
      <div class="box-content">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
          
          
          <thead>
            <tr>
              <th>S.No</th>
              <th>Request ID</th>
              <th>Customer Name</th>
              <th>Customer Email</th>
              <th>Property #</th>
              <th>Property Address</th>
              <th>City</th>
              <th>State</th>
        
              <th>Vendor Name</th>

              <th>Status</th>
              <th>Action</th>
              
            </tr>
          </thead>
          <tbody>
          
          {{--*/ $loop = 1 /*--}}
          @foreach ($request_maintenance as $rm)
     
          @if($numberofrequestids['requested_services_count'][$rm->id]==$numberofrequestids['assigned_services_count'][$rm->id])  
         
        <tr>
                
            <td   @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $loop !!}</td>
            <td @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $rm->id !!}</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!! $rm->user->first_name !!}</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!! $rm->user->email !!}</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!! $rm->asset->asset_number !!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->property_address !!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->city->name !!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->state->name !!}</td>

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
             $assignedUsers .= "Service :".   $servicetitle ." <br/>Vendor:". $first_name." ".$last_name."<br/>" ;
            }

            ?>

            <td  @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!$assignedUsers!!}</td>
            
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
            @if($rm->status==4)
            <span class="label label-important">Cancelled</span>
            @else
             <span class="label label-blue">Assigned</span>
            @endif
           
           
           </td>
         
            <td class="center popover-examples" @if($rm->emergency_request==1) style="background-color:red;" @endif><a class="btn btn-success" @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!} @endif"> <i class="halflings-icon zoom-in halflings-icon"></i> </a><a class="btn btn-danger" href="cancel-maintenance-request/{!! $rm->id !!}"> <i class="halflings-icon minus halflings-icon"></i> </a></td>
          </tr>
          {{--*/ $loop++ /*--}}
          @endif
          @endforeach
            </tbody>
          
        </table>
      </div>
    </div>
    <!--/span--> 
    
  </div>
  <!--/row--> 
  <script>
	var db_table = "{!! $db_table !!}";
 </script>
</div>
@parent
@include('common.delete_alert')
@stop
