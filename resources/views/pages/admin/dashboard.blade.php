@extends('layouts.default')
@section('content')


<div id="content" class="span11">



  <?php
  $unassigned=0;
  $assigned=0;
  $i=1;
  foreach ($requests as $rm) {
    $request_service_ids=array();
    foreach ($rm->assignRequest as $rqdata) {
        $request_service_ids[] = $rqdata->status;
    }
    if($numberofrequestids['requested_services_count'][$rm->id]!=$numberofrequestids['assigned_services_count'][$rm->id])
    {
        if($rm->status==2)
            $unassigned++;

        } else{

        if($rm->status==0 && in_array(1,$request_service_ids) )
        $assigned++;

    }

    }

          ?>


        @if(Session::has('message'))
            {!!Session::get('message')!!}
         @endif


        @if( isset($access_roles['Summary Grid']['view']) && $access_roles['Summary Grid']['view'] == 1)


        <div class="row">
            <div id="datatabledashboard">
                    <title>GSS - Summary</title>
        <div class='table-padding table-heading'>
            <div class="card blue-1">
                <div class="card-body">
                    <span>Summary</span>
                </div>
            </div>
        </div>

            <div class='table-responsive'>
            <table class="table table-striped table-bordered table-sm datatabledashboard" id='dataTable' cellspacing='0' >
        

          <thead>
            <tr>

              <th>ID #</th>
              <th>Submitter</th>
              <th>Client Type</th>
              <th>Customer</th>
              <th>Property Address</th>
              <th>City</th>
              <th>State</th>
              <th>Zip</th>
              <th>Service(s)</th>
              <th>Requested</th>
              <th>Due</th>



            </tr>

          </thead>
          <tbody>
          @if (!isset($grid))

          @foreach ($requestsNew as $rm)
            
          @if($numberofrequestids['requested_services_count'][$rm->id]!=$numberofrequestids['assigned_services_count'][$rm->id])

        <tr>

            

            <td @if($rm->emergency_request==1) style="background-color:red;" @endif>
                <a @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View">{!! $rm->id !!}</a>
            </td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user2->first_name)) {!! $rm->user2->first_name !!} {!! substr($rm->user2->last_name,0,1)."." !!} @endif</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->asset->customerType->title)) {!!  $rm->asset->customerType->title !!} @endif</td>
             <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user->first_name)) {!! $rm->user->first_name !!} @endif   @if(isset($rm->user->last_name)) {!! $rm->user->last_name !!}@endif</td>


           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->asset->property_address)) {!! $rm->asset->property_address !!} @endif @if (isset($rm->asset->unit)) #{!! $rm->asset->unit !!}@endif</td>



                    <?php if(isset($rm->asset->city->name)){?>
                                                 <td @if($rm->emergency_request==1) style="background-color:red;" @endif>  {!! $rm->asset->city->name !!}</td>
                                               <?php } else {?>
                                                <td></td>

                                                <?php } ?>

                                  <?php if(isset( $rm->asset->state->name)){?>
                                                  <td @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $rm->asset->state->name !!}</td>
                                             <?php   } else {?>
                                                <td></td>

                                                <?php } ?>




           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->asset->zip)) {!!  $rm->asset->zip !!} @endif </td>
           
           <?php
$servicedate="";
$due_date="";
  foreach ($rm->requestedService as  $value) {
            if(isset( $value->service->title))
            {
                $servicedate .=  $value->service->title;
            }
             

            if(isset($value->due_date))
            {
              $style="";
              if( (strtotime(date('m/d/Y'))>strtotime($due_date)))
                {
                $style='style="background-color:yellow;"';
                }
                         $due_date .= "<span ".$style."> ". $value->due_date . '</span> <br>';
            }
            else
            {

                $due_date .=   'Not Set<br>';



            }
 $servicedate .=   ' <br>';

            }
            ?>

  <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $servicedate !!} </td>
             <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!!date('m/d/Y',strtotime( $rm->created_at )) !!}</td>

   <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $due_date !!} </td>



          </tr>


          @elseif(1==2)

            <tr>


                <td @if($rm->emergency_request==1) style="background-color:red;" @endif>
                    <a @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View">{!! $rm->id !!}</a>
                </td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user2->first_name)) {!! $rm->user2->last_name !!} @endif</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!! $rm->user->first_name !!} {!! $rm->user->last_name !!}</td>

          <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!! $rm->user->email !!}</td>

  <?php

    $servicedate="";
  foreach ($rm->requestedService as  $value) {
            if(isset( $value->service->title))
             $servicedate .=  $value->service->title  ;

            if(isset($value->due_date))
            {
                         $servicedate .= "<br>".    $value->due_date . ', <br>';
            }
            else
            {
        $servicedate .=   ', <br>';


            }

            }
            $assignedUsers="";

            foreach ($rm->assignRequest as  $assignRequestData) {

              $servicetitle="";
              if(isset($assignRequestData->requestedService->service->title))
              {
                $servicetitle=$assignRequestData->requestedService->service->title;
              }

               $first_name="";
              if(isset($assignRequestData->user->first_name))
              {
                $first_name=$assignRequestData->user->first_name;
              }


               $last_name="";
       if(isset($assignRequestData->user->last_name))
              {
                $last_name=$assignRequestData->user->last_name;
              }
             $assignedUsers .= "Service :".   $servicetitle ." <br/>Vendor:". $first_name." ".$last_name."<br/>---------<br/>" ;
            }

            ?>

            <td  @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!$assignedUsers!!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->property_address !!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->city->name !!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->state->name !!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->zip !!}</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!!date('m/d/Y h:i:s A',strtotime( $rm->created_at )) ;!!} </td>
           <?php
$servicedate="";
  foreach ($rm->requestedService as  $value) {
            if(isset( $value->service->title))
             $servicedate .=  $value->service->title  ;

            if(isset($value->due_date))
            {
                         $servicedate .= "<br>".    $value->due_date . ', <br>';
            }
            else
            {
        $servicedate .=   ', <br>';


            }

            }
            ?>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
            @if(isset($rm->jobType->id))
                {!!$rm->jobType->title!!}

            @endif
            </td>
          <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $servicedate !!} </td>



          </tr>



          @endif
          @endforeach
            </tbody>
        @endif
        </table>
                   </div>
        </div>
        </div>
                                 </div>
                            </div>
                        </div><!--/span-->
                      </div>

@endif


                    </div>

        </div><!--/span-->

      </div><!--/row-->

      </div>

@stop