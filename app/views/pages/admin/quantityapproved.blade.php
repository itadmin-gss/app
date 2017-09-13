@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <div class="clearfix">
        <a class="btn btn-info accBtn" href="{{URL::to('add-new-customer')}}"> Add Customer </a>
    </div>
    <p id="message" style="display:none">Saved...</p>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Quantity of Approved Orders</h2>
                <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
            </div>
            @if(Session::has('message'))
            {{Session::get('message')}}
            @endif
             <div class="box-content admtable listCstm">
                <div class="admtableInr">
                <div id="access-error" class="hide">
                    <h4 class="alert alert-error">Warning! Access Denied</h4>
                </div>
                <div id="access-success" class="hide">
                    <h4 class="alert alert-success">Success! Action Successful</h4>
                </div>
                <div id="delete-success" class="hide">
                    <h4 class="alert alert-success">Success! Delete Successful</h4>
                </div>
                <div id="delete-error" class="hide">
                    <h4 class="alert alert-error">Warning! Access Denied</h4>
                </div>
                <table class="table table-striped table-bordered bootstrap-datatable ">
                    <thead>
                        <tr>
                    
                            <th>Quantity(Order Ids)</th>
                            <th>Date</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                          <?php

                foreach ($orders as $order) { ?>
                <tr>
                <td>{{$order->total}}({{$order->order_id}})</td>
                <td>{{date('d/m/Y',strtotime($order->date))}}</td>
                </tr>
                <?php
                    }
                ?>
              
                    </tbody>

                </table>
            </div>
               </div>
        </div>
        <!--/span-->

    </div>

</div>
@parent
@include('common.delete_alert')
@stop
