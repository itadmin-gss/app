 <style type="text/css">
input,textarea {background: "#39afea !important";}
 </style>


 <script type="text/javascript" src="{!! URL::asset('assets/js/cycle.js') !!}"> </script>
 <table id="revieworderservice_{!!$data['service_id']!!}">
    <tbody>
        <tr>
       <th>Service Type</th>
       <th>{!!$data['service_name']!!}</th>
       </tr>
          @if(isset($data['due_date_'.$data['service_id']]))
       <tr>
                <td>
                    {!!Form::label('due_date', 'Due Date:', array('class'=>'control-label'))!!}
                   </td>
                    <td>
                        {!!Form::text('due_date_'.$data['service_id'], $data['due_date_'.$data['service_id']], array('class'=> 'input-xlarge span5 datepicker_list'))!!}
                    </td>
                </tr>


 @endif
           @if(isset($data['biding_prince_'.$data['service_id']]))
         <tr>
            <td>

                    {!!Form::label('biding_prince_', 'Bid Price:', array('class'=>'control-label'))!!}
            </td>
             <td>
                        {!!Form::text('biding_prince_'.$data['service_id'], $data['biding_prince_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
              </td>
             </tr>
        @endif


            @if(isset($data['service_required_date_'.$data['service_id']]))
            <tr>
            <td>
                {!!Form::label('asset_number', 'Services Required Date:', array('class'=>'control-label'))!!}
               </td>
               <td>
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

              </td>
              </tr>
          @endif

         @if(isset($data['emergency_'.$data['service_id']]))
       <tr>
             <td>
                    {!!Form::label('emergency', 'Emergency Request:', array('class'=>'control-label'))!!}
             </td>
             <td>
                    {!!Form::checkbox('emergency_'.$data['service_id'],$data['emergency_'.$data['service_id']], $data['emergency_'.$data['service_id']], array('class'=> 'input-xlarge span5'))!!}
             </td>


        </tr>
        @endif

          @if(isset($data['quantity_'.$data['service_id']]))
       <tr>
                <td>
                    {!!Form::label('quantity_', 'Quantity:', array('class'=>'control-label'))!!}
                </td>
               <td>
                        {!!Form::text('quantity_'.$data['service_id'], $data['quantity_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
               </td>
          </tr>
        @endif

        @if(isset($data['number_of_men_'.$data['service_id']]))
        <tr>
                 <td>
                    {!!Form::label('number_of_men', 'Number of Men:', array('class'=>'control-label'))!!}
                 </td>
                  <td>
                        {!!Form::text('number_of_men_'.$data['service_id'], $data['number_of_men_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                  </td>
         </tr>
        @endif

        @if(isset($data['verified_vacancy_'.$data['service_id']]))
        <tr>
                 <td>
                    {!!Form::label('verified_vacancy', 'Verified Vacancy:', array('class'=>'control-label'))!!}
                 </td>
                 <td>
                        {!!Form::text('verified_vacancy_'.$data['service_id'], $data['verified_vacancy_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                </td>
         </tr>
        @endif

        @if(isset($data['cash_for_keys_'.$data['service_id']]))
       <tr>
                 <td>
                    {!!Form::label('cash_for_keys', 'Cash for keys: ', array('class'=>'control-label'))!!}
                </td>
                 <td>
                        {!!Form::text('cash_for_keys_'.$data['service_id'], $data['cash_for_keys_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                </td>
         </tr>
        @endif

        @if(isset($data['cash_for_keys_trash_out_'.$data['service_id']]))
       <tr>
                 <td>
                    {!!Form::label('cash_for_keys_trash_out_', 'Cash for keys trash out: ', array('class'=>'control-label'))!!}
                </td>
                 <td>
                        {!!Form::text('cash_for_keys_trash_out_'.$data['service_id'], $data['cash_for_keys_trash_out_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                  </td>
         </tr>
        @endif


        @if(isset($data['trash_size_'.$data['service_id']]))
      <tr>
                 <td>
                    {!!Form::label('trash_size', 'Trash Size: ', array('class'=>'control-label'))!!}
                  </td>
                 <td>
                        {!!Form::text('trash_size_'.$data['service_id'], $data['trash_size_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                    </td>
         </tr>
        @endif


        @if(isset($data['storage_shed_'.$data['service_id']]))
    <tr>
                 <td>
                    {!!Form::label('storage_shed', 'Storage shed: ', array('class'=>'control-label'))!!}
                  </td>
                 <td>
                        {!!Form::text('storage_shed_'.$data['service_id'], $data['storage_shed_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                    </td>
         </tr>
        @endif

         @if(isset($data['lot_size_'.$data['service_id']]))
        <tr>
                 <td>
                    {!!Form::label('storage_shed', 'Lot Size: ', array('class'=>'control-label'))!!}
                    </td>
                 <td>
                        {!!Form::text('lot_size_'.$data['service_id'], $data['lot_size_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                       </td>
         </tr>
        @endif




        @if(isset($data['set_prinkler_system_type_'.$data['service_id']]))

       <tr>
                 <td>
                    {!!Form::label('set_prinkler_system_type', 'Set Prinkler System Type: ', array('class'=>'control-label'))!!}
                     </td>
                     <td>
                        {!!Form::text('set_prinkler_system_type_'.$data['service_id'], $data['set_prinkler_system_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                        </td>
         </tr>
        @endif

        @if(isset($data['install_temporary_system_type_'.$data['service_id']]))
       <tr>
                 <td>
                    {!!Form::label('install_temporary_system_type', 'Install Temporary System Type: ', array('class'=>'control-label'))!!}
                   </td>
                     <td>
                        {!!Form::text('install_temporary_system_type_'.$data['service_id'], $data['install_temporary_system_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                          </td>
         </tr>
        @endif


           @if(isset($data['carpet_service_type_'.$data['service_id']]))
    <tr>
                 <td>
                    {!!Form::label('carpet_service_type', 'Carpet Service Type: ', array('class'=>'control-label'))!!}
                    </td>
                     <td>
                        {!!Form::text('carpet_service_type_'.$data['service_id'], $data['carpet_service_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                           </td>
         </tr>
        @endif


        @if(isset($data['pool_service_type_'.$data['service_id']]))
      <tr>
                 <td>
                    {!!Form::label('pool_service_type', 'Pool Service Type: ', array('class'=>'control-label'))!!}
                   </td>
                     <td>
                        {!!Form::text('pool_service_type_'.$data['service_id'], $data['pool_service_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                               </td>
         </tr>
        @endif


          @if(isset($data['boarding_type_'.$data['service_id']]))
       <tr>
                 <td>
                    {!!Form::label('boarding_type', 'Boarding Type: ', array('class'=>'control-label'))!!}
                   </td>
                     <td>
                        {!!Form::text('boarding_type_'.$data['service_id'], $data['boarding_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                             </td>
         </tr>
        @endif

         @if(isset($data['spruce_up_type_'.$data['service_id']]))
        <tr>
                 <td>
                    {!!Form::label('spruce_up_type', 'Spruce Type: ', array('class'=>'control-label'))!!}
                  </td>
                   <td>
                        {!!Form::text('spruce_up_type_'.$data['service_id'], $data['spruce_up_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                </td>
         </tr>
        @endif

         @if(isset($data['constable_information_type_'.$data['service_id']]))
      <tr>
                 <td>
                    {!!Form::label('constable_information_type', 'Constable Information : ', array('class'=>'control-label'))!!}
                    </td>
                   <td>
                        {!!Form::text('constable_information_type_'.$data['service_id'], $data['constable_information_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                </td>
         </tr>
        @endif

         @if(isset($data['remove_carpe_type_'.$data['service_id']]))
          <tr>
                 <td>
                    {!!Form::label('remove_carpe_type', 'Remove Carpet : ', array('class'=>'control-label'))!!}
                    </td>
                   <td>
                        {!!Form::text('remove_carpe_type_'.$data['service_id'], $data['remove_carpe_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                </td>
         </tr>
        @endif


        @if(isset($data['remove_blinds_type_'.$data['service_id']]))
          <tr>
                 <td>
                    {!!Form::label('remove_blinds_type', 'Remove Blinds : ', array('class'=>'control-label'))!!}
                      </td>
                   <td>
                        {!!Form::text('remove_blinds_type_'.$data['service_id'], $data['remove_blinds_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                </td>
         </tr>
        @endif

        @if(isset($data['remove_appliances_type_'.$data['service_id']]))
       <tr>
                 <td>
                    {!!Form::label('remove_appliances_type', 'Remove Appliances : ', array('class'=>'control-label'))!!}
                   </td>
                   <td>
                        {!!Form::text('remove_appliances_type_'.$data['service_id'], $data['remove_appliances_type_'.$data['service_id']], array('class'=> 'input-xlarge'))!!}
                    </td>
         </tr>
        @endif

        <style>

        </style>
        @if(isset($data['service_images_'.$data['service_id']]))
       <tr>
                 <td>
            <h2>Images</h2>
            </td>
                   <td>
                   <a href="javascript:;" class="viewBtn bluBtn">View Photos</a>
                   <div class="reviewimagespopup">

                     <div class="cycle-slideshow cycledv" data-cycle-slides="li" data-cycle-fx='scrollHorz' data-cycle-speed='700' data-cycle-timeout='7000' data-cycle-log="false" data-cycle-prev=".reviewimagespopup .prev" data-cycle-next=".reviewimagespopup .next" data-cycle-pager=".example-pager">
                        <ul>

                          @foreach ($data['service_images_'.$data['service_id']] as $image)
                          <li><a href="{!!Config::get('app.url').'/'.Config::get('app.request_images').'/'.$image!!}" class="dwnldBtn bluBtn" target="_blank">Download</a><i class="clsIconPop">X</i><img src="{!!Config::get('app.url').'/'.Config::get('app.request_images').'/'.$image!!}" id="service_image_{!!$data['service_id']!!}_{{ $loop->iteration }}_image"></li>
                          @endforeach
                        </ul>
                  </div>
                </div>
             </td>
         </tr>
        @endif

         @if(isset($data['recurring_'.$data['service_id']]))
           {!!Form::hidden('recurring_'.$data['service_id'], 1)!!}
           <tr>
                 <td>
            <h3>Recurring Service Details</h3>
            </td>
            <td>
            </td>
            </tr>
                <tr>
                 <td>
                    {!!Form::label('Duration', 'Duration:', array('class'=>'control-label'))!!}
                 </td>
                 <td>
                        {!!Form::text('duration_'.$data['service_id'], $data['duration_'.$data['service_id']], array('class'=> 'input-xlarge span5', 'id'=> 'duration_'.$data['service_id']))!!}
                  </td>
                  </tr>

             <tr>
                 <td>
                    {!!Form::label('Starting Date', 'Starting Date:', array('class'=>'control-label'))!!}

             </td>
                 <td>
                        {!!Form::text('start_date_'.$data['service_id'], $data['start_date_'.$data['service_id']], array('class'=> 'input-xlarge span5', 'id'=> 'start_date_'.$data['service_id']))!!}
                </td>
                  </tr>

            <tr>
                 <td>
                    {!!Form::label('End Date', 'End Date:', array('class'=>'control-label'))!!}

                    </td>
                 <td>
                        {!!Form::text('end_date_'.$data['service_id'], $data['end_date_'.$data['service_id']], array('class'=> 'input-xlarge span5', 'id'=> 'end_date_'.$data['service_id']))!!}

                </td>
            </tr>
        @endif

            <tr>
              <td>
                <label class="control-label autoLabel" for="textarea2">Note:</label>
              </td><td>
                <textarea id="textarea2" rows="7" name="service_note_{!!$data['service_id']!!}" class="span10">{!!$data['service_note_'.$data['service_id']]!!}</textarea>
                </td>
             </tr>

           </tbody>
    </table>









