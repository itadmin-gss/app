@extends('layouts.default')
@section('content')
<title>GSS - Users</title>
<div id="content" class="span11">
  <div class="clearfix">
    <a class="btn btn-info accBtn" href="{!!URL::to('add-user')!!}"> Add user </a>
  </div>
<p id="message" style="display:none">Saved...</p>
  <div class="row-fluid">
    <div class="box span12">
      <div class="box-header" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Users</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>

            <div class="box-content admtable">
                              <div class="admtableInr">

        @if(Session::has('message'))
              {!!Session::get('message')!!}
        @endif
        <div id="access-error" class="hide">
            <div class="alert alert-error">Warning! Access Denied</div>
        </div>
        <div id="access-success" class="hide">
            <div class="alert alert-success">Success! Action Successful</div>
        </div>
        <div id="user-role-edit-error" class="hide">
            <div class="alert alert-error">Warning! Access Denied</div>
        </div>
        <div id="user-role-edit-success" class="hide">
            <div class="alert alert-success">Success! Role Updated Successful</div>
        </div>
        <div id="delete-success" class="hide">
            <div class="alert alert-success">Success! Delete Successful</div>
        </div>
        <div id="delete-error" class="hide">
            <div class="alert alert-error">Warning! Access Denied</div>
        </div>
        <table class="table table-striped table-bordered table-sm datatabledashboard" cellspacing="0" id="user-table">
          <!--<label> Select Date Range </label>
          <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b> </div>-->
          <thead>
            <tr>
              <th>S.No</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Access Level</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>


          @foreach ($users as $user)
          <tr id="tr-{!!$user->id!!}">
            <td>{{ $loop->iteration }}</td>
            <td class="center">{!! $user->first_name !!}</td>
            <td class="center">{!! $user->last_name !!}</td>
            <td class="center">{!! $user->username !!}</td>
            <td class="center">{!! $user->email !!}</td>
            <td>
                <div>
                    {!! Form::select('role_name', $userRoles, $user->user_role_id, array('class' => 'form-control role_name', 'onchange'=>'updateAccessLevel('.$user->id.',this)')) !!}
                    <span id="loader" style="display:none; float:left;width:20%;">
                        {!!Html::image('assets/img/loader.gif', '',array('height' => '25', 'width' => '25'))!!}
                    </span>
                </div>

            </td>
           <td class="center">
               <div class="activate">
                   @if($user->status == 1)
                   <span onclick="changeStatus(this,'user',0, {!!$user->id!!},'{!!$db_table!!}' )" style="color:green;">Active</span>
                   @else
                   <span onclick="changeStatus(this,'user',1, {!!$user->id!!},'{!!$db_table!!}' )" style="color:red;">In-Active</span>
                   @endif
               </div>
           </td>
            <td class="center popover-examples">
                <a class="btn btn-xs action-button btn-info" href="edit-profile-admin/{!! $user->id !!}" title="Edit">
                    <i class="fa fa-edit"></i>
                </a>
                <a class="btn btn-xs action-button btn-danger"  onclick="modalButtonOnClick({!!$user->id!!},'{!!$db_table!!}','user')" data-confirm="Are you sure you want to delete?" title="Delete">
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
