@extends('layouts.default')

@section('content')
<title>GSS - Properties</title>
<div id="content" class="span11">

  <div class='table-padding'>
    <h4 class='float:left;'>Properties</h4>
  </div>
  <div class='table-padding add-service-button-div'>

    <a class="btn btn-info accBtn" href="{!!URL::to('add-asset')!!}"> Add Property </a>

  </div>

  <div class='table-container'>
  <div class='table-responsive'>


          @if(Session::has('message'))

          {!!Session::get('message')!!}

          @endif



          <table class="table table-striped table-sm table-bordered datatable" width='100%' style='width:100%;' cellspacing='0' id='list-assets-table'>



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

               {{--<th>Action</th>--}}

             </tr>

           </thead>

           <tbody>

            <?php $i = 1; ?>

            @foreach ($assets_data as $asset)

            <tr>

               @if(isset($asset->customerType->title))    <td>{!!$asset->customerType->title!!}</td> @else   <td> </td> @endif
              <td>{!!$asset->asset_number!!}</td>
              <td>{!!$asset->loan_number!!}</td>
              <td>
                       <a href="{!! URL::to('asset/'.$asset->id) !!}">
                           {!!$asset->property_address!!}
                       </a>
              </td>
              <td>{!!$asset->property_status!!}</td>
              @if(isset($asset->city->name))   <td class="center">{!!$asset->city->name!!}</td> @else   <td> </td> @endif
              @if(isset($asset->state->name))   <td class="center">{!!$asset->state->name!!}</td> @else   <td> </td> @endif
              <td class="center">{!!$asset->zip!!}</td>
              @if(isset($asset->user->first_name))  <td>{!!$asset->user->first_name!!} {!!$asset->user->last_name!!} </td>  @else   <td> </td> @endif

             {{--<td class="center">--}}

                  {{--<a class="btn btn-danger btn-xs action-button" onclick='deleteSelectedAsset({!!$asset->id!!})' href="#" title="Delete">--}}
                    {{--<i class="fa fa-trash-o" aria-hidden="true"></i>--}}
                  {{--</a>--}}
             {{--</td>--}}

            </tr>

                                                              <?php $i++; ?>

                                                              @endforeach

                                                            </tbody>

                                                          </table>

                                                        </div>

                                                      </div>

                                                    </div><!--/span-->

                                                    <div id="asset_information" class="modal fade">
                                                      <div class="modal-dialog" role="document">
                                                         <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" aria-label="Close" data-dismiss="modal">x</button>
                                                                <h1>Property Detail</h1>
                                                            </div>
                                                            <div class="modal-body"></div>

                                                        </div>
                                                      </div>
                                                    </div>

                                                  </div><!--/row-->



                                                </div>



                                                @stop