@extends('layouts.default')
@section('content')

    <div id="content" style="margin-top:15px !important">
        <div class="row">
    <div id="datatabledashboard">
        <?php

        if (isset($id))
        {
            switch ($id){
                case 3:
                    echo "<div id='page-heading-dt' style='display:none;'>
                    <div class='card blue-1 '>
                        <div class='card-body'>
                            <span><span id='header-quantity-rework'></span> Awaiting Vendor to Re-work Request</span>
                        </div>
                    </div>
                </div>
                <style>
                    .rework{display:inherit !important;}

                </style>
                      <title>GSS - Rework Orders</title>";

                    break;
                case 1:
                    echo "<div id='page-heading-dt' style='display:none;'>
                    <div class='card blue-1 '>
                        <div class='card-body'>
                            <span><span id='header-quantity-inprocess'></span> Assigned to Vendor, awaiting Vendor Completion</span>
                        </div>
                    </div>
                </div>                <style>
                    .in-process{display:inherit !important;}

                </style><title>GSS - In Process Orders</title>";
                    break;
            }
        }
        ?>
    <div class='table-responsive'>
      <div class='table-padding table-heading'>

      </div>
        <table class="table table-striped table-bordered table-sm dt-responsive datatabledashboard" width='100%' style='width:100%;' cellspacing='0' id='dataTable'> 

                      <thead>

                        <tr>

                         <th>ID #</th>

                         <th>Submitter</th>

                         <th>Client Type</th>

                         <th>Customer</th>

                         <th>Property Address</th>

                         <th>City</th>

                         <th>State</th>

                         <th>Zip</th>

                         <th>Vendor Name @ Vendor Company</th>

                         <th>Service(s)</th>

                         <th>Due</th>


                       </tr>

                     </thead>

                     <tbody>


                      @foreach ($orders as $order)

                      <tr>

                        <td>
                            <?php
                            $view_url = "edit-order/".$order['order_id'];
                            ?>
                            <a href="{!!URL::to($view_url)!!}" title="View">
                                {!! $order['order_id']!!}
                            </a>
                        </td>
                        <td >{!! $order['submited_by']!!}</td>
                        <td >{!! $order['customer_type']!!}</td>
                        
                        <td class="center">{!!$order['customer_name']!!}</td>

                          <td class="center"><a href="{!! URL::to("asset/".$order['asset_id']) !!}">{!! $order['property_address'] !!}</a></td>

                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>

                        <td class="center">
                            <span>{!! trim($order['vendor_name']) !!}</span>
                        @if (isset($order['vendor_company']))
                                <span>@  {!! trim($order['vendor_company']) !!}</span>
                        @endif
                        </td>

                        <td class="center">{!! $order['service_type'] !!}</td>

                        
                        <td class="center">{!! $order['service_name'] !!}</td>
                      </tr>
                      @foreach($addl_itemz as $key => $value)
                        @if($key == $order['order_id'])
                          @foreach($value as $index => $addl_service)
                          <tr>

                              <td>
                                  <?php
                                  $view_url = "view-order/".$order['order_id'];
                                  ?>
                                  <a href="{!!URL::to($view_url)!!}" title="View">
                                      {!! $index !!}
                                  </a>
                              </td>

                            <td >{!! $order['submited_by']!!}</td>
                            <td >{!! $order['customer_type']!!}</td>
                            
                            <td class="center">{!!$order['customer_name']!!}</td>

                            <td class="center">{!! $order['property_address'] !!}</td>

                            <td class="center">{!! $order['city'] !!}</td>

                            <td class="center">{!! $order['state'] !!}</td>

                            <td class="center">{!! $order['zipcode'] !!}</td>

                            <td class="center">{!! trim($order['vendor_name']) !!}</td>

                            <td class="center">{!! $addl_service !!}</td>

                            
                            <td class="center">{!! $order['service_name'] !!}</td>

                          </tr>
                          @endforeach
                        @endif
                      @endforeach

                

                      @endforeach

                    </tbody>

                  </table>
    </div>

 </div><!--/span-->

    </div><!--/row-->

    </div>
                  <script type="text/javascript">



</script>

@stop
