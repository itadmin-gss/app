@extends('layouts.default')
@section('content')
<title>GSS - Vendors</title>
<div id="content" class="span11">
    <div class='table-padding'>
      <h4 style='float:left;'>Vendors</h4>
    </div>
    <div class='table-padding add-service-button-div'>
      <a class="btn btn-info accBtn" href="#" data-target='#add-vendor-modal' data-toggle='modal'> Add Vendor </a>
    </div>


          {{--  <div id="access-error" class="hide">
              <div class="alert alert-error">Warning! Access Denied</h4>
          </div>  --}}
           @if(Session::has('message'))
             <div id="access-success">
              <div class="alert alert-success">

                 {!!Session::get('message')!!}

              </h4>
             </div>
           @endif
          {{--  <div id="delete-success" class="hide">
              <div class="alert alert-success">Success! Delete Successful</h4>
          </div>
          <div id="delete-error" class="hide">
              <div class="alert alert-error">Warning! Access Denied</h4>
          </div>  --}}

        <div class='table-container'>
        <div class='table-responsive'>
        <table class="table table-striped table-bordered table-sm dt-responsive datatabledashboard2" width='100%' cellspacing='0' id='vendor-list-table'>

          <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Company Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>City</th>
              <th>State</th>
              <th>Services</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

          	@foreach ($vendors as $vendor)


            <tr id="tr-{!!$vendor->id!!}">
              <td>{!! $vendor->first_name !!}</td>
              <td>{!! $vendor->last_name !!}</td>
              <td>{!! $vendor->company!!}</td>
              <td>{!! $vendor->email !!}</td>
              <td>{!! $vendor->phone !!}</td>
              @if(!isset($vendor->city->name))
              <td></td>
              @else
                 <td>{!! $vendor->city->name !!}</td>
              @endif

               @if(!isset($vendor->state->name))
              <td></td>
              @else
                 <td>{!! $vendor->state->name !!}</td>
              @endif
              <td>
                <div class="ovrScroll">
                <?php
                $vendorService="";
                foreach($vendor->vendorService as $vService){
                    if (isset($vService->Services->title))
                        {
                            $vendorService.=$vService->Services->title."<br/>";

                        }


                  }?>
                {!! $vendorService !!}
                </div>
              </td>
              <td>
              <div class="activate">
                    @if($vendor->status == 1)
                    <span onclick="changeStatus(this,'vendor',0, {!!$vendor->id!!},'{!!$db_table!!}' )" class="badge badge-summary badge-success">Active</span>
                    @else
                    <span onclick="changeStatus(this,'vendor',1, {!!$vendor->id!!},'{!!$db_table!!}' )" class="badge badge-summary badge-warning">In-Active</span>
                    @endif
               </div>
              </td>
              <td class="center popover-examples">
                <div class='action-button-with-login'>

                  <a class="btn btn-info btn-xs action-button" href="edit-profile-admin/{!! $vendor->id !!}" title="Edit"> 
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                  </a>

                  <a href="javascript:void(0)" id="confirm-vendor-delete" class="btn btn-danger btn-xs action-button" data-vendor-id="{!!$vendor->id!!}" data-table="{!!$db_table!!}">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                  </a> 

                  <a class="btn btn-xs btn-primary btn-login" target="_blank"  href="{!!URL::to('login-as')!!}/{!!$vendor->id!!}" title="Login as Vendor"> Login</a>
                </div>

              </td>
            </tr>

           @endforeach
          </tbody>
        </table> </div></div>
      </div>
    </div>
    <!--/span-->

  </div>
  <!--/row-->
</div>

<div class="modal fade" id="vendor-delete-modal">
    <input type="hidden" id="vendor-delete-id" value="">
    <input type="hidden" id="vendor-delete-table" value="">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="delete-confirm">
                    <p>Are you sure you want to delete this vendor?</p>
                    <div class="pull-right">
                        <button class="btn btn-info" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-danger" id="vendor-delete-button">Delete</button>
                    </div>
                </div>
                <div class="delete-error hide">
                    <p>An error has occured. Please contact IT.</p>
                    <button class="btn btn-info" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
                </div>
                <div class="delete-message hide">
                    <p>Vendor Deleted!</p>
                    <div class="pull-right">
                        <button class="btn btn-info" id="vendor-modal-close"  aria-label="Close">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='modal fade' id='add-vendor-modal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>        
        <h4 class='modal-title'>Add Vendor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class='modal-body'>
        <div class="form-group ">
                {!! Form::open(array('url' => 'admin-create-vendor', 'id'=>'addVendorForm' , 'class'=>'form-horizontal')) !!}
                {!!Form::label('firstName', 'First Name *:', array('class' => 'control-label'))!!}
                <div class="controls">
                    {!! Form::text('first_name',  isset($user_data['first_name']) ? $user_data['first_name'] : '' , array('class'=>'form-control','id'=>'firstName'))!!}
                </div>
            
                {!!Form::label('lastName', 'Last Name *:', array('class' => 'control-label'))!!}
                <div class="controls">
                    {!! Form::text('last_name',  isset($user_data['last_name']) ? $user_data['last_name'] : '' , array('class'=>'form-control','id'=>'lastName'))!!}
                </div>
            
            
                {!!Form::label('email', 'Email *:', array('class' => 'control-label'))!!}
                <div class="controls">
                    {!! Form::email('email',  isset($user_data['email']) ? $user_data['email'] : '' , array('class'=>'form-control','id'=>'email'))!!}
                </div>
            

        </div>
          <div id="addVendorSuccessMessage" class="">                        
          </div>
          <div id="addVendorValidationErrorMessage" class="">
          </div>
      </div>
      <div class='modal-footer'>
        {!! Form::submit('Create', array('class'=>'btn btn-large btn-success text-left','id'=>'createVendorSaveButton'))!!}
      </div>
    </div>
  </div>
</div>
@parent
@include('common.delete_alert')
@stop