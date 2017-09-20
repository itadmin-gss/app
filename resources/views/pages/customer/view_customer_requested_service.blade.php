@extends('layouts.default')
@section('content')
<div id="content" class="span11">
<?php if($request_detail->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>
    <h1 class="text-center">Service Request</h1>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2>Service Request Details</h2>
            </div>
            <div class="box-content">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="center span3"><h2>Request ID:</h2></td>
                            <td class="center span3"><h2>{!!$request_detail->id!!}</h2></td>
                            <td class="center span3"><h2>Request Date:</h2></td>
                            <td class="center span3"><h2>25/6/2013</h2></td>
                        </tr>
                        <tr>
                            <td class="center span3"><h2>Property #:</h2></td>
                            <td class="center span3"><h2>{!!$request_detail->asset->asset_number!!} <button class="btn btn-small btn-success" data-target="#showServiceid" data-toggle="modal">View Property</button></h2></td>
                            <td class="center span3"><h2>Customer Name:</h2></td>
                            <td class="center span3"><h2>{!!$request_detail->user->first_name!!} {!!$request_detail->user->last_name!!}</h2></td>
                        </tr>
                        <tr>
                            <td class="center span3"><h2>City:</h2></td>
                            <td class="center span3"><h2>{!!$request_detail->asset->city->name!!}</h2></td>
                            <td class="center span3"><h2>State:</h2></td>
                            <td class="center span3"><h2>{!!$request_detail->asset->state->name!!}</h2></td>
                        </tr>
                        <tr>
                            <td class="center span3"><h2>Zip:</h2></td>
                            <td class="center span3"><h2>{!!$request_detail->asset->zip!!}</h2></td>
                            <td class="center span3"><h2>Email:</h2></td>
                            <td class="center span3"><h2>{!!$request_detail->user->email!!}</h2></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div><!--/span-->
    </div><!--/row-->

    <span><h1 class="text-center">Requested Services</h1></span>
<?php
  $totalPrice=0;
?>
    @foreach($request_detail->requestedService as $services)
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>{!!$services->service->title!!}</h2>
                <div class="box-icon">
                <?php
  $priceData=\App\SpecialPrice::getSpecialCustomerPrice($request_detail->user->id,$services->service->id);
   $servicePrice="";



 if(!empty($priceData) )
    {
         if($services->quantity=="" || $services->quantity==0)
         	{
          $servicePrice=$priceData->special_price;
        $totalPrice += $priceData->special_price;
            }
             else
             {
             	      $servicePrice=$priceData->special_price*$services->quantity ;
          $totalPrice += $priceData->special_price*$services->quantity ;

             }





    }
    else {




            if($services->quantity=="" || $services->quantity==0)
         	{
             $servicePrice=$services->service->customer_price;
          $totalPrice += $services->service->customer_price;
            }
             else
             {
             	      $servicePrice=$services->service->customer_price*$services->quantity ;
          $totalPrice += $services->service->customer_price*$services->quantity ;

             }









         }






    ?>
                Price :  ${!!$servicePrice!!}
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>

                </div>
            </div>
            <div class="box-content">
                <table>
                 <tr><td> {!!$services->service->desc!!}</td></tr>
                    @if($services->service_note != '')
                    <tr>
                        <td class="center"><h2>Customer Note:</h2>{!!$services->service_note!!}</td>
                    </tr>
                    @endif
                    <tr>
                    <div class="row-fluid browse-sec">
                        <h2>Images</h2>
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
                            ?> @endforeach


                        </ul>
                    </div>
                    </tr>
                </table>
            </div>
        </div><!--/span-->

    </div><!--/row-->
    @endforeach
   <div style="float:right;"> <h2>Total Price : ${!!$totalPrice!!}</h2></div>

</div>

<div class="modal  hide fade modelForm larg-model"  id="showServiceid">
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
                                                <label class="control-label" for="typeahead">Property Number:</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->asset_number!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Property Address:</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->property_address!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">City: </label>
                                                <label class="control-label" for="typeahead"> {!!$request_detail->asset->city->name!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">State:</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->state->name!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Zip :</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->zip!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Lockbox </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->lock_box!!}</label>
                                            </div>

                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Get / Access Code: </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->access_code!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Property Status: </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->property_status!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Customer Email Adress: </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->customer_email_address!!}</label>
                                            </div>
                                        </div>
                                        <!--/span-6-->

                                        <div class="span6">
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Loan Number:</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->loan_number!!} </label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="selectError3">Property Type:</label>
                                                <label class="control-label" for="selectError3">{!!$request_detail->asset->property_type!!}</label>
                                            </div>


                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Agent : </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->agent!!}</label>
                                            </div>




                                            <div class="clearfix"></div>
                                        </div>
                                        <!--/span-6-->

                                    </div>
                                    <div class="row-fluid">


                                        <br>
                                        <div class="control-group">
                                            <label class="control-label">Outbuilding / Shed *</label>
                                            <label class="control-label">{!!isset($request_detail->asset->outbuilding_shed) && $request_detail->asset->outbuilding_shed == 1 ? 'Yes': 'No'!!}</label>
                                            <div style="clear:both"></div>
                                            <div class="control-group hidden-phone">
                                                <div class="controls">
                                                    <label class="control-label" for="textarea2">Note:</label>
                                                    <label class="control-label label-auto" for="textarea2">{!!$request_detail->asset->outbuilding_shed_note!!}</label>
                                                </div>
                                            </div>
                                            <div class="control-group hidden-phone">
                                                <div class="controls">
                                                    <label class="control-label" for="textarea2">Directions or Special Notes:</label>
                                                    <label class="control-label label-auto" for="textarea2">This is directions or special notes</label>
                                                </div>
                                            </div>
                                        </div>
                                        <h4>Utility - On inside Property?</h4>
                                        <div class="control-group">
                                            <label class="control-label">Electric :</label>
                                            <label class="control-label">{!!isset($request_detail->asset->electric_status) && $request_detail->asset->electric_status == 1 ? 'Yes': 'No'!!}</label>
                                            <div style="clear:both"></div>

                                            <div class="control-group">
                                                <label class="control-label">Water *</label>
                                                <label class="control-label">{!!isset($request_detail->asset->water_status) && $request_detail->asset->water_status == 1 ? 'Yes': 'No'!!}</label>
                                            </div>

                                                <div class="control-group">
                                                    <label class="control-label">Gas *</label>
                                                    <label class="control-label">{!!isset($request_detail->asset->gas_status) && $request_detail->asset->gas_status == 1 ? 'Yes': 'No'!!}</label>
                                                </div>
                                                    <div class="control-group multiRadio">
                                                        <label class="control-label">Swimming *</label>
                                                        <label class="control-label">{!!$request_detail->asset->swimming_pool!!}</label>

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
                                    @stop