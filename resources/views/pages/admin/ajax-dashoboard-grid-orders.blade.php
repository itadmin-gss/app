
 <div class='table-container'>
    <div class='table-responsive'>
      <div class='table-padding table-heading'>
        <?php

          if (isset($id))
          {
            switch ($id){
              case 3:
                echo "<h4>Under Review</h4>";
              break;
              case 1:
                echo "<h4>In Process</h4>";
              break;
            }
          }
        ?>
      </div>
        <table class="table table-striped table-bordered table-sm dt-responsive datatabledashboard" width='100%' cellspacing='0' id='dataTable'> 

                      <thead>

                        <tr>

                         <th>ID #</th>

                         <th>Created</th>

                         <th>Submitter</th>

                         <th>Client Type</th>

                         <th>Customer Name</th>

                         <th>Property Address</th>

                         <th>City</th>

                         <th>State</th>

                         <th>Zip</th>

                         <th>Vendor Name</th>

                         <th>Job Types</th>

                         <th>Service Type</th>

                         <th>Due</th>
                         <th>Billing Comment</th>

                         <th>Status</th>

                         <th>Action</th>

                       </tr>

                     </thead>

                     <tbody>

                  

                      @foreach ($orders as $order)

                      <tr>

                        <td>{!! $order['order_id']!!}</td>
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



                        @if($order['request_status']==4)

                            <td class="center">
                              <a class="btn btn-success btn-xs action-button" disabled="disabled" href="#" title="View"> 
                                  <i class="fa fa-search-plus" aria-hidden="true"></i>                              
                              </a> 
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
                      @foreach($addl_itemz as $key => $value)
                        @if($key == $order['order_id'])
                          @foreach($value as $index => $addl_service)
                          <tr>

                            <td >{!! $index !!}</td>
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
                        @endif
                      @endforeach

                

                      @endforeach

                    </tbody>

                  </table>

                  <script type="text/javascript">


$('.datatabledashboard').DataTable( {
      dom: 'Bfrtip',
      "order": [[ 0, "desc" ]],
      buttons: [
      'copy', 'csv', 'excel', 'print'
      ]
    } );
</script>

