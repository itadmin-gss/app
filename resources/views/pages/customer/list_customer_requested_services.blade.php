@extends('layouts.customer_dashboard')
@section('content')
<div id="content" class="span11">
<a class="btn btn-info" href="{!!URL::to('add-new-service-request')!!}" style="float:right" >
 Add Service Request
</a>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Service Request List</h2>

            </div>
               @if(Session::has('message'))
            {!!Session::get('message')!!}
            @endif
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
            <span class="label label-warning">Requested</span>
            @elseif($assign_request['status']==2)
            <span class="label label-warning">Reviewed By Admin</span>
            @elseif($assign_request['status']==3)
            <span class="label label-warning">Reviewed By Vendor</span>
            @elseif($assign_request['status']==4)
                 <span class="label label-important">Cancelled</span>
            @endif
            </td>
                    <td class="center">
                        <a class="btn btn-success" @if($assign_request['status'] ==4) disabled='disabled' @else  href="view-customer-request-service/{!! $assign_request['request_id'] !!}" @endif title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a>

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