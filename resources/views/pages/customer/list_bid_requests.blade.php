@extends('layouts.customer_dashboardpink')
@section('content')
<div id="content" class="span11">
OSR:<select id="bidRequestonchangeCustomer">
    <option value="1" @if($status==1) selected="selected" @endif>Requested</option>
    <option value="2" @if($status==2) selected="selected" @endif>Approved</option>
    <option value="3" @if($status==3) selected="selected" @endif>Declined</option>
</select>
    <div class="row-fluid">		
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>OSR List</h2>
<!--                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>-->
            </div> 
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable datatable">
<!--                    <label> Select Date Range </label>
                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>
                    </div>-->
                    <thead>
                        <tr>
                   
                            <th>OSR ID</th>
                            <th>Property Address</th> 
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                            <th>Service Name</th>
                            <th>Bid Date</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>   
                    <tbody>
                        {{--*/ $loop = 1 /*--}}
                        @foreach ($assign_requests as $assign_request)
                        <tr>
                    
                            <td class="center">{{ $assign_request['request_id'] }}</td>
                           <td class="center">{{ $assign_request['property_address'] }}</td>
                            <td class="center">{{ $assign_request['city'] }}</td>
                            <td class="center">{{ $assign_request['state'] }}</td>
                            <td class="center">{{ $assign_request['zip'] }}</td>
                            <td class="center">{{ $assign_request['service_name'] }}</td>
                            <td class="center">{{ $assign_request['request_date'] }}</td>
                             <td class="center">{{ $assign_request['price'] }}</td>
                          <td class="center popover-examples"><a class="btn btn-success" href="{{URL::to('view-customer-bid-request')}}/{{  $assign_request['request_id'] }}" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a></td>
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