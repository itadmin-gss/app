<div class="nav-collapse sidebar-nav">

    <ul class="nav nav-tabs nav-stacked main-menu">



        <li> <a class="submenu" href="{{URL::to('admin')}}"><i class="fa-icon-user"></i><span class="hidden-tablet">Dashboard</span></a>

        </li>

         @if($access_roles['User']['view'] == 1)

        <li> <a href="{{URL::to('list-user')}}"><i class="fa-icon-user"></i><span class="hidden-tablet">Users</span></a>

            

        </li>

 		@endif

        @if($access_roles['User']['view'] == 1)

        <li> <a href="{{URL::to('list-city')}}"><i class="fa-icon-user"></i><span class="hidden-tablet">Cities</span></a>

            

        </li>

    @endif

         @if($access_roles['Access Level']['view'] == 1)

        <li> <a  href="{{URL::to('list-access-level')}}"><i class="fa-icon-user-md"></i><span class="hidden-tablet">Access Levels</span></a>

            

        </li>

         @endif

         @if($access_roles['Access Rights']['view'] == 1)

        <li> <a href="{{URL::to('access-rights')}}"><i class="fa-icon-lock"></i><span class="hidden-tablet">Access Rights</span></a>

        </li>

        @endif

        @if($access_roles['Customer']['view'] == 1)

        <li> <a href="{{URL::to('list-customer')}}"><i class="fa-icon-user"></i><span class="hidden-tablet">Customers</span></a>

           

        </li>

        @endif

        @if($access_roles['Vendor']['view'] == 1)

        <li> <a href="{{URL::to('list-vendors')}}"><i class="fa-icon-user"></i><span class="hidden-tablet">Vendors</span></a>

           

        </li>

        @endif

        @if($access_roles['Asset']['view'] == 1)

        <li> <a  href="{{URL::to('list-assets')}}"><i class="fa-icon-home"></i><span class="hidden-tablet">Properties</span></a>

        

        </li>

        @endif

        @if($access_roles['Service']['view'] == 1)

        <li> <a href="{{URL::to('list-services')}}"><i class="fa-icon-home"></i><span class="hidden-tablet">Services</span></a>

    

        </li>

        @endif

        @if($access_roles['Special Price']['view'] == 1)

        <li> <a  href="{{URL::to('list-special-prices')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Special Price</span></a>



        </li>

        @endif

          @if($access_roles['Special Price']['view'] == 1)

        <li> <a  href="{{URL::to('vendor-list-special-prices')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Vendor Special Price</span></a>



        </li>

        @endif

        @if($access_roles['Service Request']['view'] == 1)

        <li> <a href="{{URL::to('list-maintenance-request')}}"><i class="fa-icon-wrench"></i><span class="hidden-tablet">Service Request</span></a>

        

        </li>

     <!--     <li> <a href="{{URL::to('list-bidding-request')}}"><i class="fa-icon-wrench"></i><span class="hidden-tablet">Bid Request</span></a>

        

        </li> -->

       

        @endif

        @if($access_roles['Service Request']['view'] == 1)

     <!--    <li> <a href="{{URL::to('list-assigned-maintenance-request')}}"><i class="fa-icon-home"></i><span class="hidden-tablet">

Assigned Service Request</span></a>

    

        </li> -->

        @endif

        @if($access_roles['Order']['view'] == 1)

        <li> <a href="{{URL::to('list-work-order-admin')}}"><i class="fa-icon-retweet"></i><span class="hidden-tablet">Work orders</span></a>

            

        </li>
        <li> <a href="{{URL::to('list-exported-workorder')}}"><i class="fa fa-archive"></i><span class="hidden-tablet">Exported Work orders</span></a>

            

        </li>
        @endif

        @if($access_roles['Completed Request']['view'] == 1)

       <!-- <li> <a href="{{URL::to('admin-list-completed-order')}}"><i class="fa-icon-tasks"></i><span class="hidden-tablet">Completed Request</span></a>

        

        </li> -->

        @endif

        @if($access_roles['Invoice']['view'] == 1)

        <li> <a  href="{{URL::to('admin-list-invoice')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Invoices</span></a>

            

        </li>



        @endif

        @if($access_roles['Invoice']['view'] == 1)

        <li> <a  href="{{URL::to('recurring')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Recurring</span></a>

            

        </li>

        

        @endif



       <!--  <li> <a  href="{{URL::to('admin-bid-requests')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">OSR</span></a>

            

        </li>

        <li> <a  href="{{URL::to('do-request')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Add Bid Service</span></a>

            

        </li>



          <li> <a  href="{{URL::to('list-bid-services')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">List Bid Services</span></a>

            

        </li> -->

       <li> <a  href="{{URL::to('list-assets-summary')}}"><i class="fa-icon-money"></i><span class="hidden-tablet"> Property History (Summary)</span></a>

            

        </li> 

        <li> <a  href="{{URL::to('status-report')}}"><i class="fa-icon-money"></i><span class="hidden-tablet"> Status Report</span></a>

            

        </li> 

        

         <li> <a  href="{{URL::to('list-vendor-summary')}}"><i class="fa-icon-money"></i><span class="hidden-tablet"> Vendor History (Summary)</span></a>

            

        </li> 





         <li> <a  href="{{URL::to('list-service-categories')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Service Categories</span></a>

            

        </li> 

       

          <li> <a  href="{{URL::to('list-job-type')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Job Type</span></a>

            

        </li> 

         <li>

          <a  href="{{URL::to('list-customer-type')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Client Type</span></a>

            

        </li> 

          <li>

          <a  href="{{URL::to('property-report')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Property Report</span></a>

            

          </li> 



          <li>

          <a  href="{{URL::to('recurring-report')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Recurring Report</span></a>

            

          </li> 



          <li>

          <a  href="{{URL::to('reporting')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">Reporting</span></a>

            

          </li> 

         

           <li>

          <a  href="{{URL::to('whiteboard-reporting')}}"><i class="fa-icon-money"></i><span class="hidden-tablet">White Reporting</span></a>

            

          </li> 

          <li>

          <a  href="{{URL::to('quantity-of-approved-orders')}}"><i class="fa-icon-file"></i><span class="hidden-tablet">Quantity of Approved Orders</span></a>

            

          </li> 

           <li>

          <a  href="{{URL::to('/')}}/backup-scripting1234.php"><i class="fa-icon-file"></i><span class="hidden-tablet">Get Backup</span></a>

            

          </li>

          <li>

          <a  href="{{URL::to('/swap-db')}}"><i class="fa-icon-hdd"></i><span class="hidden-tablet">Swap Database</span></a>

            

          </li>

          

          

          

    </ul>

</div>

