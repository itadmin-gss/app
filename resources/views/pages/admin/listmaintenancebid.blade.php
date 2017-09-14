@extends('layouts.defaultorange')

@section('content')



<div id="content" class="span11">

  <div class="clearfix">

      <a class="btn btn-info accBtn" href="{!!URL::to('admin-add-new-service-request')!!}">Add Service Bid </a>

  </div>



  <p id="message" style="display:none">Saved...</p>
  
     @if(Session::has('message'))
<div class="messageAlert">
    {!!Session::get('message')!!}
 </div>
    @endif
   

  <div class="row-fluid">

    <div class="box span12">

      <div class="box-header" data-original-title>

        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Service Bidding List</h2>

        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>

      </div>

             

 

        <div class="box-content admtable">

        <div class="admtableInr">

        <table class="table table-striped table-bordered bootstrap-datatable datatabledashboard">

          <thead>

            <tr>

              <th>Request ID</th>

              <th>Submitted By</th>
              <th>Client Type</th>

              <th>Customer Name</th>

              <th>Customer Email</th>


              <th>Property Address</th>

              <th>City</th>

              <th>State</th>

              <th>Zip</th>

              <th>Job Type</th>

              <th>Services Type</th>

              <th>Due Date</th>

              <th>Status</th>

              <th>Action</th>

            </tr>

          </thead>

          <tbody>

          

          {{--*/ $loop = 1 /*--}}

          @foreach ($request_maintenance as $rm)

   

        

         

        <tr>

                

           

            <td @if($rm->emergency_request==1) style="background-color:red;" @endif> {!! $rm->id !!}</td>

            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user2->last_name)) {!! $rm->user2->last_name !!} @endif</td>

                        <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->asset->customerType->title )) {!!  $rm->asset->customerType->title !!} @endif</td>
            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif> @if(isset($rm->user->first_name)) {!! $rm->user->first_name !!} @endif   @if(isset($rm->user->last_name)) {!! $rm->user->last_name !!}@endif</td>

            <td @if($rm->emergency_request==1) style="background-color:red;" @endif>@if(isset($rm->user->email)) {!! $rm->user->email !!} @endif </td>



            <td class="center" @if($rm->emergency_request==1) style="background-color:red;" @endif>{!!  $rm->asset->property_address !!}</td>

  





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

                         $due_date .= "<br>".    $value->due_date . ' <br>';

                          

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


         @if($rm->status==1)

            <span class="label label-newbidrequest">New Bid Request</span>

            @elseif($rm->status==2)

            <span class="label label-awaitingvendorbid">Awaiting Vendor Bid</span>

            @elseif($rm->status==3)

            <span class="label label-completedvendorbid">Completed Vendor Bid</span>

            @elseif($rm->status==4)

            <span class="label label-newworkordergenerated">New Work Order Generated</span>
              @elseif($rm->status==5)

            <span class="label label-cancel">Cancel</span>
            @elseif($rm->status==6)

            <span class="label label-awaitingcustomerapproval">Awaiting Customer Approval</span>
            @elseif($rm->status==7)

            <span class="label label-declinedbid">Declined</span>
              @elseif($rm->status==8)

            <span class="label label-approvedbid">Approved Bid</span>
            
            @endif

          



           </td>

     

            <td class="center popover-examples" @if($rm->emergency_request==1) style="background-color:red;" @endif>

            <a class="btn btn-success"  @if($rm->status==4) disabled='disabled' @else href="view-bidding-request/{!! $rm->id !!}" @endif title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a>

            <a class="btn btn-danger" href="cancel-bidding-request/{!! $rm->id !!}" title="Cancel" onclick="return confirm('Are you sure you want to cancel?')"> <i class="halflings-icon trash halflings-icon"></i> </a>

<!--             <a class="btn btn-danger" href="delete-maintenance-request/{!! $rm->id !!}" title="Delete"> <i class="halflings-icon remove halflings-icon"></i> </a> -->

          



            </td>

          </tr>

          {{--*/ $loop++ /*--}}



          @endforeach

            </tbody>

          

        </table> 

    

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

