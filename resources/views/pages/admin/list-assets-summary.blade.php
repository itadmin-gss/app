@extends('layouts.default')
@section('content')


<style type="text/css">
.datatablegrid {display: block;}
.datatablegrid2 {display: none;}
.datatablegrid3 {display: none;}
.datatablegrid4 {display: none;}

</style>
<div id="content" class="span11">
<!-- <lable>Filter Report</lable> -->
<!-- <select id="assetSummary">
    <option value="1">Requests</option>
    <option value="2">Work Orders</option>
    <option value="3">Invoices</option>
    <option value="4">Bids</option>
</select> -->
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header datatablegrid" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Requests</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content datatablegrid admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable datatable">

                    <thead>
                        <tr>
                            <th>Client Type</th>
                            <th>Customer Name</th>
                            <th>Property Address</th>
                            <th>Unit #</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                            <th>Services</th>
                            <th>Order ID</th>
                            <th>Order Status</th>
                            <th>Completed Date</th>
                            <th>Vendor Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($assets_data as $asset)
                        @foreach ($asset->maintenanceRequest as $assetReq)
                        <tr>
                            <td>{!!$asset->customerType->title!!}</td>
                            <td> @if(isset($assetReq->user->first_name)) {!!$assetReq->user->first_name!!} @endif     @if(isset($assetReq->user->last_name)) {!!$assetReq->user->last_name!!} @endif </td>
                            <td>{!!$asset->property_address!!}</td>
                            <td>{!!$asset->UNIT!!}</td>
                            <td>{!!$asset->city->name!!}</td>
                            <td>{!!$asset->state->name!!}</td>
                            <td>{!!$asset->zip!!}</td>
                           
                          
                            <?php
                            $servicedate="";
                            foreach ($assetReq->requestedService as  $value) {
                                if(isset( $value->service->title))
                                   $servicedate .=  $value->service->title  ;

                               if(isset($value->due_date))
                               { 
                                   $servicedate .= "<br>".    $value->due_date . ', <br>';
                               }
                               else
                               {
                                $servicedate .=   ', <br>';


                            }

                        }
                        $orderID="";
                        $status_class="";
                        $status_text="";
                        $completion_date="";
                        $vendorName="";
                       foreach ($assetReq->order as  $od) {
                                  $orderID= $od->id;
                                  $status_class=$od->status_class;
                                  $status_text=$od->status_text;
                                  $completion_date=$od->completion_date;
                                  if(isset($od->vendor->first_name)){
                                  $vendorName=$od->vendor->first_name." ".$od->vendor->last_name;
                       }            }
                           
                  

                      
                        ?>
                        <td>{!! $servicedate!!}</td>
                         <td>{!! $orderID!!}</td>
                        

                         <td class="center"> <span class="label label-{!!$status_class!!} "> {!!$status_text!!} </span> </td>
                       
                         <td class="center">{!!$completion_date!!}</td>
                         <td class="center">{!!$vendorName!!}</td>

                        <td class="center">
                            <a class="btn btn-success view_asset_information"  id="{!!$asset->id!!}" title="View Property">
                                <i class="halflings-icon zoom-in halflings-icon"></i>
                            </a>
                            <a class="btn btn-info" href="edit-asset/{!!$asset->id!!}" title="Edit Property">
                                <i class="halflings-icon edit halflings-icon"></i>
                            </a>
                                <!--<a class="btn btn-danger" onclick="" href="delete-customer-asset/{!!$asset->id!!}">
                                                                    <i class="halflings-icon trash halflings-icon"></i>
                                        </a>-->
                                    </td>
                                </tr>
                                @endforeach
                                <?php $i++; ?>
                                @endforeach



                            </tbody>
                        </table>

                    </div>
              </div>
            <div class="box-header datatablegrid2" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Work Orders</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content datatablegrid2 admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable datatable2">

                    <thead>
                        <tr>
                            <th>Property #.</th>
                            <th>Property Address</th>
                            <th>Work Order ID</th>
                            <th>Customer Name</th>
                            <th>Vendor Name</th>
                            <th>Service Type / Due Date</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                      
                        @foreach ($assets_data as $asset)
                        @foreach ($asset->maintenanceRequest as $assetReq)
                        @foreach($assetReq->order as $assetOrder)
                       
                        <tr>

                            <td>{!!$asset->asset_number!!}</td>
                            <td>{!!$asset->property_address!!}</td>
                            <td>{!!$assetOrder->id!!}</td>
                            <td> @if(isset($assetOrder->customer->first_name))  {!!$assetOrder->customer->first_name!!} @endif   @if(isset($assetOrder->customer->last_name))  {!!$assetOrder->customer->last_name!!} @endif  </td>
                           <td> @if(isset($assetOrder->vendor->first_name))  {!!$assetOrder->vendor->first_name!!} @endif @if(isset($assetOrder->vendor->last_name))  {!!$assetOrder->vendor->last_name!!} @endif</td>
                          
                            <?php
                            $servicedate="";
                            foreach ($assetOrder->orderDetail as  $value) {
                                if(isset( $value->requestedService->service->title))
                                   $servicedate .=  $value->requestedService->service->title  ;

                               if(isset($value->requestedService->due_date))
                               { 
                                   $servicedate .= "<br>".    $value->requestedService->due_date . ', <br>';
                               }
                               else
                               {
                                $servicedate .=   ', <br>';


                            }

                        }
                        ?>
                        <td>{!! $servicedate!!}</td>
                        <td class="center">
                            <a class="btn btn-success "  href="view-order/{!!$assetOrder->id!!}" id="{!!$asset->id!!}">
                                <i class="halflings-icon zoom-in halflings-icon"></i>
                            </a>
                            <a class="btn btn-info" href="edit-order/{!!$assetOrder->id!!}">
                                <i class="halflings-icon edit halflings-icon"></i>
                            </a>
                                <!--<a class="btn btn-danger" onclick="" href="delete-customer-asset/{!!$asset->id!!}">
                                                                    <i class="halflings-icon trash halflings-icon"></i>
                                                                </a>-->
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                            <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>

                </div>
          </div>
         <div class="box-header datatablegrid3" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Invoices</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
        </div>
            <div class="box-content datatablegrid3 admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable datatable3">

                    <thead>
                        <tr>
                            <th>Property #.</th>
                            <th>Property Address</th>
                            <th>Invoice ID</th>
                            <th>Order ID</th>
                            <th>Vendor/Customer Name</th>
                            <th>Service Type / Due Date</th>
                             <th>Amount</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($assets_data as $asset)
                        @foreach ($asset->maintenanceRequest as $assetReq)
                        @foreach ($assetReq->invoiceRequest as $assetInv)
                        <tr>
                            <td>{!!$asset->asset_number!!}</td>
                            <td>{!!$asset->property_address!!}</td>
                            <td>{!!$assetInv->id!!}</td>
                            <td>{!!$assetInv->order->id!!}</td>
                            @if($assetInv->user_type_id==2)
                            <td>Customer -@if(isset($assetInv->customer->first_name))  {!!$assetInv->customer->first_name!!} @endif   @if(isset($assetInv->customer->last_name))  {!!$assetInv->customer->last_name!!} @endif   </td>
                            @else
                            <td>Vendor - @if(isset($assetInv->vendor->first_name))  {!!$assetInv->vendor->first_name!!} @endif   @if(isset($assetInv->vendor->last_name))  {!!$assetInv->vendor->last_name!!} @endif   </td>
                            
                            @endif

                            <?php
                            $servicedate="";
                            foreach ($assetReq->requestedService as  $value) {
                                if(isset( $value->service->title))
                                   $servicedate .=  $value->service->title  ;

                               if(isset($value->due_date))
                               { 
                                   $servicedate .= "<br>".    $value->due_date . ', <br>';
                               }
                               else
                               {
                                $servicedate .=   ', <br>';


                            }

                        }
                        ?>
                        <td>{!! $servicedate!!}</td>
                          <td>{!!$assetInv->total_amount!!}</td>
                        <td class="center">
                            <a class="btn btn-success " href="view-order/{!!$assetInv->order->id!!}"id="{!!$asset->id!!}">
                                <i class="halflings-icon zoom-in halflings-icon"></i>
                            </a>
                            <a class="btn btn-info" href="edit-order/{!!$assetInv->order->id!!}">
                                <i class="halflings-icon edit halflings-icon"></i>
                            </a>
                                <!--<a class="btn btn-danger" onclick="" href="delete-customer-asset/{!!$asset->id!!}">
                                                                    <i class="halflings-icon trash halflings-icon"></i>
                                                                </a>-->
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                            <?php $i++; ?>
                            @endforeach



                        </tbody>
                    </table>

                </div>
            </div>
                                        
            <div class="box-header datatablegrid4" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Bids</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content datatablegrid4 admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable datatable4">

                    <thead>
                        <tr>
                            <th>Property #.</th>
                            <th>Property Address</th>
                            <th>Bid ID</th>
                            <th>Customer Name</th>
                            <th>Service Type / Due Date</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($assets_data as $asset)
                        @foreach ($asset->bidRequest as $assetReq)
                        <tr>
                            <td>{!!$asset->asset_number!!}</td>
                            <td>{!!$asset->property_address!!}</td>
                            <td>{!!$assetReq->id!!}</td>
                            <td>{!!$assetReq->user->first_name!!} {!!$assetReq->user->last_name!!}</td>
                            <?php
                            $servicedate="";
                            foreach ($assetReq->requestedService as  $value) {
                                if(isset( $value->service->title))
                                   $servicedate .=  $value->service->title  ;

                               if(isset($value->due_date))
                               { 
                                   $servicedate .= "<br>".    $value->due_date . ', <br>';
                               }
                               else
                               {
                                $servicedate .=   ', <br>';


                            }

                        }
                        ?>
                        <td>{!! $servicedate!!}</td>
                        <td class="center">
                          
                            <a class="btn btn-info" href="view-admin-bid-request/{!!$assetReq->id!!}">
                                <i class="halflings-icon zoom-in halflings-icon"></i>
                            </a>
                                <!--<a class="btn btn-danger" onclick="" href="delete-customer-asset/{!!$asset->id!!}">
                                                                    <i class="halflings-icon trash halflings-icon"></i>
                                                                </a>-->
                                </td>
                            </tr>
                            @endforeach
                            <?php $i++; ?>
                            @endforeach



                        </tbody>
                    </table>
                    </div>
                </div>
            </div><!--/span-->

            <div id="asset_information" class="modal  hide fade modelForm larg-model"></div>
        </div><!--/row-->

    </div>

                                @stop