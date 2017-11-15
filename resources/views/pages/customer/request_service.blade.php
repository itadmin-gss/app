
<?php



$layout="";

if(Auth::user()->type_id==1 || Auth::user()->type_id == 4) {
    $layout="layouts.default";
}
else
    { $layout="layouts.customer_dashboard";}

$assets_data = array('' => 'Select Property');
$requested_asset_found = false;
foreach ($customer_assets as $asset) {

    $assets_data[$asset['id']] = $asset['asset_number'].' - '.$asset['property_address'];
    if ($asset['id'] == $property_id)
    {

        $requested_asset_found = true;
        //Get City/State Information
        $city = \App\City::where('id', $asset['city_id'])->get()[0];
        $city_name = $city->name;
        $state = \App\State::getStateByID($asset['state_id']);
        $client = \App\Asset::findOrFail($property_id);
        $client_type = $client->customer_type;
        break;
    }

}



?>

@extends($layout)
@section('content')

<title>GSS - Add Service Request</title>
<div id="content" class="span11">
    <h4>
        Service Request @if ($requested_asset_found) for {!! $asset['property_address'] !!}, {!! $city_name !!}, {!! $state !!} @endif
    </h4>
    <div class="row">


            <div class="col-md-8 col-lg-7 col-sm-12 custome-form ">
                @if (Session::has('message'))
                {!! Session::get('message') !!}
                @endif
                @if($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">{!! $error !!}</div>
                @endforeach
                @endif
                {!! Form::open(array('url' => 'create-service-request', 'class'=>'form-horizontal request-services')) !!}

                <div class="alert alert-error" style="display:none;" id="add_asset_alert"></div>
                <fieldset>



                        <div>
                            <ul class='step-list'>

                                <li>
                                    <div class='request-badges request-step-2'>
                                        <a href="javascript:void(0);" class='badge badge-info'>
                                            Step 1: Select Job Type
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <div class='request-badges request-step-3'>
                                        <a href="javascript:void(0);" class='badge badge-disable'>
                                            Step 2: Select Services
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <div class='request-badges request-step-4'>
                                        <a href="javascript:void(0);" class='badge badge-disable'>
                                            Step 3: Submit
                                        </a>
                                    </div>
                                </li>
                            </ul>

                            @if (!$requested_asset_found)
                                <h4>Asset chosen is set to 'Closed' status. Please change status before adding a work order</h4>

                            @else
                                {!! Form::select('asset_number',  $assets_data, $property_id, array('class'=>'form-control-no-group hide','id'=>'asset_number', 'data-rel'=>'chosen','style'=>'width:200px;'))!!}
                                {!! Form::hidden('bid_dropdown_hidden', '', array('class'=>'span7 typeahead','id'=>'bid_dropdown_hidden'))!!}
                                {!! Form::hidden('client_type_unic', $client_type, array('id' => 'client_type_unic')) !!}
                            <div class='step-2'>
                                <div class='form-group'>
                                    {!!Form::label('job_type', 'Job Type:', array('class'=>'no-class', 'id'=> 'asdasd'))!!}
                                <select name="job_type" class='form-control' id="job_type" onchange="loadServiceOnJobType()">
                                    <option value="">Select Job Type</option>

                                    <?php

                                    foreach ($jobType as $key => $value) {
                                    ?>
                                    <option value="{!!$value->id!!}">{!!$value->title!!}</option>

                                    <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>

                            <div class='step-3'>
                                <div>
                                    {!!Form::label('service_ids', 'Services Available:', array('class'=>'control-label', 'id'=> 'ssdfsdf selectError1'))!!}

                                    <div class="controls">
                                        {!!Form::select('service_ids_selected[]',$services, null, array('multiple'=>'true', 'id'=>'service_ids', 'class' => 'hidden-chosen', 'data-rel'=>'chosen') )!!}
                                    </div>
                                    <p class="addSt"> You can add multiple services</p>

                                </div>

                                <div id="bidrequest" class="bidrequest">
                                    <div class="row-fluid">
                                        <div class="span6 offset3 centered">
                                            <div class="control-group" style="display:none;"> {!! Form::label('typeahead', 'Service Code: *', array('class' => 'control-label')) !!}
                                                <div class="controls">
                                                    <div class="input-append"> 
                                                        {!! Form::text('service_code', '', array('class' => 'input-xlarge focused','id' => 'service_code')) !!}                                                     
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <center>
                                    <a href="javascript:void(0);" class="btn btn-info add-service-back-step-1">Back</a>
                                    <a href="javascript:void(0);" class="btn btn-success review-service-order" data-asset-id="{!! $property_id !!}">Review Order</a>
                                </center>

                                <div class="form-actions">
                                    <div class="pull-right">
                                        {!! Form::hidden('bid_flag', 0,array('id'=>'bid_flag')) !!}
                                        {!! Form::hidden('submitted', 1,array('id'=>'submitted')) !!}
                                        {{--  {!! Form::submit('Add Service', array('class' => 'btn btn-success btn-addbid')) !!}
                                        {!! Form::button('Cancel',array('class' => 'btn btn-large btn-info', 'onClick' =>'location.href="'.URL::to('list-services').'"')) !!}  --}}
                                    </div>
                                </div>

                            </div>
                            <div class='step-4'>

                                        <table>
                                            <tbody>
                                            <tr>
                                                <th>Job Type</th>
                                                <td id="review_order_job_type"> </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                <div id="order_review_serviceType"></div>




                            <center>
                                <a href="javascript:void(0);" class="btn btn-info add-service-back-step-2">Back</a>
                                <a href="#" class="btn btn-warning"  onclick="orderReivewsubmit()">Submit</a>
                                @if(Auth::user()->type_id==2)
                                    <a href="{!!URL::to('list-customer-requested-services')!!}" class="btn btn-success" id="cancelbuttoncustomer">Cancel</a>
                                @else
                                    <a href="{!!URL::to('asset/'.$property_id)!!}" class="btn btn-success" id="cancelbuttonadmin">Back To Asset</a>


                                @endif
                            </center>




                            </div>

                                @endif
                        </div>
                        
                    </div>


                            </div>









                    {!! Form::close() !!}





            <div id='allservices'></div>
            <div class="modal  hide fade modelForm larg-model"  id="showServiceid"> </div>


            <!--/myModa2-->
            @stop