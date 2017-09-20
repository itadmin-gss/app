

<table class="table table-striped table-bordered bootstrap-datatable datatabledashboard"> 

                      <thead>

                        <tr>

                         <th>Order ID</th>

                         <th> Additional Service </th>

                         <th>Created At</th>

                         <th>Submitted By</th>

                         <th>Client Type</th>

                         <th> Customer Name</th>

                         <th>Property Address</th>

                         <th>City</th>

                         <th>State</th>

                         <th>Zip</th>

                         <th>Vendor Name</th>

                         <th>Job Types</th>

                         <th>Service Type</th>

                         <th>Due Date</th>
                         <th>Billing Comment</th>

                         <th>Status</th>

                         <th>Action</th>

                       </tr>

                     </thead>

                     <tbody>

                  

                      @foreach ($orders as $order)

                      <tr>

                        <td>{!! $order['order_id']!!}</td>
                        <td>Not-Set</td>
                        <td >{!! $order['order_date']!!}</td>

                        <td >{!! $order['submited_by']!!}</td>
                        <td >{!! $order['customer_type']!!}</td>
                        
                        <td class="center">{!!$order['customer_name']!!}</td>

                        <td class="center">{!! $order['property_address'] !!}</td>

                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>

                        <td class="center">{!! $order['vendor_name'] !!}</td>

                        <td class="center">{!! $order['job_type'] !!}</td>

                        <td class="center">{!! $order['service_type'] !!}</td>

                        
                        <td class="center">{!! $order['service_name'] !!}</td>


<td class="center">{!! $order['billing_note'] !!}</td>


                        @if($order['request_status']==4)

                        <td class="center"> <span class="label label-important">Cancelled</span> </td>



                        @else

                        <td class="center"> <span class="label label-{!! $order['status_class'] !!}">{!! $order['status_text'] !!}</span> </td>



                        @endif



                        @if($order['request_status']==4)

                        <td class="center"><a class="btn btn-success" disabled="disabled" href="#" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" disabled="disabled" href="#"> <i class="halflings-icon edit halflings-icon"></i> </a></td>



                        @else

                        <td class="center"><a class="btn btn-success" href="view-order/{!!$order['order_id']!!}" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="edit-order/{!!$order['order_id']!!}" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a></td>



                        @endif

                      </tr>

                      @foreach($addl_itemz as $key => $value)
                        @if($key == $order['order_id'])
                          @foreach($value as $index => $addl_service)
                          <tr>

                            <td >{!! $index !!}</td>
                            <td >{!! $addl_service !!}</td>
                            <td >{!! $order['order_date']!!}</td>

                            <td >{!! $order['submited_by']!!}</td>
                            <td >{!! $order['customer_type']!!}</td>
                            
                            <td class="center">{!!$order['customer_name']!!}</td>

                            <td class="center">{!! $order['property_address'] !!}</td>

                            <td class="center">{!! $order['city'] !!}</td>

                            <td class="center">{!! $order['state'] !!}</td>

                            <td class="center">{!! $order['zipcode'] !!}</td>

                            <td class="center">{!! $order['vendor_name'] !!}</td>

                            <td class="center">{!! $order['job_type'] !!}</td>

                            <td class="center">{!! $addl_service !!}</td>

                            
                            <td class="center">{!! $order['service_name'] !!}</td>

                            <td class="center">{!! $order['billing_note'] !!}</td>



                            @if($order['request_status']==4)

                            <td class="center"> <span class="label label-important">Cancelled</span> </td>



                            @else

                            <td class="center"> <span class="label label-{!! $order['status_class'] !!}">{!! $order['status_text'] !!}</span> </td>



                            @endif



                            @if($order['request_status']==4)

                            <td class="center"><a class="btn btn-success" disabled="disabled" href="#" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" disabled="disabled" href="#"> <i class="halflings-icon edit halflings-icon"></i> </a></td>



                            @else

                            <td class="center"><a class="btn btn-success" href="view-order/{!!$order['order_id']!!}" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="edit-order/{!!$order['order_id']!!}" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a></td>



                            @endif

                          </tr>
                          @endforeach
                        @endif
                      @endforeach

                

                      @endforeach

                    </tbody>

                  </table>

                  <script type="text/javascript">


var j = jQuery.noConflict();
j('.datatabledashboard').DataTable( {
      dom: 'Bfrtip',
      "order": [[ 0, "desc" ]],
      buttons: [
      'copy', 'csv', 'excel', 'print'
      ]
    } );
</script>

