<!-- start: Content -->
<div id="content">

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="pull-left">
                <a class="btn btn-info" href="#" onclick="printDiv('content')" style="float: right;position: relative;z-index: 999;"> Print  </a>
            </div>
            <div class="pull-right">
                @if (!empty($previous))
                    <button class="btn btn-primary btn-sm left" href="#"onclick="showQuickWorkOrderPage({!! $previous !!})">« Previous</button>
                @else
                    <button class="btn btn-primary btn-sm left" disabled="disabled" href="#" onclick="showQuickWorkOrderPage({!! $previous !!})" >« Previous</button>
                @endif

                @if (!empty($next))
                    <button class="btn btn-primary btn-sm right" href="#"onclick="showQuickWorkOrderPage({!!  $next !!})">Next »</button>
                @else
                    <button class="btn btn-primary btn-sm right" disabled="disabled" href="#"onclick="showQuickWorkOrderPage({!!  $next !!})">Next »</button>
                @endif

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?php if(Auth::user()->type_id==3){
                if(isset($order->maintenanceRequest->asset->property_dead_status) && $order->maintenanceRequest->asset->property_dead_status==1){?>

                <div class ="disableProperty"><span>Property Closed</span></div>

                <?php
                }else if($order->status==5){ ?>

                <div class ="disableOrder"><span>Order Cancelled</span></div>

                <?php  }

                }
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <h1 class="text-center">Work Order</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            @if (Auth::user()->type_id == 1 || Auth::user()->type_id == 4)
                <label for="vendorAssigned">Vendor:</label>
                <select name="vendorAssigned" id="vendorAssigned">
                @foreach($vendorsDATA as $key => $value)
                    <option value="<?php echo $value->id;?>"  <?php if($order->vendor_id==$value->id) { echo "selected=selected";} ?> ><?php echo $value->first_name;?> <?php echo $value->last_name;?> <?php echo $value->company;?></option>
                @endforeach
                </select>
                <a class="btn btn-info" href="#" onclick="changeVendorOrder()" > Change Vendor  </a>
            @endif

        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <table class="table table-bordered quick-view-table">
                <tbody>
                    <td>
                        <label class="table-label">Property #: </label>
                        <span>
                        @if(isset($order->maintenanceRequest->asset->asset_number))
                            {!!$order->maintenanceRequest->asset->asset_number!!}
                        @endif
                        </span>
                    </td>

                    <td>
                        <label class="table-label">Order #: </label>
                        <span>
                            {!! $order->id !!}
                        </span>
                    </td>

                    <td>
                        <label class="table-label">Status: </label>
                        <span>
                            @if($order->status==1) In-Process @else {!!$order->status_text!!} @endif
                        </span>

                    </td>

                    <td>
                        @foreach($order_details as $order_detail)
                            <label class="table-label">
                                @if($order_detail->requestedService->service->title)
                                    {!!$order_detail->requestedService->service->title!!}
                                @endif
                            </label>
                            <span>


                                <?php if(isset($order_detail->requestedService->due_date)&&$order_detail->requestedService->due_date!="")
                                {
                                    echo 'Due Date: '. date('m/d/Y', strtotime($order_detail->requestedService->due_date));
                                }
                                else
                                    {
                                        echo 'Due Date: Not Assigned';
                                    }
                                    ?>
                            </span>
                            <span id="changeButton" style="display:none;">
                                <input type="text" class="datepicker" name="duedatechange" id="duedatechange" />
                                <button onclick="SaveDueDate('{!!$order_detail->requestedService->id!!}')">Save</button>
                            </span>

                            <?php if(Auth::user()->type_id==1||Auth::user()->type_id==4) { ?>
                                <button class="btn" onclick="changeDueDate('{!!$order->id!!}')">Update</button>
                            <?php }?>

                        @endforeach
                    </td>

                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <label class="table-label">Customer Details</label>
            <table class="table table-bordered quick-view-table">
                <tbody>
                <tr>
                    <td>
                        <label class="table-label">First Name: </label>
                        @if(!empty($order->maintenanceRequest->user->first_name))
                            <span>
                                {!! $order->maintenanceRequest->user->first_name !!}
                            </span>
                        @endif
                    </td>
                    <td>
                        <label class="table-label">Last Name: </label>
                        @if(!empty($order->maintenanceRequest->user->last_name))
                            <span>
                                {!! $order->maintenanceRequest->user->last_name !!}
                            </span>
                        @endif
                    </td>
                </tr>
                @if(Auth::user()->type_id!=3)
                    <tr>
                        <td>
                            <label class="table-label">Company: </label>
                            @if(!empty($order->maintenanceRequest->user->company))
                                <span>
                                {!! $order->maintenanceRequest->user->company !!}
                            </span>
                            @endif
                        </td>
                        <td>
                            <label class="table-label">Email: </label>
                            @if(!empty($order->maintenanceRequest->user->email))
                                <span>
                                {!! $order->maintenanceRequest->user->email !!}
                            </span>
                            @endif
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <label class="table-label">Property Details</label>
            <table class="table table-bordered quick-view-table">
                <tbody>
                    <tr>
                        <td>
                            <label class="table-label">Property Address: </label>
                            <span>
                                {!!$order->maintenanceRequest->asset->property_address!!}
                            </span>
                        </td>

                        <td>
                            <label class="table-label">City: </label>
                            <span>
                                @if (isset($order->maintenanceRequest->asset->city->name))
                                    {!! $order->maintenanceRequest->asset->city->name !!}
                                @endif
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="table-label">State: </label>
                            <span>
                                {!!$order->maintenanceRequest->asset->state->name!!}
                            </span>
                        </td>

                        <td>
                            <label class="table-label">Zip: </label>
                            <span>
                                {!!$order->maintenanceRequest->asset->zip!!}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="table-label">Lockbox: </label>
                            <span>
                                {!! $order->maintenanceRequest->asset->lock_box !!}
                            </span>
                        </td>
                        <td>
                            <label class="table-label">Gate / Access Code: </label>
                            <span>
                                {!! $order->maintenanceRequest->asset->access_code !!}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <h1 class="text-center">Requested Services</h1>
        </div>
    </div>
    <?php

        $totalPriceCustomer=0;
        $total = 0;
        $totalPriceVendor=0;
        $totalPrice=0;
        $totalRequestedServices=0;
        $RecurringFlag=0;
    ?>

    @foreach($order_details as $order_detail)
        @foreach($customData as $custom)
            @if(isset($order_detail->requestedService->service->title))


            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <label class="table-label">{!! $order_detail->requestedService->service->title !!}</label>
                            <button data-toggle="modal" class="myBtnImg"  data-target="#edit_request_service" data-backdrop="static" >
                                <i class="halflings-icon edit myBtnImg" ></i>
                                Edit Service</button>
                            <div class="pull-right">
                                <?php

                                if ($order_detail->requestedService->recurring == 1)
                                {
                                    $RecurringFlag = 1;
                                }

                                $totalRequestedServices++;




                                if (Auth::user()->type_id == 3)
                                {
                                    $SpecialPriceVendor = \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                                        ->where('customer_id', Auth::user()->id)
                                        ->where('type_id', 3)
                                        ->get();

                                    $vendor_priceFIND = 0;

                                    if (!empty($SpecialPriceVendor[0]))
                                    {
                                        if (isset($custom->vendors_price) && isset($custom->quantity))
                                        {
                                            echo "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                        }
                                        else
                                        {
                                            echo "Price: $".$SpecialPriceVendor[0]->special_price * $order_detail->requestedService->quantity;
                                        }
                                    }
                                    else
                                    {
                                        if (isset($custom->vendors_price) && isset($custom->quantity))
                                        {
                                            if( $custom->vendors_price !== NULL && $custom->quantity !== NULL)
                                            {
                                                $vendor_priceFIND += $custom->vendors_price * $custom->quantity;
                                                echo "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                            }
                                        }

                                        else
                                        {
                                            $vendor_priceFIND = $order_detail->requestedService->service->vendor_price * $order_detail->requestedService->quantity;
                                            echo "Vendor Price: ".$order_detail->requestedService->service->vendor_price * $order_detail->requestedService->quantity;
                                        }
                                    }

                                    $totalPrice += $vendor_priceFIND;

                                }
                                else if (Auth::user()->type_id==2)
                                {

                                    $SpecialPriceVendor=  \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                                        ->where('customer_id', Auth::user()->id)
                                        ->where('type_id', 2)
                                        ->get();

                                    $vendor_priceFIND = 0;
                                    if (!empty($SpecialPriceVendor[0]))
                                    {
                                        if ($custom->vendors_price !== NULL && $custom->quantity !== NULL)
                                        {
                                            $vendor_priceFIND = $custom->vendors_price * $custom->quantity;
                                            echo "Price: $".$vendor_priceFIND;
                                        }
                                        else
                                        {
                                            $vendor_priceFIND = $SpecialPriceVendor[0]->special_price * $order_detail->requestedService->quantity;
                                            echo "Price: $".$vendor_priceFIND;
                                        }
                                    }
                                    else
                                    {
                                        if (isset($custom->vendors_price) && isset($custom->quantity))
                                        {
                                            if( $custom->vendors_price !== NULL && $custom->quantity !== NULL)
                                            {
                                                $vendor_priceFIND += $custom->vendors_price * $custom->quantity;
                                                echo "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                            }
                                        }
                                    }

                                    $totalPrice += $vendor_priceFIND;

                                }
                                else
                                {



                                    $SpecialPriceCustomer=  \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                                        ->where('customer_id', $order->customer->id)
                                        ->where('type_id', 2)
                                        ->get();

                                    $customer_priceFIND = 0;

                                    if (!empty($SpecialPriceCustomer[0]))
                                    {
                                        if (!empty($custom->customer_price))
                                        {
                                            $totalPriceCustomer += $custom->customer_price * $custom->admin_quantity;
                                            echo "Customer Price: $".$custom->customer_price * $custom->admin_quantity;
                                        }
                                        else
                                        {
                                            $totalPriceCustomer += $SpecialPriceCustomer[0]->special_price;
                                            echo "Customer Price: $".$SpecialPriceCustomer[0]->special_price;
                                        }
                                    }
                                    else
                                    {
                                        if (isset($custom->customer_price) && $custom->customer_price !== NULL)
                                        {
                                            $totalPriceCustomer += $custom->customer_price * $custom->admin_quantity;
                                            echo "Customer Price: $".$custom->customer_price * $custom->admin_quantity;
                                        }
                                        else
                                        {
                                            $totalPriceCustomer = $order_detail->requestedService->service->customer_price;
                                            echo "Customer Price: $".$order_detail->requestedService->service->customer_price;
                                        }
                                    }

                                    $SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                                        ->where('customer_id','=',$order->vendor->id)
                                        ->where('type_id','=',3)
                                        ->get();

                                    if (!empty($SpecialPriceVendor[0]))
                                    {
                                        if ($custom->vendors_price !== null && $custom->quantity !== null)
                                        {
                                            $totalPriceVendor += $custom->vendors_price * $custom->quantity;
                                            echo " | Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                        }
                                        else
                                        {
                                            $totalPriceVendor += $SpecialPriceVendor[0]->special_price;
                                            echo " | Vendor Price: $".$SpecialPriceVendor[0]->special_price;
                                        }
                                    }
                                    else
                                    {
                                        if (isset($custom->vendors_price) && $custom->vendors_price !== null && $custom->quantity !== NULL)
                                        {
                                            $totalPriceVendor += $custom->vendors_price * $custom->quantity;
                                            echo " | Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                        }
                                        else
                                        {
                                            $totalPriceVendor += $order_detail->requestedService->service->vendor_price;
                                            echo " | Vendor Price: $".$order_detail->requestedService->service->vendor_price;
                                        }
                                    }

                                }


                                ?>
                            </div>
                        </div>

                        <div class="card-body">



                            <div id="vendor-note-empty-error-{!!$order_detail->id!!}" class="hide">
                                <div class="alert alert-error">Vendor Note Can not be Empty</div>
                            </div>
                            <div id="vendor-note-empty-success-{!!$order_detail->id!!}" class="hide">
                                <div class="alert alert-success">Saved Successful</div>
                            </div>

                            <div id="billing-note-empty-success" class="hide">
                                <div class="alert alert-success">Saved Successful</div>
                            </div>

                            <div id="billing-note-empty-error" class="hide">
                                 <div class="alert alert-success">Billing Note Can not be Empty</div>
                            </div>

                            <table class="table table-bordered">

                                                    <?php
                                                    if( Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {
                                                    ?>
                                                    <tr>
                                                        @if(isset($custom->customers_notes))
                                                            <td colspan="2" class="center"><label class="table-label">Customer Note: </label><p>{!!$custom->customers_notes!!}</p>   </td>
                                                        @else
                                                            <td colspan="2" class="center"><label class="table-label">Customer Note: </label><p>{!!$order_detail->requestedService->customer_note!!}</p>   </td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        @if(isset($custom->notes_for_vendors))
                                                            <td colspan="2" class="center"><label class="table-label">Note for Vendor: </label><p>{!!$custom->notes_for_vendors!!}</p></td>

                                                        @else
                                                            <td colspan="2" class="center"><label class="table-label">Note for Vendor: </label><p>{!!$order_detail->requestedService->public_notes!!}</p>

                                                            </td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        @if(isset($custom->vendors_notes))
                                                            <td colspan="2" class="center"><label class="table-label">Vendor Note: </label><p>{!!$custom->vendors_notes!!}</p></td>
                                                        @else
                                                            <td colspan="2" class="center"><label class="table-label">Vendor Note: </label><p>{!!$order_detail->requestedService->vendor_note!!}</p>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    <?php }elseif( Auth::user()->type_id == 3 ) {
                                                    ?>


                                                    <tr>
                                                        <td colspan="2" class="center"><label class="table-label">Note for Vendor:</label>

                                                            @if(isset($custom->notes_for_vendors))
                                                                <p>{!!$custom->notes_for_vendors!!}</p >
                                                            @else
                                                                <p>{!!$order_detail->requestedService->public_notes!!}</p >
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
                                                        <td colspan="2" class="center"><label class="table-label">Vendor Note:</label>
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
                                                        <td colspan="2" class="center"><label class="table-label">Customers Note:</label>
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
                                                        <td colspan="2" class="center"><label class="table-label">Admin Note:</label>
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


                                                        <td colspan="2" class="center"><label class="table-label">Billing Note:</label>
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

                                                    <tr><td><label class="table-lable">Service Details</label></td><td></td></tr>
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
                                                                <h2>
                                                                    <?php if (Auth::user()->type_id==1 || Auth::user()->type_id==3 || Auth::user()->type_id==4){ ?>
                                                                    <button data-toggle="modal" data-target="#edit_additional_item_{!!$item->id!!}" data-backdrop="static" ><i class="halflings-icon edit" ></i> Edit Service</button>
                                                                    <?php }else{ ?><i class="halflings-icon edit" ></i> <?php } ?><span class="break"></span>{!!$item->title!!}</h2>
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
                                                                        @endif

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            @endforeach
                                                    </div>
                                                </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    @endforeach

</div>

{!! Form::hidden('order_image_id', "",array("id"=>"order_image_id"))!!}
<div class="modal fade"  id="before_{!!$order_detail->id!!}">
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
<div class="modal fade"  id="during_{!!$order_detail->id!!}">
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
<div class="modal fade"  id="after_{!!$order_detail->id!!}">
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
<div role="dialog" class="modal fade"  id="export_view_images">
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
<div role="dialog" class="modal fade"  id="before_view_image_{!!$order_detail->id!!}">
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
<div role="dialog" class="modal fade"  id="during_view_image_{!!$order_detail->id!!}">
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
<div class="modal fade"  id="after_view_image_{!!$order_detail->id!!}">
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
<?php if ($RecurringFlag == 1) { ?>
<div class="modal fade"  id="recurringpopup">
    <div class="row-fluid dragImage">
        <div>
            <h2>Recurring Reminder</h2>
            This is recurring service</div>
        <br/>
        <button class="btn btn-large btn-success" data-dismiss="modal"> Close</button>

    </div>

    <!--        <div class="row-fluid">

    </div>-->
</div>
<?php }  ?>



