@extends('layouts.default')
@section('content')

<title>GSS - Maintenance Request #{!! $request_maintenance->id !!}</title>
<div id="content" class="span11">
<?php if($request_maintenance->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>
<p id="message" style="display:none">Saved...</p>
  <div class="row-fluid">
    <div class='card table-margin'>
      <div class='card-header'>
						<h4>Service Request Details</h4>
      </div>
      <div class='card-body'>
          @if(Session::has('message'))
            {!!Session::get('message')!!}
          @endif
          <table class="table">
							  <tbody>
								<tr>
									<td class="center span3"><h5>Request ID:</h5></td>
									<td class="center span3"><p>{!! $request_maintenance->id !!}</p></td>
									<td class="center span3"><h5>Request Date:</h5></td>
									<td class="center span3"><p>{!! date('m/d/Y', strtotime($request_maintenance->created_at)) !!}</p></td>
								</tr>
								<tr>
									<td class="center span3"><h5>Asset #:</h5></td>
									<td class="center span3"><p>{!! $request_maintenance->asset->asset_number !!}  <button class="btn btn-small btn-success" data-target="#showServiceid"  onclick="viewAsset({!! $request_maintenance->asset->id !!})">View Property</button></p> </td>

									<td class="center span3"><h5>Customer Name:</h5></td>
									<td class="center span3"><p>@if(isset($request_maintenance->user->first_name)) {!! $request_maintenance->user->first_name!!} @endif  @if(isset($request_maintenance->user->last_name)){!!$request_maintenance->user->last_name !!} @endif</p></td>
								   </tr>
								<tr>

                  <td class="center span3"><h5>Property Address:</h5></td>
                  <td class="center span3"><p>{!! $request_maintenance->asset->property_address !!}</p></td>

									<td class="center span3"><h5>State:</h5></td>
									<td class="center span3"><p>{!! $request_maintenance->asset->state->name !!}</p></td>
								</tr>
								<tr>
									<td class="center span3"><h5>Zip:</h5></td>
									<td class="center span3"><p>{!! $request_maintenance->asset->zip !!}</p></td>
									<td class="center span3"><h5>Email:</h5></td>

									<td class="center span3"><p>@if(isset( $request_maintenance->user->email)){!! $request_maintenance->user->email!!} @endif</p></td>
								                                    </tr>
								<tr>
                <td class="center span3"><h5>City:</h5></td>
                  <td class="center span3"><p>{!! $request_maintenance->asset->city->name !!}</p></td>

                  	<td class="center span3"><h5>Admin Notes:</h5></td>
									<td class="center span3"> {!!Form::textarea('admin_notes', isset( $request_maintenance->admin_notes) ? $request_maintenance->admin_notes : '' , array('class'=>'form-control', 'id'=>'admin_notes','onChange'=>'adminNotes(this,"'.$request_maintenance->id.'")'))!!} </td>

								</tr>



							  </tbody>
						 </table>
      </div>
    </div>

    <span><h3 class="text-center">Vendor Details</h3></span>
    <div class='table-padding'>
    			<button data-toggle="modal" data-target="#assign" class="btn btn-large btn-success assign-vendor" onclick="showMaintenanceServices('{!! $request_maintenance->id !!}')">Assign Vendor</button>
    </div>
        <?php
        $totalPriceCustomer=0;
        $totalPriceVendor=0;

        ?>
		@foreach ($request_maintenance->requestedService as $services)


                <div class="row-fluid">
				<div class="card table-margin">
					<div class="card-header" data-original-title>
						<div class='float-left'><h2><i class="halflings-icon edit"></i><span class="break"></span>{!!$services->service->title!!}</h2></div>
						<div class="card-price">
						 <?php
  $priceData=\App\SpecialPrice::getSpecialCustomerPrice($request_maintenance->user->id,$services->service->id);
   $servicePrice="";

    if(!empty($priceData) )
    {

           if($services->quantity=="" || $services->quantity==0)
         	{
          $servicePrice=$priceData->special_price;
        $totalPriceCustomer += $priceData->special_price;
            }
             else
             {
             	      $servicePrice=$priceData->special_price*$services->quantity ;
          $totalPriceCustomer += $priceData->special_price*$services->quantity ;

             }

    }
    else {

            if($services->quantity=="" || $services->quantity==0)
         	{
             $servicePrice=$services->service->customer_price;
          $totalPriceCustomer += $services->service->customer_price;
            }
             else
             {
             	      $servicePrice=$services->service->customer_price*$services->quantity ;
          $totalPriceCustomer += $services->service->customer_price*$services->quantity ;

             }

         }

         $vendorPrice=0;

         if($services->quantity=="" || $services->quantity==0)
         	{
             $totalPriceVendor += $services->service->vendor_price;
                  $vendorPrice= $services->service->vendor_price;
            }
             else
             {
             	    $totalPriceVendor += $services->service->vendor_price*$services->quantity ;
            $vendorPrice=  $services->service->vendor_price*$services->quantity ;
             }

    ?>
               Customer Price :  ${!!$servicePrice!!} ::::: Vendor Price: ${!!$vendorPrice!!}
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
						</div>
					</div>
					<div class="card-body">

						<table>
                <tr><td> {!!$services->service->desc!!}</td></tr>
								<tr>
									<td class="center"><h2>Customer Note:</h2>{!!$services->service_note!!}</td>
								</tr>
                 <tr>
                 <td class="center span3"><h2>Note for Vendor:</h2>
                 {!!Form::textarea('public_notes',isset($services->public_notes) ? $services->public_notes : '' , array('class'=>'form-control', 'id'=>'public_notes','onChange'=>'publicNotes(this,"'.$services->id.'")'))!!}
                 </td>

                </tr>
								<tr>
									<div class="row-fluid browse-sec">
                              @if(count($services->serviceImages)!=0)<h2>Images</h2>@endif


                                                          <ul class="media-list ">
                            @foreach($services->serviceImages as $images)
                            <?php
                             $docType=explode(".", $images->image_name);
                             if( $docType[1]=='jpeg'|| $docType[1]=='jpg'|| $docType[1]=='png'|| $docType[1]=='gif')
                             {
                            ?>
                            <li>{!! Html::image(Config::get('app.request_images').'/'.$images->image_name) !!}</li>


                             <?php
                          }  else {
                        	?>

                        <li> <a  href="{!!URL::to('/')!!}/{!!Config::get('app.request_images').'/'.$images->image_name!!}" target="_blank" > Download File</a></li>
                        	<?php
                        	}
                            ?>
                            @endforeach


                        </ul>

                                                      </div>
								</tr>

                  <tr><td>Service Details</td><td></td></tr>
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
                <tr><td>Service note</td>
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
					{{--  </div>  --}}
				{{--  </div>--}}

			<!--/row-->
                @endforeach



  <div class='card-footer'>
    <div style="float:right;"><h5>Total Customer Price: ${!!$totalPriceCustomer!!} Total Vendor Price: ${!!$totalPriceVendor!!} </h5>
  </div>
  </div>
</div>
    </div>

  </div>
</div>
<div class="modal fade" id="showVendorList" tabindex='-1' role='dialog' aria-hidden='true'>
  <div class='modal-dialog' role='document'>
    <div class='modal-content'>
      <div class="modal-body" style="min-height:400px !important;"></div>
      <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
        <a href="#" class="btn btn-primary" onclick="assign_request()">Save</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="showServiceid" tabindex='-1' role='dialog' aria-hidden='true'>
          <div class="modal-dialog" role="document">
        		<div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" data-target="#showServiceid" aria-label="Close">x</button>
                        <h1>Property Detail</h1>
                    </div>
                    <div class="modal-body">
                     </div>
                    <div class="modal-footer">
                        <div class="text-right">
                          <button type="button" class="btn btn-large btn-inverse" data-dismiss="modal">Close</button>
                        </div>
                    </div>
             	</div>
             </div>   
</div>




</div>
@parent
@include('common.delete_alert')
@stop
