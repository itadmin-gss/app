<div class="nav-collapse sidebar-nav">
    <ul class="nav nav-tabs nav-stacked main-menu">

        <li> <a class="submenu" href="{!!URL::to('admin')!!}"><i class="fa-icon-user"></i><span class="hidden-tablet">Dashboard</span></a>
        </li>
            <li> <a  href="{!!URL::to('admin-bid-requests')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Bid Requests</span></a>
            
        </li>

          @if($access_roles['Service Request']['view'] == 1)
        <li> <a href="{!!URL::to('list-maintenance-request')!!}"><i class="fa-icon-wrench"></i><span class="hidden-tablet">Service Request</span></a>
        
        </li>
        @endif

            @if($access_roles['Service Request']['view'] == 1)
        <li> <a href="{!!URL::to('list-assigned-maintenance-request')!!}"><i class="fa-icon-home"></i><span class="hidden-tablet">
Assigned Service Request</span></a>
    
        </li>
        @endif


          @if($access_roles['Order']['view'] == 1)
        <li> <a href="{!!URL::to('list-work-order-admin')!!}"><i class="fa-icon-retweet"></i><span class="hidden-tablet">Work orders</span></a>
            
        </li>
        @endif


             @if($access_roles['Asset']['view'] == 1)
        <li> <a  href="{!!URL::to('list-assets')!!}"><i class="fa-icon-home"></i><span class="hidden-tablet">Properties</span></a>
        
        </li>
        @endif

           @if($access_roles['Invoice']['view'] == 1)
        <li> <a  href="{!!URL::to('admin-list-invoice')!!}"><i class="fa-icon-money"></i><span class="hidden-tablet">Invoices</span></a>
            
        </li>
        @endif




        @if($access_roles['Vendor']['view'] == 1)
        <li> <a href="{!!URL::to('list-vendors')!!}"><i class="fa-icon-user"></i><span class="hidden-tablet">Vendors</span></a>
           
        </li>
        @endif
         @if($access_roles['Customer']['view'] == 1)
        <li> <a href="{!!URL::to('list-customer')!!}"><i class="fa-icon-user"></i><span class="hidden-tablet">Customers</span></a>
           
        </li>
        @endif


        
    </ul>
</div>