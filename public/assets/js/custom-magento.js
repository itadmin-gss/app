$(document).ready(function() {
    $('#data-table-sand').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": 'send-data',
         dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    });
});

$(document).ready(function() {
    $('#data-table-candidate').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
            null,
            null,
            null,
            {type: "select"},
            { type: "date-range" },
           
           
            null,
            {type: "select"},
           
        ]

    });
});


$(document).ready(function() {
    $('#data-table-productsold').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
            null,
            null,
            null,
            {type: "select"},
            { type: "date-range" },
           
           
            {type: "select"},
            {type: "select"},
           
        ]

    });
});


$(document).ready(function() {
    $('#data-table-endofyear').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
             null,
            null,
            {type: "select"}
            
           
        ]

    });
});

$(document).ready(function() {
    $('#data-table-tracking').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
             null,
          { type: "date-range" },
            null,
             null,
               null,
           
            {type: "select"},
            
           
        ]

    });
});

$(document).ready(function() {
    $('#data-table-usergrades').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
            null,
           null,  
            null,
            null,
            null,
           
            {type: "select"},
            {type: "select"},
            {type: "select"},
            
           
        ]

    });
});

$(document).ready(function() {
    $('#data-table-user-certification-level').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
            null,
           null,  
            null,
           
           
            {type: "select"},
            {type: "select"},
            {type: "select"},
            
           
        ]

    });
});

$(document).ready(function() {
    $('#data-table-passing-certifications').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
            null,
           null,  
            null,
           null,  
           {type: "select"},
           
            {type: "select"},
            {type: "select"},
            {type: "select"},
            
           
        ]

    });
});

$(document).ready(function() {
    $('#data-table-certifications-within').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
            null,
           null,  
            null,
            null,
              null, 
              { type: "date-range" }, 
            
           
        ]

    });
});

$(document).ready(function() {
    $('#data-table-average').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
            null,
            null,  
            null,
            null,
              null, 
                null, 
                      {type: "select"},
            
            
           
        ]

    });
});

$(document).ready(function() {
    $('#data-table-expiring-certificates').dataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {"sExtends": "xls", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
                {"sExtends": "pdf", "mColumns": "visible", "oSelectorOpts": {page: "current"}},
            ],
        },
    }).columnFilter({
        sPlaceHolder: "head:after",
        aoColumns: [
            null,
            null,
           null,  
            null,
            null,
              null, 
                null, 
                  null, 
                null, 
                      {type: "select"},
            
            
           
        ]

    });
});