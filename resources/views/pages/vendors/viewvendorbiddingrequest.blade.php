@extends('layouts.defaultorange')
@section('content')
  <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{!! URL::asset('assets/js/cycle.js') !!}"> </script>

   <div id="content" class="span11">
<?php if($request_maintenance->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>

<div class="row-fluid">
      <h2 class="bidRqst btn-warning" style="display: block;">Service Bid</h2>

</div><!--/row-->


    <div class="row-fluid">

        <div class="box span12">

            <table class="table table-bordered customeTable">

                <tbody>

                    <tr>

                        <td class="center span3"><h2><span>Property #:</span>{!!$request_maintenance->asset->asset_number!!}</h2></td>


                        <td class="center span3"><span>Order #:</span>{!!$request_maintenance->id!!}</td>


                        <td class="center span3">

                            <h2><span>Status:</span>


                             @if($request_maintenance->status==1)

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

            @endif
                            </h2></td>

                        <td class="center span3">

                        <h2>
                            @foreach ($request_maintenance->requestedService as $services)
                       Service: <span style="font-size: 13px !important;font-weight: normal;"> {!!$services->service->title!!}</span>
                       Due Date:<span style="font-size: 13px !important;font-weight: normal;"> @if($services->due_date=="") Not assigned @else{!!$services->due_date!!} @endif</span>
                        @endforeach



                        </h2>
                        </td>


                    </tr>

                </tbody>

            </table>

        </div><!--/span-->

    </div><!--/row-->
  <div class="row-fluid">
    <div class="box span12">

        <div class="box-header" data-original-title>
						<h2>Property Details</h2>
		</div>
      					@if(Session::has('message'))
                            {!!Session::get('message')!!}
                        @endif
      <div class="box-content">

        <table class="table">
							  <tbody>
								<!-- <tr>
									<td class="center span3"><h2>Request ID:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->id !!}</h2></td>
									<td class="center span3"><h2>Request Date:</h2></td>
									<td class="center span3"><h2>{!! date('m/d/Y', strtotime($request_maintenance->created_at)) !!}</h2></td>
								</tr> -->
								<tr>
									<td class="center span3"><h2>Property Address:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->asset->property_address !!}  <button class="btn btn-small btn-success" data-target="#showServiceid"  onclick="viewAsset({!! $request_maintenance->asset->id !!})">View Property</button></h2> </td>
								<td class="center span3"><h2>City:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->asset->city->name !!}</h2></td>

								</tr>
								<tr>
									<td class="center span3"><h2>State:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->asset->state->name !!}</h2></td>
								<td class="center span3"><h2>Zip:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->asset->zip !!}</h2></td>

								</tr>

							<tr>


									<td class="center span3"><h2>Lockbox:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->asset->lock_box !!}</h2></td>
									<td class="center span3"><h2>Gate/Access Code:</h2></td>
							        <td class="center span3"><h2>{!! $request_maintenance->asset->access_code !!}</h2></td>


							</tr>

								<!-- <tr>

                  					<td class="center span3"><h2>Vendor Notes:</h2></td>
									<td class="center span3"> {!!Form::textarea('vendor_notes', isset( $request_maintenance->admin_notes) ? $request_maintenance->vendor_notes : '' , array('class'=>'span12 typeahead', 'id'=>'vendor_notes','onChange'=>'vendorNotes(this,"'.$request_maintenance->id.'")'))!!} </td>

								</tr> -->
							  </tbody>
						 </table>
      </div>
    </div>
    <!--/span-->

     <h2 class="bidRqst btn-warning" style="display: block;text-align:left;padding:12px;margin:0px;clear: both;">Service Bid Details</h2>
    	<?php
   $totalPrice=0;
    	?>
		@foreach ($assign_requests as $assigned)
                <div class="row-fluid">
				<div class="box span12">

					<div class="box-header" data-original-title>

					<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>

						</div>
						<h2><i class="halflings-icon edit"></i><span class="break"></span>{!!$assigned->requestedService->service->title!!}</h2>

						 </div>


					</div>
					<div class="box-content" style="position:static;float: left;width: 98%;">

					<div class="boxcontentleft">

						<table>
						    <tr><td> {!!$assigned->requestedService->service->desc!!}</td></tr>



								<tr>
									<div class="row-fluid browse-sec">


								<tr>
									<td class="center"><h2>Not For Vendor:</h2>{!!$assigned->requestedService->public_notes!!}</td>



								</tr>
								 <td class="center" colspan="2">

                            <!--/   Modal-Section Start   -->
    <!--/   Modal-Section Add Before Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" class="modal hide fade modelForm"  id="before_{!!$assigned->id!!}">
        <div class="row-fluid dragImage">
                {!! Form::open(array('url' => 'add-before-images-bids', 'class'=>'dropzone', 'id'=>'form-before-'.$assigned->id)) !!}
                {!! Form::hidden('image_type', 'before')!!}
                 {!! Form::hidden('requested_id', $assigned->id)!!}
                  {!! Form::hidden('status', 1)!!}
                <button class="btn btn-large btn-success" data-dismiss="modal">Save & Close</button>
                {!! Form::close() !!}
        </div>
<!--        <div class="row-fluid">

        </div>-->
    </div>
     <!--/   Modal-Section Show Before Images Start   -->
    <div style="padding: 10px;max-height: 500px;overflow: auto;" role="dialog" class="modal hide fade modelForm"  id="before_view_image_{!!$assigned->id!!}">
        <div class="well text-center"><h1>View Before Image</h1></div>
        <div class="row-fluid" id="before_view_modal_image_{!!$assigned->id!!}">
        </div>
        <div class="row-fluid">
                <button data-dismiss="modal" style="margin:25px 0 0;" class="btn btn-large btn-success">Close</button>
        </div>
    </div>
    <!--/   Modal-Section Show Before Images End   -->
                     		   </td>
<td>


</td>


                              @if(count($assigned->requestedService->serviceImages)!=0)<h2><a href="javascript:;" class="viewBtn bluBtn">View Photos</a></h2>@endif



                   <div class="reviewimagespopup">
                    <i class="clsIconPop">X</i>
                     <div class="cycle-slideshow cycledv" data-cycle-slides="li" data-cycle-fx='scrollHorz' data-cycle-speed='700' data-cycle-timeout='7000' data-cycle-log="false" data-cycle-prev=".reviewimagespopup .prev" data-cycle-next=".reviewimagespopup .next" data-cycle-pager=".example-pager">
                        <ul>

                            @foreach($assigned->requestedService->serviceImages as $images)
                          <li><a href="{!!URL::to('/')!!}/{!!Config::get('app.request_images').'/'.$images->image_name!!}" class="dwnldBtn bluBtn" target="_blank">Download</a><img src="{!!URL::to('/')!!}/{!!Config::get('app.request_images').'/'.$images->image_name!!}" id=""></li>


                          @endforeach
                        </ul>
                  </div>
                </div>




                   <!--
                                                          <ul class="media-list ">
                            @foreach($assigned->requestedService->serviceImages as $images)
     <?php
                             $docType=explode(".", $images->image_name);
                             if( $docType[1]=='jpeg'|| $docType[1]=='jpg'|| $docType[1]=='png'|| $docType[1]=='gif')
                             {
                            ?>
                            <li style="width: 30%;margin-bottom: 15"> <a  href="{!!URL::to('/')!!}/{!!Config::get('app.request_images').'/'.$images->image_name!!}" target="_blank" > {!! Html::image(Config::get('app.request_images').'/'.$images->image_name) !!} </a></li>


                             <?php
                          }  else {
                        	?>

                        <li> <a  href="{!!URL::to('/')!!}/{!!Config::get('app.request_images').'/'.$images->image_name!!}" target="_blank" > Download File {!!$images->image_name!!}</a></li>
                        	<?php
                        	}
                            ?>


                             @endforeach


                        </ul> -->

                                                      </div>
								</tr>

						 </table>
						 </div>
						 <div class="boxcontentright">
						   <fieldset id="vendor_bid_section">
	        	<div>
                            <label>Vendor Price:</label>
                            <input type="text" name="vendor_bid_price" id="vendor_bid_price" value="{!!$assigned->requestedService->vendor_bid_price!!}" style="height: auto;padding: 0;margin: -4px 10px 0;" />
					</div>
					<div>
					<label>Vendor Note:</label>
					<textarea name="vendor_note_for_bid" id="vendor_note_for_bid">{!!$assigned->requestedService->vendor_note_for_bid!!}</textarea>
					</div>
					<div>
							<span class="pull-left">
                            <button data-toggle="modal" data-backdrop="static" data-target="#before_{!!$assigned->id!!}" class="btn btn-large btn-success">Upload Before Images</button>
                            <button data-toggle="modal" data-backdrop="static" data-target="#before_view_image_{!!$assigned->id!!}" onclick="popModalBid({!!$assigned->id!!},  'before')" class="btn">View Before Images</button>
                            </span>
                    </div>
                    <div>
                     <button class="btn  btn-success" onclick="saveBidPriceVendor('{!!$assigned->requestedService->id!!}')">Submit</button>
					</div>
							</fieldset>
							</div>
					</div>
				</div><!--/span-->

			</div><!--/row-->
                @endforeach







  </div>
<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>
<div class="modal  hide fade modelForm larg-model"  id="showVendorList">

</div>

<div class="modal hide fade modelForm larg-model"  id="showServiceid"></div>

  <div class="modal hide fade" id="declinedNotes">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Declined Note</h3>
			</div>
			<div class="modal-body">
			 Please enter notes for your reason of decline
				{!!Form::textarea('decline_note', $request_maintenance->decline_notes,array('class'=>'span','id'=>'declinenote'))!!}
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary" onclick="decline_request('{!! $request_maintenance->id !!}','{!! Auth::user()->id; !!}')">Save changes</a>
			</div>
		</div>


</div>
@parent
@include('common.delete_alert')
@stop
