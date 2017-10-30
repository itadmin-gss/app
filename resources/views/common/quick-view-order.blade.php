<style type="text/css">

                                              label{color:#000;}

span{color: #000;}
</style>
<?php if (!empty( $previous)): ?>
    <button class="btn btn-primary btn-sm left" href="#"onclick="showQuickWorkOrderPage({!! $previous !!})">« Previous</button>
<?php else: ?>
     <button class="btn btn-primary btn-sm left pull-left" disabled="disabled" href="#" onclick="showQuickWorkOrderPage({!! $previous !!})" >« Previous</button>
<?php endif ?>

<?php if (!empty($next)): ?>

<button class="btn btn-primary right pull-right" href="#"onclick="showQuickWorkOrderPage({!!  $next !!})">Next »</button>

<?php else: ?>

    <button class="btn btn-primary right" disabled="disabled" href="#"onclick="showQuickWorkOrderPage({!!  $next !!})">Next »</button>

<?php endif ?>
<!-- start: Content -->
<style type="text/css">
        #recurringpopup {
            background-color: rgb(255,0,0);
            padding: 10px;
            max-height: 500px;
            overflow: auto;
        }
        #recurringpopup h5 {
            font-weight: 500;
        }
    </style>
<div id="content" class="span11">
<?php if(Auth::user()->type_id==3){
if(isset($order->maintenanceRequest->asset->property_dead_status) && $order->maintenanceRequest->asset->property_dead_status==1){?>

<div class ="disableProperty"><span>Property Closed</span></div>

<?php
  }else if($order->status==5){ ?>

<div class ="disableOrder"><span>Order Cancelled</span></div>

<?php  }

  }
?>



    <div id="printdata" class="clearfix"  style="display:none;  @page { size: landscape; }" >

     <div class="leftPnl" >
        <img class="inLogo" width="250px" src="{!!URL::to('/')!!}/assets/images/GSS-Logo.jpg">
        <!-- <p><strong>Good Scents Services, LP </strong> 118 National Dr <br>Rockwall TX 75032</p> -->
    </div>

    <h5>Property Address:  </h5> <span >@if(isset($order->maintenanceRequest->asset->property_address)) {!!$order->maintenanceRequest->asset->property_address!!}@endif</span> <br>
    <div id="printdata1">
    </div>




</div>
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

 if(isset($order->maintenanceRequest->asset->property_dead_status) && $order->maintenanceRequest->asset->property_dead_status==1 && $order->close_property_status==0){?>
 <div style="background: red;color: #fff;padding: 12px;float: left;">
    <span>Property Closed</span>
    <br/>
    @if(isset($order->maintenanceRequest->asset->close->first_name))
    <span>User:{!!$order->maintenanceRequest->asset->close->first_name!!} {!!$order->maintenanceRequest->asset->close->last_name!!}</span>
    @endif
    <br/>
    <span>Date Time:{!!$order->maintenanceRequest->asset->property_dead_date!!}</span>
</div>
<div>
    <input name="close_property_status" onclick="closeWorkOrderOrContinue('{!!$order->id!!}','0')" type="checkbox" value="0" @if($order->close_property_status==0) checked @endif/>  Continue with Workorder
    <input name="close_property_status" onclick="closeWorkOrderOrContinue('{!!$order->id!!}','1')" type="checkbox" value="1"  @if($order->close_property_status==1) checked @endif/>  Stop Workorder
</div>
<!-- <div class ="disableProperty"><span>Property Closed</span></div> -->
<?php }elseif (isset($order->maintenanceRequest->asset->property_dead_status) && $order->maintenanceRequest->asset->property_dead_status==1 && $order->close_property_status==1) {
    ?>
    <div style="z-index: 100000;position: relative;">
        <input name="close_property_status" onclick="closeWorkOrderOrContinue('{!!$order->id!!}','0')" type="checkbox" value="0" @if($order->close_property_status==0) checked @endif/>  Continue with Workorder
        <input name="close_property_status" onclick="closeWorkOrderOrContinue('{!!$order->id!!}','1')" type="checkbox" value="1"  @if($order->close_property_status==1) checked @endif/>  Stop Workorder
    </div>
    <div class ="disableProperty">
        <span>Property Closed</span>
        <br/>
        @if(isset($order->maintenanceRequest->asset->close->first_name))
        <span>User:{!!$order->maintenanceRequest->asset->close->first_name!!} {!!$order->maintenanceRequest->asset->close->last_name!!}</span>
        @endif
        <br/>
        <span>Date Time:{!!$order->maintenanceRequest->asset->property_dead_date!!}</span>
        <span></span>
    </div>
    <?php
}

}elseif(isset($order->maintenanceRequest->asset->property_dead_status) && $order->maintenanceRequest->asset->property_dead_status==1){?>
<div class ="disableProperty">
    <span>Property Closed</span>
    <br/>
    @if(isset($order->maintenanceRequest->asset->close->first_name))
    <span>User:{!!$order->maintenanceRequest->asset->close->first_name!!} {!!$order->maintenanceRequest->asset->close->last_name!!}</span>
    @endif
    <br/>
    <span>Date Time:{!!$order->maintenanceRequest->asset->property_dead_date!!}</span>
</div>
<?php }?>


<?php

if(Auth::user()->type_id==3)
{
    ?>
   <!--  <a class="btn btn-info" style="background: #FF90A3;" href="{!!URL::to('add-bid-request')!!}/{!!$order->id!!}" style="float:right" >
     Add OSR
 </a> -->
 <?php
}

?>

<h1 class="text-center">Work Order</h1>
{!! Form::hidden('order_image_id', "",array("id"=>"order_image_id"))!!}
<div class="row-fluid">
    <div class="box span12">
        <table class="table table-bordered customeTable">
            <tbody>
                <tr>
                    <td class="center span3"><h5><span>Property #:</span>@if(isset($order->maintenanceRequest->asset->asset_number)) {!!$order->maintenanceRequest->asset->asset_number!!} @endif</h5></td>
                    <td class="center span3"><h5><span>Order #:</span>{!!$order->id!!}</h5></td>
                    <td class="center span3"><h5><span>Recurring:</span> No</h5></td>
                    <td class="center span3"><h5><span>Status:</span> @if($order->status==1) In-Process @else {!!$order->status_text!!} @endif</h5></td>
                    <td class="center span3">

                        <h5>
                            @foreach($order_details as $order_detail)
                            <span style="font-size: 13px !important;font-weight: normal;">@if($order_detail->requestedService->service->title) {!!$order_detail->requestedService->service->title!!}  @endif <?php if(isset($order_detail->requestedService->due_date)&&$order_detail->requestedService->due_date!="") { echo 'Due Date: '. date('m/d/Y', strtotime($order_detail->requestedService->due_date));} else { echo 'Due Date: Not Assigned'; } ?></span><span id="changeButton" style="display:none;"><input type="text" class="datepicker" name="duedatechange" id="duedatechange" /><button onclick="SaveDueDate('{!!$order_detail->requestedService->id!!}')">Save</button></span><?php if(Auth::user()->type_id==1||Auth::user()->type_id==4) { ?><button class="btn" onclick="changeDueDate('{!!$order->id!!}')">Update</button><?php }?>

                            @endforeach



                        </h5>
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
                <h5>Customer Details</h5>
            </div>
            <div class="box-content">
                <table class="table">
                    <tbody>
                        <tr>
                        @if(empty($order->maintenanceRequest->user->first_name))
                            <td class="center span3"><h5>Customer First Name: <span ></span></h5></td>
                        @else
                            <td class="center span3"><h5>Customer First Name: <span >{!!$order->maintenanceRequest->user->first_name!!}</span></h5></td>
                        @endif
                        @if(empty($order->maintenanceRequest->user->last_name))
                            <td class="center span3"><h5>Customer First Name: <span > </span></h5></td>
                        @else
                            <td class="center span3"><h5>Customer Last Name: <span >{!!$order->maintenanceRequest->user->last_name!!}</span></h5></td>
                        @endif

                        </tr>
                        @if(Auth::user()->type_id!=3)
                        <tr>
                          @if(empty($order->maintenanceRequest->user->company))
                            <td class="center span3"><h5>Company: <span ></span></h5></td>
                          @else
                            <td class="center span3"><h5>Company: <span >{!!$order->maintenanceRequest->user->company!!}</span></h5></td>
                          @endif
                          @if(empty($order->maintenanceRequest->user->email))
                         <td class="center span3"><h5>Email: <span ></span></h5></td>
                          @else
                            <td class="center span3"><h5>Email: <span> {!!$order->maintenanceRequest->user->email!!}</span></h5></td>

                          @endif
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
                <h5>Property Details</h5>
            </div>
            <div class="box-content">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="center span3"><h5>Property Address: <span >{!!$order->maintenanceRequest->asset->property_address!!}</span> <button class="btn btn-small btn-success" data-target="#showAsset" data-toggle="modal">View Property</button></h5></td>
                            <?php if (isset($order->maintenanceRequest->asset->city->name)): ?>
                              <td class="center span3"><h5>City: <span >{!!$order->maintenanceRequest->asset->city->name!!} </span></h5></td>
                            <?php else: ?>
                              <td class="center span3"><h5>City: <span ></span></h5></td>
                            <?php endif ?>


                        </tr>
                        <tr>
                          <td class="center span3"><h5>State: <span >{!!$order->maintenanceRequest->asset->state->name!!}</span></h5></td>
                          <td class="center span3"><h5>Zip: <span > {!!$order->maintenanceRequest->asset->zip!!}</span> </h5></td>

                      </tr>
                      <tr>
                        <td class="center span3"><h5>Lockbox: <span >{!!$order->maintenanceRequest->asset->lock_box!!}</span></h5></td>
                        <td class="center span3"><h5>Gate / Access Code: <span >{!!$order->maintenanceRequest->asset->access_code!!}</span></h5></td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div><!--/span-->
</div><!--/row-->



<span><h1 class="text-center">Requested Services</h1></span>

    <?php //echo $before_image; die;

    $totalPriceCustomer=0;
    $total = 0;
    $totalPriceVendor=0;
    $totalPrice=0;
    $totalRequestedServices=0;
    $RecurringFlag=0;
    ?>
    @foreach($order_details as $order_detail)
    @foreach($customData as $custom)
    @if(!isset($order_detail->requestedService->service->title))
    <?php continue?>
    @endif
    <div class="row-fluid">
        <div class="box span12" id="comehere">
            <div class="box-header" data-original-title>
                <h5>

                  <button data-toggle="modal" class="myBtnImg"  data-target="#edit_request_service" data-backdrop="static" >
                  <i class="halflings-icon edit myBtnImg" ></i>
                  Edit Service</button>

                <span class="break"></span>{!!$order_detail->requestedService->service->title!!}</h5>
                <div class="box-icon">
                    <?php
                    
                    if($order_detail->requestedService->recurring !== null && $order_detail->requestedService->recurring==1)
                    {
                        $RecurringFlag=1;
                    }

                    
                    $totalRequestedServices++;
                    if($order_detail->requestedService->quantity !== null && ($order_detail->requestedService->quantity=="" || $order_detail->requestedService->quantity==0))
                    {

                     if( Auth::user()->type_id==3) {
                      $SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                      ->where('customer_id','=',Auth::user()->id)
                      ->where('type_id','=',3)
                      ->get();

                      $vendor_priceFIND="";
                      
                      if(!empty($SpecialPriceVendor[0]))
                      {
                          if(isset($custom->vendors_price) && isset($custom->quantity)){ ?>

                          <?php $vendor_priceFIND+=$custom->vendors_price*$custom->quantity; ?>

                           Vendor Price:${!!$custom->vendors_price * $custom->quantity!!}

                          <?php }else{
                          $vendor_priceFIND=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;
                          ?>

                          Price : ${!!$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity!!}

                          <?php }


                      }else{

                       if ($custom->vendors_price !== null && $custom->quantity !== null) {?>

                        <?php  $vendor_priceFIND+=$custom->vendors_price*$custom->quantity; ?>


                        Vendor Price:${!!$custom->vendors_price*$custom->quantity!!}
                        <?php  }else{
                        $vendor_priceFIND=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;


                        ?>
                         Vendor Price: ${!!$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity!!}

                        <?php
                    }
                }
                ?>
 


                <?php
                $totalPrice+=$vendor_priceFIND;

                ?>
                <?php }else if( Auth::user()->type_id==2) {
                    $SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                    ->where('customer_id','=',Auth::user()->id)
                    ->where('type_id','=',2)
                    ->get();

                    $vendor_priceFIND="";
                    if(!empty($SpecialPriceVendor[0]))
                    {
                        if ($custom->vendors_price !== null && $custom->quantity !== null) {
                          $vendor_priceFIND=$custom->vendors_price * $custom->quantity;
                      ?>

                      Price : ${!!$custom->vendors_price * $custom->quantity!!}
                        <?php }else{
                           $vendor_priceFIND=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;
                      ?>

                      Price : ${!!$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity!!}
                         <?php } ?>


                      <?php


                  }else{
                    if ( $custom->vendors_price !== null && $custom->quantity !== null ) {
                      $vendor_priceFIND=$custom->vendors_price*$custom->quantity;
                     ?>
                     Price : ${!!$custom->vendors_price*$custom->quantity!!}
                   <?php }else{
                     $vendor_priceFIND=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;
                     ?>
                     Price : ${!!$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity!!}

                     <?php
                   }
                 }
                 ?>


                 <?php
                 $totalPrice+=$vendor_priceFIND;

                 ?>
                 <?php }else {


                   $SpecialPriceCustomer=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                   ->where('customer_id','=',$order->customer->id)
                   ->where('type_id','=',2)
                   ->get();
                   $customer_priceFIND="";
                   if(!empty($SpecialPriceCustomer[0]))
                   {
                    if (!empty($custom->customer_price)) {
                      $totalPriceCustomer+=$custom->customer_price * $custom->admin_quantity;?>
                      Customer Price:${!!$custom->customer_price * $custom->admin_quantity!!}
                      <?php }else{
                        $totalPriceCustomer+=$SpecialPriceCustomer[0]->special_price;
                        ?>
                        Customer Price:${!!$SpecialPriceCustomer[0]->special_price!!}
                        <?php
                    }
                }else {
                    if (!empty($custom->customer_price) && $custom->customer_price !== null) {
                     $totalPriceCustomer+=$custom->customer_price * $custom->admin_quantity;?>
                     Customer Price:${!!$custom->customer_price * $custom->admin_quantity!!}
                     <?php }else{
                      $totalPriceCustomer+=$order_detail->requestedService->service->customer_price;
                      ?>
                      Customer Price:${!!$order_detail->requestedService->service->customer_price!!}
                      <?php
                  }
              }

              $SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
              ->where('customer_id','=',$order->vendor->id)
              ->where('type_id','=',3)
              ->get();
              if(!empty($SpecialPriceVendor[0]))
              {
                if ($custom->vendors_price !== null && $custom->quantity !== null ) {
                 $totalPriceVendor+=$custom->vendors_price*$custom->quantity;
                 ?>
                 Vendor Price:${!!$custom->vendors_price*$custom->quantity!!}
                 <?php
             }else{
                $totalPriceVendor+=$SpecialPriceVendor[0]->special_price;
                ?>
                Vendor Price:${!!$SpecialPriceVendor[0]->special_price!!}
                <?php
            }
        }
        else{
            ?>
            <?php if ($custom->vendors_price !== null && $custom->quantity !== null  ) {
             $totalPriceVendor+=$custom->vendors_price*$custom->quantity;
             ?>
             Vendor Price:${!!$custom->vendors_price*$custom->quantity!!}
             <?php
         }else{ ?>
         Vendor Price:${!!$order_detail->requestedService->service->vendor_price!!}
         <?php
         $totalPriceVendor+=$order_detail->requestedService->service->vendor_price;
     }

 }
 ?>

 <?php exit; ?>

 <?php
}
}
else
{

 if( Auth::user()->type_id==3) {

  $SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
  ->where('customer_id','=',Auth::user()->id)
  ->where('type_id','=',3)
  ->get();

  $vendor_priceFIND="";
  if(!empty($SpecialPriceVendor[0]))
  {
    if (isset($custom->vendors_price)&&isset($custom->quantity)) {
       $vendor_priceFIND=$custom->vendors_price *$custom->quantity ;
      ?>

       Price : ${!!$custom->vendors_price * $custom->quantity!!}
  <?php   }else{
      $vendor_priceFIND=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;
      ?>

      Price : ${!!$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity!!}

      <?php

      }
  }else{
      if (isset($custom->vendors_price)&&isset($custom->quantity)) {
$vendor_priceFIND=$custom->vendors_price*$custom->quantity;
     ?>
     Price : ${!!$custom->vendors_price*$custom->quantity!!}
     <?php   }else{
     $vendor_priceFIND=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;
     ?>
     Price : ${!!$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity!!}

     <?php
     }
 }
 ?>


 <?php
 $totalPrice+=$vendor_priceFIND;

 ?>
 <?php }else if( Auth::user()->type_id==2) {

  $SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
  ->where('customer_id','=',Auth::user()->id)
  ->where('type_id','=',2)
  ->get();

  $vendor_priceFIND="";
  if(!empty($SpecialPriceVendor[0]))
  {
     if (isset($custom->vendors_price)&&isset($custom->quantity)) {
 $vendor_priceFIND=$custom->vendors_price*$custom->quantity;
      ?>

      Price : ${!!$custom->vendors_price*$custom->quantity!!}
<?php }else{  ?>


      $vendor_priceFIND=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;
      ?>

      Price : ${!!$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity!!}

      <?php
      }

  }else{
     if (isset($custom->vendors_price)&&isset($custom->quantity)) {
      $vendor_priceFIND=$custom->vendors_price*$custom->quantity;
      ?>

      Price : ${!!$custom->vendors_price*$custom->quantity!!}
<?php }else{  ?>

     $vendor_priceFIND=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;
     ?>
     Price : ${!!$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity!!}

     <?php
   }
 }
 ?>


 <?php
 $totalPrice+=$vendor_priceFIND;

 ?>
 <?php }
 else {

  $SpecialPriceCustomer=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
  ->where('customer_id','=',$order->customer->id)
  ->where('type_id','=',2)
  ->get();

  if(!empty($SpecialPriceCustomer[0]))
  { ?>
<?php if (!empty($custom->customer_price)&&!empty($custom->admin_quantity)){ ?>

<?php $totalPriceCustomer+=$custom->customer_price*$custom->admin_quantity;?>

Customer Price:${!!$custom->customer_price*$custom->admin_quantity!!}

<?php } else{ ?>
  <?php if (isset($custom->customer_price)&&isset($custom->quantity)){ ?>

<?php
    $totalPriceCustomer+=$custom->customer_price*$custom->admin_quantity;;

    ?>

    Customer Price:${!!$custom->customer_price*$custom->admin_quantity!!}


  <?php } else{ ?>
<?php
    $totalPriceCustomer+=$SpecialPriceCustomer[0]->special_price*$order_detail->requestedService->quantity;;

    ?>

    Customer Price:${!!$SpecialPriceCustomer[0]->special_price*$order_detail->requestedService->quantity!!}

    <?php
        }
     }
}else{ ?>
<?php if (isset($custom->customer_price)&&isset($custom->admin_quantity)  ){ ?>

<?php $totalPriceCustomer+=$custom->customer_price*$custom->admin_quantity;?>

Customer Price:${!!$custom->customer_price*$custom->admin_quantity!!}

<?php } else{

    $totalPriceCustomer+=$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity;;
    ?>
    Customer Price:${!!$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity!!}

    <?php
    }
}


$SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
->where('customer_id','=',$order->vendor->id)
->where('type_id','=',3)
->get();
if(!empty($SpecialPriceVendor[0]))
    { ?>
<?php if (isset($custom->vendors_price)||isset($custom->quantity)){?>

<?php $totalPriceVendor+=$custom->vendors_price*$custom->quantity;?>

Vendor Price:${!!$custom->vendors_price*$custom->quantity!!}

<?php }else{ ?>
<?php $totalPriceVendor+=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity;?>
Vendor Price:${!!$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity!!}
<?php } ?>


<?php
}else {
 if (isset($custom->vendors_price) && isset($custom->quantity)) { ?>

<?php  $totalPriceVendor+=$custom->vendors_price*$custom->quantity; ?>


Vendor Price:${!!$custom->vendors_price*$custom->quantity!!}
<?php  }else{ ?>
<?php $totalPriceVendor+=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity;?>
Vendor Price:${!!$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity!!}

<?php } ?>
<?php } ?>

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
     <div id="billing-note-empty-success" class="hide">
        <div class="alert alert-success">Saved Successful</h4>
                                                                </div>
                                                                 <div id="billing-note-empty-error" class="hide">
                                                                    <div class="alert alert-success">Billing Note Can not be Empty</h4>
                                                                </div>
    <table class="table">

        <?php
        if( Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {
            ?>               <tr>
            @if(isset($custom->customers_notes))
            <td colspan="2" class="center"><h5>Customer Note:</h5><span>{!!$custom->customers_notes!!}</span>   </td>
            @else
            <td colspan="2" class="center"><h5>Customer Note:</h5><span>{!!$order_detail->requestedService->customer_note!!}</span>   </td>
            @endif
        </tr>
        <tr>
           @if(isset($custom->notes_for_vendors))
           <td colspan="2" class="center"><h5>Note for Vendor:</h5><span>{!!$custom->notes_for_vendors!!}</span></td>

           @else
           <td colspan="2" class="center"><h5>Note for Vendor:</h5><span>{!!$order_detail->requestedService->public_notes!!}</span>

           </td>  @endif
       </tr>
       <tr>
        @if(isset($custom->vendors_notes))
        <td colspan="2" class="center"><h5>Vendor Note:</h5><span>{!!$custom->vendors_notes!!}</span></td>
        @else
        <td colspan="2" class="center"><h5>Vendor Note:</h5><span>{!!$order_detail->requestedService->vendor_note!!}</span>
        </td>  @endif
    </tr>
    <?php }elseif( Auth::user()->type_id == 3 ) {
     ?>


     <tr>
        <td colspan="2" class="center"><h5>Note for Vendor:</h5>

            @if(isset($custom->notes_for_vendors))
            <span>{!!$custom->notes_for_vendors!!}</span >
                @else
                <span>{!!$order_detail->requestedService->public_notes!!}</span >
                    @endif
                </tr>
                <?php
            }elseif( Auth::user()->type_id == 2 ) {
             ?>


             <?php }?>

             <tr>
                <td class="center" colspan="2">
                  <span class="pull-left">

                   <a href="#"  onclick="popModalExport({!!$order->id!!}, {!!$order_detail->id!!}, 'before')" > <button @if(Auth::user()->type_id==3
                    && $order->status==4) disabled="disabled"@endif  data-toggle="modal" data-backdrop="static" data-target="#export_view_images" id="exportImages" class="btn btn-large btn-warning pull-right myBtnImg" style=" margin: 0 15px 0 0; border-radius: 2px; font-size: 18px;"> Export Images
                   </button></a>
               </span>

               <span class="pull-left">
              <!--   <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" disabled="disabled" data-target="#before_{!!$order_detail->id!!}" class="myBtnImg btn btn-large btn-success">Upload Before Images</button> -->
                <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#before_view_image_{!!$order_detail->id!!}" onclick="popModal({!!$order->id!!}, {!!$order_detail->id!!}, 'before')" class="myBtnImg btn">View Before Images</button>
            </span>


            <span class="pull-during">
               <!--  <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static"  disabled="disabled" data-target="#during_{!!$order_detail->id!!}" class="myBtnImg btn btn-large btn-success">Upload During Images</button> -->
                <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#during_view_image_{!!$order_detail->id!!}" onclick="popModal({!!$order->id!!}, {!!$order_detail->id!!}, 'during')" class="myBtnImg btn">View During Images</button>
            </span>

            <span class="pull-right">
               <!--  <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif  data-toggle="modal" data-backdrop="static" disabled="disabled" data-target="#after_{!!$order_detail->id!!}" class="myBtnImg btn btn-large btn-success">Upload After Images</button> -->
                <button  @if(Auth::user()->type_id==3 && $order->status==4) disabled="disabled"@endif data-toggle="modal" data-backdrop="static" data-target="#after_view_image_{!!$order_detail->id!!}" onclick="popModal({!!$order->id!!}, {!!$order_detail->id!!}, 'after')" class="myBtnImg btn">View After Images</button>

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
                        <td colspan="2" class="center"><h5>Vendor Note:</h5>
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
                                        <td colspan="2" class="center"><h5>Customers Note:</h5>
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

                                                <?php } else if( Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ||Auth::user()->user_role_id == 5 ||Auth::user()->user_role_id == 6||Auth::user()->user_role_id == 8 ) {
                                                    ?>

                                                    <tr>
                                                        <td colspan="2" class="center"><h5>Admin Note:</h5>
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
                                                                <?php if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ) { ?>
                                                                <tr>


                                                        <td colspan="2" class="center"><h5>Billing Note:</h5>
                                                            @if($order->billing_note)
                                                                <span id="show-billing-note-{!!$order->id!!}">{!!$order->billing_note!!}<br>
                                                                  <button class="btn btn-primary" id="edit-billing-note-button-{!!$order->id!!}" onclick="editBillingNoteButton({!!$order->id!!})"> Edit Note </button>
                                                            </span >
                                                                <span class="hide" id="textarea-billing-note-{!!$order->id!!}">{!!Form::textarea('admin_note', $order->billing_note ,array('class'=>'span','id'=>'billing-note-'.$order->id))!!}
                                                                <button class="btn btn-large btn-warning pull-right " id="bill-btn" onclick="saveBillingNote({!!$order->id!!})">Save Billing Note</button></span>
                                                                </td>
                                                                @else
                                                                <span id="show-billing-note-{!!$order->id!!}"></span >
                                                                    <span id="textarea-billing-note-{!!$order->id!!}">{!!Form::textarea('admin_note','',array('class'=>'span','id'=>'billing-note-'.$order->id))!!}
                                                                    <button class="btn btn-large btn-warning pull-right" onclick="saveBillingNote({!!$order->id!!})">Save Billing Note</button></span></td>
                                                                    @endif
                                                                </tr>
                                                                <tr>
                                                                    <td class="center" colspan="2"><!-- <button class="btn btn-large btn-warning pull-right" onclick="saveBillingNote({!!$order->id!!})">Save Billing Note</button> --></td>

                                                                </tr>
                                                              <?php  } ?>

                                                                <tr><td><span>Service Details</span></td><td></td></tr>
                                                                @if($order_detail->requestedService->required_date!="")
                                                                <tr><td><span>Required Date</span></td>
                                                                    <td><span>{!! date('m/d/Y', strtotime($order_detail->requestedService->required_date)) !!}</span>

                                                                    </td>
                                                                </tr>
                                                                @endif

                                                                @if( $order_detail->requestedService->due_date!="")
                                                                <tr><td><span>Due Date</span></td>
                                                                    <td>
                                                                     <span> {!! date('m/d/Y', strtotime($order_detail->requestedService->due_date)) !!}</span>
                                                                  </td>
                                                              </tr>
                                                              @endif

                                                              @if($order_detail->requestedService->quantity!="")
                                                              <tr><td><span>Quantity</span></td>
                                                                  <td>
                                                                    <span id="show-vendor-qty">{!! $order_detail->requestedService->quantity !!}</span>
                                                                </td>
                                                            </tr>
                                                            @endif



                                                            @if($order_detail->requestedService->service_men!="")
                                                            <tr><td><span>Service men</span></td>
                                                                <td><span>{!!$order_detail->requestedService->service_men !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif
                                                            @if($order_detail->requestedService->service_note!="")
                                                            <tr><td><span>Service note</span></td>
                                                                <td><span>{!!$order_detail->requestedService->service_note !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif

                                                            @if($order_detail->requestedService->verified_vacancy!="")
                                                            <tr><td><span>Verified vacancy</span></td>
                                                                <td><span>{!!$order_detail->requestedService->verified_vacancy !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif
                                                            @if($order_detail->requestedService->cash_for_keys!="")
                                                            <tr><td><span>Cash for keys</span></td>
                                                                <td><span>{!!$order_detail->requestedService->cash_for_keys !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif

                                                            @if($order_detail->requestedService->cash_for_keys_trash_out!="")
                                                            <tr><td><span>Cash for keys Trash Out</span></td>
                                                                <td><span>{!!$order_detail->requestedService->cash_for_keys_trash_out !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif

                                                            @if($order_detail->requestedService->trash_size!="")
                                                            <tr><td><span>trash size</span></td>
                                                                <td><span>{!!$order_detail->requestedService->trash_size !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif


                                                            @if($order_detail->requestedService->storage_shed!="")
                                                            <tr><td><span>storage shed</span></td>
                                                                <td><span>{!!$order_detail->requestedService->storage_shed !!}</span></td>
                                                            </tr>
                                                            @endif


                                                            @if($order_detail->requestedService->lot_size!="")
                                                            <tr><td><span>lot size</span></td>
                                                                <td><span>{!!$order_detail->requestedService->lot_size !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif

                                                            @if($order_detail->requestedService->set_prinkler_system_type!="")
                                                            <tr><td><span>set prinkler system type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->set_prinkler_system_type !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif


                                                            @if($order_detail->requestedService->install_temporary_system_type!="")
                                                            <tr><td><span>install temporary system type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->install_temporary_system_type !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif



                                                            @if($order_detail->requestedService->pool_service_type!="")
                                                            <tr><td><span>pool service type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->pool_service_type !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif


                                                            @if($order_detail->requestedService->carpet_service_type!="")
                                                            <tr><td><span>carpet service type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->carpet_service_type !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif

                                                            @if($order_detail->requestedService->boarding_type!="")
                                                            <tr><td><span>boarding type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->boarding_type !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif



                                                            @if($order_detail->requestedService->spruce_up_type!="")
                                                            <tr><td><span>spruce up type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->spruce_up_type !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif



                                                            @if($order_detail->requestedService->constable_information_type!="")
                                                            <tr><td><span>constable information type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->constable_information_type !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif


                                                            @if($order_detail->requestedService->remove_carpe_type!="")
                                                            <tr><td><span>remove carpe type<span></td>
                                                                <td><span>{!!$order_detail->requestedService->remove_carpe_type!!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif


                                                            @if($order_detail->requestedService->remove_blinds_type!="")
                                                            <tr><td><span>remove blinds type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->remove_blinds_type !!}</span>
                                                                </td>
                                                            </tr>
                                                            @endif

                                                            @if($order_detail->requestedService->remove_appliances_type!="")
                                                            <tr><td><span>remove appliances type</span></td>
                                                                <td><span>{!!$order_detail->requestedService->remove_appliances_type !!}</span>

                                                                </td>
                                                            </tr>
                                                            @endif



                                                        </table>
                                                    </div>
                                                </div><!--/span-->

                                            </div><!--/row-->
                                            <!-- Edit Service request pop modal Starts
                                              1 = admin
                                              2 = customers
                                              3 = vendors -->
                                            <div role="dialog" class="modal modelForm "  id="edit_request_service">

                                            <div class="imageviewPop">
                                                <div class="well text-center"><h1>Edit Requested Service </h1></div>

                                                <div class="row-fluid">
                                                  <div class="alert alert-success" id="flash_modal" style="display: none;">Added Successfully</h4>
                          <div class="alert alert-danger" id="additional_flash_danger" style="display: none;">
                            Please Fill All the Fields!
                          </h4>
                                                <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==4){ ?>
<?php if (isset($custom->customer_price)) {
if ( $custom->customer_price != 0){ ?>
<!-- <span><?php echo $totalPriceCustomer; ?></span> -->
                                                    {!!Form::label('customer_price', 'Customer Price')!!}

                                                    {!!Form::number('customer_price',$custom->customer_price,array('id'=>'customer_price','onkeypress'=>'return isNumberKey(event)','required'))!!}
                                                  <?php }else{ ?>
                                                    {!!Form::label('customer_price', 'Customer Price')!!}

                                                    {!!Form::number('customer_price',$totalPriceCustomer,array('id'=>'customer_price','onkeypress'=>'return isNumberKey(event)','required'))!!}
                          <?php
                        }
                        }else{ ?>
                                                    {!!Form::label('customer_price', 'Customer Price')!!}

                                                    {!!Form::number('customer_price',$totalPriceCustomer,array('id'=>'customer_price','onkeypress'=>'return isNumberKey(event)','required'))!!}
                          <?php
                        }
                          ?>
                                                    <?php if (isset($custom->customers_notes)  ){ ?>
                                                    {!!Form::label('customers_notes', 'Customers Notes')!!}

                                                 {!!Form::textarea('customers_notes',$custom->customers_notes ,array('id' => 'customers_notes' ) )!!}
                                                    <?php }else{ ?>
                                                    {!!Form::label('customers_notes', 'Customers Notes')!!}

                                                 {!!Form::textarea('customers_notes', $order_detail->requestedService->customer_note ,array('id' => 'customers_notes') )!!}
                                                    <?php } ?>


                                                <?php } ?>
                                                 <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==4){ ?>
                                                <?php if (!empty($custom->admin_quantity)  ){ ?>

                                                         {!!Form::label('admin_quantity', 'Admin Quantity')!!}

                                                          {!!Form::number('admin_quantity',$custom->admin_quantity,array('id' => 'adminquantity_edit' ,'onkeypress'=>'return isNumberKey(event)'))!!}
                                                  <?php }else{?>
                                                          {!!Form::label('admin_quantity', 'Admin Quantity')!!}

                                                          {!!Form::number('admin_quantity','',array('id' => 'adminquantity_edit' ,'onkeypress'=>'return isNumberKey(event)'))!!}
                                                <?php } ?>
                                                  <?php } ?>
                                                <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==4 || $order_detail->requestedService->service->vendor_edit == 1){ ?>

                          <?php if (isset($custom->quantity)  ){ ?>
                          {!!Form::label('quantity', 'Vendor Quantity')!!}

                                                 {!!Form::number('quantity',$custom->quantity,array('id' => 'quantity_edit' ,'onkeypress'=>'return isNumberKey(event)'))!!}
                          <?php }else{ ?>
                         {!!Form::label('quantity', 'Vendor Quantity')!!}

                                                 {!!Form::number('quantity','' ,array('id' => 'quantity_edit','onkeypress'=>'return isNumberKey(event)','required'))!!}
                            <?php } ?>
                                                  <?php if (isset($custom->vendors_price)  ){ ?>
                                                     {!!Form::label('vendor_price', 'Vendor Price')!!}

                                                 {!!Form::number('vendor_price',$custom->vendors_price,array('id' => 'vendor_price','onkeypress'=>'return isNumberKey(event)','required'))!!}

                                                  <?php }else{?>
                                                 {!!Form::label('vendor_price', 'Vendor Price')!!}

                                                 {!!Form::number('vendor_price',$totalPriceVendor,array('id' => 'vendor_price','onkeypress'=>'return isNumberKey(event)','required'))!!}
                                                 <?php } ?>

                                                 <?php } ?>
                                                <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==4){
                                                 ?>
                                                  <?php if (isset($custom->vendors_notes) ){ ?>

                                                  {!!Form::label('vendor_note', 'Vendors Notes')!!}

                                                 {!!Form::textarea('vendor_note', $custom->vendors_notes ,array('id' => 'vendors_note') )!!}

                                                  <?php }else{ ?>

                                                  {!!Form::label('vendor_note', 'Vendors Notes')!!}

                                                 {!!Form::textarea('vendor_note', $order_detail->requestedService->vendor_note ,array('id' => 'vendors_note') )!!}
                                                  <?php } ?>
                                                     <?php if (isset($custom->notes_for_vendors)){ ?>
                                                 {!!Form::label('notes_for_vendors', 'Notes For Vendors')!!}

                                                 {!!Form::textarea('notes_for_vendors',$custom->notes_for_vendors,array('id' => 'notes_for_vendors') )!!}

                                                 <?php }else{?>

                                                 {!!Form::label('notes_for_vendors', 'Notes For Vendors')!!}

                                                 {!!Form::textarea('notes_for_vendors',$order_detail->requestedService->public_notes,array('id' => 'notes_for_vendors') )!!}

                                                 <?php }?>
                                                 <?php }?>




                                             </div>


                                             <div class="row-fluid">
                                                <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

                                                <button  style="margin:40px 0 25px 0;" onclick="updateRequestedService({!!$order->id!!})" class="btn btn-large btn-success">Update </button>

                                            </div>
                                               </div>

                                        </div>
                                        <!-- Edit Service request pop modal Ends -->

                                        @if(isset($items))
                                    <!--     <span><h1 class="text-center">Additional Services</h1></span> -->
                                        @endif

                                        <div class=" " id="additionalserviceform">
                                           <div class="row-fluid">
                                            <div class="box span12">
                                                <?php
                                                $total_rate = array();
                                                $vendor_additional_totaled_price = '';
                                                $totalPriceCustomerFinal = "";
                                               //$totalPriceCustomerFinal += $totalPriceCustomer;
                                                ?>


                                                @foreach($items as $item)
                                                <?php
                                                $total_rate[] = $item->rate * $item->quantity;
                                                $vendor_additional_totaled_price = $item->rate * $item->quantity;
                                                ?>

                                                @if(isset($item->customer_price))

                                                <?php $totalPriceCustomerFinal += $item->customer_price * $item->admin_quantity; ?>
                                                @else

                                                <?php $totalPriceCustomerFinal += $totalPriceCustomer; ?>
                                                @endif


                                                <div class="box-header" data-original-title="">
                                                    <h5>
                                                    <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==3 || Auth::user()->type_id==4){ ?>
                                                      <button data-toggle="modal" data-target="#edit_additional_item_{!!$item->id!!}" data-backdrop="static" ><i class="halflings-icon edit" ></i> Edit Service</button>
                                                    <?php }else{ ?><i class="halflings-icon edit" ></i> <?php } ?><span class="break"></span>{!!$item->title!!}</h5>
                                                    <div class="box-icon">
                                                      <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==2){ ?>
                                                        @if(isset($item->customer_price))

                                                        Customer Price:${!!$item->customer_price * $item->admin_quantity!!}

                                                        @else

                                                        Customer Price:${!!$totalPriceCustomer!!}

                                                        @endif
                                                        <?php } if ( Auth::user()->type_id==3) { ?>

                                                        Price:$<span id="vendor_price">{!!$vendor_additional_totaled_price!!}</span>
                                                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                                                         <?php }else{ ?>

                                                         Vendor Price:$<span id="vendor_price">{!!$vendor_additional_totaled_price!!}</span>
                                                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>

                                                          <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="box-content">

                                                    <table class="table">
                                                     <tbody>

                                                         <tr><th>Description</th>
                                                          <td style="width: 200px;">
                                                            <textarea readonly="readonly">{!! $item->description !!}</textarea>
                                                        </td>
                                                    </tr>

                                                    @if(isset($item->quantity ))
                                                    <tr><th>Quantity</th>
                                                      <td style="width: 200px;">
                                                        <span >{!! $item->quantity !!}</span>
                                                    </td>
                                                </tr>
                                                @endif
                                                @if(Auth::user()->type_id == 3  )

                                                <tr>
                                                 <!--  <div id="vendor-additional-note-empty-error-{!!$item->id!!}" class="hide">
                                                    <div class="alert alert-error">Vendor Note Can not be Empty</h4>
                                                  </div>
                                                  <div id="vendor-additional-note-empty-success-{!!$item->id!!}" class="hide">
                                                    <div class="alert alert-success">Saved Successful</h4>
                                                  </div> -->
                                                <!--   <td colspan="2" class="center"><h5>Vendor Note:</h5>
                                                    @if($item->additional_vendors_notes != "")
                                                    <span id="show-additional-vendor-note-{!!$item->id!!}">{!!$item->additional_vendors_notes!!}<br><button class="btn btn-primary" id="edit-additional-vendor-note-button-{!!$item->id!!}" onclick="editAdditionalVendorNoteButton({!!$item->id!!})"> Edit Note </button> </span >
                                                      <span style="display:none;" id="textarea-additional-vendor-note-{!!$item->id!!}">{!!Form::textarea('vendor_note', $item->additional_vendors_notes ,array('class'=>'span','id'=>'vendor-additional-note-'.$item->id))!!}</span></td> -->
                                                    @else
                                                   <!--    <span id="show-vendor-note-{!!$item->id!!}"></span >
                                                        <span id="textarea-additional-vendor-note-{!!$item->id!!}">{!!Form::textarea('additional_vendor_note','',array('class'=>'span','id'=>'vendor-additional-note-'.$item->id))!!}</span></td> -->

                                                  </tr>
                                                  @endif

                                                  <tr>
                                                  <!--   <td class="center" colspan="2"  id="save-additional-vendor-notes-button-{!!$item->id!!}" style="display:none;"><button   class="btn btn-large btn-warning pull-right"
                                                     onclick="saveAdditionalVendorNote({!!$item->id!!})">Save {!!$item->title!!}</button>
                                                   </td>
 -->
                                                 </tr>

                                                  @else
                                                  <tr>
                                                   <!--  <td colspan="2" class="center"><h5>Vendor Note:</h5>{!!$item->additional_vendors_notes!!}</td>  -->
                                                  </tr>
                                                  @endif

                                                <tr>
                                                    <td class="center" colspan="2">
                                                        <a href="#"  onclick="popModalAdditionalItemExport({!!$item->id!!}, 'before')" > <button   data-toggle="modal" data-backdrop="static" data-target="#export_additional_view_images_{!!$item->id!!}" class="btn btn-large btn-warning pull-right"style=" margin: 0 15px 0 0; border-radius: 2px; font-size: 18px;"> Export Images
                                                        </button></a>
                                                    </td>
                                                    <td class="center" colspan="2">

                                                        <span class="pull-left">
                                                            <button data-toggle="modal" data-backdrop="static" data-target="#before_{!!$item->id!!}" class="btn btn-large btn-success myBtnImg">Upload Before Images</button>
                                                            <button data-toggle="modal" data-backdrop="static" data-target="#before_view_image_{!!$item->id!!}" onclick="popModalAdditionalItem({!!$item->id!!}, 'before')" class="btn myBtnImg">View Before Images</button>
                                                        </span>



                                                    </td>
                                                    <td class="center" colspan="2">

                                                        <span class="pull-left">
                                                            <button data-toggle="modal" data-backdrop="static" data-target="#during_{!!$item->id!!}" class="btn btn-large btn-success myBtnImg">Upload During Images</button>
                                                            <button data-toggle="modal" data-backdrop="static" data-target="#during_view_image_{!!$item->id!!}" onclick="popModalAdditionalItem({!!$item->id!!}, 'during')" class="btn myBtnImg">View During Images</button>
                                                        </span>



                                                    </td>
                                                    <td class="center" colspan="2">

                                                        <span class="pull-left">
                                                            <button data-toggle="modal" data-backdrop="static" data-target="#after_{!!$item->id!!}" class="btn btn-large btn-success myBtnImg">Upload After Images</button>
                                                            <button data-toggle="modal" data-backdrop="static" data-target="#after_view_image_{!!$item->id!!}" onclick="popModalAdditionalItem({!!$item->id!!}, 'after')" class="btn myBtnImg">View After Images</button>
                                                        </span>



                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <!--/   Modal-Section Add Before Images Start   -->
                                    <div class="modal modelForm"  id="before_{!!$item->id!!}">
                                    <div class="imageviewPop">
                                        <div class="row-fluid dragImage">
                                            {!! Form::open(array('url' => 'add-additional-before-images', 'class'=>'dropzone', 'id'=>'form-additional-before-'.$item->id)) !!}
                                            {!! Form::hidden('additional_service_id', $item->id,array("id"=>"order_id_for_change"))!!}
                                            {!! Form::hidden('type', 'before')!!}
                                            <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>
                                            {!! Form::close() !!}
                                        </div>
<!--        <div class="row-fluid">

</div>-->
</div>
</div>
<!--/   Modal-Section Add Before Images End   -->

<!--/   Modal-Section Show Before Images Start   -->
<div role="dialog" class="modal modelForm"  id="before_view_image_{!!$item->id!!}">
<div class="imageviewPop">
    <div class="well text-center"><h1>View Before Image</h1></div>
    <div class="row-fluid" id="before_view_modal_image_{!!$item->id!!}">
    </div>
    <div class="row-fluid">
    asdasd
        <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

</div>
    </div>
</div>
<!--/   Modal-Section Show Before Images End   -->
<!--/   Modal-Section Add During Images Start   -->
<div class="modal modelForm"  id="during_{!!$item->id!!}">
<div class="imageviewPop">
    <div class="row-fluid dragImage">
        {!! Form::open(array('url' => 'add-additional-during-images', 'class'=>'dropzone', 'id'=>'form-during-'.$item->id)) !!}
        {!! Form::hidden('additional_service_id', $item->id,array("id"=>"order_id_for_change"))!!}
        {!! Form::hidden('type', 'during')!!}
        <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>

        {!! Form::close() !!}
    </div>
<!--        <div class="row-fluid">

</div>-->
</div>
</div>
<!--/   Modal-Section Add During Images End   -->

<!--/   Modal-Section Show During Images Start   -->
<div role="dialog" class="modal modelForm"  id="during_view_image_{!!$item->id!!}">
<div class="imageviewPop">
    <div class="well text-center"><h1>View During Image</h1></div>
    <div class="row-fluid" id="during_view_modal_image_{!!$item->id!!}">
    </div>
    <div class="row-fluid">
        <button data-dismiss="modal" style="margin: 40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

    </div>
    </div>
</div>
<!--/   Modal-Section Show Before Images End   -->

<!--/   Modal-Section Add After Images Start   -->
<div class="modal modelForm"  id="after_{!!$item->id!!}">
<div class="imageviewPop">
    <div class="row-fluid dragImage">
        {!! Form::open(array('url' => 'add-additional-after-images', 'class'=>'dropzone')) !!}
        {!! Form::hidden('additional_service_id', $item->id)!!}
        {!! Form::hidden('type', 'after')!!}
        <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>
        {!! Form::close() !!}
    </div>
    </div>
</div>
<!--/   Modal-Section Add After Images End   -->
<!--/   Modal-Section Show After Images Start   -->
<div class="modal modelForm"  id="after_view_image_{!!$item->id!!}">
<div class="imageviewPop">
    <div class="well text-center"><h1>View After Image</h1></div>
    <div class="row-fluid" id="after_view_modal_image_{!!$item->id!!}">
    </div>
    <div class="row-fluid">
        <button data-dismiss="modal" style="margin: 40px 0 25px 0;" class="btn btn-large btn-success">Close</button>
    </div>
</div>
</div>
<!--/   Modal-Section Show After Images End   -->
<!--/   Modal-Section Show Export Images Start   -->
<div role="dialog" class="modal modelForm"  id="export_additional_view_images_{!!$item->id!!}">
<div class="imageviewPop">
    <div class="well text-center"><h1>Export Image</h1></div>
    <div class="row-fluid" id="export_modal_additional_image_{!!$item->id!!}">
    </div>
    <div class="row-fluid">
        <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

        <button  style="margin:40px 0 25px 0;" id="save_value" class="btn btn-large btn-success" onclick="ExportAdditonalitempdf('#export_additional_view_images_{!!$item->id!!}')">Export To PDF</button>
    </div>
    </div>
</div>
<!--/   Modal-Section Show Export Images End   -->
<div role="dialog" class="modal modelForm"  id="edit_additional_item_{!!$item->id!!}">
<div class="imageviewPop">
    <div class="well text-center"><h1>Edit Additional Service</h1></div>
    <div class="alert alert-danger" id="additional_flash_modal_dan" style="display: none;">ERROR! Please check all the fields</h4>
    <div class="alert alert-success" id="additional_flash_modal" style="display: none;">Updated Successfully</h4>
    <div class="row-fluid">

        {!!Form::label('description', 'Description')!!}
        {!!Form::textarea('description_'.$item->id,$item->description,array('id' => 'description_'.$item->id) )!!}
         <?php if (Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {?>
              {!!Form::label('admin_quantity', 'Admin Quantity')!!}
              {!!Form::number('admin_quantity_'.$item->id,$item->admin_quantity ,array('id' => 'admin_quantity_'.$item->id,'onkeypress'=>'return isNumberKey(event)'))!!}

                    <?php } ?>
        {!!Form::label('quantity', 'Quantity')!!}
        {!!Form::number('quantity_'.$item->id,$item->quantity ,array('id' => 'quantity_'.$item->id,'onkeypress'=>'return isNumberKey(event)'))!!}
        <?php if (Auth::user()->type_id == 1 || Auth::user()->type_id == 4){ ?>
             {!!Form::label('rate', 'Vendor Price')!!}
        {!!Form::number('rate_'.$item->id,$item->rate,array('id' => 'rate_'.$item->id,'onkeypress'=>'return isNumberKey(event)'))!!}

        <?php }else{ ?>
      {!!Form::label('rate', 'Price')!!}
        {!!Form::number('rate_'.$item->id,$item->rate,array('id' => 'rate_'.$item->id,'onkeypress'=>'return isNumberKey(event)'))!!}

        <?php } ?>
        @if(Auth::user()->type_id == 1 || Auth::user()->type_id == 4)
        {!!Form::label('customer_rate', 'Customers Price')!!}
        @if(isset($item->customer_price))
        {!!Form::number('customer_price',$item->customer_price,array('id' => 'customer_price_'.$item->id,'onkeypress'=>'return isNumberKey(event)'))!!}
        @else
        {!!Form::number('customer_price',$totalPriceCustomer,array('id' => 'customer_price_'.$item->id,'onkeypress'=>'return isNumberKey(event)'))!!}
        @endif
        @endif

    </div>


    <div class="row-fluid">
        <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

        <button  style="margin:40px 0 25px 0;" onclick="updateAdditionalitem({!!$item->id!!})" class="btn btn-large btn-success">Update</button>

    </div>

</div>
</div>
@endforeach

<?php

$total =  array_sum($total_rate);

?>
</div><!--/span-->

</div>
<div class="alert alert-success" id="additional_flash" style="display: none;">Added Successfully</h4>
<div class="alert alert-danger" id="additional_flash_dan" style="display: none;">
  Please Fill All the Fields!
</h4>
<?php if (isset($order->maintenanceRequest->asset->id) && isset($order->maintenanceRequest->jobType->id)): ?>
<!--     <button disabled="disabled" class="btn btn-large btn-primary pull-left" onclick="ShowAdditionalServiceForm({!!$order_detail->requestedService->service->id!!},{!!$order->maintenanceRequest->asset->asset_number!!},{!!$order->maintenanceRequest->asset->id!!},{!!$order->maintenanceRequest->jobType->id!!})" >Add Additional Items</button> -->

<?php else: ?>
    <!-- <button class="btn btn-large btn-primary pull-left"  disabled="disabled" >U cant add additional service without property address</button> -->

<?php endif ?>

<!-- <button class="btn btn-large btn-warning pull-left" id="showform">Add Additional Items</button>
 --><div class="clearfix"></div>
<br>
<div class="span"  id="getpopup">
</div>
<div class="popModal">
<div class="span" id="additionalServiceRequeste" style="display:none;">
<h4><strong>Select Service:</strong></h4>
    <div id="tabCntrl3" class="control-group">
      {!! Form::open(array('url' => 'create-additional-service-request', 'class'=>'form-horizontal request-services')) !!}

                                <div class="tabArea">
                                    <a href="javascript:;" style="display:none;"class="btn btn-success nxtStep" data-src="#tabCntrl4">Next Step</a>
                                  <div class="controls cmboBox" id="servicename">

                                        <select class="span7 typeahead" data-rel="chosen" style="width: 200px;" id="title_test">
                                        <option> Select Service </option>
                                        <?php
                                        foreach ($allservices as $service) { ?>
                                        <option value="<?php echo $service->id;  ?>"><?php echo $service->title;  ?></option>
                                        <?php
                                        }
                                        ?>
                                        <option>Others</option>
                                        </select>
                                     </div>
                                        <!-- <a class="btn btn-small btn-success" style="cursor: pointer;" id="viewassets">Property Details</a>
                                    -->

                       <div id="bidrequest" class="bidrequest">

                          <div class="row-fluid">
                            <div class="span6 offset3 centered">
                              <div class="control-group" style="display:none;"> {!! Form::label('typeahead', 'Service Code: *', array('class' => 'control-label')) !!}
                                <div class="controls">
                                  <div class="input-append"> {!! Form::text('service_code', '', array('class' => 'input-xlarge focused',
                                    'id' => 'service_code')) !!} </div>
                                </div>
                              </div>

                              <div class="control-group" > {!! Form::label('typeahead', 'Title: *', array('class' => 'control-label')) !!}
                                <div class="controls">
                                  <div class="input-append"> {!! Form::text('title', '', array('class' => 'input-xlarge focused',
                                    'id' => 'title')) !!} </div>
                                </div>
                              </div>

                            </div>
                          </div>
                          <div class="form-actions">
                            <div class="pull-right">

                              {!! Form::hidden('bid_flag', 0,array('id'=>'bid_flag')) !!}
                              {!! Form::hidden('service_ids_selected', $order_detail->requestedService->service->id,array('id'=>'service_ids_selected')) !!}
                              {!! Form::hidden('vendorId', $order->vendor_id,array('id'=>'vendorID')) !!}
                               {!! Form::hidden('service_ids',$order_detail->requestedService->service->id,array('id'=>'service_ids')) !!}
                              {!! Form::hidden('asset_number',$order->maintenanceRequest->asset->id,array('id'=>'asset_number')) !!}
                              <?php if (isset($order->maintenanceRequest->jobType->id)): ?>
                                   {!! Form::hidden('job_type',$order->maintenanceRequest->jobType->id,array('id'=>'job_type')) !!}
                               <?php endif ?>

                                {!! Form::hidden('submitted', 1,array('id'=>'submitted')) !!}
                              {!! Form::submit('Add Service', array('class' => 'btn-success btn-addbid')) !!}
                              {!! Form::button('Cancel',array('class' => 'btn btn-large btn-info',
                                     'onClick' =>'location.href="'.URL::to('list-services').'"')) !!}
                            </div>
                          </div>

                    </div>

            </div>
    <style type="text/css">
        #prprTable { display:none; }
        #prprTable table { width:100%; margin:0 0 30px; }
        #prprTable table tr td,
        #prprTable table tr th { text-align: left; padding:10px 20px; border:3px solid #0088CC; background: #D5F1FF; font-size: 14px; color:#000; width: 50%; }
        #prprTable table tr th { color: #fff; background: #08C; }
        #prprTable .edtBtn, .bnchBtn .rgstrBtn { display:inline-block; min-width: 100px; padding:10px 20px; text-align: center; font-size: 15px; border-radius:5px; }
        #prprTable .sbmtBtn { display:inline-block; min-width: 100px; padding:10px 20px; text-align: center; font-size: 15px; border-radius:5px; }
        #prprTable .cnclBtn { display:inline-block; min-width: 100px; padding:10px 20px; text-align: center; font-size: 15px; border-radius:5px; }
        #prprTable table tr .alignCntr { text-align:center; }
        .bnchBtn { margin:20px 0; text-align: center; }
        .bnchBtn a.rgstrBtn { min-width: 200px; padding: 15px 20px; }
    </style>
    <div class="bnchBtn">
        <a href="javascript:;" class="rgstrBtn btn-primary">Review Order</a>
    </div>
    <div id="prprTable">
        <table>
            <tbody>
                <tr>
                    <th>Property</th>
                    <th id="review_order_property">{!!$order->maintenanceRequest->asset->address!!}</th>
                </tr>
                <tr>
                    <td>Job Type </td>
                    <?php if (isset($order->maintenanceRequest->jobType->title)): ?>
                       <td id="review_order_job_type">{!!$order->maintenanceRequest->jobType->title!!} </td>
                    <?php else: ?>
                        <td id="review_order_job_type"> </td>
                    <?php endif ?>

                </tr>
            </tbody>
        </table>

        <div id="order_review_serviceType">



        </div>

        <table>
            <tbody>

                <tr>
                    <td colspan="2" class="alignCntr">
                        <!-- <a href="#" class="edtBtn btn-primary">Edit</a> -->
                        <a href="#" class="sbmtBtn btn-warning"  onclick="orderReivewsubmit()">Submit</a>

                                 <a href="#" class="cnclBtn btn-success" id="cancelAdditionalbuttoncustomer">Cancel</a>





                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <fieldset>
                                        <div class=" hide row-fluid sortable requestServices" id='list_of_services'></div>
                                    </fieldset>
    <div id='allservices'></div>
            <div class="modal  modelForm larg-model"  id="showServiceid"> </div>
    {!!Form::close()!!}
                            </div>
</div>
</div>
<div class="span" id="additionalserviceitems" style="display:none;">
   <form>
       <div class="additionalserviceitemsInner">{!!Form::label('Title', 'Title *:')!!}
        <div id="serviceTitle" style="display: none;">
           {!!Form::text("service_title","",array('id'=>'title_test','placeholder'=>'Type Your New Service Name Here'))!!}
           </div>
            <div class="controls cmboBox" id="servicename">

                                        <select class="span7 typeahead" data-rel="chosen" style="width: 200px;" id="title_test">
                                        <option> Select Service </option>
                                        <?php
                                        foreach ($allservices as $service) { ?>
                                        <option value="<?php echo $service->id;  ?>"><?php echo $service->title;  ?></option>
                                        <?php
                                        }
                                        ?>
                                        <option>Others</option>
                                        </select>

                                        <!-- <a class="btn btn-small btn-success" style="cursor: pointer;" id="viewassets">Property Details</a>
                                    -->
           </div>


           </div>


          <!--  <datalist id="servicename">
            @foreach($allservices as $service)
            <option value='{!!$service->title!!}'>
               @endforeach
           </datalist> -->
           <div class="additionalserviceitemsInner">{!!Form::label('description', 'Description:')!!}
               {!!Form::textarea('description','',array('id'=> 'desc', 'placeholder' => 'Please add the description here...'))!!}</div>
               <?php if(Auth::user()->type_id == 3){ ?>
               <!-- <div class="additionalserviceitemsInner">{!!Form::label('v_notes', 'Vendor Notes:')!!}
               {!!Form::textarea('v_notes','',array('id'=> 'v_notes'))!!}</div> -->
               <?php } ?>
                <?php if (Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {?>
               <div class="additionalserviceitemsInner">{!!Form::label('adminqty', 'Admin Quantity*:')!!}
            <!--    <input name="numberField" type="text" onkeypress="return isNumbrKey(event)" /> -->
                   {!!Form::number('admin-qty','',array('id'=> 'admin_qty','required','onkeypress'=>'return isNumberKey(event)'))!!}</div>
                    <?php } ?>
               <div class="additionalserviceitemsInner">{!!Form::label('qty', 'Quantity*:')!!}
            <!--    <input name="numberField" type="text" onkeypress="return isNumberKey(event)" /> -->
                   {!!Form::number('qty','',array('id'=> 'qty','required','onkeypress'=>'return isNumberKey(event)'))!!}</div>
                   <?php if (Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {?>
                   <div class="additionalserviceitemsInner">{!!Form::label('customer_rate', 'Customer Price *:')!!}
                   {!!Form::number('customer_rate','',array('id'=> 'customer_additonal_price','required','onkeypress'=>'return isNumberKey(event)'))!!}</div>
                 <?php  } ?>
                 <div class="hide">{!!Form::label('customer_rate', 'Customer Price *:')!!}
                   {!!Form::number('customer_rate','',array('id'=> 'customer_additonal_price','required','onkeypress'=>'return isNumberKey(event)'))!!}</div>
                  <?php if (Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {?>
                   <div class="additionalserviceitemsInner">{!!Form::label('rate', 'Vendor Price *:')!!}
                   {!!Form::number('rate','',array('id'=> 'rate','required','onkeypress'=>'return isNumberKey(event)'))!!}</div>
                     <?php  }else{ ?>
                       <div class="additionalserviceitemsInner">{!!Form::label('rate', 'Price *:')!!}
                   {!!Form::number('rate','',array('id'=> 'rate','required','onkeypress'=>'return isNumberKey(event)'))!!}</div>
                       <?php  } ?>
                   <div class="additionalserviceitemsInner">
                   <button class="btn submit"
                    onclick="SubmitAdditionalItem(<?php echo Auth::user()->type_id; ?>)" type="button">Submit</button></div>
               </form>
           </div>
       </div>
       <style type="text/css">

        #additionalserviceitems, #additionalserviceitems form{margin: 0;}
        #additionalserviceitems .additionalserviceitemsInner{display: inline-block; vertical-align: middle; padding: 0 30px 0 10px;}
        #additionalserviceitems .additionalserviceitemsInner label{padding: 0; font-weight: 600;}
        #additionalserviceitems .additionalserviceitemsInner input{padding: 4px 25px;}
        #additionalserviceitems .additionalserviceitemsInner button{margin: 14px 0 0 0; padding: 4px 20px;}
    </style>

    <!--/   Modal-Section Start   -->
    <!--/   Modal-Section Add Before Images Start   -->
    <div class="modal modelForm"  id="before_{!!$order_detail->id!!}">
    <div class="imageviewPop">
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
</div>
<!--/   Modal-Section Add Before Images End   -->


<!--/   Modal-Section Start   -->
<!--/   Modal-Section Add Before Images Start   -->
<div class="modal modelForm"  id="during_{!!$order_detail->id!!}">
<div class="imageviewPop">
    <div class="row-fluid dragImage">
        {!! Form::open(array('url' => 'add-during-images', 'class'=>'dropzone', 'id'=>'form-during-'.$order_detail->id)) !!}
        {!! Form::hidden('order_id', $order->id,array("id"=>"order_id_for_change"))!!}

        {!! Form::hidden('order_details_id', $order_detail->id)!!}
        {!! Form::hidden('type', 'during')!!}
        <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>
        {!! Form::close() !!}
    </div>
<!--        <div class="row-fluid">

</div>-->
</div>
</div>


{!! Form::hidden('order_id', $order->id,array("id"=>"order_id_custom"))!!}








<!--/   Modal-Section Add After Images Start   -->
<div class="modal modelForm"  id="after_{!!$order_detail->id!!}">
<div class="imageviewPop">
    <div class="row-fluid dragImage">
        {!! Form::open(array('url' => 'add-after-images', 'class'=>'dropzone', 'id'=>'form-after-'.$order_detail->id)) !!}
        {!! Form::hidden('order_id', $order->id)!!}
        {!! Form::hidden('order_details_id', $order_detail->id)!!}
        {!! Form::hidden('type', 'after')!!}
        <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>
        {!! Form::close() !!}
    </div>
    </div>
</div>
<!--/   Modal-Section Add After Images End   -->

<style>
    #export_view_images{
        overflow: auto;
    }
</style>

<!--/   Modal-Section Show Export Images Start   -->
<div role="dialog" class="modal modelForm"  id="export_view_images">
<div class="imageviewPop">
    <div class="well text-center"><h1>Export Image</h1></div>
    <div class="row-fluid" id="export_modal_image">
    </div>
    <div class="row-fluid">
        <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

        <button  style="margin:40px 0 25px 0;" id="save_value" class="btn btn-large btn-success" onclick="Exportpdf()">Export To PDF</button>
         <button onclick="doit()" style="margin:40px 0 25px 0;" class="btn btn-large btn-primary">Download All Photos</button>
    </div>
    </div>
</div>
<!--/   Modal-Section Show Export Images End   -->


<!--/   Modal-Section Show Before Images Start   -->
<div role="dialog" class="modal modelForm"  id="before_view_image_{!!$order_detail->id!!}">
<div class="imageviewPop">
    <div class="well text-center"><h1>View Before Image</h1></div>
    <div class="row-fluid" id="before_view_modal_image_{!!$order_detail->id!!}">
    </div>
    <div class="row-fluid">
        <button data-dismiss="modal" style="margin:40px 0 25px 0;" class="btn btn-large btn-success">Close</button>


    </div>
    </div>
</div>
<!--/   Modal-Section Show Before Images End   -->



<!--/   Modal-Section Show During Images Start   -->
<div role="dialog" class="modal modelForm"  id="during_view_image_{!!$order_detail->id!!}">
<div class="imageviewPop">
    <div class="well text-center"><h1>View During Image</h1></div>
    <div class="row-fluid" id="during_view_modal_image_{!!$order_detail->id!!}">
    </div>
    <div class="row-fluid">
        <button data-dismiss="modal" style="margin: 40px 0 25px 0;" class="btn btn-large btn-success">Close</button>

    </div>
    </div>
</div>
<!--/   Modal-Section Show Before Images End   -->


<!--/   Modal-Section Show After Images Start   -->
<div class="modal modelForm"  id="after_view_image_{!!$order_detail->id!!}">
<div class="imageviewPop">
    <div class="well text-center"><h1>View After Image</h1></div>
    <div class="row-fluid" id="after_view_modal_image_{!!$order_detail->id!!}">
    </div>
    <div class="row-fluid">
        <button data-dismiss="modal" style="margin: 40px 0 25px 0;" class="btn btn-large btn-success">Close</button>
    </div>
    </div>
</div>
<!--/   Modal-Section Show After Images End   -->
<!--/   Modal-Section End   -->

@endforeach
@endforeach

<input type="hidden" name="totalRequestedServices" id="totalRequestedServices" value="<?php echo $totalRequestedServices;?>">
<?php

if(Auth::user()->type_id==3) {?>
<?php 
    if (is_numeric($total) && is_numeric($totalPrice)){
        $totalPriceVendorFinal = $totalPriceVendor+$totalPrice+$total;
    } else {
        $totalPriceVendorFinal = 0;
    }
 ?>
<div style="float:right;"><h5>Total Price: ${!!$totalPriceVendorFinal!!} </h5>
</div>

<?php }elseif (Auth::user()->type_id==2) { ?>
<?php 
    if (is_numeric($totalPrice)){
        $FinalTotalCustomer =$totalPriceCustomerFinal+$totalPrice;
    } else {
        $FinalTotalCustomer = 0;
    }
     ?>
<div style="float:right;"><h5>Total Price: ${!!$FinalTotalCustomer!!} </h5>
</div>
<?php } else { ?>

<?php 
    if (is_numeric($total)){
        $totalPriceVendorFinal = $totalPriceVendor+$total;
    } else {
        $totalPriceVendorFinal = $totalPriceVendor;
    }
    
    ?>
<?php 
    if (is_numeric($totalPriceCustomerFinal))
    {
        $FinalTotalCustomer = $totalPriceCustomerFinal+$totalPriceCustomer;
    } else {
        $FinalTotalCustomer = $totalPriceCustomerFinal;
    }
 ?>

<div style="float:right;">



  <h5>Total Customer Price: ${!!$FinalTotalCustomer!!} Total Vendor Price: ${!!$totalPriceVendorFinal!!} </h5>
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


     var myLatlng = new google.maps.LatLng({!!$order->maintenanceRequest->asset->latitude!!},{!!$order->maintenanceRequest->asset->longitude!!});

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
<div class="modal  modelForm larg-model"  id="showAsset">
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
                                <label class="control-label" for="typeahead">{!!$order->maintenanceRequest->asset->property_address!!}</label>
                            </div>
                            <div class="control-group row-sep">
                                <label class="control-label" for="typeahead">City: </label>
                                <?php if (isset($order->maintenanceRequest->asset->city->name)): ?>
                                   <label class="control-label" for="typeahead"> {!!$order->maintenanceRequest->asset->city->name!!}</label>
                                <?php else: ?>
                                   <label class="control-label" for="typeahead"> </label>
                                <?php endif ?>

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
                                <label class="control-label" for="typeahead">Gate / Access Code: </label>
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


                          </div>

                          <div class="clearfix"></div>
                      </div>
                      <!--/span-6-->

                      <div class="row-fluid">

                          <div class="control-group">
                            <label class="control-label">Outbuilding / Shed *</label>
                            @if(isset($order->maintenanceRequest->asset->outbuilding_shed) && $order->maintenanceRequest->asset->outbuilding_shed == 1)
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
                            @if($order->maintenanceRequest->asset->special_direction_note != '')
                            <label class="control-label label-auto" for="textarea2">{!!$order->maintenanceRequest->asset->special_direction_note!!}</label>
                            @else
                            <label class="control-label label-auto" for="textarea2">There is no Special Direction</label>
                            @endif
                        </div>
                    </div>
                </div>
                <h4>Utility - On inside Property?</h4>
                <div class="control-group">
                  <label class="control-label">Electric :</label>
                  @if(isset($order->maintenanceRequest->asset->electric_status) && $order->maintenanceRequest->asset->electric_status == 1)
                  <label class="control-label">Yes</label>
                  @else
                  <label class="control-label">No</label>
                  @endif
                  <div style="clear:both"></div>

                  <div class="control-group">
                      <label class="control-label">Water *</label>
                      <div class="controls">



                         @if(isset($order->maintenanceRequest->asset->water_status) && $order->maintenanceRequest->asset->water_status == 1)
                         <label class="control-label">Yes</label>
                         @else
                         <label class="control-label">No</label>
                         @endif

                     </div>

                     <div class="control-group">
                        <label class="control-label">Gas *</label>
                        <div class="controls">
                           @if(isset($order->maintenanceRequest->asset->gas_status) && $order->maintenanceRequest->asset->gas_status == 1)
                           <label class="control-label">Yes</label>
                           @else
                           <label class="control-label">No</label>
                           @endif

                       </div>
                       <div class="control-group hidden-phone">
                          <div class="controls">
                            <label class="control-label" for="textarea2">Utility Note:</label>
                            <label class="control-label label-auto" for="textarea2">{!!$order->maintenanceRequest->asset->utility_note!!}</label>
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
                            {!!$order->maintenanceRequest->asset->swimming_pool!!}
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
            <h5><i class="halflings-icon edit"></i><span class="break"></span>Order Status</h5>
        </div>
        <div class="box-content frZindex">

            <div >
               Completion date {!!Form::text('completion_date', $order->completion_date, array('class'=> 'input-small span2 datepicker', 'id'=> 'completion_date' ))!!}
               <button id="completion_date_div" style="display:none" class="btn btn-small" onclick="completionDate()" >Save</button>
               <!-- <span style="float:right;display:none;" id="edit-qty">
                <span id="show-vendor-qty-{!!$order->id!!}-{!!$order_detail->id!!}">{!! $order_detail->requestedService->quantity !!}
                    <button class="btn btn-primary" id="edit-vendor-qty-button-{!!$order->id!!}-{!!$order_detail->id!!}" onclick="editVendorQuantityButton({!!$order->id!!},{!!$order_detail->id!!})"> &nbsp;Edit Quantity </button>
                </span>
                <span class="hide" id="input-vendor-qty-{!!$order->id!!}-{!!$order_detail->id!!}">{!!Form::text('quantity', $order_detail->requestedService->quantity ,array('style'=>'width:30%', 'class'=>'span','id'=>'vendor-qty-'.$order->id.'-'.$order_detail->id))!!}
                    <button class="btn btn-large btn-warning pull-right" onclick="saveAdminQuantity({!!$order->id!!}, {!!$order_detail->id!!})">Save Quantity</button>
                </span>
            </span> -->
            <br/>
        </div>

        <tr>


            <p id="errorMessage" style="display:none; margin-top:10px;"></p>
            <div class="btn-group"  id="btn-group-unique" >      >
                <button class="btn btn-large orderstatus">@if($order->status==1) In-Process @else {!!$order->status_text!!} @endif</button>
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

                {!! Form::hidden('recurring_id', $RecurringFlag,array("id"=>"recurring_id"))!!}
                <?php
            }
            ?>
            <div class="mystatusclass"  id="btn-group-unique-mobile">
              Current Status  <button class="btn btn-large label-{!! $order->status_class !!}" style="color:#ffffff;" >@if($order->status==1) In-Process @else {!!$order->status_text!!} @endif</button>

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
            {!! Form::hidden('lol', "$order->vendor_id",array("id"=>"vendor_id"))!!}
            {!!Form::textarea('under_review_notes', '' ,array('class'=>'span','id'=>'under_review_notes'))!!}
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

<?php if ($RecurringFlag == 1) { ?>
<div class="modal modelForm"  id="recurringpopup">
    <div class="row-fluid dragImage">
        <div>
            <h5>Recurring Reminder</h5>
            This is recurring service</div>
            <br/>
            <button class="btn btn-large btn-success" data-dismiss="modal"> Close</button>

        </div>

<!--        <div class="row-fluid">

</div>-->
</div>
<?php }  ?>
</div>
<!-- end: Content -->

<!--Upload Before Image-->

<!--/Upload Before Image-->


<?php if (!empty( $previous)): ?>
    <button class="btn btn-primary left" href="#"onclick="showQuickWorkOrderPage({!! $previous !!})">« Previous</button>
<?php else: ?>
     <button class="btn btn-primary left pull-left" disabled="disabled" href="#" onclick="showQuickWorkOrderPage({!! $previous !!})" >« Previous</button>
<?php endif ?>

<?php if (!empty($next)): ?>

<button class="btn btn-primary right pull-right" href="#"onclick="showQuickWorkOrderPage({!!  $next !!})">» Next</button>

<?php else: ?>

    <button class="btn btn-primary right" disabled="disabled" href="#"onclick="showQuickWorkOrderPage({!!  $next !!})">» Next</button>

<?php endif ?>
