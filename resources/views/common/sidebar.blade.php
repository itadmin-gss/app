<?php
        
        
        $orderCounterDashboard = [];
        $work_orders_count = DB::table('orders')
            ->select(DB::raw('count(id) as numbers, status'))
            ->groupBy('status')
            ->get();

        foreach ($work_orders_count as $datacounter) {
            $orderCounterDashboard[$datacounter->status] = $datacounter->numbers;
        }

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{!!URL::to('admin')!!}"><img class='brand-image' src='{!! URL::asset("assets/images/GSS-Logo.png") !!}'></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placemesnt="right" title="Dashboard">
          <a class="nav-link" href="{!!URL::to('admin')!!}">
            <i class="fa fa-fw fa-2x fa-dashboard"></i>
            <p class="nav-link-text">Dashboard</p>
          </a>
        </li>
        
        <!-- Vendor Link -->
        @if($access_roles['Vendor']['view'] == 1)
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Vendors">
          <a class="nav-link" href="{!!URL::to('list-vendors') !!}">
            <i class="fa fa-fw fa-2x fa-users"></i>
            <p class="nav-link-text">Vendors</p>
          </a>
        </li>
        @endif

        <!-- Properties Link -->
        @if($access_roles['Asset']['view'] == 1)
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Properties">
          <a class="nav-link" href="javascript:void(0)">
            <i class="fa fa-fw fa-2x fa-home"></i>
            <p class="nav-link-text">Properties</p>
          </a>
        </li>
        @endif

        <!-- Maintenance/Service Request Link -->
        @if($access_roles['Service Request']['view'] == 1)
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Maintenance Requests">
          <a class="nav-link" href="{!!URL::to('list-maintenance-request')!!}">
            <i class="fa fa-fw fa-2x fa-wrench"></i>
            <p class="nav-link-text">Maintenance Requests</p>
          </a>
        </li>
        @endif

        <!-- Work Orders Link -->
        @if($access_roles['Order']['view'] == 1)
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Work Orders">
          <a class="nav-link" href="javascript:void(0)">
            <i class="fa fa-fw fa-2x fa-exchange"></i>
            <p class="nav-link-text">Work Orders</p>
          </a>
        </li>
        @endif

        <!-- Permissions Link -->
        @if($access_roles['Access Level']['view'] == 1)
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Permissions">
          <a class="nav-link" href="javascript:void(0)">
            <i class="fa fa-fw fa-2x fa-key"></i>
            <p class="nav-link-text">Permissions</p>
          </a>
        </li>
        @endif


  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
      <a class="nav-link " href="javascript:void(0)" >
      <i class="fa fa-fw fa-2x fa-table"></i>
      <p class="nav-link-text">Reports</p>
      </a>
      <!-- <ul class="sidenav-second-level collapse" id="collapseComponents">
      <li>
          <a href="javascript:void(0)">Monthly Recurring Orders Reports</a>
      </li>
      <li>
          <a href="javascript:void(0)">Snow/Ice Removal Reports</a>
      </li>
      <li>
          <a href="javascript:void(0)">Fannie Mae Reports</a>
      </li>
      </ul> -->
  </li>

      </ul>
      <ul class='navbar-nav'>
        <li class='nav-item' style='float:left;'>
            <a class='navbar-table-link' href='{!! URL::to("admins/1/inprocess") !!}'>
              <div class='nav-badge badge-warning badge-warning-enhance text-white'>
                @if (isset($orderCounterDashboard['1']))
                  {!! $orderCounterDashboard['1']." In-Process" !!}
                @else
                  {!! "0 In-Process" !!}
              @endif
            </div>
            </a>          
        </li>
        <li class='nav-item' style='float:left;'>
            <a class='navbar-table-link' href="{!! URL::to('admins/3/underreview') !!}">
              <div class='nav-badge badge-danger badge-danger-enhance'>
                @if (isset($orderCounterDashboard['3']))
                    {!! $orderCounterDashboard['3']." Under Review" !!}
                @else
                    {!! "0 Under Review" !!}
                @endif
            </div>
            </a>
        </li>
        <li class='nav-item' style='float:left;'>
            <a class='navbar-table-link' href="{!! URL::to('admins/4/approved') !!}">            
              <div class='nav-badge badge-success badge-success-enhance'>
                    @if (isset($orderCounterDashboard['4']))
                        {!! $orderCounterDashboard['4']." Approved" !!}
                    @else
                        {!! "0 Approved" !!}
                    @endif
   
              </div>
            </a>          
        </li>
        <li class='nav-item' style='float:left;'>
            <a class='navbar-table-link' href="{!! URL::to('admins/2/completed') !!}">
              <div class='nav-badge badge-white badge-white-enhance'>
                    @if (isset($orderCounterDashboard['2']))
                        {!! $orderCounterDashboard['2']." Completed" !!}
                    @else
                        {!! "0 Completed" !!}
                    @endif
              </div>
            </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Vendors">
            <a class="nav-link" href="javascript:void(0)">
              <i class="fa fa-fw fa-users"></i>
              <span class="nav-link-text">Vendors</span>
            </a>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Properties">
            <a class="nav-link" href="javascript:void(0)">
              <i class="fa fa-fw fa-home"></i>
              <span class="nav-link-text">Properties</span>
            </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="edit-profile">
            <i class='fa fa-fw fa-user'></i>Profile
          </a>
        </li>
        {{--  <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-envelope"></i>
            <span class="d-lg-none">Messages
              <span class="badge badge-pill badge-primary">12 New</span>
            </span>
            <span class="indicator text-primary d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
          </a>
          <div class="dropdown-menu" aria-labelledby="messagesDropdown">
            <h6 class="dropdown-header">New Messages:</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <strong>David Miller</strong>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">Hey there! This new version of SB Admin is pretty awesome! These messages clip off when they reach the end of the box so they don't overflow over to the sides!</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <strong>Jane Smith</strong>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">I was wondering if you could meet for an appointment at 3:00 instead of 4:00. Thanks!</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <strong>John Doe</strong>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">I've sent the final files over to you for review. When you're able to sign off of them let me know and we can discuss distribution.</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item small" href="#">View all messages</a>
          </div>
        </li>  --}}
        @if(Auth::check())
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
            <span class="d-lg-none">Alerts
              <span class="badge badge-pill badge-warning">              
                @if($unreadnotifications>0)
                {!!$unreadnotifications !!} New
                @else
                0 New
                @endif
              </span>
            </span>
            <span class="indicator text-warning d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu2" aria-labelledby="alertsDropdown" id='alertsDropDownWindow'>
            <h6 class="dropdown-header">New Alerts:</h6>
            <div class="dropdown-divider"></div>
            @foreach($get_notifications as $notification)
            <?php 
              	$to_time = strtotime(date("Y-m-d H:i:s") );
                $from_time = strtotime($notification->created_at);
                $totalMinutes=round((abs($to_time - $from_time) / 60),2);
                $totalHours=round((abs($to_time - $from_time) / 60)/60,2);

                $time= $totalMinutes. " min";

                if($totalMinutes>60)
                {
                  if($totalHours>1)
                    $time= round((abs($to_time - $from_time) / 60)/60,2). " hours";
                    else
                    $time= round((abs($to_time - $from_time) / 60)/60,2). " hour";
                }
              ?>
            
            <a class="dropdown-item" href="#">
              <span class="small float-right text-muted">{!! $time !!}</span>
              <div class="dropdown-message small">
                {!! $notification->message!!}
              </div>
            </a>
            <div class="dropdown-divider"></div>
            @endforeach
            <a class="dropdown-item small" href="{!! URL::to('list-work-notification-url') !!}">View all alerts</a>
            <a class='dropdown-item small' href="{!! URL::to('clear-notifications') !!}">Clear Alerts</a> 
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{!! URL::to('/logout') !!}">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li>
        @endif
      </ul>
    </div>
  </nav>






{{--  <div class="nav-collapse sidebar-nav">

    <ul class="nav nav-tabs nav-stacked main-menu">



        <li> <a class="submenu" href="{!!URL::to('admin')!!}"><i class="fa-icon-user"></i><span class="hidden-tablet">Dashboard</span></a>

        </li>

         @if($access_roles['User']['view'] == 1)

        <li> <a href="{!!URL::to('list-user')!!}"><i class="fa-icon-user"></i><span class="hidden-tablet">Users</span></a>

            

        </li>

 		@endif

        @if($access_roles['User']['view'] == 1)

        <li> <a href="{!!URL::to('list-city')!!}"><i class="fa-icon-user"></i><span class="hidden-tablet">Cities</span></a>

            

        </li>

    @endif

         @if($access_roles['Access Level']['view'] == 1)

        <li> <a  href="{!!URL::to('list-access-level')!!}"><i class="fa-icon-user-md"></i><span class="hidden-tablet">Access Levels</span></a>

            

        </li>

         @endif

         @if($access_roles['Access Rights']['view'] == 1)

        <li> <a href="{!!URL::to('access-rights')!!}"><i class="fa-icon-lock"></i><span class="hidden-tablet">Access Rights</span></a>

        </li>

        @endif

        @if($access_roles['Customer']['view'] == 1)

        <li> <a href="{!!URL::to('list-customer')!!}"><i class="fa-icon-user"></i><span class="hidden-tablet">Customers</span></a>

           

        </li>

        @endif

        @if($access_roles['Vendor']['view'] == 1)

        <li> <a href="{!!URL::to('list-vendors')!!}"><i class="fa-icon-user"></i><span class="hidden-tablet">Vendors</span></a>

           

        </li>

        @endif

        @if($access_roles['Asset']['view'] == 1)

        <li> <a  href="{!!URL::to('list-assets')!!}"><i class="fa-icon-home"></i><span class="hidden-tablet">Properties</span></a>

        

        </li>

        @endif

        @if($access_roles['Service']['view'] == 1)

        <li> <a href="{!!URL::to('list-services')!!}"><i class="fa-icon-home"></i><span class="hidden-tablet">Services</span></a>

    

        </li>

        @endif

        @if($access_roles['Special Price']['view'] == 1)

        <li> <a  href="{!!URL::to('list-special-prices')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Special Price</span></a>



        </li>

        @endif

          @if($access_roles['Special Price']['view'] == 1)

        <li> <a  href="{!!URL::to('vendor-list-special-prices')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Vendor Special Price</span></a>



        </li>

        @endif

        @if($access_roles['Service Request']['view'] == 1)

        <li> <a href="{!!URL::to('list-maintenance-request')!!}"><i class="fa-icon-wrench"></i><span class="hidden-tablet">Service Request</span></a>

        

        </li>

     <!--     <li> <a href="{!!URL::to('list-bidding-request')!!}"><i class="fa-icon-wrench"></i><span class="hidden-tablet">Bid Request</span></a>

        

        </li> -->

       

        @endif

        @if($access_roles['Service Request']['view'] == 1)

     <!--    <li> <a href="{!!URL::to('list-assigned-maintenance-request')!!}"><i class="fa-icon-home"></i><span class="hidden-tablet">

Assigned Service Request</span></a>

    

        </li> -->

        @endif

        @if($access_roles['Order']['view'] == 1)

        <li> <a href="{!!URL::to('list-work-order-admin')!!}"><i class="fa-icon-retweet"></i><span class="hidden-tablet">Work orders</span></a>

            

        </li>
        <li> <a href="{!!URL::to('list-exported-workorder')!!}"><i class="fa-icon-download-alt"></i><span class="hidden-tablet">Exported Work orders</span></a>

            

        </li>
        @endif

        @if($access_roles['Completed Request']['view'] == 1)

       <!-- <li> <a href="{!!URL::to('admin-list-completed-order')!!}"><i class="fa-icon-tasks"></i><span class="hidden-tablet">Completed Request</span></a>

        

        </li> -->

        @endif

        @if($access_roles['Invoice']['view'] == 1)

        <li> <a  href="{!!URL::to('admin-list-invoice')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Invoices</span></a>

            

        </li>



        @endif

        @if($access_roles['Invoice']['view'] == 1)

        <li> <a  href="{!!URL::to('recurring')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Recurring</span></a>

            

        </li>

        

        @endif



       <!--  <li> <a  href="{!!URL::to('admin-bid-requests')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">OSR</span></a>

            

        </li>

        <li> <a  href="{!!URL::to('do-request')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Add Bid Service</span></a>

            

        </li>



          <li> <a  href="{!!URL::to('list-bid-services')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">List Bid Services</span></a>

            

        </li> -->

       <li> <a  href="{!!URL::to('list-assets-summary')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet"> Property History (Summary)</span></a>

            

        </li> 

        <li> <a  href="{!!URL::to('status-report')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet"> Status Report</span></a>

            

        </li> 

        

         <li> <a  href="{!!URL::to('list-vendor-summary')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet"> Vendor History (Summary)</span></a>

            

        </li> 





         <li> <a  href="{!!URL::to('list-service-categories')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Service Categories</span></a>

            

        </li> 

       

          <li> <a  href="{!!URL::to('list-job-type')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Job Type</span></a>

            

        </li> 

         <li>

          <a  href="{!!URL::to('list-customer-type')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Client Type</span></a>

            

        </li> 

          <li>

          <a  href="{!!URL::to('property-report')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Property Report</span></a>

            

          </li> 



          <li>

          <a  href="{!!URL::to('recurring-report')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Recurring Report</span></a>

            

          </li> 



          <li>

          <a  href="{!!URL::to('reporting')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Reporting</span></a>

            

          </li> 

         

           <li>

          <a  href="{!!URL::to('whiteboard-reporting')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">White Reporting</span></a>

            

          </li> 

          <li>

          <a  href="{!!URL::to('quantity-of-approved-orders')!!}"><i class="fa-icon-file"></i><span class="hidden-tablet">Quantity of Approved Orders</span></a>

            

          </li> 


          

          

          

    </ul>

</div>  --}}

