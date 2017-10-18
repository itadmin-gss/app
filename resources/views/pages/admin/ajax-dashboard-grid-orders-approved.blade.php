<table class="table table-striped table-bordered bootstrap-datatable datatabledashboardapproved" id="approved_grid" >
                      <thead>

                        <tr>

                         <th>Order ID</th>
                         <th> Additional Service </th>
                         <th>Approved On</th>
                         <th> Customer Name</th>
                         <th> Client Type</th>
                         <th>Property ID</th>
                         <th>Loan Number</th>
                         <th>Property Address</th>
                         <th>Unit #</th>
                         <th>City</th>
                         <th>State</th>
                         <th>Zip</th>
                         <th>Completion Date</th>
                         <th>Vendor Submitted</th>
                         <th>Status</th>
                         <th>Job Type</th>
                         <th>Service Type</th>
                         <th>Due Date</th>
                         <th>Vendor Name</th>
                         <th>Vendor Company Name</th>
                         <th>Vendor Price</th>
                         <th>Customer Price</th>
                         <th>Billing Comment</th>
                         <th>Action</th>

                       </tr>

                     </thead>

                     <tbody>
@foreach ($orders as $order)

                      <tr>

                        <td >{!! $order['order_id']!!}</td>
                        <td>Not-Set</td>
                        <td >{!! $order['updated_at']!!}</td>

                        <td >{!! $order['customer_name']!!}</td>

                        <td >{!! $order['customer_type']!!}</td>
                        
                        <td class="center">{!!$order['asset_number']!!}</td>

                        <td class="center">{!!$order['loan_numbers']!!}</td>

                        <td class="center">{!!$order['property_address']!!}</td>
                        
                        <td class="center">{!!$order['units']!!}</td>

                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>

                        <td class="center">{!! $order['completion_date'] !!}</td>

                        <td class="center">{!! $order['vendor_submitted'] !!}</td>

                        @if($order['request_status']==4)

                        <td class="center"> <span class="label label-important">Cancelled</span> </td>



                        @else

                        <td class="center"> <span class="label label-{!! $order['status_class'] !!}">{!! $order['status_text'] !!}</span> </td>



                        @endif
                        
                        <td class="center">{!! $order['job_type'] !!}</td>
                        <td class="center">{!! $order['service_type'] !!}</td>

                        <td class="center">{!! $order['service_name'] !!}</td>



                        <td class="center">{!! $order['vendor_name'] !!}</td>

                        
                        <td class="center">{!! $order['vendor_company'] !!}</td>

                        
                        <td class="center">{!! $order['vendor_price'] !!}</td>
                        <td class="center">{!! $order['customer_price'] !!}</td>
                        <td class="center">{!! $order['billing_note'] !!}</td>




                        



                        @if($order['request_status']==4)

                        <td class="center"><a class="btn btn-success" disabled="disabled" href="#" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" disabled="disabled" href="#"> <i class="halflings-icon edit halflings-icon"></i> </a></td>



                        @else

                        <td class="center"><a class="btn btn-success" href="view-order/{!!$order['order_id']!!}" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="edit-order/{!!$order['order_id']!!}" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a>
                         </td>



                        @endif

                      </tr>
                      {{--  @foreach($addl_itemz as $key => $value)
                      @if($key == $order['order_id'])
                      @foreach($value as $index => $addl_service)
                      <tr>

                        <td >{!! $index!!}</td>
                        <td>{!!$addl_service!!}</td>
                        <td >{!! $order['updated_at']!!}</td>

                        <td >{!! $order['customer_name']!!}</td>

                        <td >{!! $order['customer_type']!!}</td>
                        
                        <td class="center">{!!$order['asset_number']!!}</td>

                        <td class="center">{!!$order['loan_numbers']!!}</td>

                        <td class="center">{!!$order['property_address']!!}</td>
                        
                        <td class="center">{!!$order['units']!!}</td>

                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>

                        <td class="center">{!! $order['completion_date'] !!}</td>
                         <td class="center">{!! $order['vendor_submitted'] !!}</td>

                        @if($order['request_status']==4)

                        <td class="center"> <span class="label label-important">Cancelled</span> </td>



                        @else

                        <td class="center"> <span class="label label-{!! $order['status_class'] !!}">{!! $order['status_text'] !!}</span> </td>



                        @endif
                        
                        <td class="center">{!! $order['job_type'] !!}</td>
                        <td class="center">{!! $addl_itemz_service_type[$index] !!}</td>

                        <td class="center">{!! $order['service_name'] !!}</td>



                        <td class="center">{!! $order['vendor_name'] !!}</td>

                        
                        <td class="center">{!! $order['vendor_company'] !!}</td>

                        
                        <td class="center">{!! $addl_itemz_rate[$index] !!}</td>
                        <td class="center">{!! $order['customer_price'] !!}</td>

                        <td class="center">{!! $order['billing_note'] !!}</td>



                        



                        @if($order['request_status']==4)

                        <td class="center"><a class="btn btn-success" disabled="disabled" href="#" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" disabled="disabled" href="#"> <i class="halflings-icon edit halflings-icon"></i> </a></td>



                        @else

                        <td class="center"><a class="btn btn-success" href="view-order/{!!$order['order_id']!!}" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="edit-order/{!!$order['order_id']!!}" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a>
                         </td>



                        @endif

                      </tr>
                      @endforeach
                      @endif
                      @endforeach  --}}

                     

                      @endforeach
                      </tbody>

                    </table>
             
                    <script type="text/javascript">


var j = jQuery.noConflict();

        var table =  j('#approved_grid').DataTable( {
                      dom: 'Bfrtip',
                      "order": [[ 0, "desc" ]],
                      buttons: [
                      'copy', 'csv', 'excel', 'print'
                      ]
                    } );
             table.on( 'buttons-action', function ( e, buttonApi, dataTable, node, config ) {
             
             if(buttonApi.text()== "Excel"){
             //console.log(buttonApi.text());
        j.ajax({
      url: baseurl + "/approved-grid-export",
      cache: false,
      success: function(data){
        alert(data);
    } 
});
}
} );

</script>

