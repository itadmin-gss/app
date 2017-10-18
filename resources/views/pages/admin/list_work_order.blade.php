@extends('layouts.default')

@section('content')

<div id="content" class="span11">

  <div class="row-fluid">

    <div class="box span12">

      <div class="box-header" data-original-title>

        <h2><i class="halflings-icon th-list"></i><span class="break"></span>Work Order List</h2>

<!--                <div class="box-icon">

                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>

                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>

                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>

                  </div>-->

                </div>



                <div class="box-content admtable">

                  <div class="admtableInr">

                    <table class="table table-striped table-bordered bootstrap-datatable work-order-table">

<!--                    <label> Select Date Range </label>

                    <div style="display: inline-block; background: none repeat scroll 0% 0% rgb(255, 255, 255); cursor: pointer; padding: 5px 10px; border: 1px solid rgb(204, 204, 204); margin-bottom: 20px;" class="btn" id="reportrange2">

                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>

                        <span>August 3, 2014 - September 1, 2014</span> <b class="caret"></b>

                      </div>-->

                      <thead>

                        <tr>

                         <th>Order ID</th>

                         <th>Created At</th>

                         <th>Submitted By</th>
                         <th>Client Type</th>

                         <th>Customer Name</th>

                         <th>Property Address</th>

                         <th>City</th>

                         <th>State</th>

                         <th>Zip</th>

                         <th>Vendor Name</th>

                         <th>Job Types</th>

                         <th>Service Type</th>

                         <th>Due Date</th>

                         <th>Status</th>

                         <th>Action</th>

                       </tr>

                     </thead>

                     <tbody>
                    </tbody>

                  </table>

                </div>

              </div>



            </div><!--/span-->









          </div><!--/row-->

        </div>

        @stop