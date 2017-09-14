@extends('layouts.vendordefaultorange')
@section('content')
<div id="content" class="span11">
   @if(Session::has('message'))
<div class="messageAlert">
    {!!Session::get('message')!!}
 </div>
    @endif
    <div class="row-fluid">		
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Service Bid List</h2>
<!--                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>-->
            </div> 
                <div class="box-content admtable">
                              <div class="admtableInr">
                <table class="table table-striped table-bordered bootstrap-datatable datatable">
<!--                    <label> Select Date Range </label>
                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>
                    </div>-->
                    <thead>
                        <tr>

                      
                            <th>Request ID</th>
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
                           
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['request_id'] !!}</td>
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['property_address'] !!}</td>
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['city'] !!}</td>
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['state'] !!}</td>
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['zip'] !!}</td>


                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['service_name'] !!}</td>
                            <!-- <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['customer_name'] !!}</td>
                             <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['asset_number'] !!}</td>-->

                             
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['request_date'] !!}</td>
                           <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif> 
                          



         
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
                            <td class="center popover-examples" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif><a class="btn btn-success" @if($assign_request['status'] ==4) disabled='disabled' @else  href="view-vendor-bidding-request/{!!  $assign_request['request_id'] !!}" @endif title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a></td>
                        </tr>
                        {{--*/ $loop++ /*--}}
                        @endforeach  
                        
                    </tbody>
                </table>            
            </div>
                </div>
        </div><!--/span-->

    </div><!--/row-->
</div>
@stop