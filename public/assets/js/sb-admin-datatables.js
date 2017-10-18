// Call the dataTables jQuery plugin
$('.datatable').DataTable( {
  "responsive" : true,
  dom: 'Bfrtip',
   "order": [[ 0, "desc" ]],
  buttons: [
  'copy', 'csv', 'excel', 'print'
  ]
});

$('.summary-work-order-table').DataTable({
  dom: 'Bfrtip',
  "responsive" : true,
  "processing": true,
  "serverSide": true,
  "ajax": baseurl + "/summary-work-order-table",
  "columnDefs": [
    {"render" : function(data, type, row){return data[0];}, "targets" : 0 }, 		//Property #	
    {"render" : function(data, type, row){return row[0][1];}, "targets" : 1 }, 		//Property Address	
    {"render" : function(data, type, row){return row[0][2];}, "targets" : 2 }, 		//Work Order ID	
    {"render" : function(data, type, row){return row[0][3];}, "targets" : 3 }, 		//Customer Name	
    {"render" : function(data, type, row){return "yup";}, "targets" : 4 }, 		//Vendor Name	
    {"render" : function(data, type, row){return "yup";}, "targets" : 5 }, 		//Service Type / Due Date	
    {"render" : function(data, type, row){return "yup";}, "targets" : 6 }, 		//Actions		
  ],
  "order": [[ 0, "desc" ]],
  buttons: [
  'copy', 'csv', 'excel', 'print'
  ]
});



$('.requests-history-table').DataTable({
  dom: 'Bfrtip',
  "responsive" : true,
  "processing": true,
  "serverSide": true,
  "ajax": baseurl + "/prop-history-table",
  "columnDefs": [
    {"render" : function(data, type, row){return data[0];}, "targets" : 0 }, //Client Type
    {"render" : function(data, type, row){return row[0][1];}, "targets" : 1 }, //Customer Name
    {"render" : function(data, type, row){return row[0][2];}, "targets" : 2 }, //Property Address
    {"render" : function(data, type, row){return row[0][3];}, "targets" : 3 }, //Unit #
    {"render" : function(data, type, row){return row[0][4];}, "targets" : 4 }, //City
    {"render" : function(data, type, row){return row[0][5];}, "targets" : 5 }, //State
    {"render" : function(data, type, row){return row[0][6];}, "targets" : 6 }, //Zip
    {"render" : function(data, type, row){return row[0][7];}, "targets" : 7 }, //Services
    {"render" : function(data, type, row){return row[0][8];}, "targets" : 8 }, //Order Id
    {"render" : function(data, type, row){
      let html = '<td class="center"> <span class="label label-'+row[0][10]+'"> '+row[0][9]+' </span> </td>';
      
      return html;
    }, "targets" : 9 }, //Order Status
    {"render" : function(data, type, row){return row[0][11];}, "targets" : 10 }, //Completed Date
    {"render" : function(data, type, row){return row[0][12];}, "targets" : 11 }, //Vendor Name
    {"render" : function(data, type, row){
      let html = '<a class="btn btn-success view_asset_information"  id="'+row[15]+'" title="View Property"><i class="halflings-icon zoom-in halflings-icon"></i></a><a class="btn btn-info" href="edit-asset/'+row[15]+'" title="Edit Property"><i class="halflings-icon edit halflings-icon"></i></a>';
      return html;
    }, "targets" : 12 }, //Action
    
  ],
  "order": [[ 0, "desc" ]],
  buttons: [
  'copy', 'csv', 'excel', 'print'
  ]
});
$('.work-order-table').DataTable( {
  dom: 'Bfrtip',
  "responsive" : true,
  "processing" : true,
  "serverSide" : true,
  "ajax" : baseurl + "/work-order-table",
  "columnDefs" : [
    {"render" : function(data, type, row){return data[0];},"targets" : 0}, //Order ID Column
    {"render" : function(data, type, row){return data;}, "targets" : 1}, //Created At Column
    {"render" : function(data, type, row){return row[0][2];}, "targets" : 2}, //Submitted By Column
    {"render" : function(data, type, row){return row[0][1];}, "targets" : 3}, //Client Type Column
    {"render" : function(data, type, row){return data;}, "targets" : 4}, //Customer Name Column
    {"render" : function(data, type, row){return row[0][3];}, "targets" : 5}, //Property Address Column
    {"render" : function(data, type, row){return row[0][4];}, "targets" : 6}, //City Column
    {"render" : function(data, type, row){return row[0][5];}, "targets" : 7}, //State Column
    {"render" : function(data, type, row){return row[0][6];}, "targets" : 8}, //Zip Address Column
    {"render" : function(data, type, row){return data;}, "targets" : 9}, //Vendor Name Column
    {"render" : function(data, type, row){return row[0][7];}, "targets" : 10}, //Job Types
    {"render" : function(data, type, row){return row[0][9];}, "targets" : 11}, //Service Type Column
    {"render" : function(data, type, row){return row[0][8];}, "targets" : 12}, //Due Date Column
    {"render" : function(data, type, row){return data[1];}, "targets" : 13}, //Status Column
    {
      "render" : function(data, type, row)
      {
        var status = row[13][0];
        if (status == 4)
        {
          return '<td class="center"><a class="btn btn-success" disabled="disabled" href="#" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" disabled="disabled" href="#"> <i class="halflings-icon edit halflings-icon"></i> </a></td>';
          
        }
        else
        {
          return '<td class="center"><a class="btn btn-success" href="view-order/'+row[0][0]+'" title="View"> <i class="halflings-icon zoom-in halflings-icon"></i> </a> <a class="btn btn-info" href="edit-order/'+row[0][0]+'" title="Edit"> <i class="halflings-icon edit halflings-icon"></i> </a></td>';
          
        }
      }, 
      "targets" : 14}, //Action Column
    

  ],
  "order": [[ 0, "desc" ]],
  buttons: [
  'copy', 'csv', 'excel', 'print'
  ]
});


$('.datatabledashboard').DataTable( {
  dom: 'B<"clear">frtip',
  "responsive" : true,
  "order": [[ 0, "desc" ]],
  buttons: [
  'copy', 'csv', 'excel', 'print'
  ]
} );

$('.datatabledashboardapproved').DataTable( {
  dom: 'Bfrtip',
  "responsive" : true,
  "order": [[ 0, "desc" ]],
  buttons: [
  'copy', 'csv', 'excel', 'print'
  ]
} );