
<?php

$layout="";
if(Auth::user()->type_id==1 || Auth::user()->type_id == 4) {
$layout="layouts.default";
 }
else
{ $layout="layouts.customer_dashboard";} ?>

@extends($layout)
@section('content')
<title>GSS - Edit Service Request</title>
<div id="content" class="span11">
    <h4>Edit Service Request</h4>

            <div class="box-content custome-form requsrForm">
                @if (Session::has('message'))
                {!! Session::get('message') !!}
                @endif
                @if($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">{!! $error !!}</div>
                @endforeach
                @endif
                {!! Form::open(array('url' => 'edit-service-request', 'class'=>'form-horizontal request-services')) !!}
            </div>
            <div class="alert alert-error" style="display:none;" id="add_asset_alert"></div>
            <div class="row small-left-margin">
                <div class="col-md-4 col-lg-4 col-sm-12">
                
                    <div class="form-inline">
                        <span class='span-inline'>
                            <span style="margin-right:5px;">Property #:</span>
                        <?php
                            
                            $assets_data = array('' => 'Select Property');
                            foreach ($customer_assets as $asset) {
                                $assets_data[$asset['id']] = $asset['asset_number'].' - '.$asset['property_address'];
                            }
                            ?>
                            {!! Form::select('asset_number',  $assets_data, $RequestedService->asset_id, array('class'=>'chosen','id'=>'asset_number'))!!}
                            
                            <span style="margin-right:5px;margin-left:5px;">OR</span>
                            @if(Auth::user()->type_id==1 || Auth::user()->type_id == 4)
                                <a href="{!!URL::to('add-asset')!!}" class="btn btn-sm btn-success" style="cursor: pointer;" >Add Property</a>
                            @else
                                <a href="{!!URL::to('add-new-customer-asset')!!}" class="btn btn-sm btn-success" style="cursor: pointer;" >Add Property</a>
                            @endif
                        </span>
                    </div>

                    {!!Form::label('service_ids', 'Services:', array('class'=>'control-label', 'id'=> 'selectError1'))!!}
                    <div class="form-group">
                        <?php
                            $services_data=array();
                            foreach ($services as $service) {
                                $services_data[$service['id']] = $service['title'];
                            }
                            $RequestedServicearray=array();
                            foreach($RequestedService->RequestedService as $RequestedServiceDATA)
                            {
                                $RequestedServicearray[]=$RequestedServiceDATA->service->id;
                            }
                        ?>

                        {!!Form::select('service_ids',$services_data,  $RequestedServicearray, array('multiple'=>'true', 'id'=>'service_ids', 'class'=>'chosen') )!!}
                        <p class="addSt"> You can add multiple services</p>
                    </div>

                    {!!Form::label('emergency_request', 'Emergency Request:', array('class'=>'control-label', 'id'=> 'ssdfsdf selectError1'))!!}

                    <div class="form-group">
                    @if($RequestedService->emergency_request==1)
                        Yes :  {!!Form::radio('emergency_request','1',1,array('disabled'))!!}
                        @else
                        Yes :  {!!Form::radio('emergency_request','1',array('disabled'))!!}
                        @endif


                        @if($RequestedService->emergency_request==0)
                        No  : {!!Form::radio('emergency_request','0','1',array('disabled'))!!}
                        @else
                        No  : {!!Form::radio('emergency_request','0',array('disabled'))!!}
                        @endif
                    </div>
                </div>

            </div>


                         <div style="float: left;display:none;" id="emergency_request_additional_text">
                            Reminder: Emergency service is to protect individual, property, or neighboring property. Work order will be handled on a rush basis.Additional fee may apply for this service.
                        </div>
                        <!--/span-6-->
                    </div>

                    <div class="row-fluid">

         <h5>List of Requested Services: </h5>


<?php
 foreach($RequestedService->RequestedService as $RequestedServiceDATA)
                                {

                                    $data=(array)$RequestedServiceDATA;

                                  //  print_r( $RequestedServiceDATA );
                                   $data['service_id']= $RequestedServiceDATA->service_id;
                                   $data['service_name']= $RequestedServiceDATA->service->title;

                                        ?>


<div class="row">

    <div id="services_list_{!!$data['service_id']!!}" class="col-md-4 col-lg-4 col-sm-12 small-left-margin">
        <div class="card">
            <div class="card-header card-header-thin card-header-inline">
                <p>{!!$RequestedServiceDATA->service->title!!}</p>
                <a href="javascript:;" title="Delete" class="btn-minimize" onclick="DeleteServiceRequest('{!!$RequestedServiceDATA->id!!}','{!!$RequestedServiceDATA->service_id!!}','{!!$RequestedService->id!!}')"><i class="fa fa-trash"></i></a>
            </div>
            <div class="card-icon">
            </div>
        </div>

        {!!Form::hidden('service_ids_selected[]', $RequestedServiceDATA->service_id)!!}

    </div>
</div>





<?php

}
?>

<div class="row">
    <div class="col-md-4 col-lg-4 col-sm-12">
        {!!Form::hidden('request_id', $RequestedService->id)!!}
        {!!Form::submit('Request', array('name'=>'request', 'class'=>'btn btn-large btn-success'))!!}
        <a href="{!!URL::to('list-customer-requested-services')!!}" > <button type="button" class="btn btn-large btn-inverse">Cancel</button></a>
    </div>
</div>
     <div class="row-fluid sortable requestServices" id='list_of_services'>
                        <div class="box offset1 span10">


                        </div>
                        <!--/span-6-->

                    </div>
                    <div class="row-fluid">
                        <div class="form-actions text-center">

                        </div>
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