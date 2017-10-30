@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <div class="clearfix">
        <a class="btn btn-info accBtn" href="{!!URL::to('add-access-level')!!}"> Add Access Level</a>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Access Level</h2>
                <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
            </div>



            @if(Session::has('message'))
            {!!Session::get('message')!!}
            @endif
            <div class="box-content admtable">
                <div class="admtableInr">
                    <div id="access-error" class="hide">
                        <div class="alert alert-error">Warning! Access Denied</h4>
                    </div>
                    <div id="access-success" class="hide">
                        <div class="alert alert-success">Success! Action Successful</h4>
                    </div>
                    <div id="delete-success" class="hide">
                        <div class="alert alert-success">Success! Delete Successful</h4>
                    </div>
                    <div id="delete-error" class="hide">
                        <div class="alert alert-error">Warning! Access Denied</h4>
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

                            @foreach ($userRoles as $userRole)
                            <tr id="tr-{!!$userRole->id!!}">
                                <td>{{ $loop->iteration }} </td>
                                <td class="center">{!! $userRole->role_name !!}</td>
                                <td class="center">{!! $userRole->description !!}</td>
                                <td class="center">
                                    <div class="activate">
                                        @if($userRole->status == 1)
                                        <span onclick="changeStatus(this,'access_level',0, {!!$userRole->id!!},'{!!$db_table!!}' )" class="label label-success">Active</span>
                                        @else
                                        <span onclick="changeStatus(this,'access_level',1, {!!$userRole->id!!},'{!!$db_table!!}' )" class="label label-important">In-Active</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="center"><a class="btn btn-info" href="edit-access-level/{!! $userRole->id !!}" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a> <a class="btn btn-danger"  onclick="modalButtonOnClick({!!$userRole->id!!},'{!!$db_table!!}','access_level')" data-confirm="Are you sure you want to delete?" title="Delete"> <i class="halflings-icon trash halflings-icon"></i> </a></td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <h1 class="text-center">Access Rights</h1>
    <div class="row-fluid">
        <div class="box span4">
            <div class="box-header" data-original-title>
                <h2>Access Level</h2>
            </div>
            <div class="box-content">
                <div class="control-group">

                    @foreach ($userRoles as $userRole)
                    <?php $userRole->role = strtolower($userRole->role_name); ?>
                    <div class="control box">
                        @if($userRole->role == 'admin')
                        {{ $checkAdmin = true }}
                        @else
                        {{ $checkAdmin = false }}
                        @endif

                        {!! Form::radio('roles', $userRole->role_name, $checkAdmin,
            	array('class' => 'switch-input access-roles', 'role-id' => $userRole->id))!!}
                        {!! $userRole->role_name !!}
                        {!! Form::hidden('role-function',$userRole->id) !!}
                    </div>
                    @endforeach


                </div>
            </div>
        </div>
        <!--/span-->
        <div class="box span8">
            <div class="box-header">
                <h2>Functions</h2>
            </div>
            <div class="alert alert-success" id="success-message">Success! Access Rights have been updated.</div>
            <span id="loader" style="display:none;align:center;">
                {!!Html::image('assets/img/loader.gif', '',
       array('height' => 200, 'width' => '200'))!!}</span>
            <div class="box-content text-center">
                {!! Form::open(array('url' => 'update-access-rights', 'class'=>'form-horizontal',
       'id' => 'access-rights-form')) !!}
                <div class="row-fluid box">
                    <div class="row-fluid box">

                        <div class="span3 text-left">
                            <h2><strong>Users</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>

                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[1] as $key=>$roleFunction)
                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>
                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>

                        <div class="span2">
                            {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Customer</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[2] as $key=>$roleFunction)
                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>
                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>

                        <div class="span2">
                            {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Property</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>

                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[3] as $key=>$roleFunction)
                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>
                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Vendor</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[4] as $key=>$roleFunction)
                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>
                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">
                            {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach

                </div>
                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Service Request</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[5] as $key=>$roleFunction)

                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}

                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Service</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[6] as $key=>$roleFunction)

                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}

                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Work Order</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[7] as $key=>$roleFunction)

                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}

                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Completed Request</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[8] as $key=>$roleFunction)

                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}

                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Invoice</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[9] as $key=>$roleFunction)

                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) !!}

                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>




                <div class="row-fluid box">
                    <div class="row-fluid box">
                        <div class="span3 text-left">
                            <h2><strong>Dashboard</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Add</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Edit</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>Delete</strong></h2>
                        </div>
                        <div class="span2">
                            <h2><strong>View</strong></h2>
                        </div>
                    </div>
                    @foreach ($roleFunctions[11] as $key=>$roleFunction)

                    <div class="row-fluid box">
                        <div class="span3 text-left"> {!! $key !!} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                    array('class' => 'switch-input', 'disabled' => 'disabled')) !!}

                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                        <div class="span2">

                            {!! Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
                {!!Form::hidden('role_id', $role_id, array('id' => 'role_id'))!!}
            </div> <!-- box content -->
        </div>
    </div>
        <!--/span-->
    </div>
    <!--/row-->
    <script>
        var db_table = "{!! $db_table !!}";
    </script>
</div>
@parent
@include('common.delete_alert')
@stop