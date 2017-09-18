@extends('layouts.default')
@section('content')

<div id="content" class="span11">
<?php if($request_maintenance->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>
<p id="message" style="display:none">Saved...</p>
  <div class="row-fluid">
    <div class="box span12">

        <div class="box-header" data-original-title>
						<h2>Service Request Details</h2>
					</div>
      					@if(Session::has('message'))
                            {!!Session::get('message')!!}
                        @endif
      <div class="box-content">
        <table class="table">
							  <tbody>
								<tr>
									<td class="center span3"><h2>Request ID:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->id !!}</h2></td>
									<td class="center span3"><h2>Request Date:</h2></td>
									<td class="center span3"><h2>{!! date('m/d/Y', strtotime($request_maintenance->created_at)) !!}</h2></td>
								</tr>
								<tr>
									<td class="center span3"><h2>Asset #:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->asset->asset_number !!}  <button class="btn btn-small btn-success" data-target="#showServiceid"  onclick="viewAsset({!! $request_maintenance->asset->id !!})">View Property</button></h2> </td>

									<td class="center span3"><h2>Customer Name:</h2></td>
									<td class="center span3"><h2>@if(isset($request_maintenance->user->first_name)) {!! $request_maintenance->user->first_name!!} @endif  @if(isset($request_maintenance->user->last_name)){!!$request_maintenance->user->last_name !!} @endif</h2></td>
								   </tr>
								<tr>

                  <td class="center span3"><h2>Property Address:</h2></td>
                  <td class="center span3"><h2>{!! $request_maintenance->asset->property_address !!}</h2></td>

									<td class="center span3"><h2>State:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->asset->state->name !!}</h2></td>
								</tr>
								<tr>
									<td class="center span3"><h2>Zip:</h2></td>
									<td class="center span3"><h2>{!! $request_maintenance->asset->zip !!}</h2></td>
									<td class="center span3"><h2>Email:</h2></td>

									<td class="center span3"><h2>@if(isset( $request_maintenance->user->email)){!! $request_maintenance->user->email!!} @endif</h2></td>
								                                    </tr>
								<tr>
                <td class="center span3"><h2>City:</h2></td>
                  <td class="center span3"><h2>{!! $request_maintenance->asset->city->name !!}</h2></td>

                  	<td class="center span3"><h2>Admin Notes:</h2></td>
									<td class="center span3"> {!!Form::textarea('admin_notes', isset( $request_maintenance->admin_notes) ? $request_maintenance->admin_notes : '' , array('class'=>'span12 typeahead', 'id'=>'admin_notes','onChange'=>'adminNotes(this,"'.$request_maintenance->id.'")'))!!} </td>

								</tr>



							  </tbody>
						 </table>
      </div>
    </div>
    <!--/span-->

    <span><h1 class="text-center">Service Request Details</h1></span>
			<button data-toggle="modal" data-target="#assign" class="btn btn-large btn-success" onclick="showMaintenanceServices('{!! $request_maintenance->id !!}')">Assign Vendor</button>
<?php
$totalPriceCustomer=0;
$totalPriceVendor=0;

?>
		@foreach ($request_maintenance->requestedService as $services)


                <div class="row-fluid">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon edit"></i><span class="break"></span>{!!$services->service->title!!}</h2>
						<div class="box-icon">
						 <?php
  $priceData=SpecialPrice::getSpecialCustomerPrice($request_maintenance->user->id,$services->service->id);
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
					<div class="box-content">

						<table>
                <tr><td> {!!$services->service->desc!!}</td></tr>
								<tr>
									<td class="center"><h2>Customer Note:</h2>{!!$services->service_note!!}</td>
								</tr>
                 <tr>
                 <td class="center span3"><h2>Note for Vendor:</h2>
                 {!!Form::textarea('public_notes',isset($services->public_notes) ? $services->public_notes : '' , array('class'=>'span12 typeahead', 'id'=>'public_notes','onChange'=>'publicNotes(this,"'.$services->id.'")'))!!}
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




</div>
@parent
@include('common.delete_alert')
@stop
