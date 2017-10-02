@extends('layouts.default')
@section('content')
<div id="content" class="span11">
  <div class="clearfix">
    <a class="btn btn-info accBtn" href="{!!URL::to('show-add-vendor')!!}"> Add Vendor </a>
  </div>
  <div class="row-fluid">
    <div class="box span12">
      <div class="box-header" data-original-title>
        <h2><i class="halflings-icon th-list"></i><span class="break"></span>List Vendors</h2>
        <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
      </div>

      <div class="box-content">
          <div id="access-error" class="hide">
              <h4 class="alert alert-error">Warning! Access Denied</h4>
          </div>
           @if(Session::has('message'))
             <div id="access-success">
              <h4 class="alert alert-success">

                 {!!Session::get('message')!!}

              </h4>
             </div>
           @endif
          <div id="delete-success" class="hide">
              <h4 class="alert alert-success">Success! Delete Successful</h4>
          </div>
          <div id="delete-error" class="hide">
              <h4 class="alert alert-error">Warning! Access Denied</h4>
          </div>
           <div class="box-content admtable listCstm">
            <div class="admtableInr">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
<!--          <label> Select Date Range </label>
          <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b> </div>-->
          <thead>
            <tr>

              <th>S.No</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Company Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>City</th>
              <th>State</th>
              <th>Services</th>
              <th>Status</th>
              <th style="width:200px !important">Action</th>
            </tr>
          </thead>
          <tbody>

          	@foreach ($vendors as $vendor)


            <tr id="tr-{!!$vendor->id!!}">
              <td>{{ $loop->iteration }}</td>
              <td>{!! $vendor->first_name !!}</td>
              <td class="center">{!! $vendor->last_name !!}</td>
              <td class="center">{!! $vendor->company!!}</td>
              <td class="center">{!! $vendor->email !!}</td>
              <td class="center">{!! $vendor->phone !!}</td>
              @if(!isset($vendor->city->name))
              <td class="center"></td>
              @else
                 <td class="center">{!! $vendor->city->name !!}</td>
              @endif

               @if(!isset($vendor->state->name))
              <td class="center"></td>
              @else
                 <td class="center">{!! $vendor->state->name !!}</td>
              @endif
              <td class="center">
                <div class="ovrScroll">
                <?php
                $vendorService="";
                foreach($vendor->vendorService as $vService){
                  $vendorService.=$vService->Services->title."<br/>";


                  }?>
                {!! $vendorService !!}
                </div>
              </td>
              <td class="center" >
              <div class="activate">
                    @if($vendor->status == 1)
                    <span onclick="changeStatus(this,'vendor',0, {!!$vendor->id!!},'{!!$db_table!!}' )" class="label label-success">Active</span>
                    @else
                    <span onclick="changeStatus(this,'vendor',1, {!!$vendor->id!!},'{!!$db_table!!}' )" class="label label-important">In-Active</span>
                    @endif
               </div>
              </td>
              <td class="center popover-examples">
              <a class="btn btn-info" href="edit-profile-admin/{!! $vendor->id !!}" title="Edit Vendor information"> <i class="halflings-icon edit halflings-icon"></i> </a>
              <a class="btn btn-danger"  onclick="modalButtonOnClick({!!$vendor->id!!},'{!!$db_table!!}','vendor')" data-confirm="Are you sure you want to delete?" title="Delete Vendor "> <i class="halflings-icon trash halflings-icon"></i> </a>
              <a class="btn" target="_blank"  href="{!!URL::to('login-as')!!}/{!!$vendor->id!!}" title="Login as Vendor"> Login</a>
                </td>
            </tr>

           @endforeach
          </tbody>
        </table> </div></div>
      </div>
    </div>
    <!--/span-->
    <script>
	var db_table = "{!! $db_table !!}";
 	</script>
  </div>
  <!--/row-->
</div>
@parent
@include('common.delete_alert')
@stop