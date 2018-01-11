<title>GSS - Approved Orders</title>
 <div class='table-container'>
    <div class='table-responsive'>
      <div class='table-padding table-heading'>
          <h4>Approved</h4>
      </div>
        <table class="table table-striped table-bordered table-sm dt-responsive datatabledashboardapproved" style='width:100%;' id="approved_grid" width='100%' cellspacing='0' >
                      <thead>

                        <tr>

                         <th>ID #</th>
                         <th>Approved</th>
                         <th>Customer Name</th>
                         <th>Client Type</th>
                         <th>Property ID</th>
                         <th>Loan</th>
                         <th>Property Address</th>
                         <th>City</th>
                         <th>State</th>
                         <th>Zip</th>
                         <th>Completed</th>
                         <th>Vendor Submitted</th>
                         <th>Service Type</th>
                         <th>Due</th>
                         <th>Vendor</th>
                         <th>Vendor Price</th>


                       </tr>

                     </thead>

                     <tbody>
@foreach ($orders as $order)

                      <tr>

                        <td >
                            <a href="view-order/{!!$order['order_id']!!}" title="View">
                                {!! $order['order_id']!!}
                            </a>
                        </td>
                        <td >{!! $order['updated_at']!!}</td>

                        <td >{!! $order['customer_name']!!}</td>

                        <td >{!! $order['customer_type']!!}</td>

                          <td>
                              <a style="opacity:1 !important;" href="javascript:void(0)" class='tooltip'  data-toggle="tooltip" data-placement="top" title="Property ID: {!! $order['asset_number'] !!}">
                                  View
                              </a>
                          </td>
                          <td>
                              <a style="opacity:1 !important;" href="javascript:void(0)" class='tooltip'  data-toggle="tooltip" data-placement="top" title="Loan ID: {!! $order['loan_numbers'] !!}">
                                  View
                              </a>
                          </td>

                        <td class="center">
                            <a href="{!! URL::to("asset/".$order['asset_id']) !!}">
                          {!!$order['property_address']!!}
                          @if ($order['units'])
                          #{!!$order['units']!!}
                          @endif
                            </a>
                        </td>

                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>

                        <td class="center">{!! $order['completion_date'] !!}</td>

                        <td class="center">{!! $order['vendor_submitted'] !!}</td>
                        
                        <td class="center">{!! $order['service_type'] !!}</td>

                        <td class="center">{!! $order['service_name'] !!}</td>

                        <td class="center">              
                          {!! $order['vendor_name'] !!} @ {!! $order['vendor_company'] !!}
                        </td>

                        
                        <td class="center">{!! $order['vendor_price'] !!}</td>
                        <td class="center">{!! $order['customer_price'] !!}</td>

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

