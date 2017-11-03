@extends('layouts.default')
@section('content')

<div id="content" class="span11">
<title>GSS - Completed Orders</title>
 <div class='table-container'>
    <div class='table-responsive'>
       <div class='table-heading'>
           <div class="card blue-4">
               <div class="card-body">
                   <span>Completed</span>
               </div>
            </div>

      <table class="table table-striped table-sm table-bordered dt-responsive datatabledashboard" style='width:100%;' width='100%' cellspacing='0' id="completion_grid">

                      <thead>

                        <tr>
                          
                         <th>ID #</th>
                         <th>Submitter</th>
                         <th>Client Type</th>
                         <th>Property ID</th>
                         <th>Loan ID</th>
                         <th>Customer</th>
                         <th>Property Address</th>
                         <th>City</th>
                         <th>State</th>
                         <th>Zip</th>
                         <th>Completed</th>
                         <th>Job Type</th>
                         <th>Service Type</th>
                         <th>Due</th>
                         <th>Vendor</th>
                         <th>Vendor Price</th>
                         <th>Customer Price</th>


                       </tr>

                     </thead>

                     <tbody>



        

                      @foreach ($orders as $order)

                      <tr>

                        <td >
                            <a href="javascript:void(0)" title="Quick-View" onclick="showQuickWorkOrderPage({!! $order['order_id'] !!})">
                                {!! $order['order_id']!!}
                            </a>
                        </td>

                        <td >{!! $order['submited_by']!!}</td>
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

                        <td >{!! $order['customer_name']!!}</td>


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

                        <td class="center">{!! $order['job_type'] !!}</td>
                        <td class="center">{!! $order['service_type'] !!}</td>

                        <td class="center">{!! $order['service_name'] !!}</td>

                        <td class="center">              
                          {!! $order['vendor_name'] !!} @ {!! $order['vendor_company'] !!}
                        </td>
                        
                        <td class="center">{!! $order['vendor_price'] !!}</td>
                        <td class="center">{!! $order['customer_price'] !!}</td>





                        




                      </tr>
                      @foreach($addl_itemz as $key => $value)
                      @if($key == $order['order_id'])
                      @foreach($value as $index => $addl_service)
                      <tr>
                          <td >
                              <a title="Quick-View" onclick="showQuickWorkOrderPage({!! $order['order_id'] !!}")>
                                  {!! $index!!}
                              </a>
                          </td>

                        <td >{!! $order['submited_by']!!}</td>
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
                        <td >{!! $order['customer_name']!!}</td>


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



                        
                        <td class="center">{!! $order['job_type'] !!}</td>
                        <td class="center">{!! $addl_itemz_service_type[$index] !!}</td>

                        <td class="center">{!! $order['service_name'] !!}</td>



                        <td class="center">              
                          {!! $order['vendor_name'] !!} @ {!! $order['vendor_company'] !!}
                        </td>

                        
                        <td class="center">{!! $addl_itemz_rate[$index] !!}</td>
                        <td class="center">{!! $addl_itemz_customerPrice[$index] !!}</td>





                        





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
    </div>

 </div><!--/span-->

</div><!--/row-->

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
@stop
