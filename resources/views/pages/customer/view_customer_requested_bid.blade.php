@extends('layouts.defaultorange')
@section('content')
<div id="content" class="span11">
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{!! URL::asset('public/assets/js/cycle.js') !!}"> </script>
<h2 class="bidRqst btn-warning" style="display: block;">Service Bid</h2>



            <table class="table table-bordered customeTable"> 
                <tbody>
                    <tr>
                        <td class="center span3"><h2><span>Property #:</span>{!!$request_detail->asset->asset_number!!}</h2></td>
                        <td class="center span3"><h2><span>Order #:</span>{!!$request_detail->id!!}</h2></td>
                        <td class="center span3"><h2><span>Recurring:</span> No</h2></td>
                        <td class="center span3"><h2><span>Status:</span> @if($request_detail->status==1)

           New Bid Request

            @elseif($request_detail->status==2)

          Awaiting Vendor Bid

            @elseif($request_detail->status==3)

         Completed Vendor Bid

            @elseif($request_detail->status==4)

           New Work Order Generated
              @elseif($request_detail->status==5)

         Cancel
            @elseif($request_detail->status==6)

     Awaiting Customer Approval
            @elseif($request_detail->status==7)

        Declined
              @elseif($request_detail->status==8)

            Approved Bid
            
            @endif</h2></td>
                        <td class="center span3">

                        <h2>
                            @foreach ($request_detail->requestedService as $services)
                       Service: <span style="font-size: 13px !important;font-weight: normal;"> {!!$services->service->title!!}</span>
                         Due Date:<span style="font-size: 13px !important;font-weight: normal;"> @if($services->due_date=="") Not assigned @else{!!$services->due_date!!} @endif</span>
                         @endforeach
                              

                        
                        </h2>
                        </td>

                    </tr>
                </tbody>
            </table>   

<?php if($request_detail->status==7){?>
<div class="declinebidstatus">Declined Note:<span>{!!$request_detail->declinebidnotes!!}</span></div>
<?php }?>

<?php if($request_detail->asset->property_dead_status==1){?>
<div class ="disableProperty"><span>Property Closed</span></div>
<?php }?>

<?php
$vendor_id="";
foreach ($request_detail->assignRequest as $value) {
   $vendor_id= $value->vendor_id;
}

?>

<div class="row-fluid">
        <p id="errorMessage" @if($request_detail->status==7) style="display:block" @else style="display:none" @endif >@if($request_detail->status==7) Bid has been declined @endif</p>
           
            </div><!--/row-->   
   
    <div class="row-fluid">
       <div class="box span12">
   
        <div class="box-header" data-original-title>
                        <h2>Customer Details</h2>
                </div>
                        @if(Session::has('message'))
                            {!!Session::get('message')!!}
                        @endif
      <div class="box-content">
        <table class="table"> 
                              <tbody>
                            
                                <tr>
                                                  
                                    <td class="center span3"><h2>Customer Name:</h2></td>
                                    <td class="center span3"><h2>@if(isset($request_detail->user->first_name)) {!! $request_detail->user->first_name!!} @endif  @if(isset($request_detail->user->last_name)){!!$request_detail->user->last_name !!} @endif</h2></td>
                               <td class="center span3"><h2>Email:</h2></td>

                  <td class="center span3"><h2>@if(isset( $request_detail->user->email)){!! $request_detail->user->email!!} @endif</h2></td>
                   
                    </tr>
                    <tr>
                    <td class="center span3"><h2>Customer Company:</h2></td>
                    <td class="center span3"><h2>@if(isset($request_detail->user->company)) {!! $request_detail->user->company!!} @endif </h2></td>
                 
                    <td class="center span3"><h2>Customer Phone:</h2></td>
                    <td class="center span3"><h2>@if(isset($request_detail->user->phone)) {!! $request_detail->user->phone!!} @endif </h2></td>
                 
                    </tr>
                            
                    
                            <!--    <tr>
               
                  <td class="center span3"><h2>Admin Notes:</h2></td>
                                    <td class="center span3"> {!!Form::textarea('admin_notes', isset( $request_detail->admin_notes) ? $request_detail->admin_notes : '' , array('class'=>'span12 typeahead', 'id'=>'admin_notes','onChange'=>'adminNotes(this,"'.$request_detail->id.'")'))!!} </td>
                               
                                </tr> -->
                <tr></tr>



                              </tbody>
                         </table>    
      </div>
    </div>
    <!--/span--> 
    </div><!--/row-->

       <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2>Property Details</h2>

            </div>

            <div class="box-content">

                <table class="table"> 

                    <tbody>

                        <tr>

                            <td class="center span3"><h2>Property Address: <span >{!!$request_detail->asset->property_address!!}</span> </h2></td>

                            <td class="center span3"><h2>City: <span >{!!$request_detail->asset->city->name !!} </span></h2></td>

                           

                        </tr>

                        <tr>

                             <td class="center span3"><h2>State: <span >{!!$request_detail->asset->state->name!!}</span></h2></td>

                             <td class="center span3"><h2>Zip: <span > {!!$request_detail->asset->zip!!}</span> </h2></td>

                            

                        </tr>

                        <tr>



                       <td class="center span3"><h2>Lockbox: <span >{!!$request_detail->asset->lock_box!!}</span></h2></td>

                       <td class="center span3"><h2>Gate / Access Code: <span >{!!$request_detail->asset->access_code!!}</span></h2></td>

                        </tr>

                       

                    </tbody>

                </table>      

            </div>



        </div><!--/span-->

    </div><!--/row--> 
   
    <h2 class="bidRqst btn-warning" style="display: block;text-align:left;padding:12px;margin:0px">Service Bid Details</h2>
        
<?php
  $totalPrice=0;
?>
    @foreach($request_detail->requestedService as $services)
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>{!!$services->service->title!!}</h2>
              


                <div class="box-icon">
                <?php
                if($services->quantity=="" || $services->quantity==0) 
          {
            $servicePrice=$services->customer_bid_price;
             $totalPrice+=$services->customer_bid_price;
          } else
          {
            $servicePrice=$services->customer_bid_price*$services->quantity;
           $totalPrice+=$services->customer_bid_price*$services->quantity;
       }







    ?>
                Price :  ${!!$servicePrice!!}
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
              
                </div>
            </div>
            <div class="box-content">
            <div class="customerBidDetails">
                <table>
                 <tr><td> {!!$services->service->desc!!}</td></tr>
                  
                    <tr>
                    <div class="row-fluid browse-sec">
                       <?php if(count($services->serviceImages ) >0){?>
                         <a href="javascript:;" class="viewBtn bluBtn">View Photos</a>
                         <?php }else { ?>
                          <h5> No Bid Images have been uploaded</h5>
                         <?php
                       }
                       ?>

                             <div class="reviewimagespopup">
                    <i class="clsIconPop">X</i>
                     <div class="cycle-slideshow cycledv" data-cycle-slides="li" data-cycle-fx='scrollHorz' data-cycle-speed='700' data-cycle-timeout='7000' data-cycle-log="false" data-cycle-prev=".reviewimagespopup .prev" data-cycle-next=".reviewimagespopup .next" data-cycle-pager=".example-pager">
                        <ul>
                          {{--*/ $loop = 1 /*--}}
                               @foreach($services->serviceImages as $images)
                          <li><a href="{!!URL::to('/')!!}/{!!Config::get('app.request_images').'/'.$images->image_name!!}" class="dwnldBtn bluBtn" target="_blank">Download</a>{!! HTML::image(Config::get('app.request_images').'/'.$images->image_name) !!}
                          </li>
                         
                          @endforeach
                        </ul>
                  </div>
                </div>

                     


                    </div>
                    </tr>
                </table>
                </div>
                <div>
                <fieldset>
                <p id="errorCalender" class="alert alert-error" style="display:none;"></p>
                     <legend><a class="btn btn-info" href="#" onclick="printDiv('printpdf')" > Convert to PDF  </a></legend>
                    <div> 
                     Bid Price : <input type="text" value="{!!$servicePrice!!}" disabled="disabled" >
                    </div>
                    <div>  @if($services->service_note != '')
                  <h2>Note For Customer :</h2>
                  <textarea disabled="disabled">{!!$services->customer_notes_bid!!}</textarea>
                 
                    @endif
                    </div>
                    
                    <div>
                     <button class="btn btn-large btn-danger"  onclick="declineBidRequestNotes('{!!$request_detail->id!!}')" >Decline</button>
                     <input type="text" class="input-small span5 datepicker " placeholder="Due date" id="date_completion_appears" name="completion_date" @if($request_detail->status==7) disabled @endif style="display:none;">  
                     <button class="btn btn-large btn-success" onclick="approveBidRequest('{!!$request_detail->id!!}','{!!$vendor_id!!}')" id="approvebutton" @if($request_detail->status==7) disabled @endif >Approve</button>
                    </div>
                </fieldset>
                </div>
                     
            </div>
        </div><!--/span-->

    </div><!--/row-->
    @endforeach
   <div style="float:right;"> <h2>Total Price : ${!!$totalPrice!!}</h2></div>

</div>
 <div class="modal  hide fade modelForm larg-model"  id="declinebidNotes">
        <h2>Declined Notes:</h2>
        <textarea id="declinebidnotes" cols="25" rows="5"></textarea>
         <input type="hidden" id="declinenoteshidden">
         <button onclick="declineBidRequest()">Save</button>
</div>
<div class="modal  hide fade modelForm larg-model"  id="showServiceid">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h1>Property Detail</h1>
            </div>
            <div class="modal-body">

                <div class="row-fluid">
                    <div class="box span12 noMarginTop">
                        <div class="custome-form assets-form">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Property Number:</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->asset_number!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Property Address:</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->property_address!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">City: </label>
                                                <label class="control-label" for="typeahead"> {!!$request_detail->asset->city->name!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">State:</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->state->name!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Zip :</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->zip!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Lockbox </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->lock_box!!}</label>
                                            </div>

                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Get / Access Code: </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->access_code!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Property Status: </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->property_status!!}</label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Customer Email Adress: </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->customer_email_address!!}</label>
                                            </div>
                                        </div>
                                        <!--/span-6-->

                                        <div class="span6">
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Loan Number:</label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->loan_number!!} </label>
                                            </div>
                                            <div class="control-group row-sep">
                                                <label class="control-label" for="selectError3">Property Type:</label>
                                                <label class="control-label" for="selectError3">{!!$request_detail->asset->property_type!!}</label>
                                            </div>


                                            <div class="control-group row-sep">
                                                <label class="control-label" for="typeahead">Agent : </label>
                                                <label class="control-label" for="typeahead">{!!$request_detail->asset->agent!!}</label>
                                            </div>




                                            <div class="clearfix"></div>
                                        </div>
                                        <!--/span-6-->

                                    </div>
                                    <div class="row-fluid">


                                        <br>
                                        <div class="control-group">
                                            <label class="control-label">Outbuilding / Shed *</label>
                                            <label class="control-label">{!!isset($request_detail->asset->outbuilding_shed) && $request_detail->asset->outbuilding_shed == 1 ? 'Yes': 'No'!!}</label>
                                            <div style="clear:both"></div>
                                            <div class="control-group hidden-phone">
                                                <div class="controls">
                                                    <label class="control-label" for="textarea2">Note:</label>
                                                    <label class="control-label label-auto" for="textarea2">{!!$request_detail->asset->outbuilding_shed_note!!}</label>
                                                </div>
                                            </div>
                                            <div class="control-group hidden-phone">
                                                <div class="controls">
                                                    <label class="control-label" for="textarea2">Directions or Special Notes:</label>
                                                    <label class="control-label label-auto" for="textarea2">This is directions or special notes</label>
                                                </div>
                                            </div>
                                        </div>
                                        <h4>Utility - On inside Property?</h4>
                                        <div class="control-group">
                                            <label class="control-label">Electric :</label>
                                            <label class="control-label">{!!isset($request_detail->asset->electric_status) && $request_detail->asset->electric_status == 1 ? 'Yes': 'No'!!}</label>
                                            <div style="clear:both"></div>

                                            <div class="control-group">
                                                <label class="control-label">Water *</label>
                                                <label class="control-label">{!!isset($request_detail->asset->water_status) && $request_detail->asset->water_status == 1 ? 'Yes': 'No'!!}</label>
                                            </div>

                                                <div class="control-group">
                                                    <label class="control-label">Gas *</label>
                                                    <label class="control-label">{!!isset($request_detail->asset->gas_status) && $request_detail->asset->gas_status == 1 ? 'Yes': 'No'!!}</label>
                                                </div>
                                                    <div class="control-group multiRadio">
                                                        <label class="control-label">Swimming *</label>
                                                        <label class="control-label">{!!$request_detail->asset->swimming_pool!!}</label>

                                                    </div>

                                                </div>
                                                </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                        <!--/span-->

                                    </div>
                                    <!--/row-->

                                    </div>


  <div class="modal  hide fade modelForm larg-model"  id="declinebidNotes">
        <h2>Declined Notes:</h2>
        <textarea id="declinebidnotes" cols="30" rows="30"></textarea>
         <input type="hidden" id="declinenoteshidden">
         <button onclick="declineBidRequest()"></button>
</div>
  
                                    <div class="modal-footer">
                                        <div class="text-right">
                                            <button type="button" class="btn btn-large btn-inverse" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>

<div id="printpdf" style="display:none;">
<style type="text/css">

    body { font-family:arial; margin:0; padding:0; }
    #pdfMain { font-family:arial; padding:40px 0; }
    #pdfMain table { width:100%; border-collapse:collapse; }
    #pdfMain table tr th,
    #pdfMain table tr td { border:2px solid #7170C3; padding:10px 20px; font-size: 17px; line-height: 25px; color: #484790; }
    #pdfMain table tr th { background:#CDCDFF; }
    #pdfMain table tr .noBrdr { border:none; }
    .wrapper { margin:0 auto; width:980px; }
    .clearfix:after { clear: both; content:""; display:block; }

    #pdfHeader { text-align:center; }
    #pdfHeader .leftPnl {  float:left; }
    #pdfHeader .leftPnl p { font-size: 18px; line-height: 23px; text-align:left; }
    #pdfHeader .leftPnl p strong { display:block; margin-bottom: 10px; font-size: 25px; }
    #pdfHeader .rightPnl { float:right }
    #pdfHeader .rightPnl h2 { margin:0 0 10px; text-align: right; font-size: 35px; }
    #pdfHeader .rightPnl tr td { min-width:130px; text-align:center; }
    #pdfHeader .pdfLogo {  }
    
    #pdfMain .cstmrName { margin:50px 0 30px; }
    #pdfMain .cstmrName p { color: #6160A5; font-size: 20px; line-height: 26px; margin:0 0 10px; }
    #pdfMain .cstmrName p input { border:2px solid #7170C3; padding:10px 20px; width:350px; border-radius:10px; background:#CDCDFF; color:#6160A5; font-size:20px; }
    
    #pdfMain .projectTble .smlTbl  { width: 400px; float: right; }
    #pdfMain .projectTble .smlTbl th, #pdfMain .projectTble .smlTbl td { padding:18px 20px; text-align: center; }
    
    #pdfMain .btmNote { margin:40px 0; }
    #pdfMain .btmNote p { margin:0; padding:10px 0; font-size:17px; }
    
    #pdfMain .ftrPdf { text-align:center; }
    #pdfMain .ftrPdf span { float:left; font-size:19px; color: #6160A5; }
    #pdfMain .ftrPdf a { font-size:19px; color: #6160A5; }
    #pdfMain .ftrPdf .mailt { float:right; }

</style>

<div id="pdfMain" class="wrapper" >
    
    <div id="pdfHeader" class="clearfix">
        <a class="pdfLogo" href="#"></a>
        <div class="leftPnl">
            <p><strong>Good Scents Services, LP </strong> 118 National Dr <br>Rockwall TX 75032</p>
        </div>
        <div class="rightPnl">
            <h2>Estimate</h2>
            <table>
                <tbody>
                    <tr>
                        <th>Date</th>
                        <th>Estimate No.</th>
                    </tr>
                    <tr>
                        <td>{!! date('m/d/Y', strtotime($request_detail->created_at)) !!}</td>
                        <td>{!!$request_detail->id!!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="cstmrName">
        <p><input id="" type="text" placeholder="@if(isset($request_detail->user->first_name)) {!!$request_detail->user->first_name!!} @endif @if(isset($request_detail->user->last_name)) {!!$request_detail->user->last_name!!} @endif"></p>
        <p>@if(isset($request_detail->user->address_1)) {!!$request_detail->user->address_1!!} @endif </p>
    </div>

    <div class="projectTble">
        <table class="smlTbl">
            <tbody>
                <tr>
                    <th>Project</th>
                </tr>
                <tr>
                    <td>{!!$request_detail->asset->property_address!!} </th>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
                <?php
                $pricing=0;?>
                 @foreach($request_detail->requestedService as $services)
                  <?php
                $pricing+=$servicePrice;?>
                <tr>
                    <td>{!!$services->service->title!!}</td>
                    <td>{!!$services->service->desc!!}</td>
                    <td>&nbsp;</td>
                    <td>${!!$servicePrice!!}</td>
                </tr>
                 @endforeach
                
               
                <tr>
                    <td colspan="2" class="noBrdr">We look forward to doing business with you.</td>
                    <td>Total</td>
                    <td>${!!$pricing!!}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
  

    <div class="ftrPdf clearfix">
        <span>972-772-0209</span>
        <a href="#">www.gssreo.com </a>
        <a class="mailt" href="mailto:accounting@gssreo.com">accounting@gssreo.com </a>
    </div>
    
</div>
</div>
                                    @stop