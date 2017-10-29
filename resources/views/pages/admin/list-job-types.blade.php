@extends('layouts.default')
@section('content')
<div id="content" class="span11">
  <div class="clearfix">
    <a class="btn btn-info accBtn" href="{!!URL::to('add-job-type')!!}"> Add Job Type </a>
  </div>
<p id="message" style="display:none">Saved...</p>
  <div class="row-fluid">
    <div class="box span12">
      <div class="box-header" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Job Types</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>

            <div class="box-content admtable">
                              <div class="admtableInr">

        @if(Session::has('message'))
              {!!Session::get('message')!!}
        @endif
        <div id="access-error" class="hide">
            <div class="alert alert-error">Warning! Access Denied</h4>
        </div>
        <div id="access-success" class="hide">
            <div class="alert alert-success">Success! Action Successful</h4>
        </div>
        <div id="user-role-edit-error" class="hide">
            <div class="alert alert-error">Warning! Access Denied</h4>
        </div>
        <div id="user-role-edit-success" class="hide">
            <div class="alert alert-success">Success! Role Updated Successful</h4>
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
              <th>Job type</th>
              <th>Action</th>


            </tr>
          </thead>
          <tbody>


          @foreach ($serviceCategories as $serCat)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="center">{!! $serCat->title !!}</td>
             <td class="center popover-examples">
                            <a class="btn btn-info" href="{!!URL::to('edit-job-type')!!}/{!! $serCat->id !!}" title="Edit job type"> <i class="halflings-icon edit halflings-icon"></i> </a>
                            <a class="btn btn-danger" href="{!!URL::to('delete-job-type')!!}/{!! $serCat->id !!}"  onclick="return confirm('Are you sure you want to delete?')" title="Delete job type"> <i class="halflings-icon trash halflings-icon"></i> </a>
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

</div>

@parent
@include('common.delete_alert')
@stop
