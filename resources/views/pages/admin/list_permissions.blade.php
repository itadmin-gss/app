@extends('layouts.default')
@section('content')
<title>GSS - Permissions</title>
<div id="content" class="span11">
    <div class="clearfix">
        <a class="btn btn-info accBtn" href="{!!URL::to('add-access-level')!!}"> Add Access Level</a>
    </div>
    <div class="row-fluid">
        <div class="alert alert-success" id="success-message" style="display:none;">Success! Access Rights have been updated.</div>
        <h4>Access Level</h4>



{{--  
            @if(Session::has('message'))
            {!!Session::get('message')!!}
            @endif  --}}
{{--  
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
                    </div>  --}}
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
                                        <span onclick="changeStatus(this,'access_level',0, {!!$userRole->id!!},'{!!$db_table!!}' )" class="badge badge-success">Active</span>
                                        @else
                                        <span onclick="changeStatus(this,'access_level',1, {!!$userRole->id!!},'{!!$db_table!!}' )" class="badge badge-warning">In-Active</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="center">
                                    <a class="btn action-button btn-info" href="edit-access-level/{!! $userRole->id !!}" title="Edit"> 
                                        <i class="fa fa-pencil-square-o"></i> 
                                    </a> 
                                    <a class="btn action-button btn-danger"  onclick="modalButtonOnClick({!!$userRole->id!!},'{!!$db_table!!}','access_level')" data-confirm="Are you sure you want to delete?" title="Delete"> 
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <h2 class="text-center">Access Rights</h2>
        <hr>
        <div class="row">
            <div class="col-md-3 col-lg-3 col-sm-12">
                <h2>Access Level</h2>
                
        
             @foreach ($userRoles as $userRole)
                <?php $userRole->role = strtolower($userRole->role_name); ?>
                <div class="control box">
                    @if($userRole->role == 'admin')
                    <?php  $checkAdmin = true ?>
                    @else
                    <?php  $checkAdmin = false ?>
                    @endif
                    {!! Form::radio('roles', $userRole->role_name, $checkAdmin, array('class' => 'switch-input access-roles', 'role-id' => $userRole->id))!!}
                    {!! $userRole->role_name !!}
                    {!! Form::hidden('role-function',$userRole->id) !!}
                    </div>
                @endforeach

            </div>

            <div class='col-md-8 col-lg-8 col-sm-12'>
                <h2>Functions</h2>
                <div class='permission-table-contents'>
                {!! Form::open(array('url' => 'update-access-rights', 'class'=>'form-horizontal', 'id' => 'access-rights-form')) !!}

                <table class='table table-sm'>
                    <tbody>
                            <tr>
                                <td colspan='2'><strong>Users</strong></td>
                            </tr>
                        @foreach ($roleFunctions[1] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>

                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                <div>
                                    {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'], array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                    <span>Add</span>
                                </div>
                                <div>
                                    {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'], array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                    <span>Edit</span>
                                </div>
                                <div>
                                    {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'], array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                    <span>Delete</span>
                                </div>

                                <div>
                                    {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'], array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                    <span>View</span>
                                </div>
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan='2'>
                                <strong>Customers</strong>
                            </td>
                        </tr>
                         @foreach ($roleFunctions[2] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'], array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'], array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'], array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>

                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'], array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>                                
                        @endforeach

                        <tr>
                            <td colspan='2'>
                                <strong>Property</strong>
                            </td>
                        </tr>
                        @foreach ($roleFunctions[3] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach

                        <tr>
                            <td colspan='2'>
                                <strong>Vendor</strong>
                            </td>
                        </tr>
                        @foreach ($roleFunctions[4] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach

                        <tr>
                            <td colspan='2'>
                                <strong>Service Request</strong>
                            </td>
                        </tr>
                        @foreach ($roleFunctions[5] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach
                        <tr>
                            <td colspan='2'>
                                <strong>Service</strong>
                            </td>
                        </tr>
                        @foreach ($roleFunctions[6] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach
                        <tr>
                            <td colspan='2'>
                                <strong>Work Order</strong>
                            </td>
                        </tr>
                        @foreach ($roleFunctions[7] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach

                        <tr>
                            <td colspan='2'>
                                <strong>Completed Request</strong>
                            </td>
                        </tr>
                        @foreach ($roleFunctions[8] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach

                        <tr>
                            <td colspan='2'>
                                <strong>Invoice</strong>
                            </td>
                        </tr>
                        @foreach ($roleFunctions[9] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach
                        <tr>
                            <td colspan='2'>
                                <strong>Dashboard</strong>
                            </td>
                        </tr>
                        @foreach ($roleFunctions[11] as $key=>$roleFunction)
                            <?php
                                $str = snake_case($key);
                                $str = str_replace(" ", "", $str);
                            ?>
                            <tr>
                                <td class="small-left-padding">{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,array('class' => 'switch-input', 'disabled' => 'disabled')) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach
                    </tbody>
                </table>

                


                
                {!!Form::hidden('role_id', $role_id, array('id' => 'role_id'))!!}
            </div> 
        </div>



            <span id="loader" style="display:none;align:center;">
                {!!Html::image('assets/img/loader.gif', '',
       array('height' => 200, 'width' => '200'))!!}</span>

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