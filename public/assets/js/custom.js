//$.noConflict();
$(document).ready(function(){

	//Event Handler -> confirm vendor delete modal
	$(document).on("click", "#confirm-vendor-delete", function(){
		$("#vendor-delete-id").val($(this).data('vendor-id'));
		$("#vendor-delete-table").val($(this).data('table'));
		$("#vendor-delete-modal").modal("toggle");
	});

	//Event Handler -> Property Photo Upload
	$("#property-photo-upload").on("click", function(){
		$("#photo-upload-modal").modal("toggle");
	});

	$("#photo-upload-select-files").on("click", function(){
		$("#photo-upload-input").click();
	})

	//Event Handler -> Vendor Profile Completion - Step 1 -> Step 2
	$("#vendor-step-1-next").on("click", function(){
		if ($("#username").val().length < 2){
			alert("A valid username is required");
			return false;
		}
		if ($("#password").val().length < 4){
			alert("Password must be 5 or more characters");
		} else {
            $(".complete-profile-step-1").hide();
            $(".complete-profile-step-2").fadeIn("fast");
		}
	});

	//Event Handler -> Vendor Profile Completion - Step 2 -> Step 1 (Back Button)
	$("#vendor-step-2-back").on("click", function(){
        $(".complete-profile-step-2").hide();
        $(".complete-profile-step-1").fadeIn("fast");
	});

	//Event Handler -> Vendor Profile Completion - Step 2 -> Step 3 (Next Button)
	$("#vendor-step-2-next").on("click", function(){
		if ($("#address_1").val().length < 5){
			alert("Address must be at least 5 characters long");
			return false;
		}

		if ($("#state_id").val() === "0"){
			alert("Please select a state");
			return false;
		}

		if ($("#city_id").val() === "0"){
			alert("Please select a city");
			return false;
		}

		if ($("#zipcode").val().length < 5){
			alert("Please enter a zip code");
			return false;
		}

		if ($("#phone").val().length < 7){
			alert("Please enter a valid phone number");
			return false;
		}

        $(".complete-profile-step-2").hide();
        $(".complete-profile-step-3").fadeIn("fast");
		$(".chosen-hidden").chosen();

	});

	//Enable pwstrength password meter
	$(".complete-profile-step-1 #password").pwstrength();

	//Even Handler -> Delete Vendor Button
	$(document).on("click", "#vendor-delete-button", function(){
		$.ajax({
			url: baseurl + "/delete-record",
			type: "get",
			data: {type: "vendor", db_table : $("#vendor-delete-table").val(), id : $("#vendor-delete-id").val()},

		}).done(function(cb){
			if (cb == 1)
			{
				$(".delete-confirm").hide();
				$(".delete-message").fadeIn("fast");
			}
			else
			{
				$(".delete-confirm").hide();
				$(".delete-error").fadeIn("fast");
			}
		});
	});

	$(document).on("click", "#vendor-modal-close", function(){
		window.location.href=baseurl+"/list-vendors";
	});
	//Event Handler -> Property Select on Service Request Page
	$("#asset_number").on("change", function(){
		$(".request-step-1 a").removeClass('badge-info').addClass('badge-disable');
		$(".request-step-2 a").removeClass('badge-disable').addClass("badge-info");
		$(".step-1").hide();
		$(".step-2").fadeIn('fast');
	});

	//Event Handler -> Job Type on Service Request Page
	$("#job_type").on("change", function(){
		$(".request-step-2 a").removeClass('badge-info').addClass('badge-disable');
		$(".request-step-3 a").removeClass('badge-disable').addClass("badge-info");
		$(".step-2").hide();
		$(".step-3").fadeIn('fast');
		setTimeout(function(){
			$(".hidden-chosen").chosen();
		},300);
	});

	$(".review-service-order").on("click", function(){
		$(".request-step-3 a").removeClass('badge-info').addClass('badge-disable');
		$(".request-step-4 a").removeClass('badge-disable').addClass("badge-info");
		$(".step-3").hide();
		$(".step-4").fadeIn('fast');
	});

	/// Enable jQuery Chosen Plugin on .chosen class ///
	$(".chosen").chosen();

	$(document).on("shown.bs.modal", ".service-information-modal", function(){
		console.log("fired");
		$(".datepicker2").datepicker();
	})

	$('body').delegate( ".viewBtn", "click", function() {
		$('.reviewimagespopup').fadeIn('fast');
	});
	$('body').delegate( ".clsIconPop", "click", function() {
		$('.reviewimagespopup').fadeOut('fast');
	});

	$('a.rightNavnl').click(function(){
			var ths = $(this);
			if(!ths.hasClass('active')){
				$('#right_navPnl').animate({ top: 50},300);
				ths.addClass('active');
			}else{
				$('#right_navPnl').animate({ top: '-100%'},300);
				ths.removeClass('active');
			}
		});

	$('.map li').hover(function(){
    $(this).find('span.titleDs').stop(true,true).fadeToggle('fast');
    $(this).find('a.removeBtn').stop(true,true).fadeToggle('fast');
  });

	$('body').delegate('.serviceReqst .box-header','click',function(){
		$(this).next('.box-content').slideToggle();
	});

	//TAB SCRIPT
    $('.tabSection > ul li a').click(function(){
        var dataSrc = $(this).data('src');
        $('.tabSection > ul li a').removeClass('active');
        $(this).addClass('active');
        $('.tabSection .tabWrapper').children('.control-group').hide();
        $('.tabSection .tabWrapper').find('.control-group'+dataSrc).show();
    });

    $('.searchModl button.close').click(function(){
      $('.searchModl').removeClass('in').hide();
      $('.modal-backdrop').removeClass('in').hide();
    });

     $('#tabCntrl0 .nxtStep').click(function(){ 
    	var datSrc = $(this).data('src');
	    	$('.tabSection > ul li a').removeClass('active');
        	$('.tabSection > ul li a[data-src='+datSrc+']').addClass('active').parent().find('span').hide();
	    	$('.tabSection .tabWrapper').children('.control-group').hide();
	    	$('.tabSection .tabWrapper').find('.control-group'+datSrc).show();
    });

    $('#tabCntrl1 .nxtStep').click(function(){ 
    	var tabDm = $('.chzn-container-single .chzn-single span').text(),
    	datSrc = $(this).data('src');

	    if(tabDm == 'Select Property' ){
	    	alert('Please Select Property Number');
	    }else{
	    	$('.tabSection > ul li a').removeClass('active');
        	$('.tabSection > ul li a[data-src='+datSrc+']').addClass('active').parent().find('span').hide();
	    	$('.tabSection .tabWrapper').children('.control-group').hide();
	    	$('.tabSection .tabWrapper').find('.control-group'+datSrc).show();
	    }
    });

    $('#tabCntrl2 .nxtStep').click(function(){ 

    	if($("#job_type").val()==""){
    			alert('Please select job type');
    		}else{
    	var datSrc = $(this).data('src');
    	$('.tabSection > ul li a').removeClass('active');
    	$('.tabSection > ul li a[data-src='+datSrc+']').addClass('active').parent().find('span').hide();
    	$('.tabSection .tabWrapper').children('.control-group').hide();
    	$('.tabSection .tabWrapper').find('.control-group'+datSrc).show();
   } 
});
	setTimeout(function(){ 
		$('#tabCntrl3 .chzn-container .chzn-drop').addClass('xtraChzn');
	},4000);
	$('body').delegate('#tabCntrl3 .chzn-container .chzn-results li','click',function(){ 
		$(this).closest('.chzn-drop').removeClass('xtraChzn');
	});
    $('#tabCntrl3 .nxtStep').click(function(){ 
    	var datSrc = $(this).data('src');

	    if($('#tabCntrl3 .chzn-container .chzn-drop').hasClass('xtraChzn')){
	    	alert('Please Select Services');
	    }else{
	    	$('.tabSection > ul li a').removeClass('active');
        	$('.tabSection > ul li a[data-src='+datSrc+']').addClass('active').parent().find('span').hide();
	    	$('.tabSection .tabWrapper').children('.control-group').hide();
	    	$('.tabSection .tabWrapper').find('.control-group'+datSrc).show();

	    	$('.tabSection .tabWrapper > .control-group').show();
	    	$('.tabSection .tabWrapper .nxtStep').hide();
	    }
    });
    $('.requsrForm .stp4 a').click(function(){ 
    	$('.tabSection .tabWrapper > .control-group').show();
    	$('.tabSection .tabWrapper .nxtStep').hide();
    });

	setTimeout(function(){
		var dtwdth = $('.table.dataTable').width();
		$('.dataTables_wrapper, .admtableInr2').css('width',dtwdth);
	},3000);

	$('.admtable').prepend('<div class="admtable2"><div class="admtableInr2"></div></div>');

	$('.admtable2').on('scroll', function (e) {
        $('.admtableInr').scrollLeft($('.admtable2').scrollLeft());
    }); 
    $('.admtableInr').on('scroll', function (e) {
        $('.admtable2').scrollLeft($('.admtableInr').scrollLeft());
    });

	/* ---------- Acivate Functions ---------- */
	template_functions();
	// init_masonry();
	// sparkline_charts();
	// charts();
	// calendars();
	// growlLikeNotifications();
	// widthFunctions();		
	// circle_progess();
	// chart();
	// messageLike();
	
	// setInterval(tempStats, 3000);
		
});

/* ---------- Like/Dislike ---------- */

function messageLike(){
	
	if($('.messagesList')) {
		
		$('.messagesList').on('click', '.star', function(){
			
			$(this).removeClass('star');
			
			$(this).addClass('dislikes');
			
			//here add your function
			
		});
		
		$('.messagesList').on('click', '.dislikes', function(){
			
			$(this).removeClass('dislikes');
			
			$(this).addClass('star');
			
			//here add your function
			
		});	
		
	}	
	
}

/* ---------- Temp Stats ---------- */

function tempStats(){

	if($('.tempStat')) {
		
		$('.tempStat').each(function(){
			
			var temp = Math.floor(Math.random()*(1+120));
			
			$(this).html(temp + '°');
						
			if (temp < 20) {
				
				$(this).animate({
				            borderColor: "#67c2ef"
				        }, 'fast');
				
			} else if (temp > 19 && temp < 40) {
				
				$(this).animate({
				            borderColor: "#CBE968"
				        }, 'slow');
				
			} else if (temp > 39 && temp < 60) {
				
				$(this).animate({
				            borderColor: "#eae874"
				        }, 'slow');

			} else if (temp > 59 && temp < 80) {
				
				$(this).animate({
				            borderColor: "#fabb3d"
				        }, 'slow');

			} else if (temp > 79 && temp < 100) {

				$(this).animate({
				            borderColor: "#fa603d"
				        }, 'slow');

			} else {
				
				$(this).animate({
				            borderColor: "#ff5454"
				        }, 'slow');
				
			}
			
		});
		
	}
	
}

/* ---------- Masonry Gallery ---------- */

// function init_masonry(){
//     var $container = $('.masonry-gallery');

//     var gutter = 6;
//     var min_width = 250;
//     $container.imagesLoaded( function(){
//         $container.masonry({
//             itemSelector : '.masonry-thumb',
//             gutterWidth: gutter,
//             isAnimated: true,
//               columnWidth: function( containerWidth ) {
//                 var num_of_boxes = (containerWidth/min_width | 0);

//                 var box_width = (((containerWidth - (num_of_boxes-1)*gutter)/num_of_boxes) | 0) ;

//                 if (containerWidth < min_width) {
//                     box_width = containerWidth;
//                 }

//                 $('.masonry-thumb').width(box_width);

//                 return box_width;
//               }
//         });
//     });
// }

/* ---------- Numbers Sepparator ---------- */

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1.$2");
    return x;
}

/* ---------- Template Functions ---------- */		
		
function template_functions(){
	
	/* ---------- ToDo List Action Buttons ---------- */
	
	$(".todo-remove").click(function(){
		
		$(this).parent().parent().fadeTo("slow", 0.00, function(){ //fade
			$(this).slideUp("slow", function() { //slide up
		    	$(this).remove(); //then remove from the DOM
		    });
		});
		
		
		return false;
	});
	
	/* ---------- Skill Bars ---------- */
	$(".meter > span").each(function() {
		$(this)
		.data("origWidth", $(this).width())
		.width(0)
		.animate({
			width: $(this).data("origWidth")
		}, 3000);
	});
	
	/* ---------- Disable moving to top ---------- */
	$('a[href="#"][data-top!=true]').click(function(e){
		e.preventDefault();
	});
	
	/* ---------- Text editor ---------- */
	// $('.cleditor').cleditor();
	
	/* ---------- Datapicker ----------*/
	// $('.datepicker').datepicker(); 
	
	/* ---------- Notifications ---------- */
	$('.noty').click(function(e){
		e.preventDefault();
		var options = $.parseJSON($(this).attr('data-noty-options'));
		noty(options);
	});

	/* ---------- Uniform ---------- */
	// $("input:checkbox, input:radio, input:file").not('[data-no-uniform="true"],#uniform-is-ajax').uniform();

	/* ---------- Choosen ---------- */
	// $('[data-rel="chosen"],[rel="chosen"]').chosen();
	
	/* $( ".control-group" ).delegate( ".chzn-results li", "click", function() {
		$('.tabSection > ul li a[data-src=#tabCntrl2]').trigger('click');
	});*/
	
	/* ---------- Tabs ---------- */
	$('#myTab a:first').tab('show');
	$('#myTab a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	});

	/* ---------- Makes elements soratble, elements that sort need to have id attribute to save the result ---------- */
	// $('.sortable').sortable({
	// 	revert:true,
	// 	cancel:'.btn,.box-content,.nav-header',
	// 	update:function(event,ui){
	// 		//line below gives the ids of elements, you can make ajax call here to save it to the database
	// 		//console.log($(this).sortable('toArray'));
	// 	}
	// });

	/* ---------- Tooltip ---------- */
	// $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});

	/* ---------- Popover ---------- */
	// $('[rel="popover"],[data-rel="popover"]').popover();

	/* ---------- File Manager ---------- */
	// var elf = $('.file-manager').elfinder({
	// 	url : 'misc/elfinder-connector/connector.php'  // connector URL (REQUIRED)
	// }).elfinder('instance');

	/* ---------- Star Rating ---------- */
	// $('.raty').raty({
	// 	score : 4 //default stars
	// });

	/* ---------- Uploadify ---------- */
	// $('#file_upload').uploadify({
	// 	'swf'      : 'misc/uploadify.swf',
	// 	'uploader' : 'misc/uploadify.php'
	// 	// Put your options here
	// });

	/* ---------- Fullscreen ---------- */
	$('#toggle-fullscreen').button().click(function () {
		var button = $(this), root = document.documentElement;
		if (!button.hasClass('active')) {
			$('#thumbnails').addClass('modal-fullscreen');
			if (root.webkitRequestFullScreen) {
				root.webkitRequestFullScreen(
					window.Element.ALLOW_KEYBOARD_INPUT
				);
			} else if (root.mozRequestFullScreen) {
				root.mozRequestFullScreen();
			}
		} else {
			$('#thumbnails').removeClass('modal-fullscreen');
			(document.webkitCancelFullScreen ||
				document.mozCancelFullScreen ||
				$.noop).apply(document);
		}
	});
	// New DATA TABLE
	


	//var table2 ="";
	// $('.datatabledashboard').DataTable( {
	// 	responsive: true,
	// 	"aaSorting": [[0,'desc']],
 //        dom: 'T<"clear">lfrtip',
 //          tableTools: {
 //            "sSwfPath": "//cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
 //            "aButtons": [
 //                {"sExtends": "csv", "sButtonText": "CSV Export", "mColumns": "visible"},
                
 //            ],
 //        },
 //    } );

  //   	var datatabledashboardapproved =$('.datatabledashboardapproved').DataTable( {
		// responsive: true,
		// "aaSorting": [[0,'desc']],
  //       dom: 'T<"clear">lfrtip',
  //         tableTools: {
  //           "sSwfPath": "//cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
  //           "aButtons": [
  //               {"sExtends": "csv", "sButtonText": "CSV Export", "mColumns": "visible"},
                
  //           ],
  //       },
  //   } );

    
// 	/* ---------- Datable ---------- */
// table2=	$('.datatabledashboard').DataTable( {
// 		    "ordering": false,
//         dom: 'T<"clear">lfrtip',
//         "oTableTools": {
//    "sSwfPath": "../phpnewlatest/copy_csv_xls_pdf.swf",
//             "sRowSelect": "multi",
//             "aButtons": [
//                 {
//                     "sExtends": "csv",
//     				 "sButtonText": "CSV Export",
//                     "bSelectedOnly": false,
//      "fnComplete": function ( nButton, oConfig, oFlash, sFlash ) {
//                         var oTT = TableTools.fnGetInstance( 'datatable' );
//                         var nRow = $('.datatable tbody tr');
//                         oTT.fnDeselect(nRow);
//                     }
//                 }
//             ]
//         }
//     } );



	// var table = $('.datatable').DataTable( {
	// 		dom: 'Bfrtip',
	// 		buttons: [
	// 			'copy', 'csv', 'excel', 'print'
	// 		]
	// 	 } );
		//$('.datatable').DataTable( {
		// responsive: true,
		// "aaSorting": [[0,'desc']],
  //       dom: 'T<"clear">lfrtip',
  //         tableTools: {
  //           "sSwfPath": "//cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
  //           "aButtons": [
  //               {"sExtends": "csv", "sButtonText": "CSV Export", "mColumns": "visible"},
                
  //           ],
  //       },
  //   } );
// #firstcolumn is a <input type="text"> element
// $('#firstcolumn').on( 'keyup', function () {
//     table
//         .columns( 0 )
//         .search( this.value )
//         .draw();
// } );
 
// // #firstcolumn is a <input type="text"> element
// $('#firstcolumn').on( 'keyup', function () {
//     table2
//         .columns( 0 )
//         .search( this.value )
//         .draw();
// } );
//  // #firstcolumn is a <input type="text"> element
// $('#firstcolumn').on( 'keyup', function () {
//     datatabledashboardapproved
//         .columns( 0 )
//         .search( this.value )
//         .draw();
// } );
 


        
 //        $('.datatable').dataTable().fnDestroy();
	// $('.datatable').dataTable({
	// 		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
	// 		 "sDom ": 'T<"clear ">lfrtip',
 //        "oTableTools ": {
 //            "aButtons ": [
 //                "csv "
 //            ]
 //        },
	// 		"sPaginationType": "bootstrap",
	// 		"oLanguage": {
	// 		"sLengthMenu": "_MENU_ records per page"
	// 		}
	// 	} );


	  
	// $('.datatable2').dataTable({
	// 		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
	// 		"sPaginationType": "bootstrap",
	// 		"oLanguage": {
	// 		"sLengthMenu": "_MENU_ records per page"
	// 		}
	// 	} );

		// $('.datatable2').DataTable( {
  //       dom: 'T<"clear">lfrtip',
  //       "oTableTools": {
  //  "sSwfPath": "../phpnewlatest/copy_csv_xls_pdf.swf",
  //           "sRowSelect": "multi",
  //           "aButtons": [
  //               {
  //                   "sExtends": "csv",
  //   				 "sButtonText": "CSV Export",
  //                   "bSelectedOnly": false,
  //    "fnComplete": function ( nButton, oConfig, oFlash, sFlash ) {
  //                       var oTT = TableTools.fnGetInstance( 'datatable2' );
  //                       var nRow = $('.datatable2 tbody tr');
  //                       oTT.fnDeselect(nRow);
  //                   }
  //               }
  //           ]
  //       }
  //   } );


	  // $('.datatable3').dataTable().fnDestroy();
	// $('.datatable3').dataTable({
	// 		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
	// 		"sPaginationType": "bootstrap",
	// 		"oLanguage": {
	// 		"sLengthMenu": "_MENU_ records per page"
	// 		}
	// 	} );



		// $('.datatable3').DataTable( {
  //       dom: 'T<"clear">lfrtip',
  //       "oTableTools": {
  //  "sSwfPath": "../phpnewlatest/copy_csv_xls_pdf.swf",
  //           "sRowSelect": "multi",
  //           "aButtons": [
  //               {
  //                   "sExtends": "csv",
  //   				 "sButtonText": "CSV Export",
  //                   "bSelectedOnly": false,
  //    "fnComplete": function ( nButton, oConfig, oFlash, sFlash ) {
  //                       var oTT = TableTools.fnGetInstance( 'datatable3' );
  //                       var nRow = $('.datatable3 tbody tr');
  //                       oTT.fnDeselect(nRow);
  //                   }
  //               }
  //           ]
  //       }
  //   } );


	//   $('.datatable4').dataTable().fnDestroy();
	// // $('.datatable4').dataTable({
	// // 		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
	// // 		"sPaginationType": "bootstrap",
	// // 		"oLanguage": {
	// // 		"sLengthMenu": "_MENU_ records per page"
	// // 		}
	// // 	} );


		// $('.datatable4').DataTable( {
  //       dom: 'T<"clear">lfrtip',
  //       "oTableTools": {
  //  "sSwfPath": "../phpnewlatest/copy_csv_xls_pdf.swf",
  //           "sRowSelect": "multi",
  //           "aButtons": [
  //               {
  //                   "sExtends": "csv",
  //   				 "sButtonText": "CSV Export",
  //                   "bSelectedOnly": false,
  //    "fnComplete": function ( nButton, oConfig, oFlash, sFlash ) {
  //                       var oTT = TableTools.fnGetInstance( 'datatable4' );
  //                       var nRow = $('.datatable4 tbody tr');
  //                       oTT.fnDeselect(nRow);
  //                   }
  //               }
  //           ]
  //       }
  //   } );

	$('.btn-close').click(function(e){
		e.preventDefault();
		$(this).parent().parent().parent().fadeOut();
	});
	$('.btn-minimize').click(function(e){
		e.preventDefault();
		var $target = $(this).parent().parent().next('.box-content');
		if($target.is(':visible')) $('i',$(this)).removeClass('icon-chevron-up').addClass('icon-chevron-down');
		else 					   $('i',$(this)).removeClass('icon-chevron-down').addClass('icon-chevron-up');
		$target.slideToggle();
	});
	$('.btn-setting').click(function(e){
		e.preventDefault();
		$('#myModal').modal('show');
	});
	
	
	/* ---------- Progress  ---------- */

		// $(".simpleProgress").progressbar({
		// 	value: 89
		// });

		// $(".progressAnimate").progressbar({
		// 	value: 1,
		// 	create: function() {
		// 		$(".progressAnimate .ui-progressbar-value").animate({"width":"100%"},{
		// 			duration: 10000,
		// 			step: function(now){
		// 				$(".progressAnimateValue").html(parseInt(now)+"%");
		// 			},
		// 			easing: "linear"
		// 		})
		// 	}
		// });

		// $(".progressUploadAnimate").progressbar({
		// 	value: 1,
		// 	create: function() {
		// 		$(".progressUploadAnimate .ui-progressbar-value").animate({"width":"100%"},{
		// 			duration: 20000,
		// 			easing: 'linear',
		// 			step: function(now){
		// 				$(".progressUploadAnimateValue").html(parseInt(now*40.96)+" Gb");
		// 			},
		// 			complete: function(){
		// 				$(".progressUploadAnimate + .field_notice").html("<span class='must'>Upload Finished</span>");
		// 			} 
		// 		})
		// 	}
		// });
		
		// if($(".taskProgress")) {
		
		// 	$(".taskProgress").each(function(){
				
		// 		var endValue = parseInt($(this).html());
												
		// 		$(this).progressbar({
		// 			value: endValue
		// 		});
				
		// 		$(this).parent().find(".percent").html(endValue + "%");
				
		// 	});
		
		// }
		
		// if($(".progressBarValue")) {
		
		// 	$(".progressBarValue").each(function(){
				
		// 		var endValueHTML = $(this).find(".progressCustomValueVal").html();
				
		// 		var endValue = parseInt(endValueHTML);
												
		// 		$(this).find(".progressCustomValue").progressbar({
					
		// 			value: 1,
		// 			create: function() {
		// 				$(this).find(".ui-progressbar-value").animate({"width": endValue + "%"},{
		// 					duration: 5000,
		// 					step: function(now){
																
		// 						$(this).parent().parent().parent().find(".progressCustomValueVal").html(parseInt(now)+"%");
		// 					},
		// 					easing: "linear"
		// 				})
		// 			}
		// 		});
				
		// 	});
		
		// }
	
	
	/* ---------- Custom Slider ---------- */
		// $(".sliderSimple").slider();

		// $(".sliderMin").slider({
		// 	range: "min",
		// 	value: 180,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderMinLabel" ).html( "$" + ui.value );
		// 	}
		// });

		// $(".sliderMin-1").slider({
		// 	range: "min",
		// 	value: 50,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderMin1Label" ).html( "$" + ui.value );
		// 	}
		// });

		// $(".sliderMin-2").slider({
		// 	range: "min",
		// 	value: 100,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderMin2Label" ).html( "$" + ui.value );
		// 	}
		// });

		// $(".sliderMin-3").slider({
		// 	range: "min",
		// 	value: 150,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderMin3Label" ).html( "$" + ui.value );
		// 	}
		// });

		// $(".sliderMin-4").slider({
		// 	range: "min",
		// 	value: 250,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderMin4Label" ).html( "$" + ui.value );
		// 	}
		// });

		// $(".sliderMin-5").slider({
		// 	range: "min",
		// 	value: 350,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderLabel" ).html( "$" + ui.value );
		// 	}
		// });
		
		// $(".sliderMin-6").slider({
		// 	range: "min",
		// 	value: 450,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderLabel" ).html( "$" + ui.value );
		// 	}
		// });
		
		// $(".sliderMin-7").slider({
		// 	range: "min",
		// 	value: 550,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderLabel" ).html( "$" + ui.value );
		// 	}
		// });
		
		// $(".sliderMin-8").slider({
		// 	range: "min",
		// 	value: 650,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderLabel" ).html( "$" + ui.value );
		// 	}
		// });
		
		
		// $(".sliderMax").slider({
		// 	range: "max",
		// 	value: 280,
		// 	min: 1,
		// 	max: 700,
		// 	slide: function( event, ui ) {
		// 		$( ".sliderMaxLabel" ).html( "$" + ui.value );
		// 	}
		// });

		// $( ".sliderRange" ).slider({
		// 	range: true,
		// 	min: 0,
		// 	max: 500,
		// 	values: [ 192, 470 ],
		// 	slide: function( event, ui ) {
		// 		$( ".sliderRangeLabel" ).html( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		// 	}
		// });

		// $( "#sliderVertical-1" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 60,
		// });

		// $( "#sliderVertical-2" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 40,
		// });

		// $( "#sliderVertical-3" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 30,
		// });

		// $( "#sliderVertical-4" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 15,
		// });

		// $( "#sliderVertical-5" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 40,
		// });

		// $( "#sliderVertical-6" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 80,
		// });
		
		// $( "#sliderVertical-7" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 60,
		// });

		// $( "#sliderVertical-8" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 40,
		// });

		// $( "#sliderVertical-9" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 30,
		// });

		// $( "#sliderVertical-10" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 15,
		// });

		// $( "#sliderVertical-11" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 40,
		// });

		// $( "#sliderVertical-12" ).slider({
		// 	orientation: "vertical",
		// 	range: "min",
		// 	min: 0,
		// 	max: 100,
		// 	value: 80,
		// });
			
}

/* ---------- Circle Progess Bars ---------- */
function circle_progess() {
	
	var divElement = $('div'); //log all div elements
	
	if (retina()) {
		
		$(".whiteCircle").knob({
	        'min':0,
	        'max':100,
	        'readOnly': true,
	        'width': 240,
	        'height': 240,
			'bgColor': 'rgba(255,255,255,0.5)',
	        'fgColor': 'rgba(255,255,255,0.9)',
	        'dynamicDraw': true,
	        'thickness': 0.2,
	        'tickColorizeValues': true
	    });
	
		$(".circleStat").css('zoom',0.5);
		$(".whiteCircle").css('zoom',0.999);		
		
	} else {
		
		$(".whiteCircle").knob({
	        'min':0,
	        'max':100,
	        'readOnly': true,
	        'width': 120,
	        'height': 120,
			'bgColor': 'rgba(255,255,255,0.5)',
	        'fgColor': 'rgba(255,255,255,0.9)',
	        'dynamicDraw': true,
	        'thickness': 0.2,
	        'tickColorizeValues': true
	    });
		
	}
	
	$(".circleStatsItemBox").each(function(){
		
		var value = $(this).find(".value > .number").html();
		var unit = $(this).find(".value > .unit").html();
		var percent = $(this).find("input").val()/100;
		
		countSpeed = 2300*percent;
		
		endValue = value*percent;
		
		$(this).find(".count > .unit").html(unit);
		$(this).find(".count > .number").countTo({
			
			from: 0,
		    to: endValue,
		    speed: countSpeed,
		    refreshInterval: 50,
		    onComplete: function(value) {
		    	console.debug(this);
		    }
		
		});
				
	});
	
	$('.circleChart').each(function(){
		
		var circleColor = $(this).parent().css('color');
		
		$(this).knob({
	        'min':0,
	        'max':100,
	        'readOnly': true,
	        'width': 120,
	        'height': 120,
	        'fgColor': circleColor,
	        'dynamicDraw': true,
	        'thickness': 0.2,
	        'tickColorizeValues': true,
			'skin':'tron'
	    });
		
	});	
	
}                

/* ---------- Calendars ---------- */

function calendars(){
	

	$('#external-events div.external-event').each(function() {

		// it doesn't need to have a start or end
		var eventObject = {
			title: $.trim($(this).text()) // use the element's text as the event title
		};
		
		// store the Event Object in the DOM element so we can get to it later
		$(this).data('eventObject', eventObject);
		
		// make the event draggable using jQuery UI
		$(this).draggable({
			zIndex: 999,
			revert: true,      // will cause the event to go back to its
			revertDuration: 0  //  original position after the drag
		});
		
	});
	
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();

	$('#main_calendar').fullCalendar({
		header: {
			left: 'title',
			right: 'prev,next today,month,agendaWeek,agendaDay'
		},
		editable: true,
		events: [
			{
				title: 'All Day Event',
				start: new Date(y, m, 1)
			},
			{
				title: 'Long Event',
				start: new Date(y, m, d-5),
				end: new Date(y, m, d-2)
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d-3, 16, 0),
				allDay: false
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d+4, 16, 0),
				allDay: false
			},
			{
				title: 'Meeting',
				start: new Date(y, m, d, 10, 30),
				allDay: false
			},
			{
				title: 'Lunch',
				start: new Date(y, m, d, 12, 0),
				end: new Date(y, m, d, 14, 0),
				allDay: false
			},
			{
				title: 'Birthday Party',
				start: new Date(y, m, d+1, 19, 0),
				end: new Date(y, m, d+1, 22, 30),
				allDay: false
			},
			{
				title: 'Click for Google',
				start: new Date(y, m, 28),
				end: new Date(y, m, 29),
				url: 'http://google.com/'
			}
		]
	});
	
	$('#main_calendar_phone').fullCalendar({
		header: {
			left: 'title',
			right: 'prev,next'
		},
		defaultView: 'agendaDay',
		editable: true,
		events: [
			{
				title: 'All Day Event',
				start: new Date(y, m, 1)
			},
			{
				title: 'Long Event',
				start: new Date(y, m, d-5),
				end: new Date(y, m, d-2)
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d-3, 16, 0),
				allDay: false
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d+4, 16, 0),
				allDay: false
			},
			{
				title: 'Meeting',
				start: new Date(y, m, d, 10, 30),
				allDay: false
			},
			{
				title: 'Lunch',
				start: new Date(y, m, d, 12, 0),
				end: new Date(y, m, d, 14, 0),
				allDay: false
			},
			{
				title: 'Birthday Party',
				start: new Date(y, m, d+1, 19, 0),
				end: new Date(y, m, d+1, 22, 30),
				allDay: false
			},
			{
				title: 'Click for Google',
				start: new Date(y, m, 28),
				end: new Date(y, m, 29),
				url: 'http://google.com/'
			}
		]
	});		
	
			
	$('#calendar').fullCalendar({
		header: {
			left: 'title',
			right: 'prev,next today,month,agendaWeek,agendaDay'
		},
		editable: true,
		droppable: true, // this allows things to be dropped onto the calendar !!!
		drop: function(date, allDay) { // this function is called when something is dropped
		
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');
			
			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);
			
			// assign it the date that was reported
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;
			
			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
			
			// is the "remove after drop" checkbox checked?
			if ($('#drop-remove').is(':checked')) {
				// if so, remove the element from the "Draggable Events" list
				$(this).remove();
			}
			
		}
	});
	
}

/* ---------- Widget Area Functions ---------- */
function widget_area_functions() {
	
	/* ---------- Just Gage Charts ---------- */
	
	var g1;
	
	setInterval(function() {
	          g1.refresh(getRandomInt(0, 100));
	}, 2500);
	
	var g1 = new JustGage({
	    id: "cpu-usage", 
	    value: getRandomInt(0, 100), 
	    min: 0,
	    max: 100,
	    title: "CPU",
	    label: "Usage",
	    levelColorsGradient: false
	 });
	
	/* ---------- Bar Stats ---------- */
	
	if (retina()) {

		$(".bar-stat > .chart").each(function(){

			var chartColor = $(this).css('color');	

			$(this).sparkline('html', {
			    type: 'bar',
			    height: '80', // Double pixel number for retina display
				barWidth: '10', // Double pixel number for retina display
				barSpacing: '4', // Double pixel number for retina display
			    barColor: chartColor,
			    negBarColor: '#eeeeee'}
			);

			$(this).css('zoom',0.5);

		});

	} else {

		$(".bar-stat > .chart").each(function(){

			var chartColor = $(this).css('color');

			$(this).sparkline('html', {				
			    type: 'bar',
			    height: '40',
				barWidth: '5',
				barSpacing: '2',
			    barColor: chartColor,
			    negBarColor: '#eeeeee'}
			);

		});

	}
	
}

/* ---------- Sparkline Charts ---------- */
function sparkline_charts() {
	
	//generate random number for charts
	randNum = function(){
		//return Math.floor(Math.random()*101);
		return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
	}

	var chartColours = ['#2FABE9', '#FA5833', '#b9e672', '#bbdce3', '#9a3b1b', '#5a8022', '#2c7282'];

	//sparklines (making loop with random data for all 7 sparkline)
	i=1;
	for (i=1; i<9; i++) {
	 	var data = [[1, 3+randNum()], [2, 5+randNum()], [3, 8+randNum()], [4, 11+randNum()],[5, 14+randNum()],[6, 17+randNum()],[7, 20+randNum()], [8, 15+randNum()], [9, 18+randNum()], [10, 22+randNum()]];
	 	placeholder = '.sparkLineStats' + i;
		
		if (retina()) {
			
			$(placeholder).sparkline(data, {
				width: 200,//Width of the chart - Defaults to 'auto' - May be any valid css width - 1.5em, 20px, etc (using a number without a unit specifier won't do what you want) - This option does nothing for bar and tristate chars (see barWidth)
				height: 60,//Height of the chart - Defaults to 'auto' (line height of the containing tag)
				lineColor: '#2FABE9',//Used by line and discrete charts to specify the colour of the line drawn as a CSS values string
				fillColor: '#f2f7f9',//Specify the colour used to fill the area under the graph as a CSS value. Set to false to disable fill
				spotColor: '#467e8c',//The CSS colour of the final value marker. Set to false or an empty string to hide it
				maxSpotColor: '#b9e672',//The CSS colour of the marker displayed for the maximum value. Set to false or an empty string to hide it
				minSpotColor: '#FA5833',//The CSS colour of the marker displayed for the mimum value. Set to false or an empty string to hide it
				spotRadius: 2,//Radius of all spot markers, In pixels (default: 1.5) - Integer
				lineWidth: 1//In pixels (default: 1) - Integer
			});
			
			$(placeholder).css('zoom',0.5);
			
		} else {
			
			$(placeholder).sparkline(data, {
				width: 100,//Width of the chart - Defaults to 'auto' - May be any valid css width - 1.5em, 20px, etc (using a number without a unit specifier won't do what you want) - This option does nothing for bar and tristate chars (see barWidth)
				height: 30,//Height of the chart - Defaults to 'auto' (line height of the containing tag)
				lineColor: '#2FABE9',//Used by line and discrete charts to specify the colour of the line drawn as a CSS values string
				fillColor: '#f2f7f9',//Specify the colour used to fill the area under the graph as a CSS value. Set to false to disable fill
				spotColor: '#467e8c',//The CSS colour of the final value marker. Set to false or an empty string to hide it
				maxSpotColor: '#b9e672',//The CSS colour of the marker displayed for the maximum value. Set to false or an empty string to hide it
				minSpotColor: '#FA5833',//The CSS colour of the marker displayed for the mimum value. Set to false or an empty string to hide it
				spotRadius: 2,//Radius of all spot markers, In pixels (default: 1.5) - Integer
				lineWidth: 1//In pixels (default: 1) - Integer
			});
			
		}
	
	}
	
}

/* ---------- Charts ---------- */


function growlLikeNotifications() {
	
	$('#add-sticky').click(function(){

		var unique_id = $.gritter.add({
			// (string | mandatory) the heading of the notification
			title: 'This is a sticky notice!',
			// (string | mandatory) the text inside the notification
			text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
			// (string | optional) the image to display on the left
			image: 'img/avatar.jpg',
			// (bool | optional) if you want it to fade out on its own or just sit there
			sticky: true,
			// (int | optional) the time you want it to be alive for before fading out
			time: '',
			// (string | optional) the class name you want to apply to that specific message
			class_name: 'my-sticky-class'
		});

		// You can have it return a unique id, this can be used to manually remove it later using
		/* ----------
		setTimeout(function(){

			$.gritter.remove(unique_id, {
				fade: true,
				speed: 'slow'
			});

		}, 6000)
		*/

		return false;

	});

	$('#add-regular').click(function(){

		$.gritter.add({
			// (string | mandatory) the heading of the notification
			title: 'This is a regular notice!',
			// (string | mandatory) the text inside the notification
			text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
			// (string | optional) the image to display on the left
			image: 'img/avatar.jpg',
			// (bool | optional) if you want it to fade out on its own or just sit there
			sticky: false,
			// (int | optional) the time you want it to be alive for before fading out
			time: ''
		});

		return false;

	});

    $('#add-max').click(function(){

        $.gritter.add({
            // (string | mandatory) the heading of the notification
            title: 'This is a notice with a max of 3 on screen at one time!',
            // (string | mandatory) the text inside the notification
            text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
            // (string | optional) the image to display on the left
            image: 'img/avatar.jpg',
            // (bool | optional) if you want it to fade out on its own or just sit there
            sticky: false,
            // (function) before the gritter notice is opened
            before_open: function(){
                if($('.gritter-item-wrapper').length == 3)
                {
                    // Returning false prevents a new gritter from opening
                    return false;
                }
            }
        });

        return false;

    });

	$('#add-without-image').click(function(){

		$.gritter.add({
			// (string | mandatory) the heading of the notification
			title: 'This is a notice without an image!',
			// (string | mandatory) the text inside the notification
			text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.'
		});

		return false;
	});

    $('#add-gritter-light').click(function(){

        $.gritter.add({
            // (string | mandatory) the heading of the notification
            title: 'This is a light notification',
            // (string | mandatory) the text inside the notification
            text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
            class_name: 'gritter-light'
        });

        return false;
    });

	$('#add-with-callbacks').click(function(){

		$.gritter.add({
			// (string | mandatory) the heading of the notification
			title: 'This is a notice with callbacks!',
			// (string | mandatory) the text inside the notification
			text: 'The callback is...',
			// (function | optional) function called before it opens
			before_open: function(){
				alert('I am called before it opens');
			},
			// (function | optional) function called after it opens
			after_open: function(e){
				alert("I am called after it opens: \nI am passed the jQuery object for the created Gritter element...\n" + e);
			},
			// (function | optional) function called before it closes
			before_close: function(e, manual_close){
                var manually = (manual_close) ? 'The "X" was clicked to close me!' : '';
				alert("I am called before it closes: I am passed the jQuery object for the Gritter element... \n" + manually);
			},
			// (function | optional) function called after it closes
			after_close: function(e, manual_close){
                var manually = (manual_close) ? 'The "X" was clicked to close me!' : '';
				alert('I am called after it closes. ' + manually);
			}
		});

		return false;
	});

	$('#add-sticky-with-callbacks').click(function(){

		$.gritter.add({
			// (string | mandatory) the heading of the notification
			title: 'This is a sticky notice with callbacks!',
			// (string | mandatory) the text inside the notification
			text: 'Sticky sticky notice.. sticky sticky notice...',
			// Stickeh!
			sticky: true,
			// (function | optional) function called before it opens
			before_open: function(){
				alert('I am a sticky called before it opens');
			},
			// (function | optional) function called after it opens
			after_open: function(e){
				alert("I am a sticky called after it opens: \nI am passed the jQuery object for the created Gritter element...\n" + e);
			},
			// (function | optional) function called before it closes
			before_close: function(e){
				alert("I am a sticky called before it closes: I am passed the jQuery object for the Gritter element... \n" + e);
			},
			// (function | optional) function called after it closes
			after_close: function(){
				alert('I am a sticky called after it closes');
			}
		});

		return false;

	});

	$("#remove-all").click(function(){

		$.gritter.removeAll();
		return false;

	});

	$("#remove-all-with-callbacks").click(function(){

		$.gritter.removeAll({
			before_close: function(e){
				alert("I am called before all notifications are closed.  I am passed the jQuery object containing all  of Gritter notifications.\n" + e);
			},
			after_close: function(){
				alert('I am called after everything has been closed.');
			}
		});
		return false;

	});


}

/* ---------- Additional functions for data table ---------- */
// $.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
// {
// 	return {
// 		"iStart":         oSettings._iDisplayStart,
// 		"iEnd":           oSettings.fnDisplayEnd(),
// 		"iLength":        oSettings._iDisplayLength,
// 		"iTotal":         oSettings.fnRecordsTotal(),
// 		"iFilteredTotal": oSettings.fnRecordsDisplay(),
// 		"iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
// 		"iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
// 	};
// }
// $.extend( $.fn.dataTableExt.oPagination, {
// 	"bootstrap": {
// 		"fnInit": function( oSettings, nPaging, fnDraw ) {
// 			var oLang = oSettings.oLanguage.oPaginate;
// 			var fnClickHandler = function ( e ) {
// 				e.preventDefault();
// 				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
// 					fnDraw( oSettings );
// 				}
// 			};

// 			$(nPaging).addClass('pagination').append(
// 				'<ul>'+
// 					'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
// 					'<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
// 				'</ul>'
// 			);
// 			var els = $('a', nPaging);
// 			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
// 			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
// 		},

// 		"fnUpdate": function ( oSettings, fnDraw ) {
// 			var iListLength = 5;
// 			var oPaging = oSettings.oInstance.fnPagingInfo();
// 			var an = oSettings.aanFeatures.p;
// 			var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

// 			if ( oPaging.iTotalPages < iListLength) {
// 				iStart = 1;
// 				iEnd = oPaging.iTotalPages;
// 			}
// 			else if ( oPaging.iPage <= iHalf ) {
// 				iStart = 1;
// 				iEnd = iListLength;
// 			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
// 				iStart = oPaging.iTotalPages - iListLength + 1;
// 				iEnd = oPaging.iTotalPages;
// 			} else {
// 				iStart = oPaging.iPage - iHalf + 1;
// 				iEnd = iStart + iListLength - 1;
// 			}

// 			for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
// 				// remove the middle elements
// 				$('li:gt(0)', an[i]).filter(':not(:last)').remove();

// 				// add the new list items and their event handlers
// 				for ( j=iStart ; j<=iEnd ; j++ ) {
// 					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
// 					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
// 						.insertBefore( $('li:last', an[i])[0] )
// 						.bind('click', function (e) {
// 							e.preventDefault();
// 							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
// 							fnDraw( oSettings );
// 						} );
// 				}

// 				// add / remove disabled classes from the static elements
// 				if ( oPaging.iPage === 0 ) {
// 					$('li:first', an[i]).addClass('disabled');
// 				} else {
// 					$('li:first', an[i]).removeClass('disabled');
// 				}

// 				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
// 					$('li:last', an[i]).addClass('disabled');
// 				} else {
// 					$('li:last', an[i]).removeClass('disabled');
// 				}
// 			}
// 		}
// 	}
// });

/* ---------- Page width functions ---------- */

$(window).bind("resize", widthFunctions);

function widthFunctions(e) {
	
    var winHeight = $(window).height();
    var winWidth = $(window).width();
    
	if (winWidth < 980 && winWidth > 767) {
		
		if($(".main-menu-span").hasClass("span2")) {
			
			$(".main-menu-span").removeClass("span2");
			$(".main-menu-span").addClass("span1");
			
		}
		
		if($("#content").hasClass("span10")) {
			
			$("#content").removeClass("span10");
			$("#content").addClass("span11");
			
		}
				
		$("a").each(function(){
			
			if($(this).hasClass("quick-button-small span1")) {

				$(this).removeClass("quick-button-small span1");
				$(this).addClass("quick-button span2 changed");
			
			}
			
		});
		
		$(".circleStatsItem, .circleStatsItemBox").each(function() {
			
			var getOnTablet = $(this).parent().attr('onTablet');
			var getOnDesktop = $(this).parent().attr('onDesktop');
			
			if (getOnTablet) {
			
				$(this).parent().removeClass(getOnDesktop);
				$(this).parent().addClass(getOnTablet);
			
			}
			  			
		});
		
		$(".tempStatBox").each(function() {
			
			var getOnTablet = $(this).attr('onTablet');
			var getOnDesktop = $(this).attr('onDesktop');
			
			if (getOnTablet) {
			
				$(this).removeClass(getOnDesktop);
				$(this).addClass(getOnTablet);
			
			}
			  			
		});
		
		$(".box").each(function(){
			
			var getOnTablet = $(this).attr('onTablet');
			var getOnDesktop = $(this).attr('onDesktop');
			
			if (getOnTablet) {
			
				$(this).removeClass(getOnDesktop);
				$(this).addClass(getOnTablet);
			
			}
			  			
		});
		
		$(".widget").each(function(){
			
			var getOnTablet = $(this).attr('onTablet');
			var getOnDesktop = $(this).attr('onDesktop');
			
			if (getOnTablet) {
			
				$(this).removeClass(getOnDesktop);
				$(this).addClass(getOnTablet);
			
			}
			  			
		});
							
	} else {
		
		if($(".main-menu-span").hasClass("span1")) {
			
			$(".main-menu-span").removeClass("span1");
			$(".main-menu-span").addClass("span2");
			
		}
		
		if($("#content").hasClass("span11")) {
			
			$("#content").removeClass("span11");
			$("#content").addClass("span11");
			
		}
		
		$("a").each(function(){
			
			if($(this).hasClass("quick-button span2 changed")) {

				$(this).removeClass("quick-button span2 changed");
				$(this).addClass("quick-button-small span1");
			
			}
			
		});
		
		$(".circleStatsItem, .circleStatsItemBox").each(function() {
			
			var getOnTablet = $(this).parent().attr('onTablet');
			var getOnDesktop = $(this).parent().attr('onDesktop');
			
			if (getOnTablet) {
			
				$(this).parent().removeClass(getOnTablet);
				$(this).parent().addClass(getOnDesktop);
			
			}
			  			
		});
		
		$(".tempStatBox").each(function() {
			
			var getOnTablet = $(this).attr('onTablet');
			var getOnDesktop = $(this).attr('onDesktop');
			
			if (getOnTablet) {
			
				$(this).removeClass(getOnTablet);
				$(this).addClass(getOnDesktop);
			
			}
			  			
		});
		
		$(".box").each(function(){
			
			var getOnTablet = $(this).attr('onTablet');
			var getOnDesktop = $(this).attr('onDesktop');
			
			if (getOnTablet) {
			
				$(this).removeClass(getOnTablet);
				$(this).addClass(getOnDesktop);
			
			}
			  			
		});
		
		$(".widget").each(function(){
			
			var getOnTablet = $(this).attr('onTablet');
			var getOnDesktop = $(this).attr('onDesktop');
			
			if (getOnTablet) {
			
				$(this).removeClass(getOnTablet);
				$(this).addClass(getOnDesktop);
			
			}
			  			
		});
		
	}
	
	if($('.timeline')) {
		$('.timeslot').each(function(){
			var timeslotHeight = $(this).find('.task').outerHeight();
			$(this).css('height',timeslotHeight);
		});
	}





  //Set up imgAreaSelect
	$('body').on('click','.start-tagging', function() {
		
		$('.start-tagging').toggle("hide");
		$('.finish-tagging').toggle("hide");
		//load imgAreaSelect (#imageid must equal the id or class of your image.
		//$('img#imageid').imgAreaSelect({ handles: true, onSelectChange: selectChange, keys: { arrows: 15, shift: 5 }, aspectRatio: '4:3', maxWidth: 150, maxHeight: 100 });
		$('img#fancybox-img').imgAreaSelect({
			disable: false, //enable/disable tagging
			handles: true, //grab handels when selecting the area
			keys: { arrows: 15, shift: 5 }, //keyboard support
			aspectRatio: '1:1',
			maxWidth: 150, //adjust the max tag width
			maxHeight: 150, //adjust the max tag height
			fadeSpeed: 200,
			onSelectEnd: function(img, selection){ //after you have selected an area, show the form and insert tag location values into a form
				$('input#x1').val(selection.x1);
				$('input#y1').val(selection.y1);
				$('input#x2').val(selection.x2);
				$('input#y2').val(selection.y2);
				$('input#w').val(selection.width);
				$('input#h').val(selection.height);
				$('#title_container').css('left', selection.x1);
				$('#title_container').css('top', selection.y2);
				$('#title_container').removeClass("hide");
				if (selection.width == 0 && selection.height == 0) { $('#title_container').addClass("hide"); } //if there is no selection, hide the form
		   },
		   onSelectStart: function(img, selection){
				$('#title_container').addClass("hide"); //if reselecting, hide the form
		   },
		});
	});
	$('.finish-tagging').click(function(){
		$('.start-tagging').toggle("hide");
		$('.finish-tagging').toggle("hide");
		$('#title_container').addClass("hide");
		$('img#imageid').imgAreaSelect({ disable: true, hide: true }); //disable imgareaselect, this along with start/finish-tagging can be removed if needed
	});

  //Tag list hovers ( when hovering the list of tags show the titles.
  $('#titles a.title').hover(function() {
    $('.map li').find('a.' + $(this).attr('id')).addClass('hover');
    $('.map li').find('a.' + $(this).attr('id')).find('span').show().addClass('selected');
  }, function() {
    $('.map li').find('a.' + $(this).attr('id')).removeClass('hover');
    $('.map li').find('a.' + $(this).attr('id')).find('span').hide().removeClass('selected');
  });

  //when hovering the tagged areas show the title
  $('.map ul li a').hover(function() {
    $(this).find('span').show();
  }, function() {
    $(this).find('span').hide();  
  });

  // $('.map').hover(function() {
  //   $(this).find('img').show();
  // }, function() {
  //   $(this).find('img').hide();  
  // });

	//Close the error box for form pages
	$('a#error-link').click(function() {
		$('#error-box').slideUp('slow');
		return false;
	});
	$('a#warning-link').click(function() {
		$('#warning-box').slideUp('slow');
		return false;
	});



}

