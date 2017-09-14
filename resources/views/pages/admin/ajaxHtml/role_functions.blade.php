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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] , $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
        $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
       $attr) }} </span></div>
        </div>

        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
       $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
<div class="row-fluid box">
    <div class="row-fluid box">
        <div class="span3 text-left">
            <h2><strong>Asset</strong></h2>
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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
        $attr) }} </span></div>
        </div>
          <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
        $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,
        $attr) }} </span></div>
        </div>

        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,
        $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
<div class="row-fluid box">
    <div class="row-fluid box">
        <div class="span3 text-left">
            <h2><strong>Order Request</strong></h2>
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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
       $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
       $attr) }} </span></div>
        </div>
         <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
       $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
       $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
       $attr) }} </span></div>
        </div>
         <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
       $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
       
       <div class="row-fluid box">
    <div class="row-fluid box">
        <div class="span3 text-left">
            <h2><strong>Order</strong></h2>
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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
       $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
       $attr) }} </span></div>
        </div>
         <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
       $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
       
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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
       $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
       $attr) }} </span></div>
        </div>
         <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
       $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
       
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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
       $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
       $attr) }} </span></div>
        </div>
         <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
       $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>

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
            <div class="checker"><span <?php if ($roleFunction['add']) echo 'class="checked"'; ?>>
                    <?php
                    $str = snake_case($key);
                    $str = str_replace(" ", "", $str);
                    if ($_REQUEST['role_id'] == 1) {
                        $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                    } else {
                        $attr = array('class' => 'switch-input');
                    }
                    ?>
                    {{ Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,
        $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['edit']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_edit', '1', $roleFunction['edit'] ,
       $attr) }} </span></div>
        </div>
        <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['delete']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_delete', '1', $roleFunction['delete'] ,
       $attr) }} </span></div>
        </div>
         <div class="span2">
            <div class="checker"><span <?php if ($roleFunction['view']) echo 'class="checked"'; ?>> {{ Form::checkbox($str.'_view', '1', $roleFunction['view'] ,
       $attr) }} </span></div>
        </div>
    </div>
    @endforeach </div>
       
{{Form::hidden('role_id', $role_id, array('id' => 'role_id'))}}