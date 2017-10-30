<title>GSS - Completed Orders</title>
 <div class='table-container'>
    <div class='table-responsive'>
       <div class='table-padding table-heading'>
          <h4>Completed</h4>
      </div>   
      <table class="table table-striped table-sm table-bordered dt-responsive datatabledashboard" style='width:100%;' width='100%' cellspacing='0' id="completion_grid">

                      <thead>

                        <tr>
                          
                         <th>ID #</th>
                         <th>Submitted</th>
                         <th>Customer</th>
                         <th>Client Type</th>
                         <th>Property Address</th>
                         <th>City</th>
                         <th>State</th>
                         <th>Zip</th>
                         <th>Completed</th>
                         <th>Status</th>
                         <th>Job Type</th>
                         <th>Service Type</th>
                         <th>Due</th>
                         <th>Vendor</th>
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

                        <td >{!! $order['updated_at']!!}</td>

                        <td >{!! $order['customer_name']!!}</td>

                        <td >{!! $order['customer_type']!!}</td>

                        <td class="center">
                          {!!$order['property_address']!!}
                          @if ($order['units'])
                          #{!!$order['units']!!}
                          @endif
                        </td>
                        
                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>

                        <td class="center">{!! $order['completion_date'] !!}</td>


                        @if($order['request_status']==4)

                        <td class="center"> <span class="badge badge-summary badge-important">Cancelled</span> </td>



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

                        <td class="center">              
                          {!! $order['vendor_name'] !!} @ {!! $order['vendor_company'] !!}
                        </td>
                        
                        <td class="center">{!! $order['vendor_price'] !!}</td>
                        <td class="center">{!! $order['customer_price'] !!}</td>
                         <td class="center">{!! $order['billing_note'] !!}</td>




                        



                        @if($order['request_status']==4)
                        <td class="center"> 
                          <div class='action-button-group'>
                            <a class="btn btn-info btn-xs action-button" disabled="disabled" href="#"> 
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>
                            <a class="btn btn-xs action-button btn-primary" title="Quick-View" onclick="showQuickWorkOrderPage({!! $order['order_id'] !!})"> 
                                <i class="fa fa-file-o" aria-hidden="true"></i>
                            </a>
                          </div>
                        </td>



                        @else

                        <td class="center">
       
                              <?php 
                                $edit_url = "edit-order/".$order['order_id'];
                              ?>
                          <a class="btn btn-info btn-xs action-button" href="{!!URL::to($edit_url) !!}" title="Edit"> 
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                          </a>
                                
                          <a class="btn btn-xs action-button btn-primary" title="Quick-View" onclick="showQuickWorkOrderPage({!! $order['order_id'] !!})"> 
                            <i class="fa fa-file-o" aria-hidden="true"></i>
                          </a>

                        </td>



                        @endif

                      </tr>
                      @foreach($addl_itemz as $key => $value)
                      @if($key == $order['order_id'])
                      @foreach($value as $index => $addl_service)
                      <tr>

                        <td >{!! $index!!}</td>
                        <td >{!! $order['updated_at']!!}</td>

                        <td >{!! $order['customer_name']!!}</td>

                        <td >{!! $order['customer_type']!!}</td>

                        <td class="center">
                          {!!$order['property_address']!!}
                          @if ($order['units'])
                          #{!!$order['units']!!}
                          @endif
                        </td>

                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>

                        <td class="center">{!! $order['completion_date'] !!}</td>


                        @if($order['request_status']==4)

                        <td class="center"> <span class="badge badge-summary badge-danger">Cancelled</span> </td>



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
                        <td class="center">{!! $addl_itemz_service_type[$index] !!}</td>

                        <td class="center">{!! $order['service_name'] !!}</td>



                        <td class="center">              
                          {!! $order['vendor_name'] !!} @ {!! $order['vendor_company'] !!}
                        </td>

                        
                        <td class="center">{!! $addl_itemz_rate[$index] !!}</td>
                        <td class="center">{!! $addl_itemz_customerPrice[$index] !!}</td>

                          <td class="center">{!! $order['billing_note'] !!}</td>



                        



                        @if($order['request_status']==4)

                        <td class="center"> 
                        <a class="btn btn-info btn-xs action-button" disabled="disabled" href="#"> 
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-xs action-button btn-primary" title="Quick-View" onclick="showQuickWorkOrderPage({!! $order['order_id'] !!})"> 
                            <i class="fa fa-file-o" aria-hidden="true"></i>
                        </a></td>


                        @else

                        <td class="center">
       
                  
                          <?php 
                            $edit_url = "edit-order/".$order['order_id'];
                          ?>
                          <a class="btn btn-info btn-xs action-button" href="{!!URL::to($edit_url) !!}" title="Edit"> 
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                          </a>
                                
                          <a class="btn btn-xs action-button btn-primary" title="Quick-View" onclick="showQuickWorkOrderPage({!! $order['order_id'] !!})"> 
                            <i class="fa fa-file-o" aria-hidden="true"></i>
                          </a>

                        </td>

                        @endif

                      </tr>
                      @endforeach
                      @endif
                      @endforeach

                     

                      @endforeach

                    </tbody>

                  </table>

                  <div class='modal fade' id="quick-view-modal">
                    <div class='modal-dialog' role='dialog'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <span class='pull-right'>
                            <a href="javascript:void(0)" data-dismiss="modal">
                            &times;
                            </a>
                          </span>
                        </div>
                        <div class='modal-body'></div>
                      </div>
                    </div>
                  </div>    
                   
<script>



        var table =  $('#completion_grid').DataTable( {
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
      url: baseurl + "completed-grid-export",
      cache: false,
      success: function(data){
        alert(data);
    } 
});
}
} );
                  </script>
                