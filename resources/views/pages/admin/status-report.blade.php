@extends('layouts.default')
@section('content')


<style type="text/css">
.datatablegrid {display: block;}
.datatablegrid2 {display: none;}
.datatablegrid3 {display: none;}
.datatablegrid4 {display: none;}

</style>
<div id="content" class="span11">
<lable>Filter Report</lable>
<select id="assetSummary">
    <option value="1">In-Process</option>
    <option value="2">Completed</option>
    <option value="3">Under Review</option>
    <option value="4">Approved</option>
    <option value="5">Cancelled</option>
    
</select>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header datatablegrid" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Status Report</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content datatablegrid admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable datatable">

                    <thead>
                        <tr>
                            <th>Client Type</th>
                            <th>Customer Name</th>
                            <th>Property Address</th>
                            <th>Unit #</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                            <th>Services</th>
                            <th>Order ID</th>
                            <th>Order Status</th>
                            <th>Completed Date</th>
                            <th>Vendor Name</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($assets_data as $asset)
                    
                        <tr>
                            <td>{!!$asset->maintenanceRequest->asset->customerType->title!!}</td>

                            <td>@if(isset($asset->customer->first_name)) {!!$asset->customer->first_name!!} @endif @if(isset($asset->customer->last_name)) {!!$asset->customer->last_name!!} @endif</td>
                            <td>{!!$asset->maintenanceRequest->asset->property_address!!} </td>
                            <td>{!!$asset->maintenanceRequest->asset->UNIT!!} </td>
                            <td>{!!$asset->maintenanceRequest->asset->city->name!!} </td>
                            <td>{!!$asset->maintenanceRequest->asset->state->name!!} </td>
                            <td>{!!$asset->maintenanceRequest->asset->zip!!} </td>
                            <?php
                            $servicedate="";
                            foreach ($asset->maintenanceRequest->requestedService as  $value) {
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

                        }?>
                        <td>{!! $servicedate!!}</td>
                        <td>{!! $asset->id!!}</td>
                        <td class="center"> <span class="label label-{!!$asset->status_class!!} "> {!!$asset->status_text!!} </span> </td>
                       <td>{!! $asset->completion_date!!}</td>
                       <td>@if(isset($asset->vendor->first_name)) {!!$asset->vendor->first_name." ".$asset->vendor->last_name;!!} @endif</td>

                           
              
                  
                        

                       
                       
                      

                     
                                </tr>
                           
                                <?php $i++; ?>
                                @endforeach



                            </tbody>
                        </table>

                    </div>
              </div>
           
   
           
            </div><!--/span-->

            <div id="asset_information" class="modal  hide fade modelForm larg-model"></div>
        </div><!--/row-->

    </div>

                                @stop