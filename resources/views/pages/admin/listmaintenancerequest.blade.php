@extends('layouts.default')

@section('content')


<title>Maintenance Requests</title>
<div id="content" class="span11">

    <div class='table-padding add-service-button-div'>
          <a class="btn btn-info accBtn" href="{!!URL::to('admin-add-new-service-request')!!}">Add Service Request </a>
    </div>





  <p id="message" style="display:none">Saved...</p>

  <div class="row-fluid">

    <div class="box span12 table-padding">

        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>

      </div>

                @if(Session::has('message'))

                            {!!Session::get('message')!!}

                        @endif



        <div class="box-content admtable">

        <div class="admtableInr">
        <div class='table-container'>
        <div class='table-responsive'>
        <table class="table table-striped table-bordered table-sm dt-responsive datatabledashboard" width='100%' cellspacing='0' id="maintenance_datatable">

          <thead>

            <tr>

              <th>ID #</th>

              <th>Submitter</th>
              <th>Client Type</th>
              <th>Customer Name</th>
              <th>Property Address</th>

              <th>City</th>

              <th>State</th>

              <th>Zip</th>

              <th>Job Type</th>

              <th>Services Type</th>

              <th>Due</th>

              <th>Status</th>

              <th>Action</th>

            </tr>

          </thead>

          <tbody>





          @foreach ($request_maintenance as $rm)

        <?php

               $request_service_ids=array();

             foreach ($rm->assignRequest as $rqdata) {

                $request_service_ids[] = $rqdata->status;

            }



            ?>

          @if($numberofrequestids['requested_services_count'][$rm->id]!=$numberofrequestids['assigned_services_count'][$rm->id])



        <tr>





            <td @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $rm->id !!}</td>

            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if (isset($rm->user2->first_name)) {!! $rm->user2->first_name !!} @endif @if(isset($rm->user2->last_name)) {!! substr($rm->user2->last_name,0,1)."." !!} @endif</td>
            <td @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->asset->customerType->title )) {!! $rm->asset->customerType->title  !!} @endif </td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user->first_name)) {!! $rm->user->first_name !!} @endif   @if(isset($rm->user->last_name)) {!! $rm->user->last_name !!}@endif</td>

            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->asset->property_address)) {!!  $rm->asset->property_address !!} @endif @if (isset($rm->asset->unit)) {!! $rm->asset->unit !!} @endif</td>







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












           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->asset->zip)) {!!  $rm->asset->zip !!} @endif</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
            @if(isset($rm->jobType->id))
                {!!$rm->jobType->title!!}
              @else
              {!!" "!!}
            @endif </td>




           <?php

$servicedate="";

$due_date="";

  foreach ($rm->requestedService as  $value) {

            if(isset( $value->service->title))

             $servicedate .=  $value->service->title  ;



            if(isset($value->due_date))

            {

                         $due_date ."<br>".    $value->due_date . ' <br>';



            }

            else

            {

           $due_date .=   ' <br>';





            }

             $servicedate .=   ' <br>';



            }

            ?>



  <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $servicedate !!} </td>

    <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
    @if(isset($due_date))
    {!! $due_date !!}
    @else
    {!!""!!}
    @endif </td>





            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>

            @if($rm->status==1 || $rm->status == 2 || $rm->status == 3)

            <span class="badge badge-summary badge-success">New Request</span>

            @elseif($rm->status==4)

            <span class="badge badge-summary badge-danger">Cancelled</span>

            @endif



           </td>



            <td class="center popover-examples" @if($rm->emergency_request==1) style="background-color:red;" @endif>
            <div class='action-button-group-3-wide'>

              <a class="btn btn-success btn-xs action-button"  @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View"> 
                  <i class="fa fa-search-plus" aria-hidden="true"></i>                              
              </a>

              <a class="btn btn-danger btn-xs action-button" href="cancel-maintenance-request/{!! $rm->id !!}" title="Cancel" onclick="return confirm('Are you sure you want to cancel?')"> 
                  <i class="fa fa-trash" aria-hidden="true"></i> 
              </a>

              <a class="btn btn-info btn-xs action-button"  @if($rm->status==4) disabled='disabled' @else href="{!!URL::to('admin-edit-service-request')!!}/{!! $rm->id !!}" @endif title="Edit"> 
                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </a>

            </div>





            </td>

          </tr>





           @elseif(in_array(1,$request_service_ids) )





            <tr>







            <td @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $rm->id !!}</td>

            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user2->first_name)) {!! $rm->user2->last_name !!} @endif</td>

             <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user->first_name)) {!! $rm->user->first_name !!} @endif   @if(isset($rm->user->last_name)) {!! $rm->user->last_name !!}@endif</td>



            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->user->email)) {!! $rm->user->email !!} @endif  </td>

  <?php



    $servicedate="";

  foreach ($rm->requestedService as  $value) {

            if(isset( $value->service->title))

             $servicedate .=  $value->service->title  ;



            if(isset($value->due_date))

            {

                         $servicedate .= "<br>".    $value->due_date . ' <br>';

            }

            else

            {

        $servicedate .=   ' <br>';





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



           <!--  <td  @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!$assignedUsers!!}</td> -->

           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->property_address !!}</td>

           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->city->name !!}</td>

           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->state->name !!}</td>

           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->zip !!}</td>
           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
            @if(isset($rm->jobType->id))
                {!!$rm->jobType->title!!}
              @else
              {!!" "!!}
            @endif </td>
           <?php

$servicedate="";

$due_date="";

  foreach ($rm->requestedService as  $value) {

            if(isset( $value->service->title))

             $servicedate .=  $value->service->title  ;



            if(isset($value->due_date))

            {

                         $due_date.=  $value->due_date . ' <br>';



            }

            else

            {



             $due_date .=   'Not Set <br>';





            }

               $servicedate .=   ' <br>';

            }

            ?>

          <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $servicedate !!} </td>

           <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>
    @if(isset($due_date))
    {!! $due_date !!}
    @else
    {!!""!!}
    @endif </td>


            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>

             @if($rm->status==4)

            <span class="badge badge-summary badge-danger">Cancelled</span>

            @else

             <span class="badge badge-summary" style="background-color: #4472C4;">Assigned</span>

            @endif

           </td>



            <td class="center popover-examples xtraCls" @if($rm->emergency_request==1) style="background-color:red;" @endif><a class="btn btn-success"  @if($rm->status==4) disabled='disabled' @else href="view-maintenance-request/{!! $rm->id !!}" @endif title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a><a class="btn btn-danger" href="cancel-maintenance-request/{!! $rm->id !!}" title="Cancel" data-confirm="Are you sure you want to Cancel?"> <i class="halflings-icon trash halflings-icon"></i> </a></td>

          </tr>







          @endif

          @endforeach

            </tbody>



        </table>

      </div>
      </div>

      </div>

      </div>

    </div>

    <!--/span-->



  </div>

  <!--/row-->

  <script>

  var db_table = "{!! $db_table !!}";

 </script>

</div>

@parent

@include('common.delete_alert')

@stop

