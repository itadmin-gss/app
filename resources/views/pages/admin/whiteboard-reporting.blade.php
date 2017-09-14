@extends('layouts.default')
@section('content')


<style type="text/css">
    .datatablegrid {display: block;}
    .datatablegrid2 {display: none;}
    .datatablegrid3 {display: none;}
    .datatablegrid4 {display: none;}

</style>
<div id="content" class="span11">
 <div id="mysearchReport" class="">
  <button type="button" class="close">x</button>
    <h1>Search Report</h1>

    {!! Form::open(array('url' => 'whiteboard-reporting', 'class' => 'form-horizontal', 'method' => 'post')) !!}
    <fieldset>
        <div class="row-fluid">
            <div class="span6 offset3 centered">
                <div class="control-group">
                    {!! Form::label('typeahead', 'Properties: ', array('class' => 'control-label')) !!}
                    <div class="controls">
                        <div class="input-append">

                            <?php
                            $assetData=array();
                            $assetData[""]="All";
                            foreach ($assets_data as  $assetProperty) {
                                $assetData[$assetProperty->id]=  $assetProperty->property_address;
                            }


                            $properties="";
                            if(isset($data['properties']))
                            {
                                   $properties=$data['properties'];
                            }

                            ?>
                            {!! Form::select('properties', $assetData,  $properties,
                            array('data-rel' => 'chosen')); !!}
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <?php

                    $serviceDataArray=array();
                    $serviceDataArray[""]="All";
                    foreach ($services as  $serviceDATA) {
                        $serviceDataArray[$serviceDATA->id]=  $serviceDATA->title;
                    }


                    ?>
                    {!! Form::label('typeahead', 'Service Type: *', array('class' => 'control-label')) !!}
                    <div class="controls">
                        <div class="input-append">
                        @if(!empty($data))
                         {!! Form::select('service_type[]', $serviceDataArray, $data['service_type'],
                         array('data-rel' => 'chosen','multiple'=>true)); !!}
                         @else
                           {!! Form::select('service_type[]', $serviceDataArray, '01',
                         array('data-rel' => 'chosen','multiple'=>true)); !!}
                         @endif
                     </div>
                 </div>
             </div>
             <div class="control-group">
                {!! Form::label('typeahead', 'Order Status: *', array('class' => 'control-label')) !!}
                <div class="controls">
                    <div class="input-append">
                        <?php

                        $orderStatus[""]="All";
                        $orderStatus[1]="In-Process";
                        $orderStatus[2]="Completed";
                        $orderStatus[3]="Under Review";
                        $orderStatus[4]="Approved";
                        $orderStatus[6]="Cancelled";

                                 $order_status_post="";
                            if(isset($data['order_staus']))
                            {
                                   $order_status_post=$data['order_staus'];
                            }
                           
                        ?>
                        {!! Form::select('order_staus', $orderStatus, $order_status_post,
                        array('data-rel' => 'chosen')); !!}
                    </div>
                </div>
            </div>
            
            <div class="control-group">
                {!! Form::label('typeahead', 'Date From: ', array('class' => 'control-label')) !!}
                <div class="controls">
                    <div class="input-append">
                      {!! Form::text('date_from', '', array('class' => 'datepicker  input-xlarge focused',
                      'id' => 'date_from')) !!}

                  </div>
              </div>
          </div>

          <div class="control-group">
            {!! Form::label('typeahead', 'Date To: ', array('class' => 'control-label')) !!}
            <div class="controls">
                <div class="input-append">
                  {!! Form::text('date_to', '', array('class' => 'datepicker  input-xlarge focused',
                  'id' => 'date_to')) !!}

              </div>
          </div>
      </div>

  </div>
</div>
<div class="">

    <div class="pull-right">
    <a class="btn btn-info " href="{!!URL::to('reporting')!!}">Reset </a>
       {!! Form::submit('Generate', array('class' => 'btn btn-large btn-success')) !!}


   </div>
</div>
</fieldset>
{!!Form::close()!!}
</div>

<div class="row-fluid">
    <div class="box span12">
        <div class="box-header datatablegrid" data-original-title>
            <h2><i class="halflings-icon th-list"></i><span class="break"></span>Orders</h2>
            <div class="box-icon">
                <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
            </div>
        </div>




        <div class="box-content datatablegrid admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                      <table class="table table-striped table-bordered bootstrap-datatable datatable">

<!--                    <label> Select Date Range </label>

                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">

                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>

                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>

                      </div>-->

                      <thead>

                        <tr>

                         <th>Client type</th>
                         <th>Customer Name</th>
                         <th>Property Address</th>
                           <th>Unit</th>
                         <th>City</th>

                         <th>State</th>

                         <th>Zip</th>
                          <th>Service</th>
                           <th>Order Status</th>
                          <th>Vendor Name</th>
                           <th>Due Date</th>

                         <th>Completion Date</th>

                       
                       </tr>

                     </thead>

                     <tbody>



                      {{--*/ $loop = 1 /*--}}

                      @foreach ($orders as $order)

                      <tr>

                        <td>{!! $order['clientType']!!}</td>
                        <td class="center">{!! $order['customer_name'] !!}</td>
                         <td class="center">{!! $order['property_address'] !!}</td>
                            <td class="center">{!! $order['unit'] !!}</td>
                        <td class="center">{!! $order['city'] !!}</td>

                        <td class="center">{!! $order['state'] !!}</td>

                        <td class="center">{!! $order['zipcode'] !!}</td>
                        <td class="center">{!! $order['service_name'] !!}</td>


                          @if($order['request_status']==4)

                        <td class="center"> <span class="label label-important">Cancelled</span> </td>



                        @else

                        <td class="center"> <span class="label label-{!! $order['status_class'] !!}">{!! $order['status_text'] !!}</span> </td>



                        @endif



                      
                        <td class="center">{!! $order['vendor_name'] !!}</td>
                          <td class="center">{!! $order['due_date'] !!}</td>

                        <td class="center">{!! $order['completion_date'] !!}</td>

                      
                      





                      

                      </tr>

                      {{--*/ $loop++ /*--}}

                      @endforeach

                    </tbody>

                  </table>

</div>
</div>

                                    
                                  

                                   


                                    </div><!--/row-->

                            </div>



                            @stop