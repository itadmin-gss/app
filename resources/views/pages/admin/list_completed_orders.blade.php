@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Work Order List</h2>
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
                            <th>S.No</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Service Name</th>
                            <th>Property #</th>
                            <th>Order Date</th>
                            <th>Vendor Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{--*/ $loop = 1 /*--}}
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $loop }}</td>
                            <td class="center">{{ $order['order_id'] }}</td>
                            <td class="center">{{ $order['customer_name'] }}</td>
                            <td class="center">{{ $order['service_name'] }}</td>
                            <td class="center">{{ $order['asset_number'] }}</td>
                            <td class="center">{{ $order['order_date'] }}</td>
                            <td class="center">{{ $order['vendor_name'] }}</td>
                            <td class="center">
                                @if($order['status']==2)
                                <span class="label label-success">Completed</span>
                                @elseif($order['status']==1)
                                <span class="label label-warning">In-Progress</span>
                                @elseif($order['status']==3)
                                <span class="label label-notice">On Approval</span>
                               
                                @endif
                            </td>
                            <td class="center"><a class="btn btn-success" href="view-order/{{$order['order_id']}}"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="edit-order/{{$order['order_id']}}"> <i class="halflings-icon edit halflings-icon"></i> </a></td>
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