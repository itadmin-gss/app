@extends('layouts.vendordefault')

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

          <div class="box-content admtable">

                              <div class="admtableInr">

                <table class="table table-striped table-bordered bootstrap-datatable datatable">

<!--                    <label> Select Date Range </label>

                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">

                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>

                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>

                    </div>-->

                    <thead>

                        <tr><!--

                         a.      Order ID, Property Address, City, State, Zip, Service Name, Order Date, Status, Action -->



                            <th>Order ID</th>

                            <th>Property Address</th>

                            <th>City</th>

                            <th>State</th>

                            <th>Zipcode</th>

                            <th>Service Name</th>

                            <th>Due Date</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>



                        @foreach ($orders as $order)

                        <tr>

                            <td class="center">{!! $order['order_id'] !!}</td>

                            <td class="center">{!! $order['property_address'] !!}</td>

                            <td class="center">{!! $order['city'] !!}</td>

                            <td class="center">{!! $order['state'] !!}</td>

                            <td class="center">{!! $order['zipcode'] !!}</td>

                            <td class="center">{!! $order['service_name'] !!}</td>

                            <td class="center">{!! $order['due_date'] !!}</td>





                             @if($order['request_status']==4)

                             <td class="center"> <span class="label label-important">Cancelled</span> </td>



                             @else

                             <td class="center"> <span class="label label-{!! $order['status_class'] !!}">{!! $order['status_text'] !!}</span> </td>



                             @endif

                @if($order['request_status']==4)

                            <td class="center"><a class="btn btn-success" disabled="disabled" href="#"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" disabled="disabled" href="#"> <i class="halflings-icon edit halflings-icon"></i> </a></td>



                           @else

                            <td class="center"><a class="btn btn-success" href="view-order/{!!$order['order_id']!!}" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="edit-order/{!!$order['order_id']!!}" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a></td>



                           @endif</tr>



                        @endforeach

                    </tbody>

                </table>

            </div>

            </div>

        </div><!--/span-->



    </div><!--/row-->

</div>

@stop