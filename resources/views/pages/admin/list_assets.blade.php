@extends('layouts.default')

@section('content')

<div id="content" class="span11">

  <div class="clearfix">

    <a class="btn btn-info accBtn" href="{!!URL::to('add-asset')!!}"> Add Property </a>

  </div>

  <div class="row-fluid">

    <div class="box span12">

      <div class="box-header" data-original-title>

        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Properties</h2>

        <div class="box-icon">

          <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>

          <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>

          <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>

        </div>

      </div>

      <div class="box-content admtable">
         <h4 class="alert alert-danger" id="assetFlash" style="display: none;">Deleted Successfully!</h4>
        <div class="admtableInr">

          @if(Session::has('message'))

          {!!Session::get('message')!!}

          @endif



          <table class="table table-striped table-bordered bootstrap-datatable datatable">



            <thead>

              <tr>

               <th>Client Type</th>
               <th>Property ID</th>
               <th>Loan Num</th>
               <th>Address</th>
               <th>Status</th>
               <th>City</th>
               <th>State</th>
               <th>Zip</th>
               <th>Customer Name</th>

               <th>Action</th>

             </tr>

           </thead>

           <tbody>

            <?php $i = 1; ?>

            @foreach ($assets_data as $asset)

            <tr>

               @if(isset($asset->customerType->title))    <td>{!!$asset->customerType->title!!}</td> @else   <td> </td> @endif
              <td>{!!$asset->asset_number!!}</td>
              <td>{!!$asset->loan_number!!}</td>
              <td>{!!$asset->property_address!!}</td>
              <td>{!!$asset->property_status!!}</td>
              @if(isset($asset->city->name))   <td class="center">{!!$asset->city->name!!}</td> @else   <td> </td> @endif
              @if(isset($asset->state->name))   <td class="center">{!!$asset->state->name!!}</td> @else   <td> </td> @endif
              <td class="center">{!!$asset->zip!!}</td>
              @if(isset($asset->user->first_name))  <td>{!!$asset->user->first_name!!} {!!$asset->user->last_name!!} </td>  @else   <td> </td> @endif

             <td class="center">

                <a class="btn btn-success view_asset_information"  id="{!!$asset->id!!}" title="View">

                  <i class="halflings-icon zoom-in halflings-icon"></i>

                </a>

                <a class="btn btn-info" href="edit-asset/{!!$asset->id!!}" title="Edit">

                  <i class="halflings-icon edit halflings-icon"></i>

                </a>

                                <a class="btn btn-warning" onclick="" href="delete-customer-asset/{!!$asset->id!!}" title="Close">

                                                                    <i class="halflings-icon refresh halflings-icon"></i>

                                                                  </a>
                                <a class="btn btn-danger" onclick='deleteSelectedAsset({!!$asset->id!!})' href="#" title="Delete">

                                                                    <i class="halflings-icon trash halflings-icon"></i>

                                                                  </a>

                                                                </td>

                                                              </tr>

                                                              <?php $i++; ?>

                                                              @endforeach

                                                            </tbody>

                                                          </table>

                                                        </div>

                                                      </div>

                                                    </div><!--/span-->

                                                    <div id="asset_information" class="modal  hide fade modelForm larg-model"></div>

                                                  </div><!--/row-->



                                                </div>



                                                @stop