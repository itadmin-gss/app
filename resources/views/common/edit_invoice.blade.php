@extends('layouts.default')
@section('content')
<!-- start: Content -->
<a class="btn btn-info" href="#" onclick="printDiv('content')" style="float:right"> Print  </a>

<div id="content" class="span11">

    <h1 class="text-center">Invoice Order</h1>
 
    <div class="row-fluid">
        <div class="box span12">
            <table class="table table-bordered customeTable"> 
                <tbody>
                    <tr>
                        <td class="center span3"><h2><span>Property #:</span>{!!$order->maintenanceRequest->asset->asset_number!!}</h2></td>
                        <td class="center span3"><h2><span>Order #:</span>{!!$order->id!!}</h2></td>
                        <td class="center span3"><h2><span>Recurring:</span> No</h2></td>
                        <td class="center span3"><h2><span>Status:</span> @if($order->status==1) In-Progress @else {!!$order->status_text!!} @endif</h2></td>
                        <td class="center span3">

                        <h2>
                       <!--  @foreach($order_details as $order_detail)
                        	<span style="font-size: 13px !important;font-weight: normal;">{!!$order_detail->requestedService->service->title!!}   <?php if($order_detail->requestedService->due_date!="") { echo 'Due Date: '. date('m/d/Y', strtotime($order_detail->requestedService->due_date));} else { echo 'Due Date: Not Assigned'; } ?></span>
                        <br/>------------------<br/>
                        	  @endforeach


                         -->
                        </h2>
                        </td>
                        <td class="center text-center span3"><h2><button class="btn btn-small btn-success" data-target="#showAsset" data-toggle="modal">View Property</button></h2></td>
                    </tr>
                </tbody>
            </table>      
        </div><!--/span-->
    </div><!--/row-->
      <?php
     if( Auth::user()->type_id != 3 ) {
        ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2>Customer Details</h2>
            </div>
            <div class="box-content">
                <table class="table"> 
                    <tbody>
                        <tr>
                            <td class="center span3"><h2>Customer First Name: <span >{!!$order->maintenanceRequest->user->first_name!!}</span></h2></td>
                            <td class="center span3"><h2>Customer Last Name: <span >{!!$order->maintenanceRequest->user->last_name!!}</span></h2></td>
                        </tr>
                         @if(Auth::user()->type_id!=3)
                         <tr>
                            <td class="center span3"><h2>Company: <span >{!!$order->maintenanceRequest->user->company!!}</span></h2></td>
                            <td class="center span3"><h2>Email: <span >{!!$order->maintenanceRequest->user->email!!}</span></h2></td>
                        </tr>
                        @endif
                    </tbody>
                </table>      
            </div>

        </div><!--/span-->
    </div><!--/row-->   
    <?php } ?>

    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2>Property Details</h2>
            </div>
            <div class="box-content">
                <table class="table"> 
                    <tbody>
                        <tr>
                            <td class="center span3"><h2>Property Address: <span >{!!$order->maintenanceRequest->asset->property_address!!}</span></h2></td>
                              <td class="center span3"><h2>City: <span >{!!$order->maintenanceRequest->asset->city->name!!} </span></h2></td>
                         
                         </tr>
                        <tr>
                              <td class="center span3"><h2>State: <span >{!!$order->maintenanceRequest->asset->state->name!!}</span></h2></td>
                             <td class="center span3"><h2>Zip: <span > {!!$order->maintenanceRequest->asset->zip!!}</span> </h2></td>
                          
                        </tr>
                        <tr>
                            <td class="center span3"><h2>Lockbox: <span >{!!$order->maintenanceRequest->asset->lock_box!!}</span></h2></td>
                       <td class="center span3"><h2>Get / Access Code: <span >{!!$order->maintenanceRequest->asset->access_code!!}</span></h2></td>
                       </tr>
                       
                    </tbody>
                </table>      
            </div>

        </div><!--/span-->
    </div><!--/row-->   
   
    <?php //echo $before_image; die;

$totalPriceCustomer=0;
$totalPriceVendor=0;
$totalPrice=0;
     ?>
    @foreach($order_details as $order_detail)
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>{!!$order_detail->requestedService->service->title!!}</h2>
                <div class="box-icon">
                <?php

                 if($order_detail->requestedService->quantity=="" || $order_detail->requestedService->quantity==0)
                 {



                 if( Auth::user()->type_id==3) {?>
                Price : ${!!$order_detail->requestedService->service->vendor_price!!}
                <?php
$totalPrice+=$order_detail->requestedService->service->vendor_price

                ?>
                <?php }else if( Auth::user()->type_id==2) {?>
           Price : ${!!$order_detail->requestedService->service->customer_price!!}
               <?php
$totalPrice+=$order_detail->requestedService->service->customer_price

                ?>
                <?php }
                else {?>
Customer Price:${!!$order_detail->requestedService->service->customer_price!!} Vendor Price:${!!$order_detail->requestedService->service->vendor_price!!}
 <?php
$totalPriceCustomer+=$order_detail->requestedService->service->customer_price;
$totalPriceVendor+=$order_detail->requestedService->service->vendor_price;

     ?>
                <?php
                }
            }
            else
            {
            	
 if( Auth::user()->type_id==3) {?>
                Price : ${!!$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity!!}
                <?php
$totalPrice+=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;

                ?>
                <?php }else if( Auth::user()->type_id==2) {?>
           Price : ${!!$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity!!}
               <?php
$totalPrice+=$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity;

                ?>
                <?php }
                else {?>
Customer Price:${!!$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity!!} Vendor Price:${!!$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity!!}
 <?php
$totalPriceCustomer+=$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity;;
$totalPriceVendor+=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;;

     ?>
                <?php
                }

            }
                ?>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                </div>
            </div>
            
            <div class="box-content">
                <div id="vendor-note-empty-error-{!!$order_detail->id!!}" class="hide">
                    <div class="alert alert-error">Vendor Note Can not be Empty</h4>
                </div>
                <div id="vendor-note-empty-success-{!!$order_detail->id!!}" class="hide">
                    <div class="alert alert-success">Saved Successful</h4>
                </div>
                <table class="table"> 
                  
                                    <?php
     if( Auth::user()->type_id == 1 ) {
        ?>        <tr>
                        <td colspan="2" class="center"><h2>Customer Note:</h2>{!!$order_detail->requestedService->customer_note!!}   </td>
                    </tr> 
                    <tr>  
                       <td colspan="2" class="center"><h2>Vendor Note:</h2>{!!$order_detail->requestedService->vendor_note!!}   </td>
                   </tr>
                   <?php }elseif( Auth::user()->type_id == 3 ) {
                         ?>
                        
                     <tr>
                     <td colspan="2" class="center"><h2>Admin Note:</h2>{!!$order_detail->requestedService->admin_note!!}   </td>
                         </tr> 
                           <?php 
                     }elseif( Auth::user()->type_id == 2 ) {
                         ?>


                         <?php }?>
                 
                    <tr>
                        <td class="center" colspan="2">
                            <span class="pull-left">
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#before_{!!$order_detail->id!!}" class="btn btn-large btn-success">Upload Before Images</button>
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#before_view_image_{!!$order_detail->id!!}" onclick="popModal({!!$order->id!!}, {!!$order_detail->id!!}, 'before')" class="btn">View Before Images</button>
                            </span>
                            <span class="pull-right">
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#after_view_image_{!!$order_detail->id!!}" onclick="popModal({!!$order->id!!}, {!!$order_detail->id!!}, 'after')" class="btn">View After Images</button>
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif  data-toggle="modal" data-backdrop="static" data-target="#after_{!!$order_detail->id!!}" class="btn btn-large btn-success">Upload After Images</button>
                            </span>
                        </td>
                    </tr>

                    <!-- <tr>
                      <td colspan="2" class="center"><label class="control-label" for="typeahead">Vendor Note:</label><textarea style="width: 100%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 139px;" rows="6" id="limit"></textarea></td>
                    </tr> -->

                       <?php
     if( Auth::user()->type_id == 3 ) {
        ?>
                    <tr>
                        <td colspan="2" class="center"><h2>Vendor Note:</h2>
                            @if($order_detail->requestedService->vendor_note)
                            <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!$order_detail->requestedService->vendor_note!!}<br><button class="btn btn-primary" id="edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}" onclick="editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})"> Edit Note </button> </span >
                            <span class="hide" id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('vendor_note', $order_detail->requestedService->vendor_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                            @else
                            <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}"></span >
                            <span id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('vendor_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                            @endif
                    </tr>
                     <tr>
                        <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right"  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif onclick="saveVendorNote({!!$order->id!!}, {!!$order_detail->id!!})">Save {!!$order_detail->requestedService->service->title!!}</button></td>

                    </tr>
                    <?php } else if( Auth::user()->type_id == 2 ) {
                    ?>

                     <tr>
                        <td colspan="2" class="center"><h2>Customers Note:</h2>
                            @if($order_detail->requestedService->customer_note)
                            <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!$order_detail->requestedService->custumer_note!!}<br><button class="btn btn-primary" id="edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}" onclick="editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})"> Edit Note </button> </span >
                            <span class="hide" id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('custumer_note', $order_detail->requestedService->customer_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                            @else
                            <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}"></span >
                            <span id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('custumer_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                            @endif
                    </tr>
                     <tr>
                        <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right"  onclick="saveCustomerNote({!!$order->id!!}, {!!$order_detail->id!!})">Save {!!$order_detail->requestedService->service->title!!}</button></td>

                    </tr>
                
                    <?php } else if( Auth::user()->type_id == 1 ) {
                    ?>

                     <tr>
                        <td colspan="2" class="center"><h2>Admin Note:</h2>
                            @if($order_detail->requestedService->admin_note)
                            <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!$order_detail->requestedService->admin_note!!}<br><button class="btn btn-primary" id="edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}" onclick="editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})"> Edit Note </button> </span >
                            <span class="hide" id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('admin_note', $order_detail->requestedService->admin_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                            @else
                            <span id="show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}"></span >
                            <span id="textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::textarea('admin_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                            @endif
                    </tr>
                     <tr>
                        <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right" onclick="saveAdminNote({!!$order->id!!}, {!!$order_detail->id!!})">Save {!!$order_detail->requestedService->service->title!!}</button></td>

                    </tr>
                    <?php } ?>


                   
                </table>      
            </div>
        </div><!--/span-->

    </div><!--/row-->
    
    
    
    <!--/   Modal-Section Start   -->
    <!--/   Modal-Section Add Before Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="before_{!!$order_detail->id!!}">
        <div class="row-fluid dragImage">
                {!! Form::open(array('url' => 'add-before-images', 'class'=>'dropzone', 'id'=>'form-before-'.$order_detail->id)) !!}
                {!! Form::hidden('order_id', $order->id,array("id"=>"order_id_for_change"))!!}
                {!! Form::hidden('order_details_id', $order_detail->id)!!}
                {!! Form::hidden('type', 'before')!!}
                <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button> 
                {!! Form::close() !!} 
        </div>
<!--        <div class="row-fluid">
                
        </div>-->
    </div>
    <!--/   Modal-Section Add Before Images End   -->
    
    <!--/   Modal-Section Add After Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="after_{!!$order_detail->id!!}">
        <div class="row-fluid dragImage">
                {!! Form::open(array('url' => 'add-after-images', 'class'=>'dropzone', 'id'=>'form-after-'.$order_detail->id)) !!}
                {!! Form::hidden('order_id', $order->id)!!}
                {!! Form::hidden('order_details_id', $order_detail->id)!!}
                {!! Form::hidden('type', 'after')!!}
                <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button> 
                {!! Form::close() !!} 
        </div>
    </div>
    <!--/   Modal-Section Add After Images End   -->
    
    
    <!--/   Modal-Section Show Before Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" role="dialog" class="modal hide fade modelForm"  id="before_view_image_{!!$order_detail->id!!}">
        <div class="well text-center"><h1>View Before Image</h1></div>
        <div class="row-fluid" id="before_view_modal_image_{!!$order_detail->id!!}">
        </div>
        <div class="row-fluid">
                <button data-dismiss="modal" class="btn btn-large btn-success">Close</button> 
        </div>
    </div>
    <!--/   Modal-Section Show Before Images End   -->
    
    
    <!--/   Modal-Section Show After Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="after_view_image_{!!$order_detail->id!!}">
        <div class="well text-center"><h1>View After Image</h1></div>
        <div class="row-fluid" id="after_view_modal_image_{!!$order_detail->id!!}">
        </div>
        <div class="row-fluid">
                <button data-dismiss="modal" class="btn btn-large btn-success">Close</button> 
        </div>
    </div>
    <!--/   Modal-Section Show After Images End   -->
    <!--/   Modal-Section End   -->
    
    @endforeach
       <?php if( Auth::user()->type_id==3 || Auth::user()->type_id==2) {?>
       <!--   <div style="float:right;"><h2>Total Price: ${!!$totalPrice!!} </h2>
    </div>  --> 

        <?php }else { ?>
                
<!--   <div style="float:right;"><h2>Total Customer Price: ${!!$totalPriceCustomer!!} Total Vendor Price: ${!!$totalPriceVendor!!} </h2>
    </div>   -->

    <?php } ?>

    <div style="float: left;">
     @if(Auth::user()->type_id==1)
    <div id="errorMessage" style="display:none;"></div>
        Total Invoice Price
 <input type="text" id="invoice_price" name="invoice_price" value="{!!$order->total_amount!!}"  />
 <input type="hidden" id="invoice_id" name="invoice_id" value="{!!$order->id!!}" />
 <a class="btn btn-info" href="#" onclick="updateInvoice()" style="float:right"> Update Price  </a>
    @endif
    </div>
    <!--/   Asset Modal-Section Start   -->
    <div class="modal  hide fade modelForm larg-model"  id="showAsset">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h1>Property Detail</h1>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row-fluid">
                          <div class="box span12 noMarginTop">
                            <div class="custome-form assets-form">
                              <form class="form-horizontal">
                                <fieldset>
                                  <div class="row-fluid">
                                    <div class="span6">
                                     
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Property Address:</label>
                                        <label class="control-label" for="typeahead">{!!$order->maintenanceRequest->asset->property_address!!}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">City: </label>
                                        <label class="control-label" for="typeahead"> {!!$order->maintenanceRequest->asset->city->name!!}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">State:</label>
                                        <label class="control-label" for="typeahead">{!!$order->maintenanceRequest->asset->state->name!!}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Zip :</label>
                                        <label class="control-label" for="typeahead">{!!$order->maintenanceRequest->asset->zip!!}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Lockbox </label>
                                        <label class="control-label" for="typeahead">{!!$order->maintenanceRequest->asset->lock_box!!}</label>
                                      </div>
                                     
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Get / Access Code: </label>
                                        <label class="control-label" for="typeahead">{!!$order->maintenanceRequest->asset->access_code!!} </label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Occupancy Status: </label>
                                        <label class="control-label" for="typeahead">{!!$order->maintenanceRequest->asset->occupancy_status!!} </label>
                                      </div>
                                      
                                    </div>
                                    <!--/span-6-->
                                    
                                    <div class="span6">
                                   
                                    
                                                        <div class="control-group row-sep">

                                  <div id="map_canvas" style="height:250px"></div>

<script type="text/javascript">
    function loadScript(src,callback){
  
    var script = document.createElement("script");
    script.type = "text/javascript";
    if(callback)script.onload=callback;
    document.getElementsByTagName("head")[0].appendChild(script);
    script.src = src;
  }
  
  
  loadScript('//maps.googleapis.com/maps/api/js?v=3&sensor=false&callback=initialize',
              function(){});


function initialize() {
     
   var myLatlng = new google.maps.LatLng({!!$order->maintenanceRequest->asset->latitude!!},{!!$order->maintenanceRequest->asset->longitude!!});
   
    var mapOptions = {
          zoom: 17,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map_canvas'),
            mapOptions);
    var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: ''
  });
    
  }

function log(str){
  document.getElementsByTagName('pre')[0].appendChild(document.createTextNode('['+new Date().getTime()+']\n'+str+'\n\n'));
}


</script>

                                          </div>
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                    <!--/span-6--> 
                                    
                                  <div class="row-fluid">
                                  
                                  <div class="control-group">
                                    <label class="control-label">Outbuilding / Shed *</label>
                                    @if($order->maintenanceRequest->asset->outbuilding_shed == 1)
                                    <label class="control-label">Yes</label>
                                    @else
                                    <label class="control-label">No</label>
                                    @endif
                                    <div style="clear:both"></div>
                                    <div class="control-group hidden-phone">
                                      <div class="controls">
                                        <label class="control-label" for="textarea2">Note:</label>
                                        @if($order->maintenanceRequest->asset->outbuilding_shed_note != '')
                                        <label class="control-label label-auto" for="textarea2">{!!$order->maintenanceRequest->asset->outbuilding_shed_note!!}</label>
                                        @else
                                        <label class="control-label label-auto" for="textarea2">There is no note regarding Outbuilding Shed</label>
                                        @endif
                                      </div>
                                    </div>
                                    <div class="control-group hidden-phone">
                                      <div class="controls">
                                        <label class="control-label" for="textarea2">Directions or Special Notes:</label>
                                        @if($order->maintenanceRequest->asset->outbuilding_shed_note != '')
                                        <label class="control-label label-auto" for="textarea2">{!!$order->maintenanceRequest->asset->special_direction!!}</label>
                                        @else
                                        <label class="control-label label-auto" for="textarea2">There is no Special Direction</label>
                                        @endif
                                      </div>
                                    </div>
                                  </div>
                                  <h4>Utility - On inside Property?</h4>
                                  <div class="control-group">
                                  <label class="control-label">Electric :</label>
                                  @if($order->maintenanceRequest->asset->electric_status == 1)
                                  <label class="control-label">Yes</label>
                                  @else
                                  <label class="control-label">No</label>
                                  @endif
                                  <div style="clear:both"></div>
                                  <div class="control-group hidden-phone">
                                    <div class="controls">
                                      <label class="control-label label-auto" for="textarea2">This note over here</label>
                                    </div>
                                  </div>
                                  <div class="control-group">
                                  <label class="control-label">Water *</label>
                                  <div class="controls">
                                    <label class="radio">
                                      <input type="radio" name="pool" id="optionsRadios1" value="yes" checked="">
                                      Yes </label>
                                    <label class="radio property-pool">
                                      <input type="radio" name="pool" id="optionsRadios2" value="no">
                                      No </label>
                                  </div>
                                  <div class="control-group hidden-phone">
                                    <div class="controls">
                                      <label class="control-label" for="textarea2">Notes:</label>
                                      <label class="control-label label-auto" for="textarea2">This is directions or special notes</label>
                                    </div>
                                  </div>
                                  <div class="control-group">
                                    <label class="control-label">Gas *</label>
                                    <div class="controls">
                                      <label class="radio">
                                        <input type="radio" name="pool" id="optionsRadios1" value="yes" checked="">
                                        Yes </label>
                                      <label class="radio property-pool">
                                        <input type="radio" name="pool" id="optionsRadios2" value="no">
                                        No </label>
                                    </div>
                                    <div class="control-group hidden-phone">
                                      <div class="controls">
                                        <label class="control-label" for="textarea2">Notes:</label>
                                        <label class="control-label label-auto" for="textarea2">This is directions or special notes</label>
                                      </div>
                                    </div>
                                    <div class="control-group multiRadio">
                                      <label class="control-label">Swimming *</label>
                                      <div class="controls">
                                        <label class="radio">
                                          <input type="radio" name="Swimming" id="" value="yes" checked="">
                                          Poo </label>
                                        <label class="radio property-pool">
                                          <input type="radio" name="Swimming" id="" value="no">
                                          Spa </label>
                                        <label class="radio">
                                          <input type="radio" name="Swimming" id="" value="yes" checked="">
                                          Koi Pond </label>
                                        <label class="radio property-pool">
                                          <input type="radio" name="Swimming" id="" value="no">
                                          Pond </label>
                                      </div>
                                    </div>
                                    
                                  </div>
                                </fieldset>
                              </form>
                            </div>
                          </div>
                          <!--/span--> 
                          
                        </div>
                        <!--/row-->
                        
                    </div>
                    <div class="modal-footer">
                        <div class="text-right">
                          <button type="button" class="btn btn-large btn-inverse" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
             </div>   
        </div>
    <!--/   Asset Modal-Section End   -->
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>Order Status</h2>
            </div>
            <div class="box-content">

                <tr>

 
<p id="errorMessage" style="display:none"></p>
                <div class="btn-group"          >
                    <button class="btn btn-large orderstatus">@if($order->status==1) In-Progress @else {!!$order->status_text!!} @endif</button>
                    <button class="btn btn-large dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu mystatusclass"  @if(Auth::user()->type_id==3 && $order->status==4) style="display:none;" @endif  >
                        <li><a href="#">In-Progress</a></li>
                        <li><a href="#">In-Completed</a></li>
                        <li><a href="#">Completed</a></li>

                       @if(Auth::user()->type_id==1||Auth::user()->type_id==2)
                        <li><a href="#">Approved</a></li>
                        @endif
                       
                    </ul>
                </div>
                        

                </tr>
                <tr>
                    <td class="center" colspan="2"></td>
                </tr>      
            </div>
        </div><!--/span-->

    </div><!--/row-->

    

</div>
<!-- end: Content -->

<!--Upload Before Image-->

<!--/Upload Before Image-->
@stop
