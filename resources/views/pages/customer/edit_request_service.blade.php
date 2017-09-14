
<?php 

$layout="";
if(Auth::user()->type_id==1) { 
$layout="layouts.default";
 }
else 
{ $layout="layouts.customer_dashboard";} ?>

@extends($layout)
@section('content')
<div id="content" class="span11">
    <div class="row-fluid">
        <div class="box span12 noMarginTop">
            <h1>Edit Service Request</h1>

            <div class="box-content custome-form requsrForm">
                @if (Session::has('message'))
                {!! Session::get('message') !!}
                @endif
                @if($errors->has())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">{!! $error !!}</div>
                @endforeach
                @endif
                {!! Form::open(array('url' => 'edit-service-request', 'class'=>'form-horizontal request-services')) !!}

                <div class="alert alert-error" style="display:none;" id="add_asset_alert"></div>
                <fieldset>
                    <div class="row-fluid">
                        <div class="span5 center">
                            <div class="control-group" >
                                {!!Form::label('asset_number', 'Property #:', array('class'=>'control-label'))!!}
                                <div class="controls"  style="width: 550px;" >
                                    <?php
                                    $assets_data = array('' => 'Select Property');
                                    foreach ($customer_assets as $asset) {
                                        $assets_data[$asset['id']] = $asset['asset_number'].' - '.$asset['property_address'];
                                    } 
                                    ?>
                                    {!! Form::select('asset_number',  $assets_data, $RequestedService->asset_id, array('class'=>'span7 typeahead','id'=>'asset_number', 'data-rel'=>'chosen'))!!}
                                    <!-- <a class="btn btn-small btn-success" style="cursor: pointer;" id="viewassets">Property Details</a>
                                  -->
                                  OR
                                 @if(Auth::user()->type_id==1)
                                          <a href="{!!URL::to('add-asset')!!}" class="btn btn-small btn-success" style="cursor: pointer;" >Add Property</a>
                                    @else
                                       <a href="{!!URL::to('add-new-customer-asset')!!}" class="btn btn-small btn-success" style="cursor: pointer;" >Add Property</a>
                                     @endif
                                </div>
                            </div>
                           
                            <div class="control-group dmCntr">

                                {!!Form::label('service_ids', 'Services:', array('class'=>'control-label', 'id'=> 'ssdfsdf selectError1'))!!}
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
                                <div class="controls">
                                    {!!Form::select('service_ids',$services_data,  $RequestedServicearray, array('multiple'=>'true', 'id'=>'service_ids', 'data-rel'=>'chosen') )!!}
<!--                                <select id="selectError1" multiple data-rel="chosen">
                                                                        <option>Option 1</option>
                                                                        <option selected>Option 2</option>
                                                                        <option>Option 3</option>
                                                                        <option>Option 4</option>
                                                                        <option>Option 5</option>
                                                                  </select>-->
                                 </div>
                            </div>

                                     <p class="addSt"> You can add multiple services</p> 
                                 

                             <div class="control-group">
                                {!!Form::label('emergency_request', 'Emergency Request:', array('class'=>'control-label', 'id'=> 'ssdfsdf selectError1'))!!}
                            
                                <div class="controls dmcntrl" style="margin-top: 6px;">
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

         <h3>List of Requested Services: </h3>
                              

<?php
 foreach($RequestedService->RequestedService as $RequestedServiceDATA)
                                {

                                    $data=(array)$RequestedServiceDATA;
                              
                                  //  print_r( $RequestedServiceDATA );
                                   $data['service_id']= $RequestedServiceDATA->service_id;
                                   $data['service_name']= $RequestedServiceDATA->service->title;
                                   
                                        ?>

     
                                 
     <div id="services_list_{!!$data['service_id']!!}" class="box span12 noMarginTop listSlide">
    <div class="box-header ">
        <h2>{!!$RequestedServiceDATA->service->title!!}</h2>

        <div class="box-icon">
            <a href="javascript:;" title="Delete" class="btn-minimize" onclick="DeleteServiceRequest('{!!$RequestedServiceDATA->id!!}','{!!$RequestedServiceDATA->service_id!!}','{!!$RequestedService->id!!}')"><i class="halflings-icon remove"></i></a>
        </div>
    </div>
    
      
                            {!!Form::hidden('service_ids_selected[]', $RequestedServiceDATA->service_id)!!}
       

    </div>



<?php

}
?>

     <div class="row-fluid sortable requestServices" id='list_of_services'>
                        <div class="box offset1 span10">
                   

                        </div>
                        <!--/span-6-->

                    </div>
                    <div class="row-fluid">
                        <div class="form-actions text-center">
                            {!!Form::hidden('request_id', $RequestedService->id)!!}
                            {!!Form::submit('Request', array('name'=>'request', 'class'=>'btn btn-large btn-success'))!!}
                                                                    	<a href="{!!URL::to('list-customer-requested-services')!!}" > <button type="button" class="btn btn-large btn-inverse">Cancel</button></a>
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