@extends('layouts.default')
@section('content')

    <div id="content" class="span11">
 <div class='table-container'>
    <div class='table-responsive'>
      <div class='table-padding table-heading'>
        <?php

          if (isset($id))
          {
            switch ($id){
              case 3:
                echo "<div class='card blue-3'>
                        <div class='card-body'>
                            <span>Under Review</span>
                        </div>
                      </div>
                      <title>GSS - Under Review Orders</title>";
              break;
              case 1:
                echo "<div class='card blue-2'><div class='card-body'><span>In Process</span></div></div><title>GSS - In Process Orders</title>";
              break;
            }
          }
        ?>
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
                            $view_url = "view-order/".$order['order_id'];
                            ?>
                            <a href="{!!URL::to($view_url)!!}" title="View">
                                {!! $order['order_id']!!}
                            </a>
                        </td>
                        <td >{!! $order['submited_by']!!}</td>
                        <td >{!! $order['customer_type']!!}</td>
                        
                        <td class="center">{!!$order['customer_name']!!}</td>

                        <td class="center">{!! $order['property_address'] !!}</td>

                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>

                        <td class="center">
                        {!! $order['vendor_name'] !!}
                        @if (isset($order['vendor_company']))
                          @  {!! $order['vendor_company'] !!}
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

                            <td class="center">{!! $order['vendor_name'] !!}</td>

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


$('.datatabledashboard').DataTable( {
      dom: 'Bfrtip',
      "order": [[ 0, "desc" ]],
      buttons: [
      'copy', 'csv', 'excel', 'print'
      ]
    } );
</script>

@stop
