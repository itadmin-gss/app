
<?php

$layout="";

if(Auth::user()->type_id==1 || Auth::user()->type_id == 4) {
    $layout="layouts.default";
}
else
    { $layout="layouts.customer_dashboard";} ?>

@extends($layout)
@section('content')

<div id="content" class="span11">
    <div class="row-fluid">
        <div class="box span12 noMarginTop">
            <h1>Service Request</h1>
            <div class="box-content custome-form requsrForm">
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

                    <div class='card new-service-request'>
                        <div class='card-header'>
                            <h3>Service Request</h3>
                        </div>
                        <div class='card-body'>
                            <ul class='step-list'>
                                <li>
                                    <h4>
                                        <a href="javascript:void(0);" class="badge badge-info">
                                            Step 2: Choose Property
                                        </a>
                                    </h4>
                                </li>
                                <li>
                                    <h4>
                                        <a href="javascript:void(0);" class='badge badge-disable'>
                                            Step 3: Select Job Type
                                        </a>
                                    </h4>
                                </li>
                                <li>
                                    <h4>
                                        <a href="javascript:void(0);" class='badge badge-disable' id="step4click">
                                            Step 4: Select Services
                                        </a>
                                    </h4>
                                </li>
                                <li>
                                    <h4>
                                        <a href="javascript:void(0);" class='badge badge-disable'>
                                            Step 5: Submit
                                        </a>
                                    </h4>
                                </li>
                            </ul>

                            <div class='step-1'>
                                <ul class='property-list'>
                                    <li>Property #: </li>
                                    <li>
                                        
                                        <?php
                                            $assets_data = array('' => 'Select Property');
                                            foreach ($customer_assets as $asset) {
                                                $assets_data[$asset['id']] = $asset['asset_number'].' - '.$asset['property_address'];
                                            }
                                        ?>
                                        {!! Form::select('asset_number',  $assets_data, '', array('class'=>'form-control-no-group chosen','id'=>'asset_number', 'data-rel'=>'chosen','style'=>'width:200px;'))!!}
                                        
                                    </li>
                                    <li>
                                        
                                        <span> OR</span>
                                        @if(Auth::user()->type_id==1 || Auth::user()->type_id == 4)
                                        <a href="{!!URL::to('add-asset')!!}/1" class="btn btn-small btn-success" style="cursor: pointer;" >Add Property</a>
                                        @else
                                        <a href="{!!URL::to('add-new-customer-asset')!!}/1" class="btn btn-small btn-success" style="cursor: pointer;" >Add Property</a>
                                        @endif
                                    
                                    </li>
                                </ul>
                                {!! Form::hidden('bid_dropdown_hidden', '', array('class'=>'span7 typeahead','id'=>'bid_dropdown_hidden'))!!}
                            </div>

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
                                <div class="clearfix">
                                        {!!Form::label('service_ids', 'Services:', array('class'=>'control-label', 'id'=> 'ssdfsdf selectError1'))!!}

                                        <div class="controls">
                                            {!!Form::select('service_ids',$services, null, array('multiple'=>'true', 'id'=>'service_ids', 'data-rel'=>'chosen') )!!}

                                        </div>
                                        <p class="addSt"> You can add multiple services</p>

                                </div>

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
                                        {!! Form::hidden('submitted', 1,array('id'=>'submitted')) !!}
                                    {!! Form::submit('Add Service', array('class' => 'btn-success btn-addbid')) !!}
                                    {!! Form::button('Cancel',array('class' => 'btn btn-large btn-info',
                                            'onClick' =>'location.href="'.URL::to('list-services').'"')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class='step-4'>
                                <a href="javascript:;" class="btn btn-success nxtStep" data-src="#tabCntrl3">Next Step</a>
                            </div>


                           
                        </div>
                        
                    </div>


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
                    <th id="review_order_property"></th>
                </tr>
                <tr>
                    <td>Job Type </td>
                    <td id="review_order_job_type"> </td>
                </tr>
            </tbody>
        </table>

        <div id="order_review_serviceType">



        </div>
        <table>
            <tbody>

                <tr>
                    <td colspan="2" class="alignCntr">
                        <a href="#" class="edtBtn btn-primary">Edit</a>
                        <a href="#" class="sbmtBtn btn-warning"  onclick="orderReivewsubmit()">Submit</a>
                        @if(Auth::user()->type_id==2)
                                 <a href="{!!URL::to('list-customer-requested-services')!!}" class="cnclBtn btn-success" id="cancelbuttoncustomer">Cancel</a>
                                        @else
                                       <a href="{!!URL::to('list-maintenance-request')!!}" class="cnclBtn btn-success" id="cancelbuttonadmin">Cancel</a>


                                        @endif

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
                            </div>


                            <div id="tabCntrl4" class="control-group">

                                <div class="clearfix">
                                    <div class="clearfix emrgnGrp" style="display:none;">
                                        {!!Form::label('emergency_request', 'Emergency Request:', array('class'=>'control-label', 'id'=> 'ssdfsdf selectError1'))!!}
                                        <div class="controls dmcntrl">
                                            Yes :  {!!Form::radio('emergency_request','1')!!}
                                            No  : {!!Form::radio('emergency_request','0','1')!!}
                                        </div>
                                    </div>
                                    <div style="display:none;" id="emergency_request_additional_text">
                                        Reminder: Emergency service is to protect individual, property, or neighboring property. Work order will be handled on a rush basis.Additional fee may apply for this service.
                                    </div>
                                    <h3>List of Requested Services: </h3>
                                    <fieldset>
                                        <div class="row-fluid sortable requestServices" id='list_of_services'></div>
                                    </fieldset>
                                </div><!--/span-6-->

                                <div class="form-actions text-center">
                               <!--      <div class="bnchBtn">
        <a href="javascript:;" class=" btn-primary" id="editRevieworder" style="padding:20px;">Review Order</a>
    </div>                          <div style="display:none;">
                                    {!!Form::button('Bid Request', array('name'=>'bid',  'id'=>'bidRequest' , 'class'=>'btn btn-large btn-success','style'=>"display:none;"))!!}
                                    {!!Form::submit('Request', array('name'=>'request', 'class'=>'btn btn-large btn-success',  'id'=>'RequestType','style'=>"display:none;"))!!}
                                        @if(Auth::user()->type_id==2)
                                    <a href="{!!URL::to('list-customer-requested-services')!!}" > <button type="button" class="btn btn-large btn-inverse">Cancel</button></a>
                                        @else
                                        <a href="{!!URL::to('list-maintenance-request')!!}" > <button type="button" class="btn btn-large btn-inverse">Cancel</button></a>

                                        @endif
                                    </div></div> -->
                            </div>

                        </div><!-- CLEARFIX END HERE -->

                    </div>

                    </fieldset>
                    {!! Form::close() !!}
                </div>
                    </div><!--/span-->
                </div><!--/row-->
            </div>




            <div id='allservices'></div>
            <div class="modal  hide fade modelForm larg-model"  id="showServiceid"> </div>


            <!--/myModa2-->
            @stop