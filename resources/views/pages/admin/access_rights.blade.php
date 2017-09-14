@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <h1 class="text-center">Access Rights</h1>
    <div class="row-fluid">
        <div class="box span4">
            <div class="box-header" data-original-title>
                <h2>Access Level</h2>
            </div>
            <div class="box-content">
                <div class="control-group">
                    {{--*/ $loop = 1 /*--}}
                    @foreach ($userRoles as $userRole)
                    <?php $userRole->role = strtolower($userRole->role_name); ?>
                    <div class="control box">
                        @if($userRole->role == 'admin')
                        {{--*/ $checkAdmin = true /*--}}
                        @else
                        {{--*/ $checkAdmin = false /*--}}
                        @endif

                        {{ Form::radio('roles', $userRole->role_name, $checkAdmin,
            	array('class' => 'switch-input access-roles', 'role-id' => $userRole->id))}}
                        {{ $userRole->role_name }}
                        {{ Form::hidden('role-function',$userRole->id) }}
                    </div>
                    {{--*/ $loop++ /*--}}
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
                {{HTML::image('public/assets/img/loader.gif', '',
       array('height' => 200, 'width' => '200'))}}</span>
            <div class="box-content text-center">
                {{ Form::open(array('url' => 'update-access-rights', 'class'=>'form-horizontal',
       'id' => 'access-rights-form')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>
                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>

                        <div class="span2">
                            {{ Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>
                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>

                        <div class="span2">
                            {{ Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>
                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>
                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">
                            {{ Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}

                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}

                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}

                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}

                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                	array('class' => 'switch-input', 'disabled' => 'disabled')) }}

                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
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
                        <div class="span3 text-left"> {{ $key }} </div>
                        <div class="span2">
                            <?php
                            $str = snake_case($key);
                            $str = str_replace(" ", "", $str);
                            ?>

                            {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
                    array('class' => 'switch-input', 'disabled' => 'disabled')) }}

                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
              array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                        <div class="span2">

                            {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
                array('class' => 'switch-input', 'disabled' => 'disabled')) }}
                        </div>
                    </div>
                    @endforeach
                </div>
                {{Form::hidden('role_id', $role_id, array('id' => 'role_id'))}}
            </div> <!-- box content -->
        </div>
    </div>
    <!--/span-->
</div>
<!--/row-->

<div class="row-fluid">
    <div class="box span12 text-right">
        {{Form::button('Save', array('name'=>'save_continue',
    'class'=>'btn btn-large btn-success', 'data-target' => "#assign", 'id' => 'save-access-rights'))}}
        {{Form::close()}}
    </div>
    <!--/span-->
</div>
<!--/row-->

</div>
@stop