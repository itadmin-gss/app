@extends('layouts.default')
@section('content')
<div id="content" class="span11">
{!! $username="";!!}
Sort By <select id="Invoiceonchange">
    <option value="2" @if($status==2) selected="selected" <?php $username="Customer";?>  @endif>Customer</option>
    <option value="3" @if($status==3) selected="selected" <?php $username="Vendor";?> @endif>Vendor</option>
</select>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>{!!$username!!} Invoices</h2>
<!--                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>-->
            </div>
            <div class="box-content">
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
                        
                            <th>Order ID</th>
                            <th>Client Type</th>
                            <th>Customer Name</th>
                            <th>Service Name</th>
                            <th>Property Address</th>
                            <th>Order Date</th>
                            <th>Completion Date</th>
                            <th>Vendor Name</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        {{--*/ $loop = 1 /*--}}
                        @foreach ($orders as $order)
                        <tr>
                       
                            <td class="center">{!! $order['order_id'] !!}</td>
                            <td class="center">{!! $order['ClientType'] !!}</td>
                            
                            <td class="center">{!! $order['customer_name'] !!}</td>
                            <td class="center">{!! $order['service_name'] !!}</td>
                            <td class="center">{!! $order['propery_address'] !!}</td>
                            <td class="center">{!! $order['order_date'] !!}</td>
                            <td class="center">{!! $order['completion_date'] !!}</td>
                            
                            <td class="center">{!! $order['vendor_name'] !!}</td>
                            <td class="center">{!! $order['price'] !!}</td>
                            <td class="center"> <span class="label label-gray">@if($order['status']==1) Approved @else Deactive @endif </span> </td>
                            <td class="center"><a class="btn btn-success" href="{!!URL::to('view-order')!!}/{!!$order['order_id']!!}" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="{!!URL::to('edit-invoice')!!}/{!!$order['id']!!}" title="Edit Invoice"> <i class="halflings-icon edit halflings-icon"></i> </a></td>
                        </tr>
                        {{--*/ $loop++ /*--}}
                        @endforeach
                    </tbody>
                </table>
                </div>
                </div>
            </div>
        </div><!--/span-->

    </div><!--/row-->
</div>
@stop