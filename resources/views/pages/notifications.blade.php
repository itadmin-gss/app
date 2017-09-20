@extends('layouts.default')
@section('content')
<div id="content" class="span11">

    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Notifications</h2>
<!--                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>-->
            </div>
            <div class="box-content admtable">
                              <div class="admtableInr">
                <table class="table table-striped table-bordered bootstrap-datatable datatable">
<!--                    <label> Select Date Range </label>
                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>
                    </div>-->
                    <thead>
                        <tr>

                            <th>Notification ID</th>
                            <th>Sendor</th>
                            <th>Recepient</th>
                            <th>Message</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>




                   @foreach($get_notifications as $notificatioData)
                     <tr>
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
                                ?>
                                  <td class="center">{!! $notificatioData->id!!}</td>
                                   <td class="center">@if(isset($notificatioData->sender->first_name)) {!! $notificatioData->sender->first_name!!} @endif @if(isset($notificatioData->sender->last_name)) {!! $notificatioData->sender->last_name!!}@endif </td>
                                   <td class="center"> @if(isset($notificatioData->recepient->first_name)) {!! $notificatioData->recepient->first_name!!}@endif @if(isset($notificatioData->recepient->last_name)) {!! $notificatioData->recepient->last_name!!} @endif</td>
                                    <td class="center">{!! $notificatioData->message!!}</td>
                                    <td class="center">{!!$time!!}</td>
                                  <td class="center">
                                  <a class="btn btn-success" onClick="ChangeNotificationStatus('{!! $notificatioData->id!!}','{!!$notificatioData->notification_url!!}','{!!$notificatioData->message!!}')"  title="View Notification">
                                  <i class="halflings-icon zoom-in halflings-icon"></i>
                                  </a>
                                  </td>



                           </tr>
               @endforeach

                    </tbody>
                </table>
            </div>
            </div>
        </div><!--/span-->

    </div><!--/row-->
</div>
@stop