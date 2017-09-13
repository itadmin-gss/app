@extends('layouts.default')
@section('content')
<div id="content" class="span11">
<a class="btn btn-info" href="{{URL::to('add-service')}}" style="float:right" >
 Add Service
</a>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Services</h2>
                <div class="box-icon"> <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a> </div>
            </div>
            @if(Session::has('message'))
            {{Session::get('message')}}
            @endif
            <div class="box-content">
                <div id="access-error" class="hide">
                    <h4 class="alert alert-error">Warning! Access Denied</h4>
                </div>
                <div id="access-success" class="hide">
                    <h4 class="alert alert-success">Success! Action Successful</h4>
                </div>
                 <div class="box-content admtable">
                              <div class="admtableInr">
                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                    <!--<label> Select Date Range </label>
                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b> </div>-->
                    <thead>
                        <tr>
                            <th>S.No</th>

                            <th>Service Code</th>

                            <th>Service Category</th>

                            <th>Title</th>

                            <th>Customer Price</th>

                            <th>Client Type</th>

                            <th>Job Type</th>

                            <th>Vendor Price</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        {{--*/ $loop = 1 /*--}}
                        @foreach ($services as $service)
                        <tr>
                            <td>{{ $loop }}</td>
                            <td>{{ $service->service_code }}</td>
                            <td>@if(isset($service->serviceCategory->title)){{ $service->serviceCategory->title  }}@endif</td>
                            <td class="center">{{ $service->title }}</td>
                            <td class="center">{{ $service->customer_price }}</td>

                           
                            
                            @if(isset($service->getcustomertype->title))
                            <td>{{ $service->getcustomertype->title }}</td>
                            @else
                            <td> </td>
                            @endif

                             @if(isset($service->getjobtype->title))
                            <td>{{ $service->getjobtype->title }}</td>
                            @else
                            <td> </td>
                            @endif
                           
                            <td class="center">{{ $service->vendor_price }}</td>
                            <td class="center"> 
                                <div class="activate">
                                    @if($service->status == 1)
                                    <span onclick="changeStatus(this,'service',0, {{$service->id}},'{{$db_table}}' )" class="label label-success">Active</span>
                                    @else
                                    <span onclick="changeStatus(this,'service',1, {{$service->id}},'{{$db_table}}' )" class="label label-important">In-Active</span>
                                    @endif
                                </div>
                            </td>
              <td class="center"><a class="btn btn-info" href="edit-service/{{ $service->id }}" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a> <!--<a class="btn btn-danger" href="#"> <i class="halflings-icon trash halflings-icon"></i> </a>--></td>
                        </tr>
                        {{--*/ $loop++ /*--}}
                        @endforeach
                    </tbody>
                </table> 
                </div>
             </div>
            </div>
        </div>
        <!--/span--> 
        <script>
            var db_table = "{{ $db_table }}";
        </script>
    </div>
    <!--/row--> 
</div>
@stop