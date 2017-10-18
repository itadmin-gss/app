 <div class='table-container'>
    <div class='table-responsive'>
      <h4>Under Review</h4>
<table class="table table-striped table-bordered table-sm dt-responsive datatabledashboardapproved" id="approved_grid" width='100%' cellspacing='0' >
                      <thead>

                        <tr>

                         <th>Order ID</th>
                         <th>Add'l Service </th>
                         <th>Approved On</th>
                         <th>Customer Name</th>
                         <th>Client Type</th>
                         <th>Property ID</th>
                         <th>Loan Number</th>
                         <th>Property Address</th>
                         <th>Unit #</th>
                         <th>City</th>
                         <th>State</th>
                         <th>Zip</th>
                         <th>Completed</th>
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

                        <td class="center"> <span class="badge badge-danger">Cancelled</span> </td>



                        @else
                            <?php 
                            switch ($order['status_class'])
                            {
                              case "black":
                                $status_class = "default";
                              break;
                              case "blue":
                                $status_class = "primary";
                              break;
                              case "green":
                                $status_class = "success";
                              break;
                              case "important":
                                $status_class = "danger";
                              break;
                              case "warning":                          
                              case "yellow":
                                $status_class = "warning";
                              break;
                            }
                            ?>
                            <td class="center"> <span class="badge badge-summary badge-{!! $status_class !!}">{!! $order['status_text'] !!}</span> </td>




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

                            <td class="center">
                              <a class="btn btn-success btn-xs action-button" disabled="disabled" href="#" title="View"> 
                                  <i class="fa fa-search-plus" aria-hidden="true"></i>                              </a> 
                              <a class="btn btn-info btn-xs action-button" disabled="disabled" href="#"> 
                                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                              </a>
                              </td>

                        @else


                            <td class="center">
                              <div class='action-button-group'>
                                <a class="btn btn-success btn-xs action-button" href="view-order/{!!$order['order_id']!!}" title="View"> 
                                  <i class="fa fa-search-plus" aria-hidden="true"></i>
                                </a> 
                                <a class="btn btn-info btn-xs action-button" href="edit-order/{!!$order['order_id']!!}" title="Edit"> 
                                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                              </div>
                            </td>



                        @endif

                      </tr>


                     

                      @endforeach
                      </tbody>

                    </table>
             
                    <script type="text/javascript">




            var table =  $('#approved_grid').DataTable( {
                      dom: 'Bfrtip',
                      "order": [[ 0, "desc" ]],
                      buttons: [
                      'copy', 'csv', 'excel', 'print'
                      ]
                    } );
             table.on( 'buttons-action', function ( e, buttonApi, dataTable, node, config ) {
             
             if(buttonApi.text()== "Excel"){
             //console.log(buttonApi.text());
        $.ajax({
      url: baseurl + "/approved-grid-export",
      cache: false,
      success: function(data){
        alert(data);
    } 
});
}
} );

</script>

