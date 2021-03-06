@extends('layouts.defaultorange')
@section('content')
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{!! URL::asset('public/assets/js/cycle.js') !!}"> </script>
<div id="content" class="span11">
<h2 class="bidRqst btn-warning" style="display: block;">Service Bid</h2>
   <table class="table table-bordered customeTable">
                <tbody>
                    <tr>
                        <td class="center span3"><h2><span>Property #:</span>{!!$request_maintenance->asset->asset_number!!}</h2></td>
                        <td class="center span3"><h2><span>Order #:</span>{!!$request_maintenance->id!!}</h2></td>
                        <td class="center span3"><h2><span>Recurring:</span> No</h2></td>
                        <td class="center span3"><h2><span>Status:</span> @if($request_maintenance->status==1)

           New Bid Request

            @elseif($request_maintenance->status==2)

          Awaiting Vendor Bid

            @elseif($request_maintenance->status==3)

         Completed Vendor Bid

            @elseif($request_maintenance->status==4)

           New Work Order Generated
              @elseif($request_maintenance->status==5)

         Cancel
            @elseif($request_maintenance->status==6)

     Awaiting Customer Approval
            @elseif($request_maintenance->status==7)

        Declined
              @elseif($request_maintenance->status==8)

            Approved Bid

            @endif</h2></td>
                        <td class="center span3">

                        <h2>
                            @foreach ($request_maintenance->requestedService as $services)
                       Service: <span style="font-size: 13px !important;font-weight: normal;"> {!!$services->service->title!!}</span>
                        @endforeach



                        </h2>
                        </td>

                    </tr>
                </tbody>
            </table>
<?php if($request_maintenance->status==7){?>
<div class="declinebidstatus">Declined Note:<span>{!!$request_maintenance->declinebidnotes!!}</span></div>
<?php }?>
<?php if($request_maintenance->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>
<p id="message" style="display:none">Saved...</p>
  <div class="row-fluid">
    <div class="box span12">

        <div class="box-header" data-original-title>
						<h2>Customer Details</h2>
				</div>
      					@if(Session::has('message'))
                            {!!Session::get('message')!!}
                        @endif
      <div class="box-content">
        <table class="table">
							  <tbody>

                <tr>

                  <td class="center span3"><h2>Customer Name:</h2></td>
                  <td class="center span3"><h2>@if(isset($request_maintenance->user->first_name)) {!! $request_maintenance->user->first_name!!} @endif  @if(isset($request_maintenance->user->last_name)){!!$request_maintenance->user->last_name !!} @endif</h2></td>
                 <td class="center span3"><h2>Email:</h2></td>

                  <td class="center span3"><h2>@if(isset( $request_maintenance->user->email)){!! $request_maintenance->user->email!!} @endif</h2></td>

                    </tr>
                    <tr>
                    <td class="center span3"><h2>Customer Company:</h2></td>
                    <td class="center span3"><h2>@if(isset($request_maintenance->user->company)) {!! $request_maintenance->user->company!!} @endif </h2></td>

                    <td class="center span3"><h2>Customer Phone:</h2></td>
                    <td class="center span3"><h2>@if(isset($request_maintenance->user->phone)) {!! $request_maintenance->user->phone!!} @endif </h2></td>

                    </tr>
                <tr></tr>



							  </tbody>
						 </table>
      </div>
    </div>
    <!--/span-->



    <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2>Property Details</h2>

            </div>

            <div class="box-content">

                <table class="table">

                    <tbody>

                        <tr>

                            <td class="center span3"><h2>Property Address: <span >{!!$request_maintenance->asset->property_address!!}</span> </h2></td>

                            <td class="center span3"><h2>City: <span >{!!$request_maintenance->asset->city->name !!} </span></h2></td>



                        </tr>

                        <tr>

                             <td class="center span3"><h2>State: <span >{!!$request_maintenance->asset->state->name!!}</span></h2></td>

                             <td class="center span3"><h2>Zip: <span > {!!$request_maintenance->asset->zip!!}</span> </h2></td>



                        </tr>

                        <tr>



                       <td class="center span3"><h2>Lockbox: <span >{!!$request_maintenance->asset->lock_box!!}</span></h2></td>

                       <td class="center span3"><h2>Gate / Access Code: <span >{!!$request_maintenance->asset->access_code!!}</span></h2></td>

                        </tr>



                    </tbody>

                </table>

            </div>



        </div><!--/span-->

    </div><!--/row-->

    <span><h1 class="text-center">Service Bid Details</h1></span>

<?php
$totalPriceCustomer=0;
$totalPriceVendor=0;

?>
		@foreach ($request_maintenance->requestedService as $services)


                <div class="row-fluid">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon edit"></i><span class="break"></span>{!!$services->service->title!!}</h2>

					</div>
          <input type="hidden" name="bypassornot" id="bypassornot" value="0">
					<div class="box-content"  style="float:left;width:98%;">

                   <div class="boxcontentleft">
              	<table>
                <tr><td> {!!$services->service->desc!!}</td></tr>



								<tr>
									<div class="row-fluid browse-sec">



                           <?php
                         $imagecounter=0;
                         foreach ($request_maintenance->assignRequest as $imagesData) {
foreach ($imagesData->assignRequestBidsImage as  $images) {
 $imagecounter++;
  }
  }?>
           <h5> Vendor Bid Images:</h5>

                     <?php if( $imagecounter==0) {?>
                     <h5> No Bid Images have been uploaded</h5>
                      <?php }else {?>
                   <h2><a href="javascript:;" class="viewBtn bluBtn">View Photos</a></h2>
                      <?php } ?>





                   <div class="reviewimagespopup">
                    <i class="clsIconPop">X</i>
                     <div class="cycle-slideshow cycledv" data-cycle-slides="li" data-cycle-fx='scrollHorz' data-cycle-speed='700' data-cycle-timeout='7000' data-cycle-log="false" data-cycle-prev=".reviewimagespopup .prev" data-cycle-next=".reviewimagespopup .next" data-cycle-pager=".example-pager">
                        <ul>
                          {{--*/ $loop = 1 /*--}}
                                            <?php
foreach ($request_maintenance->assignRequest as $imagesData) {
foreach ($imagesData->assignRequestBidsImage as  $images) {
 ?>
                          <li><a href="{!!URL::to('/')!!}/{!!Config::get('app.bid_images_before').'/'.$images->image_name!!}" class="dwnldBtn bluBtn" target="_blank">Download</a>  {!! Html::image(Config::get('app.bid_images_before').'/'.$images->image_name) !!}</li>

                          {{--*/ $loop++ /*--}}
                        <?php
}
}
                        ?>
                        </ul>
                  </div>
                </div>










                                                      </div>
								</tr>

                @if($services->required_date!="")
                <tr><td>Required Date</td>
                <td>{!! date('m/d/Y', strtotime($services->required_date)) !!}

                </td>
                </tr>
                @endif

                   @if( $services->due_date!="")
                <tr><td>Due Date</td>
                <td>
              {!! date('m/d/Y', strtotime($services->due_date)) !!}
                </td>
                </tr>
                @endif

                  @if($services->quantity!="")
                <tr><td>Quantity</td>
                <td>{!! $services->quantity !!}

                </td>
                </tr>
                @endif



                    @if($services->service_men!="")
                <tr><td>Service men</td>
                <td>{!!$services->service_men !!}

                </td>
                </tr>
                @endif
                    @if($services->service_note!="")
                <tr><td>Service note:</td>
                <td>{!!$services->service_note !!}

                </td>
                </tr>
                @endif

                    @if($services->verified_vacancy!="")
                <tr><td>Verified vacancy</td>
                <td>{!!$services->verified_vacancy !!}

                </td>
                </tr>
                @endif
                  @if($services->cash_for_keys!="")
                <tr><td>Cash for keys</td>
                <td>{!!$services->cash_for_keys !!}

                </td>
                </tr>
                @endif

                   @if($services->cash_for_keys_trash_out!="")
                <tr><td>Cash for keys Trash Out</td>
                <td>{!!$services->cash_for_keys_trash_out !!}

                </td>
                </tr>
                @endif

                   @if($services->trash_size!="")
                <tr><td>trash size</td>
                <td>{!!$services->trash_size !!}

                </td>
                </tr>
                @endif


                   @if($services->storage_shed!="")
                <tr><td>storage shed</td>
                <td>{!!$services->storage_shed !!}

                </td>
                </tr>
                @endif


                   @if($services->lot_size!="")
                <tr><td>lot size</td>
                <td>{!!$services->lot_size !!}

                </td>
                </tr>
                @endif

                   @if($services->set_prinkler_system_type!="")
                <tr><td>set prinkler system type</td>
                <td>{!!$services->set_prinkler_system_type !!}

                </td>
                </tr>
                @endif


                   @if($services->install_temporary_system_type!="")
                <tr><td>install temporary system type</td>
                <td>{!!$services->install_temporary_system_type !!}

                </td>
                </tr>
                @endif



                   @if($services->pool_service_type!="")
                <tr><td>pool service type</td>
                <td>{!!$services->pool_service_type !!}

                </td>
                </tr>
                @endif


                   @if($services->carpet_service_type!="")
                <tr><td>carpet service type</td>
                <td>{!!$services->carpet_service_type !!}

                </td>
                </tr>
                @endif

                 @if($services->boarding_type!="")
                <tr><td>boarding type</td>
                <td>{!!$services->boarding_type !!}

                </td>
                </tr>
                @endif



                 @if($services->spruce_up_type!="")
                <tr><td>spruce up type</td>
                <td>{!!$services->spruce_up_type !!}

                </td>
                </tr>
                @endif



                 @if($services->constable_information_type!="")
                <tr><td>constable information type</td>
                <td>{!!$services->constable_information_type !!}

                </td>
                </tr>
                @endif


                  @if($services->remove_carpe_type!="")
                <tr><td>remove carpe type</td>
                <td>{!!$services->remove_carpe_type !!}

                </td>
                </tr>
                @endif


                 @if($services->remove_blinds_type!="")
                <tr><td>remove blinds type</td>
                <td>{!!$services->remove_blinds_type !!}

                </td>
                </tr>
                @endif

                    @if($services->remove_appliances_type!="")
                <tr><td>remove appliances type</td>
                <td>{!!$services->remove_appliances_type !!}

                </td>
                </tr>
                @endif




						 </table>
               <fieldset class="bypasscustomer" disabled="disabled" style="background:grey !important">
                      <legend>Assign Vendor:</legend>

                      Vendor Bid Price   <input type="text" name="vendor_bid_price" id="vendor_bid_price" value="{!!$services->vendor_bid_price!!}" disabled="disabled" style="background:grey !important"/>
                    <h2>Admin Note:</h2>
                 {!!Form::textarea('public_notes',isset($services->public_notes) ? $services->public_notes : '' , array('style'=>'background:grey','class'=>'span12 typeahead', 'id'=>'public_notes','onChange'=>'publicNotesBid(this,"'.$services->id.'")'))!!}
                <h2>Vendor Note</h2>
                 {!!Form::textarea('vendor_note_for_bid',isset($services->vendor_note_for_bid) ? $services->vendor_note_for_bid : '' , array('style'=>'background:grey','class'=>'span12 typeahead', 'id'=>'vendor_note_for_bid'))!!}

                <!--  <button class="btn btn-large btn-success" onclick="saveBidPrice('{!!$services->id!!}')">Submit Bid to Customer</button> -->
                 <button data-toggle="modal" data-target="#assign" class="btn btn-large btn-success" onclick="showBidServices('{!! $request_maintenance->id !!}')" disabled="disabled" style="background:grey !important">Request Bid from Vendor</button>

                   </fieldset>

             </div>
                 <div class="boxcontentright">



                <fieldset class="bypassvendor">
                      <legend>Submit Bid To Customer:</legend>

                     Customer Bid Price       <input type="text" name="customer_bid_price" id="customer_bid_price" value="{!!$services->customer_bid_price!!}" />
                   <h2>Percentage Options</h2>
                   <select id="markupoption" onchange="markup('vendor_bid_price','customer_bid_price')">
                    <option value="100">option 1</option>
                    <option value="90">option 2</option>
                    <option value="80">option 3</option>
                  </select>
                  <h2>Note for Customer:</h2>
                   {!!Form::textarea('customer_notes_bid',isset($services->customer_notes_bid) ? $services->customer_notes_bid : '' , array('class'=>'span12 typeahead', 'id'=>'customer_notes_bid','onChange'=>'customerNotesBid(this,"'.$services->id.'")'))!!}


<!--                   <button data-toggle="modal" data-target="#assign" class="btn btn-large btn-success" onclick="showBidServicesWorkOrder('{!! $request_maintenance->id !!}',1,'{!!$services->id!!}')">Don't Request Bid From Vendor</button> -->
                   <button class="btn btn-large btn-success" onclick="saveBidPriceDirectSendWithoutReminder('{!!$services->id!!}')">Submit Bid to Customer</button>

                  </fieldset>
      </div>
					</div>
				</div><!--/span-->

			</div><!--/row-->
                @endforeach




  <div style="float:right;"><h2>Total Customer Price: ${!!$totalPriceCustomer!!} Total Vendor Price: ${!!$totalPriceVendor!!} </h2>
    </div>

  </div>

<div class="modal  hide fade modelForm larg-model"  id="showVendorList">

</div>

<div class="modal hide fade modelForm larg-model"  id="showServiceid"></div>


  <div class="modal  hide fade modelForm larg-model"  id="remainderByPass">
        <h2>Remind me on:</h2>
        <br/>
         <input type="text" class="datepicker" id="datepickerremainder">
         <input type="hidden" id="remindMehidden">
         <button onclick="saveBidPriceSend()">Save</button>
</div>


</div>
@parent
@include('common.delete_alert')
@stop
