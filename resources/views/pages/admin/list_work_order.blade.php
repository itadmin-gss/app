@extends('layouts.default')

@section('content')
<title>GSS - Work Orders</title>
<div id="content" class="span11">
    <div class='table-padding'>
      <h4 style='float:left;'>Work Orders</h4>
    </div>


                    <table class="table table-striped table-bordered table-sm dt-responsive work-order-table" width='100%' style='width:100%;' cellspacing='0' id="work-order-table">



                      <thead>

                        <tr>

                         <th>ID #</th>
                         <th>Submitter</th>
                         <th>Client Type</th>

                         <th>Customer</th>

                         <th>Property Address</th>

                         <th>City</th>

                         <th>State</th>

                         <th>Zip</th>

                         <th>Vendor Name @ Vendor Company</th>

                         <th>Service Type</th>

                         <th>Due</th>

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