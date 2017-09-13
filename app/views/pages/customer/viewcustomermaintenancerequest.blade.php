@extends('layouts.defaultpink')
@section('content')
<div id="content" class="span11">
<?php if($request_maintenance->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>
  <div class="row-fluid">
    <div class="box span12">
     
        <div class="box-header" data-original-title>
						<h2>OSR Details</h2>
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
									<td class="center span3"><h2>Property #:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->asset_number }}  <button class="btn btn-small btn-success" data-target="#showServiceid"  onclick="viewAsset({{ $request_maintenance->asset->id }})">View Property</button></h2> </td>
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
									<td class="center span3"><h2>Email:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->user->email}}</h2></td>
									<td></td><td></td>
								</tr>
							  </tbody>
						 </table>    
      </div>
    </div>
    <!--/span--> 


    
     <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2>Property Details</h2>

            </div>

            <div class="box-content">

                <table class="table"> 

                    <tbody>

                        <tr>

                            <td class="center span3"><h2>Property Address: <span >{{$request_maintenance->asset->property_address}}</span> </h2></td>

                            <td class="center span3"><h2>City: <span >{{$request_maintenance->asset->city->name }} </span></h2></td>

                           

                        </tr>

                        <tr>

                             <td class="center span3"><h2>State: <span >{{$request_maintenance->asset->state->name}}</span></h2></td>

                             <td class="center span3"><h2>Zip: <span > {{$request_maintenance->asset->zip}}</span> </h2></td>

                            

                        </tr>

                        <tr>



                       <td class="center span3"><h2>Lockbox: <span >{{$request_maintenance->asset->lock_box}}</span></h2></td>

                       <td class="center span3"><h2>Gate / Access Code: <span >{{$request_maintenance->asset->access_code}}</span></h2></td>

                        </tr>

                       

                    </tbody>

                </table>      

            </div>



        </div><!--/span-->

    </div><!--/row-->	
    
    <span><h1 class="text-center">OSR Services</h1></span>
    	
		@foreach ($assign_requests as $assigned)
                <div class="row-fluid">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon edit"></i><span class="break"></span>{{$assigned->service->title}}</h2>
						<div class="box-icon">
						
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							
						</div>
					</div>
					<div class="box-content">
						<table>
						   <tr><td> {{$assigned->service->desc}}</td></tr> 
								<tr>
									<td class="center"><h2>Customer Note:</h2>{{$assigned->service_note}}</td>
								</tr>
								<tr>
									<div class="row-fluid browse-sec">
                                                   

            
                                                                            
                              @if(count($assigned->serviceImages)!=0)<h2>Images</h2>@endif
                                                       
                                                        
                                                          <ul class="media-list ">
                            @foreach($assigned->serviceImages as $images)
     <?php
                             $docType=explode(".", $images->image_name);
                             if( $docType[1]=='jpeg'|| $docType[1]=='jpg'|| $docType[1]=='png'|| $docType[1]=='gif')
                             {
                            ?>
                            <li>{{ HTML::image(Config::get('app.request_images').'/'.$images->image_name) }}</li>
                          

                             <?php 
                          }  else {
                        	?>
                    
                        <li> <a  href="{{URL::to('/')}}/{{Config::get('app.request_images').'/'.$images->image_name}}" target="_blank" > Download File</a></li>
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
			
			
			
		<div class="row-fluid">
		<p id="errorMessage" style="display:none">Saved...</p>
				<div class="box span12 text-center">
					<div class="span6 box text-center">   
						<button class="btn btn-large btn-danger"  {{ $request_maintenance->status!=1? 'disabled=disabled':'' }} onclick="decline_bid_request('{{ $request_maintenance->id }}','{{ $request_maintenance->vendor_id }}')">Decline</button>
					</div>
					<div class="span6 box text-center">   
						<button class="btn btn-large btn-success" {{ $request_maintenance->status!=1? 'disabled=disabled':'' }} onclick="accept_bid_request('{{ $request_maintenance->id }}','{{ $request_maintenance->vendor_id }}')">Approve</button>
					</div>
				</div><!--/span-->
			</div><!--/row-->	
    
    
    
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
		
  
  
 
</div>
@parent
@include('common.delete_alert')
@stop
