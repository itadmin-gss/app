@extends('layouts.customer_dashboard')

@section('content')

<div id="content" class="span11">

<a class="btn btn-info" href="{{URL::to('add-new-customer-asset')}}" style="float:right" >

 Add Property

</a>

    <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Properties</h2>

                <div class="box-icon">

<!--                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>-->

                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>

<!--                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>-->

                </div>

            </div>

            <div class="box-content">

                <table class="table table-striped table-bordered bootstrap-datatable datatable">



                    <thead>

                        <tr>



                            <th>Property ID</th>

                            <th>Property Address</th>

                            <th>Property Status</th>

                            <th>City </th>

                            <th>State</th>

                            <th>Zip</th>
                            <th>Client Type</th>
                            <th>Customer Name</th>

                            

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                            <?php $i=1; ?>

                        @foreach ($assets_data as $asset)

                        <tr>

                       

                            <td class="center">{{$asset->asset_number}}</td>

                             <td class="center">{{$asset->property_address}}</td>
                             @if(isset($asset->property_status))
                             <td class="center">{{$asset->property_status}}</td>
                             @else
                             <td class="center"></td>
                             @endif

                              @if(isset($asset->city->name))   <td class="center">{{$asset->city->name}}</td> @else   <td> </td>

                          @endif

                              @if(isset($asset->state->name))   <td class="center">{{$asset->state->name}}</td>  @else   <td> </td>

                          @endif

                           <td class="center">{{$asset->zip}}</td>

                            @if(isset($asset->customerType->title))
                             <td class="center">{{$asset->customerType->title}}</td>
                             @else
                             <td class="center"></td>
                             @endif

                             @if(isset($asset->user->first_name))  <td>{{$asset->user->first_name}} {{$asset->user->last_name}} </td> 

                          @else   <td> </td>

                          @endif

                         

                            <td class="center"> {{ isset($asset->status) && $asset->status == 1 ? '<span class="label label-success">Active</span>' : '<span class="label">Inactive</span>' }}



                            </td>

                            <td class="center">

                                <a class="btn btn-success view_asset_information" title="View Property" id="{{$asset->id}}">

                                    <i class="halflings-icon zoom-in halflings-icon"></i>

                                </a>

                                <a class="btn btn-info" href="edit-customer-asset/{{$asset->id}}" title="Edit Property" >

                                    <i class="halflings-icon edit halflings-icon"></i>

                                </a>

<!--<a class="btn btn-danger" onclick="" href="delete-customer-asset/{{$asset->id}}">

                                    <i class="halflings-icon trash halflings-icon"></i>

                                </a>-->

                            </td>

                        </tr>

                        <?php $i++; ?>

                        @endforeach







                    </tbody>

                </table>

            </div>



          <div class="modal  hide fade modelForm larg-model"  id="asset_information">



        </div>

		</div>

		</div>

		</div>

		</div>

		</div>

		</div>

        </div><!--/span-->



    </div><!--/row-->

</div>

@stop