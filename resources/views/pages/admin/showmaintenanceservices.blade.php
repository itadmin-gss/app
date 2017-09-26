<style>
  .modal-header {
    border-bottom: 0 solid #eee !important;
    padding: 0 !important;
  }
  .redcross
  {
    color:
  }
 </style>

<script type="text/javascript">
 jQuery('#vendor_id_chossen').on('change', function(evt, params) {
  
   jQuery('input[name="vendor"]').val(this.value);
   if(this.value!="")
   {
   jQuery('input[name="vendor"]').attr('checked','checked');
   }
   else
   {
     jQuery('input[name="vendor"]').removeAttr('checked');
   }
 });

</script>
<!--<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>-->
<script>

    var baseurl = "{!!Config::get('app.url')!!}";

</script>

<script src="{!! URL::asset('assets/js/jquery.chosen.min.js') !!}"></script>
<script src="{!! URL::asset('assets/js/custom.js') !!}"></script>


<div class="modal-header">


</div>
<div class="modal-body" style="min-height:400px !important;">
  <div class="row-fluid">

    {!! Form::open(array('url' => 'assign-service-request','id'=>'assignRequest')) !!}

    <div id="errorMessage" style="display: none;"> </div>
    <div  style="width:100%;float:left;"><h3 style="width:50%;float:left;">Requested Services</h3>
      <h3 style="width:50%;float:left;">Available Vendors</h3></div>
      {!! Form::hidden('request_id', $request_maintenance->id);!!}


      @if(count($request_maintenance->requestedService())==count($assigned_services))

     
      <table class="table table-striped table-bordered" style="width:48%;float:left;margin-right: 2%;"><tr>
       <td class="center">

         All requested services have been assigned
       </td>

     </tr>
   </table>
   @else
   <table class="table table-striped table-bordered" style="width:48%;float:left;margin-right: 2%;">

    <thead>

      <tr>
        <th>Select</th>
        <th>Service Name</th>

      </tr>
    </thead>   
    <tbody>

     @foreach ($request_maintenance->requestedService()->get() as $services)
     @if(!in_array($services->id,$assigned_services)) 
     <tr>
       <td class="center">{!! Form::checkbox('services[]', $services->id);!!}


        <td class="center">{!!$services->service->title!!}</td>

      </tr>
      @endif

      @endforeach



    </tr>
  </tbody>
</table>  
@endif

<table class="table table-striped table-bordered" style="width:48%;float:left;">
  <thead>

    <tr>
      
      <th>Vendor Name</th>

    </tr>
  </thead>   
  <tbody>
    <tr>
     <td class="center">
       <div class="controls"  style="width: 380px">

       <select name="vendor_id" class="span7 typeahead" id="vendor_id_chossen" data-rel='chosen' style='width: 520px;'>
   
  
        <?php
        $firstVendorId="";
        $firstVendorCounter=0;
        $totalCounter=0;
        $vendor_data = array('' => 'Select Vendor');
        foreach ($vendors as $vendor) {


if($techDatalatitude[$vendor->id]==1)
          {
            $totalCounter++;
          $vendorName="";
          $isdiabled="";
          $vendorName = $vendor->first_name." ".$vendor->last_name." ".$vendor->company;
        

        ?> <?php
         if(in_array($vendor->id,$already_assigned_users))  
           {
            $vendorName .= " (Already Declined)";
           
            ?>
             <optgroup  value="{!!$vendor->id!!}" label="{!!$vendorName!!}" ><option value="" style="display:none"> Can't Select</option></optgroup>
      
            <?php
           }else{?>
        <option value="{!!$vendor->id!!}" >{!!$vendorName!!}</option>
        <?php
         }
       
       if($firstVendorCounter==0){
       $firstVendorId=$vendor->id;
       $firstVendorCounter=1;
              }
        }
}
        if($totalCounter==0)
         {
       ?>
        <option value="">No available vendors</option>
       
       <?php }?>
</select> 
<div style="display:none;">
{!! Form::radio('vendor',  $firstVendorId,true);!!} 
</div>

       <!-- {!! Form::select('vendor_id',  $vendor_data, '', array('class'=>'span7 typeahead','id'=>'vendor_id_chossen', 'data-rel'=>'chosen','style'=>'width: 520px;'))!!}
 -->
     </td>



   </tr>


   <tr>
     <td>     
     </div></td>
   </tr>


 </tr>
</tbody>
</table>  
{!! Form::close() !!}
</div>



</div>
<div class="modal-footer">
  <a href="#" class="btn" data-dismiss="modal">Close</a>
  <a href="#" class="btn btn-primary" onclick="assign_request()">Save</a>
</div>
