<div class="modal hide fade modelForm"  id="{!!$service_data->id!!}">
    {!!Form::open(array('class' => 'add_service_form', 'id' => $service_data->id, 'files'=>true))!!}
    <div class="modal-header">
        <button style="display: none;" type="button" class="close" data-dismiss="modal">x</button>
        <h3>{!!$service_data->title!!}</h3>
        <div class="adddescription"> {!!$service_data->desc!!} </div>
    </div>
    <div class="modal-body">
        <div class="alert alert-error" style="display:none;" id="error_messages_{!!$service_data->id!!}"></div>


        <div class="row-fluid">

            @if($service_data->req_date == 1)
            <div class="control-group">
                {!!Form::label('Service Required Date', 'Services Required Date:', array('class'=>'control-label'))!!}
                <div class="controls timesection">
                    {!!Form::text('service_required_date_'.$service_data->id, '', array('class'=> 'input-xlarge span5 datepicker', 'id'=> 'service_required_date_'.$service_data->id ))!!}
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                        $time_hours[$i] = $i;
                    }
                    ?>
                    {!!Form::select('time_hours_'.$service_data->id,  $time_hours, array('class'=>'span2','id'=>'time_hours'))!!}
                    <?php
                    for ($i = 0; $i <= 60; $i++) {
                        $number = sprintf("%02s", $i);
                        $time_minutes[$number] = $number;
                    }
                    ?>
                    {!!Form::select('time_minutes_'.$service_data->id,  $time_minutes, array('class'=>'span2','id'=>'time_minutes'))!!}


                    <?php $meridiem = array('AM' => 'AM', 'PM' => 'PM'); ?>
                    {!!Form::select('time_meridiem_'.$service_data->id,  $meridiem, array('class'=>'span2','id'=>'time_meridiem'))!!}
                </div>

            </div>
            @endif

              <div class="row-fluid">
            @if($service_data->due_date == 1)
            <div class="control-group">
                {!!Form::label('Due Date', 'Due Date:', array('class'=>'control-label'))!!}
                <div class="controls timesection">
                    {!!Form::text('due_date_'.$service_data->id, '', array('class'=> 'input-xlarge span5 datepicker', 'id'=> 'due_date_'.$service_data->id ,'style'=>'border:3px solid','required'))!!}
                    </div>

            </div>
            @endif

              @if($service_data->emergency == 1)
            <div class="control-group">
                {!!Form::label('Emergency', 'Emergency Request:', array('class'=>'control-label'))!!}
                <div class="controls timesection">
                    {!!Form::checkbox('emergency_'.$service_data->id,1, '', array('class'=> 'input-xlarge span5', 'id'=> 'emergency_'.$service_data->id))!!}
                    </div>

            </div>
            @endif


         <!--    <div class="control-group">

                <div class="span6">
                    {!!Form::label('quantity', 'Quantity:', array('class'=>'control-label'))!!}
                    <div class="controls">
             {!!Form::text('quantity_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'quantity_'.$service_data->id))!!}
                </div>
                </div>
            </div> -->

            @if($service_data->number_of_men == 1)

            <div class="control-group">

                <div class="span6">
                    {!!Form::label('number_of_men', 'Number of Men:', array('class'=>'control-label'))!!}
                    <div class="controls">
                    @if($serviceTypeArray['number_of_men']=="text")
                        {!!Form::text('number_of_men_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'number_of_men_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['number_of_men']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['number_of_men']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('number_of_men_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'number_of_men_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['number_of_men']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['number_of_men']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('number_of_men',$value, '', array("onClick"=>"replicateValues('number_of_men_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('number_of_men_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'number_of_men_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['number_of_men']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['number_of_men']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('number_of_men',$value, '', array("onClick"=>"appendValues('number_of_men_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('number_of_men_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'number_of_men_'.$service_data->id))!!}


                    @endif

                    </div>
                </div>

            </div>
            @endif

            @if($service_data->verified_vacancy == 1)

            <div class="control-group">

                <div class="span6">
                    {!!Form::label('verified_vacancy', 'Verified Vacancy:', array('class'=>'control-label'))!!}
                    <div class="controls">

                    @if($serviceTypeArray['verified_vacancy']=="text")
                        {!!Form::text('verified_vacancy_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'verified_vacancy_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['verified_vacancy']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['verified_vacancy']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('verified_vacancy_'.$service_data->id, $selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'verified_vacancy_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['verified_vacancy']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['verified_vacancy']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('verified_vacancy',$value, '', array("onClick"=>"replicateValues('verified_vacancy_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('verified_vacancy_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'verified_vacancy_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['verified_vacancy']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['verified_vacancy']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('verified_vacancy',$value, '', array("onClick"=>"appendValues('verified_vacancy_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('verified_vacancy_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'verified_vacancy_'.$service_data->id))!!}


                    @endif
                    </div>
                </div>

            </div>
            @endif


            @if($service_data->cash_for_keys == 1)

            <div class="control-group">

                <div class="span6">
                    {!!Form::label('cash_for_keys', 'Cash for keys:', array('class'=>'control-label'))!!}
                    <div class="controls">
                    
                      @if($serviceTypeArray['cash_for_keys']=="text")
                        {!!Form::text('cash_for_keys_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'cash_for_keys_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['cash_for_keys']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['cash_for_keys']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>

                    {!!Form::select('cash_for_keys_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'cash_for_keys_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['cash_for_keys']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['cash_for_keys']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('cash_for_keys',$value, '', array("onClick"=>"replicateValues('cash_for_keys_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('cash_for_keys_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'cash_for_keys_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['cash_for_keys']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['cash_for_keys']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('cash_for_keys',$value, '', array("onClick"=>"appendValues('verified_vacancy_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('cash_for_keys_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'cash_for_keys_'.$service_data->id))!!}


                    @endif



                    </div>
                </div>

            </div>
            @endif


            @if($service_data->cash_for_keys_trash_out == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('cash_for_keys_trash_out', 'Cash for keys trash out:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       

                      @if($serviceTypeArray['cash_for_keys_trash_out']=="text")
                        {!!Form::text('cash_for_keys_trash_out_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'cash_for_keys_trash_out_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['cash_for_keys_trash_out']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['cash_for_keys_trash_out']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>

                    {!!Form::select('cash_for_keys_trash_out_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'cash_for_keys_trash_out_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['cash_for_keys_trash_out']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['cash_for_keys_trash_out']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('cash_for_keys_trash_out',$value, '', array("onClick"=>"replicateValues('cash_for_keys_trash_out_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('cash_for_keys_trash_out_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'cash_for_keys_trash_out_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['cash_for_keys_trash_out']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['cash_for_keys_trash_out']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('cash_for_keys_trash_out',$value, '', array("onClick"=>"appendValues('cash_for_keys_trash_out_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('cash_for_keys_trash_out_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'cash_for_keys_trash_out_'.$service_data->id))!!}


                    @endif



                    </div>
                </div>

            </div>

            @endif

            @if($service_data->trash_size == 1)

            <div class="control-group">

                <div class="span6">
                    {!!Form::label('trash_size', 'Trash Size:', array('class'=>'control-label'))!!}
                    <div class="controls">
                      
                     @if($serviceTypeArray['trash_size']=="text")
                        {!!Form::text('trash_size_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'trash_size_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['trash_size']=="select")
                   
 <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['trash_size']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>

                    {!!Form::select('trash_size_'.$service_data->id, $selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'trash_size_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['trash_size']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['trash_size']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('trash_size',$value, '', array("onClick"=>"replicateValues('trash_size_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('trash_size_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'trash_size_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['trash_size']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['trash_size']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('trash_size',$value, '', array("onClick"=>"appendValues('trash_size_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('trash_size_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'trash_size_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>
            @endif

            @if($service_data->storage_shed == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('storage_shed', 'Storage shed:', array('class'=>'control-label'))!!}
                    <div class="controls">
                        
                         @if($serviceTypeArray['storage_shed']=="text")
                        {!!Form::text('storage_shed_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'storage_shed_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['trash_size']=="select")
                     <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['storage_shed']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('storage_shed_'.$service_data->id,  $selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'storage_shed_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['storage_shed']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['storage_shed']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('storage_shed',$value, '', array("onClick"=>"replicateValues('storage_shed_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('storage_shed_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'storage_shed_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['storage_shed']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['storage_shed']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('storage_shed',$value, '', array("onClick"=>"appendValues('storage_shed_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('storage_shed_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'storage_shed_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif

            @if($service_data->lot_size == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('lot_size', 'Lot size:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['lot_size']=="text")
                        {!!Form::text('lot_size_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'lot_size_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['lot_size']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['lot_size']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('lot_size_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'lot_size_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['lot_size']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['lot_size']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('lot_size',$value, '', array("onClick"=>"replicateValues('lot_size_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('lot_size_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'lot_size_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['lot_size']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['lot_size']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('lot_size',$value, '', array("onClick"=>"appendValues('lot_size_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('lot_size_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'lot_size_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif


                 @if($service_data->set_prinkler_system_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('set_prinkler_system_type', 'Set Prinkler System Type:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['set_prinkler_system_type']=="text")
                        {!!Form::text('set_prinkler_system_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'set_prinkler_system_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['set_prinkler_system_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['set_prinkler_system_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('set_prinkler_system_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'set_prinkler_system_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['set_prinkler_system_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['set_prinkler_system_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('set_prinkler_system_type',$value, '', array("onClick"=>"replicateValues('set_prinkler_system_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('set_prinkler_system_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'set_prinkler_system_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['set_prinkler_system_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['set_prinkler_system_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('set_prinkler_system_type',$value, '', array("onClick"=>"appendValues('set_prinkler_system_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('set_prinkler_system_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'set_prinkler_system_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 




                 @if($service_data->install_temporary_system_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('install_temporary_system_type', 'Install Temporary System Type:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['install_temporary_system_type']=="text")
                        {!!Form::text('install_temporary_system_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'install_temporary_system_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['install_temporary_system_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['install_temporary_system_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('install_temporary_system_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'install_temporary_system_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['install_temporary_system_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['install_temporary_system_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('install_temporary_system_type',$value, '', array("onClick"=>"replicateValues('install_temporary_system_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('install_temporary_system_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'install_temporary_system_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['install_temporary_system_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['install_temporary_system_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('install_temporary_system_type',$value, '', array("onClick"=>"appendValues('install_temporary_system_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('install_temporary_system_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'install_temporary_system_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 


                 @if($service_data->carpet_service_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('carpet_service_type', 'Carpet Service Type:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['carpet_service_type']=="text")
                        {!!Form::text('carpet_service_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'carpet_service_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['carpet_service_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['carpet_service_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('carpet_service_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'carpet_service_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['carpet_service_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['carpet_service_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('carpet_service_type',$value, '', array("onClick"=>"replicateValues('carpet_service_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('carpet_service_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'carpet_service_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['carpet_service_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['carpet_service_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('carpet_service_type',$value, '', array("onClick"=>"appendValues('carpet_service_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('carpet_service_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'carpet_service_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 


                 @if($service_data->pool_service_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('pool_service_type', 'Pool Service Type:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['pool_service_type']=="text")
                        {!!Form::text('pool_service_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'pool_service_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['pool_service_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['pool_service_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('pool_service_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'pool_service_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['pool_service_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['pool_service_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('pool_service_type',$value, '', array("onClick"=>"replicateValues('pool_service_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('pool_service_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'pool_service_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['pool_service_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['pool_service_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('pool_service_type',$value, '', array("onClick"=>"appendValues('pool_service_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('pool_service_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'pool_service_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 


                 @if($service_data->boarding_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('boarding_type', 'Boarding Type:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['boarding_type']=="text")
                        {!!Form::text('boarding_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'boarding_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['boarding_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['boarding_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('boarding_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'boarding_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['boarding_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['boarding_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('boarding_type',$value, '', array("onClick"=>"replicateValues('boarding_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('boarding_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'boarding_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['boarding_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['boarding_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('boarding_type',$value, '', array("onClick"=>"appendValues('boarding_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('boarding_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'boarding_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 



                 @if($service_data->spruce_up_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('spruce_up_type', 'Spruce Up Type:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['spruce_up_type']=="text")
                        {!!Form::text('spruce_up_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'spruce_up_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['spruce_up_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['spruce_up_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('spruce_up_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'spruce_up_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['spruce_up_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['spruce_up_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('spruce_up_type',$value, '', array("onClick"=>"replicateValues('spruce_up_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('spruce_up_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'spruce_up_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['spruce_up_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['spruce_up_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('spruce_up_type',$value, '', array("onClick"=>"appendValues('spruce_up_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('spruce_up_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'spruce_up_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 


               @if($service_data->constable_information_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('constable_information_type', 'Constable Information :', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['constable_information_type']=="text")
                        {!!Form::text('constable_information_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=>'constable_information_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['constable_information_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['constable_information_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('constable_information_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'constable_information_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['constable_information_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['constable_information_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('constable_information_type',$value, '', array("onClick"=>"replicateValues('constable_information_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('constable_information_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'constable_information_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['constable_information_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['constable_information_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('constable_information_type',$value, '', array("onClick"=>"appendValues('constable_information_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('constable_information_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'constable_information_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 


               @if($service_data->remove_carpe_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('remove_carpe_type', 'Remove Carpet:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['remove_carpe_type']=="text")
                        {!!Form::text('remove_carpe_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_carpe_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['remove_carpe_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['remove_carpe_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('remove_carpe_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_carpe_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['remove_carpe_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['remove_carpe_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('remove_carpe_type',$value, '', array("onClick"=>"replicateValues('remove_carpe_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('remove_carpe_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_carpe_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['remove_carpe_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['remove_carpe_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('remove_carpe_type',$value, '', array("onClick"=>"appendValues('remove_carpe_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('remove_carpe_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_carpe_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 

               @if($service_data->remove_blinds_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('remove_blinds_type', 'Remove Blinds:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['remove_blinds_type']=="text")
                        {!!Form::text('remove_blinds_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_blinds_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['remove_blinds_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['remove_blinds_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('remove_blinds_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_blinds_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['remove_blinds_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['remove_blinds_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('remove_blinds_type',$value, '', array("onClick"=>"replicateValues('remove_blinds_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('remove_blinds_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_blinds_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['remove_blinds_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['remove_blinds_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('remove_blinds_type',$value, '', array("onClick"=>"appendValues('remove_blinds_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('remove_blinds_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_blinds_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 

               @if($service_data->remove_appliances_type == 1)
            <div class="control-group">

                <div class="span6">
                    {!!Form::label('remove_appliances_type', 'Remove Appliances:', array('class'=>'control-label'))!!}
                    <div class="controls">
                       @if($serviceTypeArray['remove_appliances_type']=="text")
                        {!!Form::text('remove_appliances_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_appliances_type_'.$service_data->id))!!}
                     @elseif($serviceTypeArray['remove_appliances_type']=="select")
                    <?php
                    $selectData=array();
                    $variable  = explode(",",$serviceValueArray['remove_appliances_type']);
                    foreach ($variable as $key => $value) {
                        $selectData[$value]=$value;
                    }
                    ?>
                    {!!Form::select('remove_appliances_type_'.$service_data->id,$selectData, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_appliances_type_'.$service_data->id))!!}
                    
                    @elseif($serviceTypeArray['remove_appliances_type']=="radio")

                    <?php
                   $radioDATA= explode(",",$serviceValueArray['remove_appliances_type']);
                   foreach ($radioDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%"> 
                  {!!$value!!}     
                    </td>
                  <td style="width: 50%">
                   {!!Form::radio('remove_appliances_type',$value, '', array("onClick"=>"replicateValues('remove_appliances_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                  
                  </tr>
                  </table>
                    <?php
                    }

                    ?>
                    {!!Form::hidden('remove_appliances_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_appliances_type_'.$service_data->id))!!}
                    @elseif($serviceTypeArray['remove_appliances_type']=="checkbox")


                    <?php
                   $checkboxDATA= explode(",",$serviceValueArray['remove_appliances_type']);
                   foreach ($checkboxDATA as $key => $value) {
                    
                    ?>
                    <table style="width: 111px;">
                   <tr> 
                   <td style="width: 50%">   {!!$value!!} </td>
                   <td style="width: 50%"> {!!Form::checkbox('remove_appliances_type',$value, '', array("onClick"=>"appendValues('remove_appliances_type_$service_data->id',this)" ,'class'=> 'input-xlarge span5', 'id'=> $key.$service_data->id))!!}
                   </td>
                    </tr>
                    </table>
                   <?php
                    }

                    ?>
                    {!!Form::hidden('remove_appliances_type_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'remove_appliances_type_'.$service_data->id))!!}


                    @endif


                    </div>
                </div>

            </div>

            @endif 

            <div class="control-group">

                <div class="span6">
                    {!!Form::label('Upload_image'.$service_data->id, 'Upload Image:', array('class'=>'control-label'))!!}

                    <div class="controls">
                        {!!Form::file('service_image_'.$service_data->id.'[]', array('multiple'=>true, 'id'=>'service_image_'.$service_data->id))!!}
                    </div>
                </div>

            </div>
             @if($service_data->recurring == 1)
            <div class="control-group" style="float: left;width: 520px;">

               
                    {!!Form::label('recurring', 'Recurring:', array('class'=>'control-label'))!!}
                    <div class="controls">
                      {!! Form::checkbox('recurring_'.$service_data->id, '' ,false,['onClick' => "openFields(this,'$service_data->id')"] ) !!} 
                      </div>
              
                <div style="display:none;" id="recurring_fields_{!!$service_data->id!!}" >
                 <div class="control-group" >
                    {!!Form::label('Duration', 'Duration:', array('class'=>'control-label'))!!}
                  
                    <div class="controls">
                        {!!Form::text('duration_'.$service_data->id, '', array('class'=> 'input-xlarge span5', 'id'=> 'duration_'.$service_data->id))!!}
                      <span>(Days)<span>
                    </div>
                </div>
                <div class="control-group">
                {!!Form::label('Starting Date', 'Starting Date:', array('class'=>'control-label'))!!}
                <div class="controls timesection">
                    {!!Form::text('start_date_'.$service_data->id, '', array('class'=> 'input-xlarge span5 datepicker', 'id'=> 'start_date_'.$service_data->id ))!!}
                </div>

                </div>
                  <div class="control-group">
                {!!Form::label('End Date', 'End Date:', array('class'=>'control-label'))!!}
                <div class="controls timesection">
                    {!!Form::text('end_date_'.$service_data->id, '', array('class'=> 'input-xlarge span5 datepicker', 'id'=> 'end_date_'.$service_data->id ))!!}
                </div>

                </div>
                </div>

            </div>

            @endif

            <div class="clearfix"></div>

           
            <div class="control-group hidden-phone">
                <div class="controls">
                    {!!Form::label('service_note_'.$service_data->id, 'Notes:', array('class'=>'control-label'))!!}
                    {!!Form::textarea('service_note_'.$service_data->id, '', array('class'=>'span12', 'id'=>'service_note_'.$service_data->id, 'rows'=>'7','style'=>"border:5px solid"))!!}
                    {!!Form::hidden('service_id', $service_data->id, array('id'=>'current_form_service_id'))!!}
                    {!!Form::hidden('service_name', $service_data->title)!!}
                </div>
            </div>


        </div>


    </div>
    <div class="modal-footer">
        <div id='ajax-loader-{!!$service_data->id!!}' class="ajax-loader" style="display: none;"><img src="{!!Config::get('app.img_dir')!!}/progress2.gif"> loading...</div>
        <a href="#" onclick="close_popup({!!$service_data->id!!})" class="btn" data-dismiss="modal">Close</a>


        <button id="{!!$service_data->id!!}" class="btn btn-primary save_service">Save</button>
<!--        <input type="submit" id="{!!$service_data->id!!}"  class="btn btn-primary save_service" value="Save">-->
    </div>

    {!!Form::close()!!}
</div>
<script>

function openFields(obj,id)
{  
 
    if( $(obj).is(':checked'))
    {
  
    $("#recurring_fields_"+id).show('slow');
    }
    else
    {
        $("#recurring_fields_"+id).hide('slow');
    }
}


            $(".add_service_form").on('submit', (function(e) {



    var current_sevice_id_selected = this.id;
            var errors = '';
//            if ($('#service_required_date_' + current_sevice_id_selected).val() == "") {
//    errors += 'Please select required date.<br>';
//    }

//    if ($('#service_image_' + current_sevice_id_selected).val() == "") {
//    errors += 'Please choose image to upload.<br>';
//    }



    if (errors != '') {
    $('#error_messages_' + current_sevice_id_selected).html(errors).slideDown('slow');
            return false;
    }

    $('#ajax-loader-' + current_sevice_id_selected).show();
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax(
            {
            url: '{!!URL::to("ajax-service-information-list")!!}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data)
                    {
       
               
                    $('#list_of_services').append(data);
                            // var current_form_service_id = $('#current_form_service_id').val();
                            $('#' + current_sevice_id_selected).modal('hide');
                            $('#ajax-loader-' + current_sevice_id_selected).hide();

                            var dateToday = new Date();
                            $('.datepicker_list').datepicker({
                    minDate: dateToday
                    });

                            @if($service_data->bid_flag==1)
                               $('.nxtStep').trigger('click');
                            @endif


//                            $('.datepicker_list').datepicker();
                    },
                    error: function()
                    {

                    }
            });


    $.ajax(
            {
            url: '{!!URL::to("ajax-service-information-list-review-order")!!}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data)
                    {
        
                    $('#order_review_serviceType').append(data);
                 
                    },
                    error: function()
                    {

                    }
            });


    }));
            function close_popup(id){
            $('#service_ids option[value="' + id + '"]').prop("selected", false);
                    $('#service_ids').trigger('liszt:updated');
                    $('#error_messages_' + id).slideUp('slow');
                     $('#ajax-loader-' + id).hide();
                     $('.popUpOvrlay ').hide();
                     $('.modal-backdrop ').hide();
                     
                    return false;
            }
    function removeservice(id) {
    var over = '<div id="overlay">' +
            '<img id="loading" src="public/assets/img/loader.gif">' +
            '</div>';
            $(over).appendTo('body');
            var image_name = $('#' + id + '_input').val();
            $.ajax({
            type: 'Post',
                    url: 'remove-file-from-directory',
                    data: {
                    image_name: image_name
                    },
                    cache: false,
                    success: function(response) {
                    $('#' + id + '_image').remove();
                            $('#' + id + '_input').remove();
                            $('#' + id + '_li').remove();
                            $('#overlay').remove();
                    }
            });
    }


</script>

