@extends('layouts.default')
@section('content')
<!-- start: Content -->

<div id="content" class="span11">
<?php

if(Auth::user()->type_id==1 || Auth::user()->type_id==4)
{
    ?>
<div id="vendorChangeMsg" style="display:none;" class="alert alert-success"></div>
Vendor: <select name="vendorsAssigned" id="vendorsAssigned" >
    <?php 

    foreach ($vendorsDATA as $key => $value) {
     ?>
      <option value="<?php echo $value->id;?>"  <?php if($order->vendor_id==$value->id) { echo "selected=selected";} ?> ><?php echo $value->first_name;?> <?php echo $value->last_name;?> <?php echo $value->company;?></option>
     <?php
    }?>
        
        </select>

<a class="btn btn-info" href="#" onclick="changeVendorOrder()" > Change Vendor  </a>
<?php } ?>
<a class="btn btn-info" href="#" onclick="printDiv('content')" style="float: right;position: relative;z-index: 999;"> Print  </a>

<?php

if(Auth::user()->type_id==1||Auth::user()->type_id==3)
{

 if($order->maintenanceRequest->asset->property_dead_status==1 && $order->close_property_status==0){?>
<div style="background: red;color: #fff;padding: 12px;float: left;">
<span>Property Closed</span>
<br/>
@if(isset($order->maintenanceRequest->asset->close->first_name))
<span>User:{{$order->maintenanceRequest->asset->close->first_name}} {{$order->maintenanceRequest->asset->close->last_name}}</span>
@endif
<br/>
<span>Date Time:{{$order->maintenanceRequest->asset->property_dead_date}}</span>
</div>
<div>
<input name="close_property_status" onclick="closeWorkOrderOrContinue('{{$order->id}}','0')" type="checkbox" value="0" @if($order->close_property_status==0) checked @endif/>  Continue with Workorder
<input name="close_property_status" onclick="closeWorkOrderOrContinue('{{$order->id}}','1')" type="checkbox" value="1"  @if($order->close_property_status==1) checked @endif/>  Stop Workorder
</div>
<!-- <div class ="disableProperty"><span>Property Closed</span></div> -->
<?php }elseif ($order->maintenanceRequest->asset->property_dead_status==1 && $order->close_property_status==1) {
?>
<div style="z-index: 100000;position: relative;">
<input name="close_property_status" onclick="closeWorkOrderOrContinue('{{$order->id}}','0')" type="checkbox" value="0" @if($order->close_property_status==0) checked @endif/>  Continue with Workorder
<input name="close_property_status" onclick="closeWorkOrderOrContinue('{{$order->id}}','1')" type="checkbox" value="1"  @if($order->close_property_status==1) checked @endif/>  Stop Workorder
</div>
<div class ="disableProperty">
<span>Property Closed</span>
<br/>
@if(isset($order->maintenanceRequest->asset->close->first_name))
<span>User:{{$order->maintenanceRequest->asset->close->first_name}} {{$order->maintenanceRequest->asset->close->last_name}}</span>
@endif
<br/>
<span>Date Time:{{$order->maintenanceRequest->asset->property_dead_date}}</span>
<span></span>
</div>
<?php
}

 }elseif($order->maintenanceRequest->asset->property_dead_status==1){?>
<div class ="disableProperty">
<span>Property Closed</span>
<br/>
@if(isset($order->maintenanceRequest->asset->close->first_name))
<span>User:{{$order->maintenanceRequest->asset->close->first_name}} {{$order->maintenanceRequest->asset->close->last_name}}</span>
@endif
<br/>
<span>Date Time:{{$order->maintenanceRequest->asset->property_dead_date}}</span>
</div>
<?php }?>


 <?php

if(Auth::user()->type_id==3)
{
?>
<a class="btn btn-info" style="background: #FF90A3;" href="{{URL::to('add-bid-request')}}/{{$order->id}}" style="float:right" >
 Add OSR
</a>
<?php
}

 ?>

    <h1 class="text-center">Work Order</h1>
  {{ Form::hidden('order_image_id', "",array("id"=>"order_image_id"))}} 
    <div class="row-fluid">
        <div class="box span12">
            <table class="table table-bordered customeTable"> 
                <tbody>
                    <tr>
                        <td class="center span3"><h2><span>Property #:</span>{{$order->maintenanceRequest->asset->asset_number}}</h2></td>
                        <td class="center span3"><h2><span>Order #:</span>{{$order->id}}</h2></td>
                        <td class="center span3"><h2><span>Recurring:</span> No</h2></td>
                        <td class="center span3"><h2><span>Status:</span> @if($order->status==1) In-Process @else {{$order->status_text}} @endif</h2></td>
                        <td class="center span3">

                        <h2>
                        @foreach($order_details as $order_detail)
                            <span style="font-size: 13px !important;font-weight: normal;">@if($order_detail->requestedService->service->title) {{$order_detail->requestedService->service->title}}  @endif <?php if(isset($order_detail->requestedService->due_date)&&$order_detail->requestedService->due_date!="") { echo 'Due Date: '. date('m/d/Y', strtotime($order_detail->requestedService->due_date));} else { echo 'Due Date: Not Assigned'; } ?></span><span id="changeButton" style="display:none;"><input type="text" class="datepicker" name="duedatechange" id="duedatechange" /><button onclick="SaveDueDate('{{$order_detail->requestedService->id}}')">Save</button></span><?php if(Auth::user()->type_id==1||Auth::user()->type_id==4) { ?><button class="btn" onclick="changeDueDate('{{$order->id}}')">Update</button><?php }?>
                        
                              @endforeach


                        
                        </h2>
                        </td>

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
                            <td class="center span3"><h2>Customer First Name: <span >{{$order->maintenanceRequest->user->first_name}}</span></h2></td>
                            <td class="center span3"><h2>Customer Last Name: <span >{{$order->maintenanceRequest->user->last_name}}</span></h2></td>
                        </tr>
                         @if(Auth::user()->type_id!=3)
                         <tr>
                            <td class="center span3"><h2>Company: <span >{{$order->maintenanceRequest->user->company}}</span></h2></td>
                            <td class="center span3"><h2>Email: <span >{{$order->maintenanceRequest->user->email}}</span></h2></td>
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
                            <td class="center span3"><h2>Property Address: <span >{{$order->maintenanceRequest->asset->property_address}}</span> <button class="btn btn-small btn-success" data-target="#showAsset" data-toggle="modal">View Property</button></h2></td>
                              <td class="center span3"><h2>City: <span >{{$order->maintenanceRequest->asset->city->name}} </span></h2></td>
                         
                         </tr>
                        <tr>
                              <td class="center span3"><h2>State: <span >{{$order->maintenanceRequest->asset->state->name}}</span></h2></td>
                             <td class="center span3"><h2>Zip: <span > {{$order->maintenanceRequest->asset->zip}}</span> </h2></td>
                          
                        </tr>
                        <tr>
                            <td class="center span3"><h2>Lockbox: <span >{{$order->maintenanceRequest->asset->lock_box}}</span></h2></td>
                       <td class="center span3"><h2>Gate / Access Code: <span >{{$order->maintenanceRequest->asset->access_code}}</span></h2></td>
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
$totalRequestedServices=0;
$RecurringFlag=0;
     ?>
    @foreach($order_details as $order_detail)
    @if(!isset($order_detail->requestedService->service->title))
    <?php continue?>
    @endif
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>{{$order_detail->requestedService->service->title}}</h2>
                <div class="box-icon">
                <?php
                if($order_detail->requestedService->recurring==1)
                {
                    $RecurringFlag=1;
                }
                $totalRequestedServices++;
                 if($order_detail->requestedService->quantity=="" || $order_detail->requestedService->quantity==0)
                 {

                 if( Auth::user()->type_id==3) {
                  $SpecialPriceVendor=  SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                             ->where('customer_id','=',Auth::user()->id)
                             ->where('type_id','=',3)
                             ->get();

             $vendor_priceFIND="";
             if(!empty($SpecialPriceVendor[0]))
            {
                  $vendor_priceFIND=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;
            ?>

            Price : ${{$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity}}
      
            <?php


            }else{
                 $vendor_priceFIND=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;

             ?>
                Price : ${{$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity}}

                <?php 
                }
                ?>


                <?php
$totalPrice+=$vendor_priceFIND;

                ?>
                <?php }else if( Auth::user()->type_id==2) { 
                    $SpecialPriceVendor=  SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                             ->where('customer_id','=',Auth::user()->id)
                             ->where('type_id','=',2)
                             ->get();

             $vendor_priceFIND="";
             if(!empty($SpecialPriceVendor[0]))
            {
                  $vendor_priceFIND=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;
            ?>

            Price : ${{$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity}}
      
            <?php


            }else{
                 $vendor_priceFIND=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;
             ?>
                Price : ${{$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity}}

                <?php 
                }
                ?>


                <?php
$totalPrice+=$vendor_priceFIND;

                ?>
                <?php }
                else {


                       $SpecialPriceCustomer=  SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                             ->where('customer_id','=',$order->customer->id)
                             ->where('type_id','=',2)
                             ->get();
 $customer_priceFIND="";
             if(!empty($SpecialPriceCustomer[0]))
            {
                $totalPriceCustomer+=$SpecialPriceCustomer[0]->special_price;
 ?>
Customer Price:${{$SpecialPriceCustomer[0]->special_price}}
 <?php
            }else {
                  $totalPriceCustomer+=$order_detail->requestedService->service->customer_price;
                    ?>
Customer Price:${{$order_detail->requestedService->service->customer_price}}
<?php
}

 $SpecialPriceVendor=  SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                             ->where('customer_id','=',$order->vendor->id)
                             ->where('type_id','=',3)
                             ->get();
    if(!empty($SpecialPriceVendor[0]))
            {
                $totalPriceVendor+=$SpecialPriceVendor[0]->special_price;
                ?>
                 Vendor Price:${{$SpecialPriceVendor[0]->special_price}}
                 <?php
            }
            else{
?>
 Vendor Price:${{$order_detail->requestedService->service->vendor_price}}
 <?php
 $totalPriceVendor+=$order_detail->requestedService->service->vendor_price;
}


     ?>
                <?php
                }
            }
            else
            {
                
 if( Auth::user()->type_id==3) {

                  $SpecialPriceVendor=  SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                             ->where('customer_id','=',Auth::user()->id)
                             ->where('type_id','=',3)
                             ->get();

             $vendor_priceFIND="";
             if(!empty($SpecialPriceVendor[0]))
            {
                  $vendor_priceFIND=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;
            ?>

            Price : ${{$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity}}
      
            <?php


            }else{
                 $vendor_priceFIND=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;
             ?>
                Price : ${{$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity}}

                <?php 
                }
                ?>


                <?php
$totalPrice+=$vendor_priceFIND;

                ?>
                <?php }else if( Auth::user()->type_id==2) {
         
                  $SpecialPriceVendor=  SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                             ->where('customer_id','=',Auth::user()->id)
                             ->where('type_id','=',2)
                             ->get();

             $vendor_priceFIND="";
             if(!empty($SpecialPriceVendor[0]))
            {
                  $vendor_priceFIND=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;
            ?>

            Price : ${{$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity}}
      
            <?php


            }else{
                 $vendor_priceFIND=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;
             ?>
                Price : ${{$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity}}

                <?php 
                }
                ?>


                <?php
$totalPrice+=$vendor_priceFIND;

                ?>
                <?php }
                else {

                      $SpecialPriceCustomer=  SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                             ->where('customer_id','=',$order->customer->id)
                             ->where('type_id','=',2)
                             ->get();

             if(!empty($SpecialPriceCustomer[0]))
            {


$totalPriceCustomer+=$SpecialPriceCustomer[0]->special_price*$order_detail->requestedService->quantity;;

                ?>

Customer Price:${{$SpecialPriceCustomer[0]->special_price*$order_detail->requestedService->quantity}} 
 
                <?php
            }else{

$totalPriceCustomer+=$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity;;

                    ?>
Customer Price:${{$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity}} 
 
    <?php
    }


 $SpecialPriceVendor=  SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                             ->where('customer_id','=',$order->vendor->id)
                             ->where('type_id','=',3)
                             ->get();
    if(!empty($SpecialPriceVendor[0]))
            { 
                $totalPriceVendor+=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;;


                ?>
         Vendor Price:${{$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity}}

        <?php
            }else {
$totalPriceVendor+=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;;

    ?>

    Vendor Price:${{$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity}}
    <?php } ?>
 <?php

 
     ?>
                <?php
                }

            }
                ?>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                </div>
            </div>
            
            <div class="box-content">
                <div id="vendor-note-empty-error-{{$order_detail->id}}" class="hide">
                    <h4 class="alert alert-error">Vendor Note Can not be Empty</h4>
                </div>
                <div id="vendor-note-empty-success-{{$order_detail->id}}" class="hide">
                    <h4 class="alert alert-success">Saved Successful</h4>
                </div>
                <table class="table"> 
                  
                                    <?php
                 if( Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {
                    ?>               <tr>
                        <td colspan="2" class="center"><h2>Customer Note:</h2>{{$order_detail->requestedService->customer_note}}   </td>
                    </tr> 
                      <tr>
                        <td colspan="2" class="center"><h2>Note for Vendor:</h2>{{$order_detail->requestedService->public_notes}}   </td>
                    </tr> 
                    <tr>  
                       <td colspan="2" class="center"><h2>Vendor Note:</h2>{{$order_detail->requestedService->vendor_note}}   </td>
                   </tr>
                   <?php }elseif( Auth::user()->type_id == 3 ) {
                         ?>
                        
                 
                      <tr>
                        <td colspan="2" class="center"><h2>Note for Vendor:</h2>
                            @if($order_detail->requestedService->public_notes)
                            <span>{{$order_detail->requestedService->public_notes}}</span >
                            @else
                            <span>Sorry! Vendor has not added any public note</span >
                            @endif
                    </tr>
                           <?php 
                     }elseif( Auth::user()->type_id == 2 ) {
                         ?>


                         <?php }?>
                 
                    <tr>
                        <td class="center" colspan="2">
                            <span class="pull-left">
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#before_{{$order_detail->id}}" class="btn btn-large btn-success">Upload Before Images</button>
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#before_view_image_{{$order_detail->id}}" onclick="popModal({{$order->id}}, {{$order_detail->id}}, 'before')" class="btn">View Before Images</button>
                            </span>


                             <span class="pull-during">
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#during_{{$order_detail->id}}" class="btn btn-large btn-success">Upload During Images</button>
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#during_view_image_{{$order_detail->id}}" onclick="popModal({{$order->id}}, {{$order_detail->id}}, 'during')" class="btn">View During Images</button>
                            </span>

                            <span class="pull-right">
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#after_view_image_{{$order_detail->id}}" onclick="popModal({{$order->id}}, {{$order_detail->id}}, 'after')" class="btn">View After Images</button>
                            <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif  data-toggle="modal" data-backdrop="static" data-target="#after_{{$order_detail->id}}" class="btn btn-large btn-success">Upload After Images</button>
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
                            <span id="show-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{$order_detail->requestedService->vendor_note}}<br><button class="btn btn-primary" id="edit-vendor-note-button-{{$order->id}}-{{$order_detail->id}}" onclick="editVendorNoteButton({{$order->id}},{{$order_detail->id}})"> Edit Note </button> </span >
                            <span class="hide" id="textarea-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{Form::textarea('vendor_note', $order_detail->requestedService->vendor_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))}}</span></td>
                            @else
                            <span id="show-vendor-note-{{$order->id}}-{{$order_detail->id}}"></span >
                            <span id="textarea-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{Form::textarea('vendor_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))}}</span></td>
                            @endif
                    </tr>
                     <tr>
                        <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right"  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif onclick="saveVendorNote({{$order->id}}, {{$order_detail->id}})">Save {{$order_detail->requestedService->service->title}}</button></td>

                    </tr>
                    <?php } else if( Auth::user()->type_id == 2 ) {
                    ?>

                     <tr>
                        <td colspan="2" class="center"><h2>Customers Note:</h2>
                            @if($order_detail->requestedService->customer_note)
                            <span id="show-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{$order_detail->requestedService->custumer_note}}<br><button class="btn btn-primary" id="edit-vendor-note-button-{{$order->id}}-{{$order_detail->id}}" onclick="editVendorNoteButton({{$order->id}},{{$order_detail->id}})"> Edit Note </button> </span >
                            <span class="hide" id="textarea-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{Form::textarea('custumer_note', $order_detail->requestedService->customer_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))}}</span></td>
                            @else
                            <span id="show-vendor-note-{{$order->id}}-{{$order_detail->id}}"></span >
                            <span id="textarea-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{Form::textarea('custumer_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))}}</span></td>
                            @endif
                    </tr>
                     <tr>
                        <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right"  onclick="saveCustomerNote({{$order->id}}, {{$order_detail->id}})">Save {{$order_detail->requestedService->service->title}}</button></td>

                    </tr>
                
                    <?php } else if( Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ||Auth::user()->user_role_id == 5 ||Auth::user()->user_role_id == 6||Auth::user()->user_role_id == 8 ) {
                    ?>

                     <tr>
                        <td colspan="2" class="center"><h2>Admin Note:</h2>
                            @if($order_detail->requestedService->admin_note)
                            <span id="show-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{$order_detail->requestedService->admin_note}}<br><button class="btn btn-primary" id="edit-vendor-note-button-{{$order->id}}-{{$order_detail->id}}" onclick="editVendorNoteButton({{$order->id}},{{$order_detail->id}})"> Edit Note </button> </span >
                            <span class="hide" id="textarea-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{Form::textarea('admin_note', $order_detail->requestedService->admin_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))}}</span></td>
                            @else
                            <span id="show-vendor-note-{{$order->id}}-{{$order_detail->id}}"></span >
                            <span id="textarea-vendor-note-{{$order->id}}-{{$order_detail->id}}">{{Form::textarea('admin_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))}}</span></td>
                            @endif
                    </tr>
                     <tr>
                        <td class="center" colspan="2"><button class="btn btn-large btn-warning pull-right" onclick="saveAdminNote({{$order->id}}, {{$order_detail->id}})">Save {{$order_detail->requestedService->service->title}}</button></td>

                    </tr>
                    <?php } ?>


                          <tr><td>Service Details</td><td></td></tr>
                @if($order_detail->requestedService->required_date!="")
                <tr><td>Required Date</td>
                <td>{{ date('m/d/Y', strtotime($order_detail->requestedService->required_date)) }}
              
                </td>
                </tr>
                @endif

                   @if( $order_detail->requestedService->due_date!="")
                <tr><td>Due Date</td>
                <td>
              {{ date('m/d/Y', strtotime($order_detail->requestedService->due_date)) }}
                </td>
                </tr>
                @endif

                  @if($order_detail->requestedService->quantity!="")
                <tr><td>Quantity</td>
              <td>
                    <span id="show-vendor-qty">{{ $order_detail->requestedService->quantity }}</span>
                    </td>
                </tr>
                @endif



                    @if($order_detail->requestedService->service_men!="")
                <tr><td>Service men</td>
                <td>{{$order_detail->requestedService->service_men }}
              
                </td>
                </tr>
                @endif
                    @if($order_detail->requestedService->service_note!="")
                <tr><td>Service note</td>
                <td>{{$order_detail->requestedService->service_note }}
              
                </td>
                </tr>
                @endif

                    @if($order_detail->requestedService->verified_vacancy!="")
                <tr><td>Verified vacancy</td>
                <td>{{$order_detail->requestedService->verified_vacancy }}
              
                </td>
                </tr>
                @endif
                  @if($order_detail->requestedService->cash_for_keys!="")
                <tr><td>Cash for keys</td>
                <td>{{$order_detail->requestedService->cash_for_keys }}
              
                </td>
                </tr>
                @endif

                   @if($order_detail->requestedService->cash_for_keys_trash_out!="")
                <tr><td>Cash for keys Trash Out</td>
                <td>{{$order_detail->requestedService->cash_for_keys_trash_out }}
              
                </td>
                </tr>
                @endif

                   @if($order_detail->requestedService->trash_size!="")
                <tr><td>trash size</td>
                <td>{{$order_detail->requestedService->trash_size }}
              
                </td>
                </tr>
                @endif


                   @if($order_detail->requestedService->storage_shed!="")
                <tr><td>storage shed</td>
                <td>{{$order_detail->requestedService->storage_shed }}
              
                </td>
                </tr>
                @endif


                   @if($order_detail->requestedService->lot_size!="")
                <tr><td>lot size</td>
                <td>{{$order_detail->requestedService->lot_size }}
              
                </td>
                </tr>
                @endif

                   @if($order_detail->requestedService->set_prinkler_system_type!="")
                <tr><td>set prinkler system type</td>
                <td>{{$order_detail->requestedService->set_prinkler_system_type }}
              
                </td>
                </tr>
                @endif
                

                   @if($order_detail->requestedService->install_temporary_system_type!="")
                <tr><td>install temporary system type</td>
                <td>{{$order_detail->requestedService->install_temporary_system_type }}
              
                </td>
                </tr>
                @endif
                


                   @if($order_detail->requestedService->pool_service_type!="")
                <tr><td>pool service type</td>
                <td>{{$order_detail->requestedService->pool_service_type }}
              
                </td>
                </tr>
                @endif
                

                   @if($order_detail->requestedService->carpet_service_type!="")
                <tr><td>carpet service type</td>
                <td>{{$order_detail->requestedService->carpet_service_type }}
              
                </td>
                </tr>
                @endif

                 @if($order_detail->requestedService->boarding_type!="")
                <tr><td>boarding type</td>
                <td>{{$order_detail->requestedService->boarding_type }}
              
                </td>
                </tr>
                @endif
                


                 @if($order_detail->requestedService->spruce_up_type!="")
                <tr><td>spruce up type</td>
                <td>{{$order_detail->requestedService->spruce_up_type }}
              
                </td>
                </tr>
                @endif
                


                 @if($order_detail->requestedService->constable_information_type!="")
                <tr><td>constable information type</td>
                <td>{{$order_detail->requestedService->constable_information_type }}
              
                </td>
                </tr>
                @endif
                

                  @if($order_detail->requestedService->remove_carpe_type!="")
                <tr><td>remove carpe type</td>
                <td>{{$order_detail->requestedService->remove_carpe_type }}
              
                </td>
                </tr>
                @endif
                

                 @if($order_detail->requestedService->remove_blinds_type!="")
                <tr><td>remove blinds type</td>
                <td>{{$order_detail->requestedService->remove_blinds_type }}
              
                </td>
                </tr>
                @endif
                
                    @if($order_detail->requestedService->remove_appliances_type!="")
                <tr><td>remove appliances type</td>
                <td>{{$order_detail->requestedService->remove_appliances_type }}
              
                </td>
                </tr>
                @endif


                   
                </table>      
            </div>
        </div><!--/span-->

    </div><!--/row-->
    
    
    
    <!--/   Modal-Section Start   -->
    <!--/   Modal-Section Add Before Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="before_{{$order_detail->id}}">
        <div class="row-fluid dragImage">
                {{ Form::open(array('url' => 'add-before-images', 'class'=>'dropzone', 'id'=>'form-before-'.$order_detail->id)) }}
                {{ Form::hidden('order_id', $order->id,array("id"=>"order_id_for_change"))}}
                {{ Form::hidden('order_details_id', $order_detail->id)}}
                {{ Form::hidden('type', 'before')}}
                <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button> 
                {{ Form::close() }} 
        </div>
<!--        <div class="row-fluid">
                
        </div>-->
    </div>
    <!--/   Modal-Section Add Before Images End   -->
    

<!--/   Modal-Section Start   -->
    <!--/   Modal-Section Add Before Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="during_{{$order_detail->id}}">
        <div class="row-fluid dragImage">
                {{ Form::open(array('url' => 'add-during-images', 'class'=>'dropzone', 'id'=>'form-during-'.$order_detail->id)) }}
                {{ Form::hidden('order_id', $order->id,array("id"=>"order_id_for_change"))}}

                {{ Form::hidden('order_details_id', $order_detail->id)}}
                {{ Form::hidden('type', 'during')}}
                <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button> 
                {{ Form::close() }} 
        </div>
<!--        <div class="row-fluid">
                
        </div>-->
    </div>











    <!--/   Modal-Section Add After Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="after_{{$order_detail->id}}">
        <div class="row-fluid dragImage">
                {{ Form::open(array('url' => 'add-after-images', 'class'=>'dropzone', 'id'=>'form-after-'.$order_detail->id)) }}
                {{ Form::hidden('order_id', $order->id)}}
                {{ Form::hidden('order_details_id', $order_detail->id)}}
                {{ Form::hidden('type', 'after')}}
                <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button> 
                {{ Form::close() }} 
        </div>
    </div>
    <!--/   Modal-Section Add After Images End   -->
    
    
    <!--/   Modal-Section Show Before Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" role="dialog" class="modal hide fade modelForm"  id="before_view_image_{{$order_detail->id}}">
        <div class="well text-center"><h1>View Before Image</h1></div>
        <div class="row-fluid" id="before_view_modal_image_{{$order_detail->id}}">
        </div>
        <div class="row-fluid">
                <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button> 
        </div>
    </div>
    <!--/   Modal-Section Show Before Images End   -->



     <!--/   Modal-Section Show During Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" role="dialog" class="modal hide fade modelForm"  id="during_view_image_{{$order_detail->id}}">
        <div class="well text-center"><h1>View During Image</h1></div>
        <div class="row-fluid" id="during_view_modal_image_{{$order_detail->id}}">
        </div>
        <div class="row-fluid">
                <button data-dismiss="modal" style="margin: 40px 0 25px 0;" class="btn btn-large btn-success">Close</button> 
        </div>
    </div>
    <!--/   Modal-Section Show Before Images End   -->
    
    
    <!--/   Modal-Section Show After Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="after_view_image_{{$order_detail->id}}">
        <div class="well text-center"><h1>View After Image</h1></div>
        <div class="row-fluid" id="after_view_modal_image_{{$order_detail->id}}">
        </div>
        <div class="row-fluid">
                <button data-dismiss="modal" style="margin: 40px 0 25px 0;" class="btn btn-large btn-success">Close</button> 
        </div>
    </div>
    <!--/   Modal-Section Show After Images End   -->
    <!--/   Modal-Section End   -->
    
    @endforeach
    <input type="hidden" name="totalRequestedServices" id="totalRequestedServices" value="<?php echo $totalRequestedServices;?>">
       <?php 

        if(Auth::user()->type_id==3 || Auth::user()->type_id==2) {?>
         <div style="float:right;"><h2>Total Price: ${{$totalPrice}} </h2>
    </div>  

        <?php }else { ?>
                
  <div style="float:right;"><h2>Total Customer Price: ${{$totalPriceCustomer}} Total Vendor Price: ${{$totalPriceVendor}} </h2>
    </div>  

    <?php } ?>

      <!--/   Asset Modal-Section Start   -->

      <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
<script type="text/javascript">



    // function loadScript(src,callback){

    //     var script = document.createElement("script");
    //     script.type = "text/javascript";
    //     if(callback)script.onload=callback;
    //     document.getElementsByTagName("head")[0].appendChild(script);
    //     script.src = src;
    // }


    // loadScript('//maps.googleapis.com/maps/api/js?v=3&sensor=false&callback=initialize',
    //   function(){});



    function initialize() {


     var myLatlng = new google.maps.LatLng({{$order->maintenanceRequest->asset->latitude}},{{$order->maintenanceRequest->asset->longitude}});

     var mapOptions = {
      zoom: 17,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('map_canvas'),
    mapOptions);
  
  var newc = map.getStreetView();


  console.log(newc);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: ''
  });

}

setTimeout(function(){ 

initialize();
}, 5000);
function log(str){
  document.getElementsByTagName('pre')[0].appendChild(document.createTextNode('['+new Date().getTime()+']\n'+str+'\n\n'));
}

 
</script>
    <div class="modal  hide fade modelForm larg-model"  id="showAsset">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button"  style="margin: 40px 0 25px 0;" class="close" data-dismiss="modal">x</button>
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
                                        <label class="control-label" for="typeahead">{{$order->maintenanceRequest->asset->property_address}}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">City: </label>
                                        <label class="control-label" for="typeahead"> {{$order->maintenanceRequest->asset->city->name}}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">State:</label>
                                        <label class="control-label" for="typeahead">{{$order->maintenanceRequest->asset->state->name}}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Zip :</label>
                                        <label class="control-label" for="typeahead">{{$order->maintenanceRequest->asset->zip}}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Lockbox </label>
                                        <label class="control-label" for="typeahead">{{$order->maintenanceRequest->asset->lock_box}}</label>
                                      </div>
                                     
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Gate / Access Code: </label>
                                        <label class="control-label" for="typeahead">{{$order->maintenanceRequest->asset->access_code}} </label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Occupancy Status: </label>
                                        <label class="control-label" for="typeahead">{{$order->maintenanceRequest->asset->occupancy_status}} </label>
                                      </div>
                                      
                                    </div>
                                    <!--/span-6-->
                                    
                                    <div class="span6">
                                   
                                    
                                                        <div class="control-group row-sep">

                                  <div id="map_canvas" style="height:250px"></div>


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
                                        <label class="control-label label-auto" for="textarea2">{{$order->maintenanceRequest->asset->outbuilding_shed_note}}</label>
                                        @else
                                        <label class="control-label label-auto" for="textarea2">There is no note regarding Outbuilding Shed</label>
                                        @endif
                                      </div>
                                    </div>
                                    <div class="control-group hidden-phone">
                                      <div class="controls">
                                        <label class="control-label" for="textarea2">Directions or Special Notes:</label>
                                        @if($order->maintenanceRequest->asset->special_direction_note != '')
                                        <label class="control-label label-auto" for="textarea2">{{$order->maintenanceRequest->asset->special_direction_note}}</label>
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
                                 
                                  <div class="control-group">
                                  <label class="control-label">Water *</label>
                                  <div class="controls">
                                
                                    

                                     @if($order->maintenanceRequest->asset->water_status == 1)
                                  <label class="control-label">Yes</label>
                                  @else
                                  <label class="control-label">No</label>
                                  @endif
                                  
                                  </div>
                                  
                                  <div class="control-group">
                                    <label class="control-label">Gas *</label>
                                    <div class="controls">
                                       @if($order->maintenanceRequest->asset->gas_status == 1)
                                  <label class="control-label">Yes</label>
                                  @else
                                  <label class="control-label">No</label>
                                  @endif
                                  
                                    </div>
                                     <div class="control-group hidden-phone">
                                      <div class="controls">
                                        <label class="control-label" for="textarea2">Utility Note:</label>
                                        <label class="control-label label-auto" for="textarea2">{{$order->maintenanceRequest->asset->utility_note}}</label>
                                      </div>
                                    </div>
                                    <div class="control-group multiRadio">
                                      <label class="control-label">Swimming *</label>
                                      <div class="controls">
                                        <label class="radio">
                                        

                                        @if($order->maintenanceRequest->asset->swimming_pool=="pool")
                                        Pool
                                        @elseif($order->maintenanceRequest->asset->swimming_pool=="spa")
                                        Spa
                                        @elseif($order->maintenanceRequest->asset->swimming_pool=="koi-pond")
                                        Koi Pond
                                        @elseif($order->maintenanceRequest->asset->swimming_pool=="pond")
                                         Pond
                                         @else
                                         {{$order->maintenanceRequest->asset->swimming_pool}}
                                        @endif
                                          
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
            
            <div >
               Completion date {{Form::text('completion_date', $order->completion_date, array('class'=> 'input-small span2 datepicker', 'id'=> 'completion_date' ))}}   
                 <button id="completion_date_div" style="display:none" class="btn btn-small" onclick="completionDate()" >Save</button>
                 <span style="float:right;display:none;" id="edit-qty">
                    <span id="show-vendor-qty-{{$order->id}}-{{$order_detail->id}}">{{ $order_detail->requestedService->quantity }}
                        <button class="btn btn-primary" id="edit-vendor-qty-button-{{$order->id}}-{{$order_detail->id}}" onclick="editVendorQuantityButton({{$order->id}},{{$order_detail->id}})"> &nbsp;Edit Quantity </button>
                    </span>
                    <span class="hide" id="input-vendor-qty-{{$order->id}}-{{$order_detail->id}}">{{Form::text('quantity', $order_detail->requestedService->quantity ,array('style'=>'width:30%', 'class'=>'span','id'=>'vendor-qty-'.$order->id.'-'.$order_detail->id))}}
                    <button class="btn btn-large btn-warning pull-right" onclick="saveAdminQuantity({{$order->id}}, {{$order_detail->id}})">Save Quantity</button>
                    </span>
                </span>
                <br/>
            </div>
            
               <tr>

    
                <p id="errorMessage" style="display:none; margin-top:10px;"></p>
                <div class="btn-group"  id="btn-group-unique" >      >
                    <button class="btn btn-large orderstatus">@if($order->status==1) In-Process @else {{$order->status_text}} @endif</button>
                    <button class="btn btn-large dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu mystatusclass"  @if(Auth::user()->type_id==3 && $order->status==4) style="display:none;" @endif  >
                        <li><a href="#">In-Process</a></li>
                     
                        <li><a href="#">Completed</a></li>

                       @if(Auth::user()->type_id==4||Auth::user()->type_id==1||Auth::user()->type_id==2)
                        <li><a href="#">Approved</a></li>
                        <li><a href="#" class="underreview">Under Review</a></li>
                        
                        <li><a href="#">Cancelled</a></li>
     
                      
                        @endif
                       
                    </ul>
                </div>
                <?php 


                if($RecurringFlag==1)
                {
                    ?>
          
  {{ Form::hidden('recurring_id', $RecurringFlag,array("id"=>"recurring_id"))}}
                <?php
                }
                ?>
                 <div class="mystatusclass"  id="btn-group-unique-mobile">
                  Current Status  <button class="btn btn-large label-{{ $order->status_class }}" style="color:#ffffff;" >@if($order->status==1) In-Process @else {{$order->status_text}} @endif</button>
                  
                    <ul class=""  @if(Auth::user()->type_id==3 && $order->status==4) style="display:none;" @endif  >
                        <li class="btn label-warning"><a href="#" style="color:#ffffff;">In-Process</a></li>
                     
                        <li class="btn " style="background: #000000;"><a href="#" style="color:#ffffff;">Completed</a></li>

                       @if(Auth::user()->type_id==4||Auth::user()->type_id==1||Auth::user()->type_id==2)
                        <li class="btn " style="background: gray;color:#ffffff;"><a href="#" style="color:#ffffff;">Approved</a></li>
                         <li class="btn underreview" style="background: gray;color:#ffffff;"><a href="#" style="color:#ffffff;">Under Review</a></li>
                      
                        <li class="btn " style="background: gray;color:#ffffff;"><a href="#" style="color:#ffffff;">Cancelled</a></li>
                       
                        @endif
                       
                    </ul>
                </div>
                <div style="display:none;" id="under_review_notes_section">

                {{Form::textarea('under_review_notes', '' ,array('class'=>'span','id'=>'under_review_notes'))}}
                <a class="btn btn-info" href="#" onclick="under_review_notes('<?php echo $order->vendor_id;?>')" > Save</a>

                </div>
                              @if((Auth::user()->type_id==3||Auth::user()->type_id==1||Auth::user()->type_id==4||Auth::user()->type_id==5||Auth::user()->type_id==6) &&(count($OrderReviewNote))>0)

                <div class="reviews_note_history">

                <h3>Under Review Notes History</h3>
                <ul>
                <?php
                foreach ($OrderReviewNote as  $value) {
                   ?>
                   <li><?php echo $value->review_notes."---".date('m/d/Y h:i:s A',strtotime( $value->created_at )) ;?></li>
                   <?php
                }
                ?>
                </ul>
                </div>
                @endif
                        

                </tr>
                <tr>
                    <td class="center" colspan="2"></td>
                </tr>      
            </div>
        </div><!--/span-->

    </div><!--/row-->

     <!--/   Modal-Section Start   -->
    <!--/   Modal-Section Add Before Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="recurringpopup">
        <div class="row-fluid dragImage">
                <div>
                <h2>Recurring Reminder</h2>
                This is recurring service</div>
                <br/>
                <button class="btn btn-large btn-success" style="margin: 40px 0 25px 0;" data-dismiss="modal"> Close</button> 
              
        </div>
<!--        <div class="row-fluid">
                
        </div>-->
    </div>

</div>
<!-- end: Content -->

<!--Upload Before Image-->

<!--/Upload Before Image-->
@stop

