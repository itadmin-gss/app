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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>

                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                <div>
                                    {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'], $attr) !!}
                                    <span>Add</span>
                                </div>
                                <div>
                                    {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'], $attr) !!}
                                    <span>Edit</span>
                                </div>
                                <div>
                                    {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'], $attr) !!}
                                    <span>Delete</span>
                                </div>

                                <div>
                                    {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'], $attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'], $attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'], $attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'], $attr) !!}
                                        <span>Delete</span>
                                    </div>

                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'], $attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,$attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,$attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,$attr) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,$attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,$attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,$attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,$attr) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,$attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,$attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,$attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,$attr) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,$attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,$attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,$attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,$attr) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,$attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,$attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,$attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,$attr) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,$attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,$attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,$attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,$attr) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,$attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,$attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,$attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,$attr) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,$attr) !!}
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
                                if ($_REQUEST['role_id'] == 1) {
                                    $attr = array('class' => 'switch-input', 'disabled' => 'disabled');
                                } else {
                                    $attr = array('class' => 'switch-input');
                                }
                            ?>
                            <tr>
                                <td>{!! $key !!}</td>
                                <td>
                                    <div>
                                        {!! Form::checkbox($str.'_add', '1' , $roleFunction['add'] ,$attr) !!}
                                        <span>Add</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_edit', '1' , $roleFunction['edit'] ,$attr) !!}
                                        <span>Edit</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_delete', '1' , $roleFunction['delete'] ,$attr) !!}
                                        <span>Delete</span>
                                    </div>
                                    <div>
                                        {!! Form::checkbox($str.'_view', '1' , $roleFunction['view'] ,$attr) !!}
                                        <span>View</span>
                                    </div>
                                </td>
                            </tr>

                        </div>
                        @endforeach
                    </tbody>
                </table>

                


                
                {!!Form::hidden('role_id', $role_id, array('id' => 'role_id'))!!}





