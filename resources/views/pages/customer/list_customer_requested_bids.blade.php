@extends('layouts.customer_dashboardorange')
@section('content')
<div id="content" class="span11">
<a class="btn btn-info" href="{!!URL::to('add-new-service-request')!!}" style="float:right" >
 Add Service Bid
</a>

 @if(Session::has('messagedecline'))
<div class="messagedeclineAlert">
    {!!Session::get('messagedecline')!!}
 </div>
    @endif
   @if(Session::has('message'))
<div class="messageAlert">
    {!!Session::get('message')!!}
 </div>
    @endif
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Service Request List</h2>

            </div>
              
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable datatable">

            </div>
            <thead>
                <tr>
          
                 
                    <th>Request ID</th>
                    <th>Client Type</th>
                    <th>Property Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Service Name</th>
                     <th>Request Date</th>

                   
                    
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                {{--*/ $loop = 1 /*--}}
                @foreach ($assign_requests as $assign_request)
                <tr>
                
                    <td class="center">{!! $assign_request['request_id'] !!}</td>
                        <td class="center">{!! $assign_request['ClientType'] !!}</td>
                    <td class="center">{!! $assign_request['property_address'] !!}</td>
                    <td class="center">{!! $assign_request['city'] !!}</td>
                    <td class="center">{!! $assign_request['state'] !!}</td>
                    <td class="center">{!! $assign_request['zip'] !!}</td>
                     <td class="center">{!! $assign_request['serviceType'] !!}</td>
                     <td class="center">{!! $assign_request['request_date'] !!}</td>
                    
                 
                        <td class="center">
           
         @if($assign_request['status']==1)

            <span class="label label-newbidrequest">New Bid Request</span>

            @elseif($assign_request['status']==2)

            <span class="label label-awaitingvendorbid">Awaiting Vendor Bid</span>

            @elseif($assign_request['status']==3)

            <span class="label label-completedvendorbid">Completed Vendor Bid</span>

            @elseif($assign_request['status']==4)

            <span class="label label-newworkordergenerated">New Work Order Generated</span>
              @elseif($assign_request['status']==5)

            <span class="label label-cancel">Cancel</span>
            @elseif($assign_request['status']==6)

            <span class="label label-awaitingcustomerapproval">Awaiting Customer Approval</span>
            @elseif($assign_request['status']==7)

            <span class="label label-declinedbid">Declined</span>
              @elseif($assign_request['status']==8)

            <span class="label label-approvedbid">Approved Bid</span>
            
            @endif
            </td>
                    <td class="center">
                        <a class="btn btn-success" @if($assign_request['status'] ==4 || $assign_request['status'] ==7) disabled='disabled' @else  href="view-customer-request-bid/{!! $assign_request['request_id'] !!}" @endif title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a>

                    </td>
                </tr>
                {{--*/ $loop++ /*--}}
                @endforeach
            </tbody>
            </table>
        </div>
    </div><!--/span-->

</div><!--/row-->
</div>
@stop