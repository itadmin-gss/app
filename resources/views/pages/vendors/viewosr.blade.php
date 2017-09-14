@extends('layouts.defaultpink')
@section('content')
<div id="content" class="span11">
<p id="errorMessage" style="display:none">Saved...</p>
  <div class="row-fluid">
    <div class="box span12">
     <h1>On-Site Request</h1>
        <div class="box-header" data-original-title>
						<h2>OSR Details</h2>
					</div>
      					@if(Session::has('message'))
                            {{Session::get('message')}}
                        @endif

                      
      <div class="box-content">
        <table class="table"> 
							  <tbody>
								 <?php
                       
                        if($request_maintenance->status==3)
                        {
                        ?>
                        <tr> <td class='center span3'><h2>Decline Notes:</h2></td><td>{{ $request_maintenance->decline_notes}}</td></tr>
                        
                        <?php
                        	}
                        ?>
							 
								<tr>
									<td class="center span3"><h2>Request ID:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->id }}</h2></td>
									<td class="center span3"><h2>Request Date:</h2></td>
									<td class="center span3"><h2>{{ date('d/m/Y', strtotime($request_maintenance->created_at)) }}</h2></td>
								</tr>
								<tr>
									<td class="center span3"><h2>Property #:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->asset_number }}  <button class="btn btn-small btn-success" data-target="#showServiceid"  onclick="viewAsset({{ $request_maintenance->asset->id }})">View Property</button></h2> </td>
									<td class="center span3"><h2>Vendor Name:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->user->first_name.' '.$request_maintenance->user->last_name }} </h2></td>
								</tr>
								
								<tr>
									<td class="center span3"><h2>Zip:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->asset->zip }}</h2></td>
									<td class="center span3"><h2>Email:</h2></td>
									<td class="center span3"><h2>{{ $request_maintenance->user->email}}</h2></td>
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
						
							Vendor Price : {{$assigned->biding_prince}}
						</div>
					</div>
					<div class="box-content">
						<table> 
								<tr>
									<td class="center"><h2>Vendor Notes:</h2>{{$assigned->service_note}}</td>
								</tr>
						<!-- 		<tr><td class="center"><h2>Customer Price:</h2> <input type="text" name="customer_price[]" value="<?php if(isset($assigned->customer_price)) { echo $assigned->customer_price; } ?>" id="customer_price_{{$assigned->id}}" /></td>
								
									<tr><td class="center"><h2>Vendor Price:</h2> <input type="text" name="vendor_price[]" value="<?php if(isset($assigned->biding_prince)) { echo $assigned->biding_prince; } ?>" id="vendor_price_{{$assigned->id}}" /> <button onclick="markup('vendor_price_{{$assigned->id}}',  'customer_price_{{$assigned->id}}')" class="btn btn-large btn-success">Mark Up</button></td>
										
									-->
         			<?php 
         			if($request_maintenance->status==3)
                        {
                        ?>
						<tr><td class="center"><h2>Vendor Price:</h2> <input type="text" name="vendor_price[]" value="<?php if(isset($assigned->biding_prince)) { echo $assigned->biding_prince; } ?>" id="vendor_price_{{$assigned->id}}" /> <button onclick="changePrice('vendor_price_{{$assigned->id}}',  '{{$assigned->id}}')" class="btn btn-large btn-success">Change Price</button></td></tr>
										
							<?php

						}
							?>
								
								<tr>
									<div class="row-fluid browse-sec">
                                                   

            
                                                                            
                              @if(count($assigned->serviceImages)!=0)<h2>Images</h2>@endif
                                                       
                                                        
                                                          <ul class="media-list ">
                            @foreach($assigned->serviceImages as $images)

                            <li>{{ HTML::image(Config::get('app.request_images').'/'.$images->image_name) }}</li>
                            @endforeach


                        </ul>
                                                        
                                                      </div>
								</tr>
								
						 </table>      
					</div>
				</div><!--/span-->

			</div><!--/row-->
                @endforeach
			
			
			
		<div class="row-fluid">
				<!-- <div class="box span12 text-center">
					<div class="span6 box text-center">   
						<button class="btn btn-large btn-danger" {{ $request_maintenance->status!=1? 'disabled=disabled':'' }} onclick="admin_decline_bid_request('{{ $request_maintenance->id }}','{{ $request_maintenance->vendor_id }}')">Decline</button>
					</div>
					<div class="span6 box text-center">   
						<button class="btn btn-large btn-success" {{ $request_maintenance->status!=1? 'disabled=disabled':'' }} onclick="admin_accept_bid_request('{{ $request_maintenance->id }}','{{ $request_maintenance->vendor_id }}')">Approve</button>
					</div>
				</div --><!--/span-->
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
