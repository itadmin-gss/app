@extends('layouts.default')
@section('content')


<style type="text/css">
.datatablegrid {display: block;}
.datatablegrid2 {display: none;}
.datatablegrid3 {display: none;}
.datatablegrid4 {display: none;}

</style>
<div id="content" class="span11">
 <label>Filter Report</label> 
<select id="assetSummary">
    <option value="1">Requests</option>
    <option value="2">Work Orders</option>
    <option value="3">Invoices</option>
    <option value="4">Bids</option>
</select> 
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header datatablegrid" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Requests</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content datatablegrid admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable requests-history-table">

                    <thead>
                        <tr>
                            <th>Client Type</th>
                            <th>Customer Name</th>
                            <th>Property Address</th>
                            <th>Unit #</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                            <th>Services</th>
                            <th>Order ID</th>
                            <th>Order Status</th>
                            <th>Completed Date</th>
                            <th>Vendor Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                            </tbody>
                        </table>

                    </div>
              </div>
            <div class="box-header datatablegrid2" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Work Orders</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content datatablegrid2 admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable summary-work-order-table">

                    <thead>
                        <tr>
                            <th>Property #.</th>
                            <th>Property Address</th>
                            <th>Work Order ID</th>
                            <th>Customer Name</th>
                            <th>Vendor Name</th>
                            <th>Service Type / Due Date</th>                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        </tbody>
                    </table>

                </div>
          </div>
         <div class="box-header datatablegrid3" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Invoices</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
        </div>
            <div class="box-content datatablegrid3 admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable datatable3">

                    <thead>
                        <tr>
                            <th>Property #.</th>
                            <th>Property Address</th>
                            <th>Invoice ID</th>
                            <th>Order ID</th>
                            <th>Vendor/Customer Name</th>
                            <th>Service Type / Due Date</th>
                             <th>Amount</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      

                        </tbody>
                    </table>

                </div>
            </div>
                                        
            <div class="box-header datatablegrid4" data-original-title>
                <h2><i class="halflings-icon th-list"></i><span class="break"></span>Bids</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content datatablegrid4 admtable">
            <div class="admtableInr">
                @if(Session::has('message'))
                {!!Session::get('message')!!}
                @endif

                <table class="table table-striped table-bordered bootstrap-datatable datatable4">

                    <thead>
                        <tr>
                            <th>Property #.</th>
                            <th>Property Address</th>
                            <th>Bid ID</th>
                            <th>Customer Name</th>
                            <th>Service Type / Due Date</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                         
                        </tbody>
                    </table>
                    </div>
                </div>
            </div><!--/span-->

            <div id="asset_information" class="modal  hide fade modelForm larg-model"></div>
        </div><!--/row-->

    </div>

                                @stop