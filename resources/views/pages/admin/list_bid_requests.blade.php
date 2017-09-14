@extends('layouts.defaultpink')
@section('content')
<div id="content" class="span11">
    <div class="clearfix">
       OSR: <select id="bidRequestonchange">
            <option value="1" @if($status==1) selected="selected" @endif>Requested</option>
            <option value="2" @if($status==2) selected="selected" @endif>Approved</option>
            <option value="3" @if($status==3) selected="selected" @endif>Declined</option>
        </select>
        <a class="btn btn-info accBtn" href="{!!URL::to('admin-add-bid-request')!!}"> Add OSR  </a>
    </div>
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
                         
                            <th>OSR ID</th>
                            <th>Service Name</th>
                            <th>Vendor Name</th>
                            <th>Property #</th>
                            <th>Request Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>   
                    <tbody>
                        {{--*/ $loop = 1 /*--}}
                        @foreach ($assign_requests as $assign_request)
                        <tr>
                           
                            <td class="center">{!! $assign_request['request_id'] !!}</td>
                            <td class="center">{!! $assign_request['service_name'] !!}</td>
                            <td class="center">{!! $assign_request['customer_name'] !!}</td>
                            <td class="center">{!! $assign_request['asset_number'] !!}</td>
                            <td class="center">{!! $assign_request['request_date'] !!}</td>
                            <td class="center popover-examples"><a class="btn btn-success" href="{!!URL::to('view-admin-bid-request')!!}/{!!  $assign_request['request_id'] !!}"> <i class="halflings-icon zoom-in halflings-icon"></i> </a></td>
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