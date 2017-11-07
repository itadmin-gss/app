//$.noConflict();
$(document).ready(function(){

	var myDropZone;

	// Dropzone -> Property Photo Upload Options
	Dropzone.options.dropzoneUpload = {
		dictDefaultMessage: "Drag your file or click to upload",
		url: baseurl+ '/property-photo-upload',
		previewTemplate: document.querySelector("#dropzone-preview-template").innerHTML,
		previewsContainer: "#tpl",
		acceptedFiles: ".jpg, .jpeg, .gif, .png",
		maxFiles: 1,
		thumbnailWidth: null,
		thumbnailHeight: null,
		autoProcessQueue: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	};

	$("#dropzone-cancel").on("click", function(){
		$(".dropzone-control-buttons").hide();
		$("#dropzone-upload").fadeIn("fast");
		myDropZone.removeAllFiles();
	});

	//Event Handler -> confirm vendor delete modal
	$(document).on("click", "#confirm-vendor-delete", function(){
		$("#vendor-delete-id").val($(this).data('vendor-id'));
		$("#vendor-delete-table").val($(this).data('table'));
		$("#vendor-delete-modal").modal("toggle");
	});

	//Event Handler -> Property Photo Upload
	$("#property-photo-upload").on("click", function(){
		$("#photo-upload-modal").modal("toggle");
        myDropZone = Dropzone.forElement("#dropzone-upload");
        myDropZone.on("addedfile", function(file){
            $("#dropzone-upload").hide();
            $(".dropzone-control-buttons").fadeIn("fast");
        });
	});

	$("#photo-upload-modal").on("hidden.bs.modal", function(){
        $(".dropzone-control-buttons").hide();
        $("#dropzone-upload").fadeIn("fast");
        myDropZone.removeAllFiles();
	});

	$("#dropzone-process-upload").on("click", function(){
		myDropZone.processQueue();
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

		if ($("#password").val() !== $("#password_2").val())
		{
			alert("Passwords do not match");
			return false;
		}

		if ($("#password").val().length < 4){
			alert("Password must be 5 or more characters");
			return false;
		}

		$(".complete-profile-step-1").hide();
        $(".complete-profile-step-2").fadeIn("fast");

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


	$("#vendor-step-4-back").on("click", function(){
		$(".complete-profile-review").hide();
		$(".complete-profile-step-3").fadeIn("fast");
	});

	//Event Handler -> Vendor Profile Completion - Step 3 -> Step 4 (Next Button)
	$("#vendor-step-3-next").on("click", function(){

		$(".review-username").text($("#username").val());
		$(".review-address-1").text($("#address_1").val());
		$(".review-address-2").text($("#address_2").val());
		$(".review-city").text($("#city_id option:selected").text());
		$(".review-state").text($("#state_id option:selected").text());
		$(".review-zip").text($("#zipcode").val());
		$(".review-company").text($("#company").val());
		$(".review-phone").text($("#phone").val());

		var services = "";
		$("#vendor_services option:selected").each(function(){
			var $this = $(this);
			services += $this.text()+"<br>";
		});

		$(".review-services").html(services);
		$(".review-ziplist").text($("#zip_list").val());

        $(".complete-profile-step-3").hide();
        $(".complete-profile-review").fadeIn("fast");

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

		





	
	











