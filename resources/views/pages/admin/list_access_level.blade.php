@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <div class="clearfix">
        <a class="btn btn-info accBtn" href="{{URL::to('add-access-level')}}"> Add Access Level</a>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Access Level</h2>
                <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
            </div>



            @if(Session::has('message'))
            {{Session::get('message')}}
            @endif
            <div class="box-content admtable">
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
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <!--<label> Select Date Range </label>
                        <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b> </div>-->
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $loop = 1 /*--}}
                            @foreach ($userRoles as $userRole)
                            <tr id="tr-{{$userRole->id}}">
                                <td>{{ $loop }} </td>
                                <td class="center">{{ $userRole->role_name }}</td>
                                <td class="center">{{ $userRole->description }}</td>
                                <td class="center"> 
                                    <div class="activate">
                                        @if($userRole->status == 1)
                                        <span onclick="changeStatus(this,'access_level',0, {{$userRole->id}},'{{$db_table}}' )" class="label label-success">Active</span>
                                        @else
                                        <span onclick="changeStatus(this,'access_level',1, {{$userRole->id}},'{{$db_table}}' )" class="label label-important">In-Active</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="center"><a class="btn btn-info" href="edit-access-level/{{ $userRole->id }}" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a> <a class="btn btn-danger"  onclick="modalButtonOnClick({{$userRole->id}},'{{$db_table}}','access_level')" data-confirm="Are you sure you want to delete?" title="Delete"> <i class="halflings-icon trash halflings-icon"></i> </a></td>
                            </tr>
                            {{--*/ $loop++ /*--}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/span--> 
    </div>
    <!--/row--> 
    <script>
        var db_table = "{{ $db_table }}";
    </script>
</div>
@parent
@include('common.delete_alert')
@stop