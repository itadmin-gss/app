@extends('layouts.default')

@section('content')

<!-- start: Content -->

<div id="content" class="span11">

@if($message == "")
    
     @else
   <div class="alert alert-success">
        {!!$message!!}
    </h4>
     @endif
 
<a class="btn btn-info" href="#" onclick="printDiv('content')" style="float: right;position: relative;z-index: 999;float:right"> Print  </a>



<?php if($order->maintenanceRequest->asset->property_dead_status==1){?>

<div class ="disableProperty"><span>Property Closed</span></div>

<?php }?>

    <h1 class="text-center">Work Order</h1>

<div class="bidAlert">Work Order was generated due to Approved Bid. </div>

    <div class="row-fluid">

        <div class="box span12">

            <table class="table table-bordered customeTable"> 

                <tbody>

                    <tr>

                        <td class="center span3"><h2><span>Property #:</span>{!!$order->maintenanceRequest->asset->asset_number!!}</h2></td>

                        <td class="center span3"><h2><span>Order #:</span>{!!$order->id!!}</h2></td>

                        <td class="center span3"><h2><span>Recurring:</span> No</h2></td>

                        <td class="center span3">

                            <h2><span>Status:</span>

                               @if($order->status==1) In-Progress @else {!!$order->status_text!!} @endif

                            </h2></td>

                        <td class="center span3">

                        	  <h2>

                        	  @foreach($order_details as $order_detail)

                        	<span style="font-size: 13px !important;font-weight: normal;">{!!$order_detail->requestedService->service->title!!}   <?php if($order_detail->requestedService->due_date!="") { echo 'Due Date: '. date('m/d/Y', strtotime($order_detail->requestedService->due_date));} else { echo 'Due Date: Not Assigned'; } ?></span>

                        <br/>------------------<br/>

                        	  @endforeach





                        

                        </h2>



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

                            <td class="center span3"><h2>Property Address: <span >{!!$order->maintenanceRequest->asset->property_address!!}</span> <button class="btn btn-small btn-success" data-target="#showAsset" data-toggle="modal">View Property</button></h2></td>

                            <td class="center span3"><h2>City: <span >{!!$order->maintenanceRequest->asset->city->name!!} </span></h2></td>

                           

                        </tr>

                        <tr>

                             <td class="center span3"><h2>State: <span >{!!$order->maintenanceRequest->asset->state->name!!}</span></h2></td>

                             <td class="center span3"><h2>Zip: <span > {!!$order->maintenanceRequest->asset->zip!!}</span> </h2></td>

                            

                        </tr>

                        <tr>



                       <td class="center span3"><h2>Lockbox: <span >{!!$order->maintenanceRequest->asset->lock_box!!}</span></h2></td>

                       <td class="center span3"><h2>Gate / Access Code: <span >{!!$order->maintenanceRequest->asset->access_code!!}</span></h2></td>

                        </tr>

                       

                    </tbody>

                </table>      

            </div>



        </div><!--/span-->

    </div><!--/row-->	

    <span><h1 class="text-center">Requested Services</h1></span>

    <?php //echo $before_image; die;



$totalPriceCustomer=0;

$totalPriceVendor=0;

$totalPrice=0;

$totalBidPrice=0;

$totalRequestedServices=0;

     ?>

    @foreach($order_details as $order_detail)

    <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2><i class="halflings-icon edit"></i><span class="break"></span>{!!$order_detail->requestedService->service->title!!}</h2>



                <div class="box-icon">
       <?php
                $totalRequestedServices++;
                if($order_detail->requestedService->quantity==""||$order_detail->requestedService->quantity==0)
                {
                    $order_detail->requestedService->quantity=1;
                }
                 if( Auth::user()->type_id==3) {
              
                  $vendor_priceFIND=$order_detail->requestedService->bidding_prince*$order_detail->requestedService->quantity;
            ?>

            Price  : ${!!$order_detail->requestedService->bidding_prince*$order_detail->requestedService->quantity!!}
      
    
                <?php
$totalPrice+=$vendor_priceFIND;

                ?>
                <?php }else if( Auth::user()->type_id==2) { 
               
                  $vendor_priceFIND=$order_detail->requestedService->customer_price*$order_detail->requestedService->quantity;
            ?>

            Price  : ${!!  $vendor_priceFIND=$order_detail->requestedService->customer_price*$order_detail->requestedService->quantity!!}
      
           
                <?php
$totalPrice+=$vendor_priceFIND;

                ?>
                <?php }
                else {


                $totalPriceCustomer+=$order_detail->requestedService->customer_price*$order_detail->requestedService->quantity;
 ?>
Customer Price:${!!$order_detail->requestedService->customer_price*$order_detail->requestedService->quantity!!}

<?php

                $totalPriceVendor+=$order_detail->requestedService->bidding_prince*$order_detail->requestedService->quantity;
                ?>
                 Vendor Price:${!!$order_detail->requestedService->bidding_prince*$order_detail->requestedService->quantity!!}
             
                <?php
                }
           ?>
                

                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>

            

            

                </div>

            </div>

            

            <div class="box-content">

             {!!$order_detail->requestedService->service->desc!!}

                <div id="vendor-note-empty-error-{!!$order_detail->id!!}" class="hide">

                    <div class="alert alert-error">Vendor Note Can not be Empty</h4>

                </div>

                <div id="vendor-note-empty-success-{!!$order_detail->id!!}" class="hide">

                    <div class="alert alert-success">Saved Successful</h4>

                </div>

                <table class="table"> 

                                   <?php

                 if( Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {

                    ?>               <tr>

                        <td colspan="2" class="center"><h2>Customer Note:</h2>{!!$order_detail->requestedService->customer_note!!}   </td>

                    </tr> 

                      <tr>

                        <td colspan="2" class="center"><h2>Note for Vendor:</h2>{!!$order_detail->requestedService->public_notes!!}   </td>

                    </tr> 

                    <tr>  

                       <td colspan="2" class="center"><h2>Vendor Note:</h2>{!!$order_detail->requestedService->vendor_note!!}   </td>

                   </tr>

                   <?php }elseif( Auth::user()->type_id == 3 ) {

                         ?>

                        

                 

                      <tr>

                        <td colspan="2" class="center"><h2>Note for Vendor:</h2>

                            @if($order_detail->requestedService->public_notes)

                            <span>{!!$order_detail->requestedService->public_notes!!}</span >

                            @else

                            <span>Sorry! Vendor has not added any public note</span >

                            @endif

                    </tr>

                           <?php 

                     }elseif( Auth::user()->type_id == 2 ) {

                         ?>

   <tr>

                        <td colspan="2" class="center"><h2>Customer Note:</h2>{!!$order_detail->requestedService->customer_note!!}   {!!$order_detail->id!!}</td>

                    </tr>



                         <?php }?>



               

                    <tr>

                        <td class="center" colspan="2">

                            <span class="pull-left">

                            <button data-toggle="modal" data-backdrop="static" data-target="#before_view_image_{!!$order_detail->id!!}" onclick="popViewModal({!!$order->id!!}, {!!$order_detail->id!!}, 'before')" class="btn btn-large btn-success">View Before Images</button>

                            </span>

                            <span class="pull-right">

                            <button data-toggle="modal" data-backdrop="static" data-target="#after_view_image_{!!$order_detail->id!!}" onclick="popViewModal({!!$order->id!!}, {!!$order_detail->id!!}, 'after')" class="btn btn-large btn-success">View After Images</button>

                            </span>

                        </td>

                    </tr>



                    <!-- <tr>

                      <td colspan="2" class="center"><label class="control-label" for="typeahead">Vendor Note:</label><textarea style="width: 100%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 139px;" rows="6" id="limit"></textarea></td>

                    </tr> -->

                     <?php  if( Auth::user()->type_id==3) { ?>

                    <tr>

                        <td colspan="2" class="center"><h2>Vendor Note:</h2>

                            @if($order_detail->requestedService->vendor_note)

                            <span>{!!$order_detail->requestedService->vendor_note!!}</span >

                            @else

                            <span>Sorry! Vendor has not added any note</span >

                            @endif

                    </tr>

                      <tr>

                        <td colspan="2" class="center"><h2>Note for Vendor:</h2>

                            @if($order_detail->requestedService->public_notes)

                            <span>{!!$order_detail->requestedService->public_notes!!}</span >

                            @else

                            <span>Sorry! Vendor has not added any public note</span >

                            @endif

                    </tr>

                         <?php } ?>





                          <tr><td>Service Details</td><td></td></tr>

                @if($order_detail->requestedService->required_date!="")

                <tr><td>Required Date</td>

                <td>{!! date('m/d/Y', strtotime($order_detail->requestedService->required_date)) !!}

              

                </td>

                </tr>

                @endif



                   @if( $order_detail->requestedService->due_date!="")

                <tr><td>Due Date</td>

                <td>

              {!! date('m/d/Y', strtotime($order_detail->requestedService->due_date)) !!}

                </td>

                </tr>

                @endif



                  @if($order_detail->requestedService->quantity!="")

                <tr><td>Quantity</td>

                <td>{!! $order_detail->requestedService->quantity !!}

              

                </td>

                </tr>

                @endif







                    @if($order_detail->requestedService->service_men!="")

                <tr><td>Service men</td>

                <td>{!!$order_detail->requestedService->service_men !!}

              

                </td>

                </tr>

                @endif

                    @if($order_detail->requestedService->service_note!="")

                <tr><td>Service note</td>

                <td>{!!$order_detail->requestedService->service_note !!}

              

                </td>

                </tr>

                @endif



                    @if($order_detail->requestedService->verified_vacancy!="")

                <tr><td>Verified vacancy</td>

                <td>{!!$order_detail->requestedService->verified_vacancy !!}

              

                </td>

                </tr>

                @endif

                  @if($order_detail->requestedService->cash_for_keys!="")

                <tr><td>Cash for keys</td>

                <td>{!!$order_detail->requestedService->cash_for_keys !!}

              

                </td>

                </tr>

                @endif



                   @if($order_detail->requestedService->cash_for_keys_trash_out!="")

                <tr><td>Cash for keys Trash Out</td>

                <td>{!!$order_detail->requestedService->cash_for_keys_trash_out !!}

              

                </td>

                </tr>

                @endif



                   @if($order_detail->requestedService->trash_size!="")

                <tr><td>trash size</td>

                <td>{!!$order_detail->requestedService->trash_size !!}

              

                </td>

                </tr>

                @endif





                   @if($order_detail->requestedService->storage_shed!="")

                <tr><td>storage shed</td>

                <td>{!!$order_detail->requestedService->storage_shed !!}

              

                </td>

                </tr>

                @endif





                   @if($order_detail->requestedService->lot_size!="")

                <tr><td>lot size</td>

                <td>{!!$order_detail->requestedService->lot_size !!}

              

                </td>

                </tr>

                @endif



                   @if($order_detail->requestedService->set_prinkler_system_type!="")

                <tr><td>set prinkler system type</td>

                <td>{!!$order_detail->requestedService->set_prinkler_system_type !!}

              

                </td>

                </tr>

                @endif

                



                   @if($order_detail->requestedService->install_temporary_system_type!="")

                <tr><td>install temporary system type</td>

                <td>{!!$order_detail->requestedService->install_temporary_system_type !!}

              

                </td>

                </tr>

                @endif

                





                   @if($order_detail->requestedService->pool_service_type!="")

                <tr><td>pool service type</td>

                <td>{!!$order_detail->requestedService->pool_service_type !!}

              

                </td>

                </tr>

                @endif

                



                   @if($order_detail->requestedService->carpet_service_type!="")

                <tr><td>carpet service type</td>

                <td>{!!$order_detail->requestedService->carpet_service_type !!}

              

                </td>

                </tr>

                @endif



                 @if($order_detail->requestedService->boarding_type!="")

                <tr><td>boarding type</td>

                <td>{!!$order_detail->requestedService->boarding_type !!}

              

                </td>

                </tr>

                @endif

                





                 @if($order_detail->requestedService->spruce_up_type!="")

                <tr><td>spruce up type</td>

                <td>{!!$order_detail->requestedService->spruce_up_type !!}

              

                </td>

                </tr>

                @endif

                





                 @if($order_detail->requestedService->constable_information_type!="")

                <tr><td>constable information type</td>

                <td>{!!$order_detail->requestedService->constable_information_type !!}

              

                </td>

                </tr>

                @endif

                



                  @if($order_detail->requestedService->remove_carpe_type!="")

                <tr><td>remove carpe type</td>

                <td>{!!$order_detail->requestedService->remove_carpe_type !!}

              

                </td>

                </tr>

                @endif

                



                 @if($order_detail->requestedService->remove_blinds_type!="")

                <tr><td>remove blinds type</td>

                <td>{!!$order_detail->requestedService->remove_blinds_type !!}

              

                </td>

                </tr>

                @endif

                

                    @if($order_detail->requestedService->remove_appliances_type!="")

                <tr><td>remove appliances type</td>

                <td>{!!$order_detail->requestedService->remove_appliances_type !!}

              

                </td>

                </tr>

                @endif

                </table>      

            </div>

        </div><!--/span-->



    </div><!--/row-->

    

    

    

    <!--/   Modal-Section Start   -->

    <!--/   Modal-Section Add Before Images Start   -->



    <!--/   Modal-Section Add Before Images End   -->

    

    <!--/   Modal-Section Add After Images Start   -->

    

    <!--/   Modal-Section Add After Images End   -->

    

    

    <!--/   Modal-Section Show Before Images Start   -->

    <div style="padding: 10px;" class="modal hide fade modelForm viewImageModal"  id="before_view_image_{!!$order_detail->id!!}">

        <div class="well text-center"><h1>View Before Image</h1></div>

        <div class="row-fluid" id="before_view_modal_image_{!!$order_detail->id!!}">  

        </div>

        <div class="row-fluid">

                <button data-dismiss="modal" class="btn btn-large btn-success">Close</button> 

        </div>

    </div>

    <!--/   Modal-Section Show Before Images End   -->

    

    

    <!--/   Modal-Section Show After Images Start   -->

    <div style="padding: 10px;" class="modal hide fade modelForm viewImageModal"  id="after_view_image_{!!$order_detail->id!!}">

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

     <?php if(Auth::user()->type_id==3 || Auth::user()->type_id==2) {?>



         <div style="float:right;"><h2>Total Price: ${!!$totalPrice!!} </h2>

    </div>  



        <?php  }else { ?>

                

  <div style="float:right;"><h2>Total Customer Price: ${!!$totalPriceCustomer!!} Total Vendor Price: ${!!$totalPriceVendor!!}  </h2>

    </div>  



    <?php } ?>

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

  

  

  loadScript('http://maps.googleapis.com/maps/api/js?v=3&sensor=false&callback=initialize',

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

                <div class="box-content text-center">

                <tr>



        <td class="right"><span class="label label-@if($order->status==1){!!'warning'!!}@else{!!$order->status_class!!}@endif"><h2> @if($order->status==1) In-Progress @else {!!$order->status_text!!} @endif

        </h2></span></td>

   

                </tr>

               <div style="margin-top: 20px;">

               Completion date {!!Form::text('completion_date', $order->completion_date, array('class'=> 'input-small span2 datepicker', 'id'=> 'completion_date' ,'disabled'=>'disabled'))!!}   

               </div>   

            </div>

             



        </div><!--/span-->



    </div><!--/row-->



    



</div>

<!-- end: Content -->



<!--Upload Before Image-->



<!--/Upload Before Image-->

@stop