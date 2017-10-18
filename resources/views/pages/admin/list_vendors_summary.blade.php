@extends('layouts.default')
@section('content')

<style type="text/css">
.datatablegrid {display: block;}
.datatablegrid2 {display: none;}
.datatablegrid3 {display: none;}
.datatablegrid4 {display: none;}

</style>
<div id="content" class="span11">
<div class="clearfix">
    <lable>Filter Report</lable>
    <select id="assetSummary">
        <option value="1">Requests</option>
        <option value="2">Work Orders</option>
        <option value="3">Invoices</option>
        <option value="4">Bids</option>
    </select>
</div>
  <div class="row-fluid">
    <div class="box span12">
      <div class="box-header datatablegrid" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Requests</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>

           <div class="box-content datatablegrid admtable">
            <div class="admtableInr">
         <table class="table  table-striped table-bordered bootstrap-datatable datatable">
<!--                    <label> Select Date Range </label>
                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>
                    </div>-->
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Vendor Name</th>
                            <th>Request ID</th>
                            <th>Service Name</th>
                            <th>Property #</th>
                            <th>Property Address</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($assign_requests as $assign_request)
                        <tr>
                            <td @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{{ $loop->iteration }}</td>
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['vendor_name'] !!}</td>
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['request_id'] !!}</td>
                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['service_name'] !!}</td>
                            <!-- <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['customer_name'] !!}</td>
                             --><td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['asset_number'] !!}</td>
                             <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['property_address'] !!}</td>

                            <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>{!! $assign_request['request_date'] !!}</td>
                           <td class="center" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif>  @if($assign_request['status']==1)
            <span class="label label-warning">Requested</span>
            @elseif($assign_request['status']==2)
            <span class="label label-warning">Reviewed By Admin</span>
            @elseif($assign_request['status']==3)
            <span class="label label-warning">Reviewed By Vendor</span>
            @endif
            </td>
                            <td class="center popover-examples" @if($assign_request['emergency_request']==1) style="background-color:red;" @endif><a class="btn btn-success" href="view-maintenance-request/{!!  $assign_request['request_id'] !!}" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a></td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>
                </div>
            </div>

        <div class="box-header datatablegrid2" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Work Orders</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>
      <div class="box-content datatablegrid2 admtable">
            <div class="admtableInr">
                 <table class="table table-striped table-bordered bootstrap-datatable datatable2">
<!--                    <label> Select Date Range </label>
                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>
                    </div>-->
                    <thead>
                        <tr>

                            <th>S.No</th>
                            <th>Vendor Name</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Service Name</th>
                            <th>Property #</th>
                            <th>Property Address</th>
                            <th>Order Date</th>
                            <th>Vendor Name</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zipcode</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="center">{!! $order['vendor_name'] !!}</td>
                            <td class="center">{!! $order['order_id'] !!}</td>
                            <td class="center">{!! $order['customer_name'] !!}</td>
                            <td class="center">{!! $order['service_name'] !!}</td>
                            <td class="center">{!! $order['asset_number'] !!}</td>
                                <td class="center">{!! $order['property_address'] !!}</td>
                            <td class="center">{!! $order['order_date'] !!}</td>
                            <td class="center">{!! $order['vendor_name'] !!}</td>
                                <td class="center">{!! $order['city'] !!}</td>
                            <td class="center">{!! $order['state'] !!}</td>
                            <td class="center">{!! $order['zipcode'] !!}</td>
                             <td class="center"> <span class="label label-{!! $order['status_class'] !!}">{!! $order['status_text'] !!}</span> </td>
                            <td class="center"><a class="btn btn-success" href="view-order/{!!$order['order_id']!!}" title="View Order"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="edit-order/{!!$order['order_id']!!}" title="Edit Order"> <i class="halflings-icon edit halflings-icon"></i> </a></td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>



                 <div class="box-header datatablegrid3" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Invoice</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>

           <div class="box-content datatablegrid3 admtable">
            <div class="admtableInr">
         <table class="table table-striped table-bordered bootstrap-datatable datatable3">
<!--                    <label> Select Date Range </label>
                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>
                    </div>-->
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Vendor Name</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Service Name</th>
                            <th>Property #</th>
                            <th>Order Date</th>

                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>


                        @foreach ($invoices as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="center">{!! $order['vendor_name'] !!}</td>
                            <td class="center">{!! $order['order_id'] !!}</td>
                            <td class="center">{!! $order['customer_name'] !!}</td>
                            <td class="center">{!! $order['service_name'] !!}</td>
                            <td class="center">{!! $order['asset_number'] !!}</td>
                            <td class="center">{!! $order['order_date'] !!}</td>

                            <td class="center">{!! $order['price'] !!}</td>
                            <td class="center"> <span class="label label-gray">@if($order['status']==1) Approved @else Deactive @endif </span> </td>
                            <td class="center"><a class="btn btn-success" href="{!!URL::to('view-order')!!}/{!!$order['order_id']!!}" title="View Order"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" title="Edit Order" href="{!!URL::to('edit-order')!!}/{!!$order['order_id']!!}"> <i class="halflings-icon edit halflings-icon"></i> </a></td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>

<div class="box-header datatablegrid4" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Bids</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>

           <div class="box-content datatablegrid4 admtable">
            <div class="admtableInr">
         <table class="table table-striped table-bordered bootstrap-datatable datatable4">
<!--                    <label> Select Date Range </label>
                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>
                    </div>-->
                    <thead>
                        <tr>
                            <th>S.No</th>
                             <th>Vendor Name</th>
                            <th>Bid Request ID</th>
                            <th>Service Name</th>

                            <th>Property #</th>
                            <th>Request Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($assign_bids as $assign_request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                             <td class="center">{!! $assign_request['customer_name'] !!}</td>
                            <td class="center">{!! $assign_request['request_id'] !!}</td>
                            <td class="center">{!! $assign_request['service_name'] !!}</td>

                            <td class="center">{!! $assign_request['asset_number'] !!}</td>
                            <td class="center">{!! $assign_request['request_date'] !!}</td>
                            <td class="center popover-examples"><a class="btn btn-success" href="{!!URL::to('view-admin-bid-request')!!}/{!!  $assign_request['request_id'] !!}"> <i class="halflings-icon zoom-in halflings-icon"></i> </a></td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>
                </div>
            </div>

      </div>
    </div>
    <!--/span-->
    <script>
	var db_table = "{!! $db_table !!}";
 	</script>
  </div>
  <!--/row-->
</div>
@parent
@include('common.delete_alert')
@stop