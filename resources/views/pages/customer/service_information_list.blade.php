<div id="services_list_{!!$data['service_id']!!}" class="box span12 noMarginTop listSlide serviceReqst">
    <div class="box-header ">
        <h2>{!!$data['service_name']!!}</h2>
        <div class="box-icon">
            <a href="javascript:;" title="Expand" class=""><i class="halflings-icon chevron-up"></i></a>
        </div>
    </div>
    <div class="box-content accordion-group request-box-content">



    	  @if(isset($data['biding_prince_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('biding_prince_', 'Bid Price:', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('biding_prince_'.$data['service_id'], $data['biding_prince_'.$data['service_id']], array('readonly'=>'readonly' ,'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif




        <div class="row-fluid">



            @if(isset($data['service_required_date_'.$data['service_id']]))
            <div class="control-group">
                {!!Form::label('asset_number', 'Services Required Date:', array('class'=>'control-label'))!!}
                <div class="controls timesection">
                    {!!Form::text('service_required_date_'.$data['service_id'], $data['service_required_date_'.$data['service_id']], array('class'=> 'input-xlarge span5 datepicker_list'))!!}
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                        $time_hours[$i] = $i;
                    }
                    ?>
                    {!!Form::select('time_hours_'.$data['service_id'],  $time_hours, $data['time_hours_'.$data['service_id']], array('class'=>'span2','id'=>'time_hours'))!!}
                    <?php
                    for ($i = 0; $i <= 60; $i++) {
                        $number = sprintf("%02s", $i);
                        $time_minutes[$number] = $number;
                    }
                    ?>
                    {!!Form::select('time_minutes_'.$data['service_id'],  $time_minutes, $data['time_minutes_'.$data['service_id']], array('class'=>'span2','id'=>'time_minutes'))!!}


                    <?php $meridiem = array('AM' => 'AM', 'PM' => 'PM'); ?>
                    {!!Form::select('time_meridiem_'.$data['service_id'],  $meridiem,  $data['time_meridiem_'.$data['service_id']],  array('class'=>'span2','id'=>'time_meridiem'))!!}
                </div>

            </div>
            @endif

        </div>

  @if(isset($data['due_date_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('due_date', 'Due Date:', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('due_date_'.$data['service_id'], $data['due_date_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",  'class'=> 'input-xlarge span12 datepicker_list'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

         @if(isset($data['emergency_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('emergency', 'Emergency Request:', array('class'=>'control-label'))!!}


                    {!!Form::checkbox('emergency_'.$data['service_id'],$data['emergency_'.$data['service_id']], $data['emergency_'.$data['service_id']], array('class'=> 'input-xlarge span5'))!!}


                </div>
            </div>

        </div>
        @endif

          @if(isset($data['quantity_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('quantity_', 'Quantity:', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('quantity_'.$data['service_id'], $data['quantity_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

        @if(isset($data['number_of_men_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('number_of_men', 'Number of Men:', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('number_of_men_'.$data['service_id'], $data['number_of_men_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

        @if(isset($data['verified_vacancy_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('verified_vacancy', 'Verified Vacancy:', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('verified_vacancy_'.$data['service_id'], $data['verified_vacancy_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

        @if(isset($data['cash_for_keys_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('cash_for_keys', 'Cash for keys: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('cash_for_keys_'.$data['service_id'], $data['cash_for_keys_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

        @if(isset($data['cash_for_keys_trash_out_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('cash_for_keys_trash_out_', 'Cash for keys trash out: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('cash_for_keys_trash_out_'.$data['service_id'], $data['cash_for_keys_trash_out_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif


        @if(isset($data['trash_size_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('trash_size', 'Trash Size: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('trash_size_'.$data['service_id'], $data['trash_size_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif


        @if(isset($data['storage_shed_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('storage_shed', 'Storage shed: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('storage_shed_'.$data['service_id'], $data['storage_shed_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

         @if(isset($data['lot_size_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('storage_shed', 'Lot Size: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('lot_size_'.$data['service_id'], $data['lot_size_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif




        @if(isset($data['set_prinkler_system_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('set_prinkler_system_type', 'Set Prinkler System Type: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('set_prinkler_system_type_'.$data['service_id'], $data['set_prinkler_system_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

        @if(isset($data['install_temporary_system_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('install_temporary_system_type', 'Install Temporary System Type: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('install_temporary_system_type_'.$data['service_id'], $data['install_temporary_system_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif


           @if(isset($data['carpet_service_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('carpet_service_type', 'Carpet Service Type: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('carpet_service_type_'.$data['service_id'], $data['carpet_service_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif


        @if(isset($data['pool_service_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('pool_service_type', 'Pool Service Type: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('pool_service_type_'.$data['service_id'], $data['pool_service_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif


          @if(isset($data['boarding_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('boarding_type', 'Boarding Type: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('boarding_type_'.$data['service_id'], $data['boarding_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

         @if(isset($data['spruce_up_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('spruce_up_type', 'Spruce Type: ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('spruce_up_type_'.$data['service_id'], $data['spruce_up_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

         @if(isset($data['constable_information_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('constable_information_type', 'Constable Information : ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('constable_information_type_'.$data['service_id'], $data['constable_information_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

         @if(isset($data['remove_carpe_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('remove_carpe_type', 'Remove Carpet : ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('remove_carpe_type_'.$data['service_id'], $data['remove_carpe_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif


        @if(isset($data['remove_blinds_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('remove_blinds_type', 'Remove Blinds : ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('remove_blinds_type_'.$data['service_id'], $data['remove_blinds_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

        @if(isset($data['remove_appliances_type_'.$data['service_id']]))
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    {!!Form::label('remove_appliances_type', 'Remove Appliances : ', array('class'=>'control-label'))!!}
                    <div class="controls">
                        {!!Form::text('remove_appliances_type_'.$data['service_id'], $data['remove_appliances_type_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge'))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

        <style>

        </style>
        @if(isset($data['service_images_'.$data['service_id']]))
        <div class="row-fluid browse-sec">
            <h2>Images</h2>
            <ul class="media-list ">


                @foreach ($data['service_images_'.$data['service_id']] as $image)
                <li id="service_image_{!!$data['service_id']!!}_{{ $loop->iteration }}_li" class="hasicon"><img src="{!!Config::get('app.request_images').'/'.$image!!}" id="service_image_{!!$data['service_id']!!}_{{ $loop->iteration }}_image"><img src='{!!Config::get('app.img_dir')!!}/close-button.png' onclick="removeservice('service_image_{!!$data['service_id']!!}_{{ $loop->iteration }}')" class='icon'></li>

                {!!Form::hidden('service_image_list_'.$data['service_id'].'[]', $image, array('id'=>'service_image_'.$data['service_id'].'_'.$loop->iteration.'_input'))!!}


                @endforeach

            </ul>
        </div>
        @endif

         @if(isset($data['recurring_'.$data['service_id']]))
           {!!Form::hidden('recurring_'.$data['service_id'], 1)!!}
        <div class="row-fluid">
            <h3>Recurring Service Details</h3>
            <div class="span6">

                  <div class="control-group" >
                    {!!Form::label('Duration', 'Duration:', array('class'=>'control-label'))!!}

                    <div class="controls">
                        {!!Form::text('duration_'.$data['service_id'], $data['duration_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge span5', 'id'=> 'duration_'.$data['service_id']))!!}
                    </div>
                </div>
            </div>

            <div class="span6">

                  <div class="control-group" >
                    {!!Form::label('Starting Date', 'Starting Date:', array('class'=>'control-label'))!!}

                    <div class="controls">
                        {!!Form::text('start_date_'.$data['service_id'], $data['start_date_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge span5', 'id'=> 'start_date_'.$data['service_id']))!!}
                    </div>
                </div>
            </div>


            <div class="span6">

                  <div class="control-group" >
                    {!!Form::label('End Date', 'End Date:', array('class'=>'control-label'))!!}

                    <div class="controls">
                        {!!Form::text('end_date_'.$data['service_id'], $data['end_date_'.$data['service_id']], array("onchange"=>"changeReviewDueDate(this)",'class'=> 'input-xlarge span5', 'id'=> 'end_date_'.$data['service_id']))!!}
                    </div>
                </div>
            </div>

        </div>
        @endif

        <div class="control-group hidden-phone">
            <div class="controls noMarginLeft">
                <label class="control-label autoLabel" for="textarea2">Note:</label>
                <textarea id="textarea2" rows="7" onchange="changeReviewNotes(this)" name="service_note_{!!$data['service_id']!!}" class="span10">{!!$data['service_note_'.$data['service_id']]!!}</textarea>

                {!!Form::hidden('service_ids_selected[]', $data['service_id'])!!}
            </div>
        </div>

    </div>
</div><!--/span-->