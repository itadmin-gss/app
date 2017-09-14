@extends('layouts.default')
@section('content')
<div id="content" class="span11">
<?php if($request_maintenance->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>
<div class="row-fluid">
		<p id="errorMessage" style="display:none">Saved...</p>
				<div class="box span12 text-center">
					
					<div class="span6 box text-center">   
						<button class="btn btn-large btn-success"    {{ $request_maintenance->status!=1? '':'' }} onclick="accept_request('{{ $request_maintenance->id }}','{{ Auth::user()->id; }}')">Accept</button>
					</div>
					<div class="span6 box text-center">   
						<button class="btn btn-large btn-danger"    {{ $request_maintenance->status!=1? '':'' }} onclick="decline_request('{{ $request_maintenance->id }}','{{ Auth::user()->id; }}')">Decline</button>
					</div>
				</div><!--/span-->
			</div><!--/row-->	
  <div class="row-fluid">
    <div class="box span12">
     
        <div class="box-header" data-original-title>
						<h2>Service Request Details</h2>
					</div>
      					@if(Session::has('message'))
                            {{Session::get('message')}}
                        @endif
      <div class="box-content">
        <table class="table"> 
							  <tbody>
								<tr>
									<td class="center span3"><h2>Request ID:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->id }}</h2></td>
									<td class="center span3"><h2>Request Date:</h2></td>
									<td class="center span3"><h2>{{ date('m/d/Y', strtotime($request_maintenance->created_at)) }}</h2></td>
								</tr>
								<tr>
									<td class="center span3"><h2>Property Address:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->property_address }}  <button class="btn btn-small btn-success" data-target="#showServiceid"  onclick="viewAsset({{ $request_maintenance->asset->id }})">View Property</button></h2> </td>
						<td class="center span3"><h2>City:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->city->name }}</h2></td>
									
								</tr>
								<tr>
									<td class="center span3"><h2>State:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->state->name }}</h2></td>
								<td class="center span3"><h2>Zip:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->zip }}</h2></td>
		
								</tr>

							<tr>
							<td class="center span3"><h2>Gate/Access Code:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->access_code }}</h2></td>
		
							
									<td class="center span3"><h2>Lock Box Code:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->lock_box }}</h2></td>
		
					
							</tr>
							  </tbody>
						 </table>    
      </div>
    </div>
    <!--/span--> 
    
    <span><h1 class="text-center">Requested Services</h1></span>
    	<?php
   $totalPrice=0;
    	?>
		@foreach ($assign_requests as $assigned)
                <div class="row-fluid">
				<div class="box span12">

					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon edit"></i><span class="break"></span>{{$assigned->requestedService->service->title}}</h2>
						
						<?php if($assigned->requestedService->due_date==""){ ?>
						
						<div style="margin-left: 16%;float: left;"><h2> Due Date :No date assigned</h2>
						<?php } 
						else 
						{
						?>
						<h2> 	Due Date :{{$assigned->requestedService->due_date}}</h2>
						<?php
						 } ?>
						 </div>
						<div class="box-icon">
						


	<?php
						$vendorPrice=0;

         if($assigned->requestedService->quantity=="" || $assigned->requestedService->quantity==0)
         	{
             $totalPrice += $assigned->requestedService->service->vendor_price;
                  $vendorPrice= $assigned->requestedService->service->vendor_price;
            }
             else
             {
             	    $totalPrice += $assigned->requestedService->service->vendor_price*$assigned->requestedService->quantity ;
            $vendorPrice=  $assigned->requestedService->service->vendor_price*$assigned->requestedService->quantity ;
             }  
?>
						<?php //$totalPrice+=$assigned->requestedService->service->vendor_price;?>
						  Price :  ${{$vendorPrice}}
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							
						</div>
					</div>
					<div class="box-content">
						<table> 
						    <tr><td> {{$assigned->requestedService->service->desc}}</td></tr>
								<!-- <tr>
									<td class="center"><h2>Customer Note:</h2>{{$assigned->requestedService->service_note}}</td>
								</tr> -->

								
								<tr>
									<div class="row-fluid browse-sec">
                                                   
<button class="btn btn-large btn-success" onclick="accept_single_request('{{ $request_maintenance->id }}','{{ Auth::user()->id; }}','{{$assigned->requestedService->id}}')">Accept</button>
				<tr>
									<td class="center"><h2>Public Note:</h2>{{$assigned->requestedService->public_notes}}</td>
								</tr>
            
                                                                            
                              @if(count($assigned->requestedService->serviceImages)!=0)<h2>Images</h2>@endif
                                                       
                                                        
                                                          <ul class="media-list ">
                            @foreach($assigned->requestedService->serviceImages as $images)
     <?php
                             $docType=explode(".", $images->image_name);
                             if( $docType[1]=='jpeg'|| $docType[1]=='jpg'|| $docType[1]=='png'|| $docType[1]=='gif')
                             {
                            ?>
                            <li style="width: 30%;margin-bottom: 15"> <a  href="{{URL::to('/')}}/{{Config::get('app.request_images').'/'.$images->image_name}}" target="_blank" > {{ HTML::image(Config::get('app.request_images').'/'.$images->image_name) }} </a></li>
                          

                             <?php 
                          }  else {
                        	?>
                    
                        <li> <a  href="{{URL::to('/')}}/{{Config::get('app.request_images').'/'.$images->image_name}}" target="_blank" > Download File {{$images->image_name}}</a></li>
                        	<?php
                        	}
                            ?> @endforeach


                        </ul>
                                                        
                                                      </div>
								</tr>
								
						 </table>      
					</div>
				</div><!--/span-->

			</div><!--/row-->
                @endforeach
			
		
			
		  <div style="float:right;"><h2>Total Price : ${{$totalPrice}}</h2></div>
    
    
    
  </div>
<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>
<div class="modal  hide fade modelForm larg-model"  id="showVendorList">
        	
</div>

<div class="modal hide fade modelForm larg-model"  id="showServiceid"></div>
		
  <div class="modal hide fade" id="declinedNotes">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Declined Note</h3>
			</div>
			<div class="modal-body">
			 Please enter notes for your reason of decline
				{{Form::textarea('decline_note', $request_maintenance->decline_notes,array('class'=>'span','id'=>'declinenote'))}}
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary" onclick="decline_request('{{ $request_maintenance->id }}','{{ Auth::user()->id; }}')">Save changes</a>
			</div>
		</div>
  
 
</div>
@parent
@include('common.delete_alert')
@stop
