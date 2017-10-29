@extends('layouts.default')
@section('content')

<div id="content" class="span11">
 
  <div class="row-fluid">
    <div class="box span12">
      <div class="box-header" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Recurring</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>
     
      <div class="box-content">
          <div id="access-error" class="hide">
              <div class="alert alert-error">Warning! Access Denied</h4>
          </div>
          <div id="delete-success" class="hide">
              <div class="alert alert-success">Success! Delete Successful
              @if(Session::has('message'))
                            {!!Session::get('message')!!}
                        @endif</h4>
          </div>
          <div id="delete-error" class="hide">
              <div class="alert alert-error">Warning! Access Denied</h4>
          </div>
           <div class="box-content admtable listCstm">
            <div class="admtableInr">

        <table class="table table-striped table-bordered bootstrap-datatable datatable">
<!--          <label> Select Date Range </label>
          <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b> </div>-->
          <thead>
            <tr>

              <th>S.no</th>
              
              <th>Email</th>
              <th>Phone</th>
              <th>Services</th>
              <th>Property Address</th> 
               <th>Property status</th> 
              <th>Duration</th>
              <th>Next Due Date</th>
              <th>Created At</th> 
         
            </tr>
          </thead>
          <tbody>
          
            @foreach ($recurrings  as $recurring)

                 <?php
                 if($recurring->requestedService->maintenanceRequest->asset->property_dead_status==1)
            {
              continue;
            }
                 
    if((isset($recurring->requestedService->maintenanceRequest->asset->user->id)) && ($recurring->requestedService->maintenanceRequest->asset->user->id==Auth::user()->id))
{

    ?>
  
            <tr id="tr-{!!$recurring->id!!}">
              <td class="center">{!!$recurring->id!!}</td>
              @if(isset($recurring->requestedService->maintenanceRequest->asset->user->first_name))
              
              <td>{!! $recurring->requestedService->maintenanceRequest->asset->user->first_name!!} {!! $recurring->requestedService->maintenanceRequest->asset->user->last_name!!}</td>
              @else
                <td> </td>
              
              @endif

              @if(isset($recurring->requestedService->maintenanceRequest->asset->user->email))
              
              <td>{!!$recurring->requestedService->maintenanceRequest->asset->user->email!!}</td>
              @else
                <td> </td>
              
              @endif

              @if(isset($recurring->user->phone))
                
              <td>{!! $recurring->user->phone !!}</td>
              @else
                <td> </td>
              
              @endif  
              @if(isset($recurring->requestedService->service->title))
                 
              <td>{!!$recurring->requestedService->service->title!!}</td>
              @else
                <td> </td>
            @endif

             @if(isset($recurring->requestedService->maintenanceRequest->asset->property_address))
                 
              <td>{!!$recurring->requestedService->maintenanceRequest->asset->property_address!!}</td>
              @else
                <td><p>its not working</p></td>
            @endif


                @if(isset($recurring->requestedService->maintenanceRequest->asset->property_status))
                 
              <td>{!!$recurring->requestedService->maintenanceRequest->asset->property_status!!}</td>
              @else
                <td><p>its not working</p></td>
            @endif
              <td class="center">{!! $recurring->duration!!} days</td>

              <td class="center">

             <?php
              $date=date_create($recurring->start_date );
              date_add($date,date_interval_create_from_date_string("$recurring->duration days"));
              $nextDate= date_format($date,"Y-m-d");
              ?>
              {!! date('m/d/Y ',strtotime($nextDate))!!}</td>
              <td class="center">{!! date('m/d/Y ',strtotime($recurring->start_date ))!!}</td>
              </tr>
      <?php
              }
    ?>
                 @endforeach
          </tbody>
        </table> </div></div>
      </div>
    </div>
    <!--/span-->
      
  </div>
  <!--/row-->
</div>

@stop