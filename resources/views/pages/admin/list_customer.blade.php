@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    <div class="clearfix">
        <a class="btn btn-info accBtn" href="{!!URL::to('add-new-customer')!!}"> Add Customer </a>
    </div>
    <p id="message" style="display:none">Saved...</p>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Customers</h2>
                <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
            </div>
            @if(Session::has('message'))
            {!!Session::get('message')!!}
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
                <table class="table table-striped table-bordered bootstrap-datatable datatable">
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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{--*/ $loop = 1 /*--}}
                        @foreach ($customers as $customer)
                        <tr id="tr-{!!$customer->id!!}">
                            <td>{!! $loop !!}</td>
                            <td class="center">{!! $customer->first_name !!}</td>
                            <td class="center">{!! $customer->last_name !!}</td>
                            <td class="center">{!! $customer->company !!}</td>
                            <td class="center">{!! $customer->email !!}</td>
                            <td class="center">{!! $customer->phone !!}</td>
                             @if(!isset($customer->city->name))
              <td class="center"></td>
              @else
                 <td class="center">{!! $customer->city->name !!}</td>
              @endif

               @if(!isset($customer->state->name))
              <td class="center"></td>
              @else
                 <td class="center">{!! $customer->state->name !!}</td>
              @endif
                            <td class="center"> 
                                <div class="activate">
                                    @if($customer->status == 1)
                                    <span onclick="changeStatus(this,'customer',0, {!!$customer->id!!},'{!!$db_table!!}' )" class="label label-success">Active</span>
                                    @else
                                    <span onclick="changeStatus(this,'customer',1, {!!$customer->id!!},'{!!$db_table!!}' )" class="label label-important">In-Active</span>
                                    @endif
                                </div>
                            </td>
                            <td class="center popover-examples">
                            <a class="btn btn-info" href="edit-profile-admin/{!! $customer->id !!}" title="Edit customer information"> <i class="halflings-icon edit halflings-icon"></i> </a> 
                            <a class="btn btn-danger"  onclick="modalButtonOnClick({!!$customer->id!!},'{!!$db_table!!}','customer')" data-confirm="Are you sure you want to delete?" title="Delete customer"> <i class="halflings-icon trash halflings-icon"></i> </a>
                            <a class="btn "  href="{!!URL::to('login-as')!!}/{!!$customer->id!!}" title="Login as customer">Login</a>
                            </td>
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
 <script>
	var db_table = "{!! $db_table !!}";
 </script>
</div>
@parent
@include('common.delete_alert')
@stop
