@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <div class="row-fluid">
        <div class="box span12 noMarginTop">
            <h1>OSR</h1>
            <div class="box-content custome-form requsrForm">
                @if (Session::has('message'))
                {!! Session::get('message') !!}
                @endif
                @if($errors->has())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">{!! $error !!}</div>
                @endforeach
                @endif
                {!! Form::open(array('url' => 'admin-create-bid-service-request', 'class'=>'form-horizontal request-services-vendor')) !!}

                <div class="alert alert-error" style="display:none;" id="add_asset_alert"></div>
                <fieldset>
                    <div class="row-fluid">
                        <div class="span5 center">
                           <div class="control-group">
                                {!!Form::label('work_order', 'Select Work Order: *', array('class'=>'control-label'))!!}


                                <div class="controls">
                                 {!! Form::select('work_order',  $order_ids , '', array('class'=>'span8 typeahead','id'=>'work_order', 'data-rel'=>'chosen'))!!}
                               </div>
                            </div>

                                  <div class="control-group">
                                {!!Form::label('job_type', 'Job Type:', array('class'=>'control-label', 'id'=> 'asdasd'))!!}
                               <select name="job_type" id="job_type" >
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


                            <div class="control-group">
                                {!!Form::label('vendor_service_ids', 'Services:', array('class'=>'control-label', 'id'=> 'ssdfsdf selectError1'))!!}
                                <?php
                                foreach ($services as $service) {
                                    $services_data[$service['id']] = $service['title'];
                                }
                                ?>
                                <div class="controls">
                                    {!!Form::select('vendor_service_ids',$services_data, null, array('multiple'=>'true', 'id'=>'vendor_service_ids', 'data-rel'=>'chosen') )!!}
<!--                                <select id="selectError1" multiple data-rel="chosen">
                                                                        <option>Option 1</option>
                                                                        <option selected>Option 2</option>
                                                                        <option>Option 3</option>
                                                                        <option>Option 4</option>
                                                                        <option>Option 5</option>
                                                                  </select>-->
                                </div>
                            </div>
                        </div>
                        <!--/span-6-->
                    </div>

                    <div class="row-fluid">

                        <div class="box offset1 span10">
                            <h3>List of Requested Services: </h3>


                            <fieldset>
                                <div class="row-fluid sortable requestServices" id='list_of_services'></div>
                            </fieldset>




                        </div>
                        <!--/span-6-->

                    </div>
                    <div class="row-fluid">
                        <div class="form-actions text-center">
                            {!!Form::submit('Request', array('name'=>'request', 'class'=>'btn btn-large btn-success'))!!}
                            <button type="button" class="btn btn-large btn-inverse">Cancel</button>
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