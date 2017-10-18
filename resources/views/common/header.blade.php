<div class="navbar-inner">
			<div class="container-fluid">

				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="{!!URL::to('/')!!}" style="overflow: hidden;">
					<img class="inLogo" src="{!!URL::to('/')!!}/assets/images/GSS-Logo.png" style="width: 110px;">
				</a>

				<!-- start: Header Menu -->


                                @if(Auth::check())
                               
				<div class="nav-no-collapse header-nav">
					<ul class="nav pull-right">
					
					<li class="dropdown hidden-phone">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
								@if($unreadnotifications>0)
								<span class="unreadnumbers">{!!$unreadnotifications!!} </span> 
								@endif
								<i class="halflings-icon white warning-sign"></i>
								
							</a>
							<ul class="dropdown-menu notifications">
								<li>
									<span class="dropdown-menu-title">You have {!!$unreadnotifications!!} unread notifications</span>
								</li>	
								@foreach($get_notifications as $notificatioData)
								<?php
					$to_time = strtotime(date("Y-m-d H:i:s") );
					$from_time = strtotime($notificatioData->created_at);
					$totalMintus=round((abs($to_time - $from_time) / 60),2);
					$totalHours=round((abs($to_time - $from_time) / 60)/60,2);

					$time= $totalMintus. " min";

					if($totalMintus>60)
					{
						if($totalHours>1)
					    $time= round((abs($to_time - $from_time) / 60)/60,2). " hours";
				    	else
				    	$time= round((abs($to_time - $from_time) / 60)/60,2). " hour";
				    	

				    }

				    $pinkBack="";
				    if (strpos($notificatioData->message,'OSR') !== false) {
				    	$pinkBack.="style='background:rgb(192, 125, 137)'";
				    }

				     $orangeBack="";
				    if (strpos($notificatioData->message,'Bid') !== false) {
				    	$orangeBack.="style='background:orange'";
				    }
								?>
                            	<li @if($notificatioData->is_read==1) class="notficationUnread" @endif {!!$pinkBack!!} {!!$orangeBack!!}>
                                    <a href="#" onClick="ChangeNotificationStatus('{!! $notificatioData->id!!}','{!!$notificatioData->notification_url!!}','{!!$notificatioData->message!!}')">
							+ <i class="halflings-icon white user"></i> <span class="message">{!! $notificatioData->message!!}</span>  <br/> <span class="time">{!!$time!!}</span> 
                                    </a>
                                </li>
                                @endforeach
                                <li>
                            		<a class="dropdown-menu-sub-footer" href="{!!URL::to('list-work-notification-url')!!}">View all notifications</a>
                            		       <a class="dropdown-menu-sub-footer" href="{!!URL::to('clear-notification')!!}">Clear Notification</a>
                            		
								</li>	
							</ul>
						</li>

						<!-- start: User Dropdown -->
						<li class="dropdown">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">

								<i class="halflings-icon white user"></i> {!! Auth::user()->first_name !!} {!!Auth::user()->last_name!!}

								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{!!URL::to('edit-profile')!!}"><i class="halflings-icon white user"></i> Profile</a></li>
								<li><a href="{!!URL::to('logout')!!}"><i class="halflings-icon white off"></i> Logout</a></li>
							</ul>
						</li>
						@if(Auth::user()->type_id==2)

						
						@endif
						<!-- end: User Dropdown -->
					</ul>
				</div>
                                @endif
				<!-- end: Header Menu -->

			</div>
		</div>
		
		
		<input type="hidden" id="firstcolumn"> 

		<div id="right_navPnl" class="nav-collapse sidebar-nav">
			
		</div>

		