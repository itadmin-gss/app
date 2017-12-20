/* Download an img */



function modalClose(){
    $("#popup1 .popup").addClass("zoomOut");
    $("#popup1").attr('style',  'display:none');
  }
  function download(img) {
      var link = document.createElement("a");
      link.href = img.src;
      link.download = true;
      link.style.display = "none";
      var evt = new MouseEvent("click", {
          "view": window,
          "bubbles": true,
          "cancelable": true
      });
  
      document.body.appendChild(link);
      link.dispatchEvent(evt);
      document.body.removeChild(link);
      console.log("Downloading...");
  }
  function showQuickWorkOrderPage(order_id){
    if ($("#quick-view-modal").hasClass('show')){
        $("#quick-view-modal").modal("toggle");
    }
     var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
    $.ajax({
        url: baseurl +"/get-quick-workorder/"+order_id,
        cache: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
        success: function(data){
            $("#overlay").remove();

        //   $(function() {
        //   $("body").on(".click", ".datepicker", function(){
        //     $(this).datepicker();
        //     });
        //   });
            $("#quick-view-modal .modal-body").html(data);
            $("#quick-view-modal").modal('toggle');
         
      } 
  });
  
  $("#popup1 .popup").addClass("zoomOut");
  $("#popup1 .popup").removeClass("zoomIn");
  }
  
  $(".popUpOvrlay .popUpOvrlay .ovrlyPop").click(function(){
    //   $('.modal-backdrop').fadeToggle();
    // $(".popUpOvrlay").fadeOut();
    // var over = '<div id="overlay">' +
    //       '<img id="loading" src="assets/img/loader.gif">' +
    //       '</div>';
    //       $(over).appendTo('body');
    // window.location.reload();
    $("#popup1 .popup").addClass("zoomOut");
  
  });
  $(document).ready(function(){
      $('.myClose').click(function(){
        // alert("sak");
      });
    $('body').delegate('.shwasst','click',function(){
      setTimeout(function(){
        $('.modal-backdrop').remove();
      },100);
    });
  });
  
  $("body").delegate('#popup1 button.myBtnImg','click',function(){
      var contactTopPosition = $("#popup1").position().top,
      mdlSrc = $(this).data('target');
      $('#popup1 .popUpInnr .modal').hide();
      $('#popup1 .popUpInnr .modal'+mdlSrc).fadeIn();
      $(".popUpInnr").scrollTop(contactTopPosition);
      $("html, body").animate({ scrollTop: 0 }, "slow");
      setTimeout(function(){
          $('.modal-backdrop').hide();
      },100);
  });
  
  
  /* Download all images in 'imgs'.
   * Optionaly filter them by extension (e.g. "jpg") and/or
   * download the 'limit' first only  */
  function downloadAll(imgs, ext, limit) {
      /* If specified, filter images by extension */
      if (ext) {
          ext = "." + ext;
          imgs = [].slice.call(imgs).filter(function(img) {
              var src = img.src;
              return (src && (src.indexOf(ext, src.length - ext.length) !== -1));
          });
  
          
      }
  
      /* Determine the number of images to download */
      limit = (limit && (0 <= limit) && (limit <= imgs.length))
              ? limit : imgs.length;
  
  
      /* (Try to) download the images */
      for (var i = 0; i < limit; i++) {
          var img = imgs[i];
          //console.log("IMG: " + img.src + " (", img, ")");
          // download(img);
      }
  }
  
  function doit() {
      var imgs = document.querySelectorAll(".imageFrame img");
  
  //         limit = (limit && (0 <= limit) && (limit <= imgs.length))
  //             ? limit : imgs.length;
  //console.log(imgs.length);
       for (var i = 0; i < imgs.length; i++) {
          var img = imgs[i];
         //console.log("IMG: " + img.src );
          download(img);
      }
       
       // downloadAll(imgs, "jpg", -1);
  }
  
  /* Create and add a "download" button on the top, left corner */
  // function addDownloadBtn() {
  //     var btn = document.createElement("button");
  //     btn.innerText = "Download all images";
  //     btn.addEventListener("click", doit);
  //     btn.style.position = "fixed";
  //     btn.style.top = btn.style.left = "0px";
  //     document.body.appendChild(btn);
  // }
  // addDownloadBtn();
  
  function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      // Added to allow decimal, period, or delete
      if (charCode == 110 || charCode == 190 || charCode == 46) 
          return true;
      
      if (charCode > 31 && (charCode < 48 || charCode > 57)) 
          return false;
      
      return true;
  } // isNumberKey
  function getQueryParams(qs) {
      qs = qs.split('+').join(' ');
  
      var params = {},
      tokens,
      re = /[?&]?([^=]+)=([^&]*)/g;
  
      while (tokens = re.exec(qs)) {
          params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
      }
  
      return params;
  }
  
  function deleteSelectedAsset(id) {
      if (confirm('Are you sure you want to delete Asset #: '+id)) {
       $.ajax({
        url: baseurl +"/delete-selected-asset/"+id,
        cache: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
          $('#assetFlash').slideDown('slow');
          window.location.reload();
      } 
  });
   } else {
     return false;
  }
  
  }
  
  $(document).ready(function() {
      $("body").delegate('#export_view_images .btn-success','click',function(){
          $(this).closest('#export_view_images').removeClass('in');
          $('.modal-backdrop').remove();
      });
      


      $('.tabBox .imageFrame').hide();
      $('.tabBox .imageFrame.exprtTab').show();
      $("body").delegate('.tabTgr li a','click',function(){
          var tab = $(this).data('tab');
          //alert(tab);
          $('.tabBox').find('.imageFrame').hide();
          $('.tabBox').find(tab).fadeIn();
      });
  
      $("body").delegate('.Osredt','click',function(){
          $("#bid_price").prop("readonly",false);
          $("#textarea2").prop("readonly",false);
      }); 
      setTimeout(function(){
          var query = getQueryParams(document.location.search);
          if(typeof query.url !== "undefined")
          {
             $("#firstcolumn").val(query.url);
  
             $( "#firstcolumn"  ).keyup();
         } 
     });
  //setInterval(auto_load,10000);
  function auto_load(){
      $.ajax({
        url: "refresh",
        cache: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
          $(".refVal").text(data);
      } 
  });
  }
  
  
  
      $("#assetSummary").change(function() {
          var assetSummary=  $(this).val();
  
  
          var over = '<div id="overlay">' +
          '<img id="loading" src="assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
  
         if(assetSummary==1)
         {
             $(".datatablegrid").show();
             $(".datatablegrid2").hide();
             $(".datatablegrid3").hide();
             $(".datatablegrid4").hide();
  
         }
         else if(assetSummary==2)
         {
             $(".datatablegrid").hide();
             $(".datatablegrid2").show();
             $(".datatablegrid3").hide();
             $(".datatablegrid4").hide();
  
         }
         else if(assetSummary==3)
         {
             $(".datatablegrid").hide();
             $(".datatablegrid2").hide();
             $(".datatablegrid3").show();
             $(".datatablegrid4").hide();
  
         }
         else if(assetSummary==4)
         {
             $(".datatablegrid").hide();
             $(".datatablegrid2").hide();
             $(".datatablegrid3").hide();
             $(".datatablegrid4").show();
  
         }
           $('#overlay').remove();
  
      });
  
  
      setTimeout(function(){ $('#dashboardclick').click();}, 2000);
  
      $("input[name='emergency_request']").change(function() {
          var emergency_request=  $(this).val();
  
          if(emergency_request=="1")
          {
              $("#emergency_request_additional_text").show();
  
          }
          else
          {
             $("#emergency_request_additional_text").hide();  
  
         }
  
  
     });
  
      $("#property_status").change(function() {
          var property_status=  $(this).val();
  
          if(property_status=="inactive")
          {
              $("#property_status_note").show();
  
          }
          else
          {
             $("#property_status_note").hide();  
  
         }
  
  
     });
  
      $("#Invoiceonchange").change(function() {
          var Invoiceonchange=  $(this).val();
          
  
          var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
  
          window.location="admin-list-invoice/"+Invoiceonchange;
  
      });
  
  
  
      $("#bidRequestonchangeCustomer").change(function() {
          var bidRequestonchange=  $(this).val();
          
  
          var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
  
          window.location="customer-bid-requests/"+bidRequestonchange;
  
      });
  
  
      $("#bidRequestonchangeVendor").change(function() {
          var bidRequestonchange=  $(this).val();
          
  
          var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
  
          window.location="vendor-bid-requests/"+bidRequestonchange;
  
      });
  
  
      $("#bidRequestonchange").change(function() {
          var bidRequestonchange=  $(this).val();
          
  
          var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
  
          window.location="admin-bid-requests/"+bidRequestonchange;
  
      });
  
  
      $('#bidRequest').click(function(e){
         $('#bid_flag').val(1);
  
         $('.request-services').submit();
     });
  
      $('body').on('click', '.mystatusclass li > a', function(e){
  
  
          var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
  
  
  
          var order_id=$("#order_id_custom").val();
          var during =0;
          var after =0;
          var before =0;
          var orderstatus=this.innerHTML;
          var orderstatusid="";
          var orderstatus_class="";
          var totalRequestedServices=$('#totalRequestedServices').val();
  
  
          $.ajax({
              type: 'Get',
              url:  baseurl +'/order-images/'+order_id,
  
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              
              success: function(response) {
  
                during=response.during;
                after=response.after;
                before=response.before;
                if (orderstatus=="Cancelled")
                  {
                      orderstatusid=5;
                      orderstatus_class="black";
                      alert(orderstatus);
                       $.ajax({
              type: 'Post',
              url:  'change-status',
  
              data: {
                  order_id: order_id,
                  orderstatusid: orderstatusid,
                  orderstatus_class:orderstatus_class,
                  orderstatus_text:orderstatus,
                  totalRequestedServices:totalRequestedServices
                  
  
  
              },
              
              success: function(response) {
  
               $('#overlay').remove();
               $('#errorMessage').focus();
               $('#errorMessage').addClass("alert alert-success");
               $('#errorMessage').slideUp('slow');
               $('#errorMessage').html(response).hide();
               $('#errorMessage').slideDown('slow');
               $('#edit-qty').show();
  
  
           }
       });
               return true;
                  }
  
                if(before<10)
                {
                 alert("Please upload 10 before images");
                 $('#overlay').remove();
                 return false;
             }
  
             if(after<=0)
             {
                 alert("Please at least one after images");
                 $('#overlay').remove();
                 return false;
             }
  
             if(during<=0)
             {
                 alert("Please at least  one during images");
                 $('#overlay').remove();
                 return false;
             }
  
  
             if(orderstatus=="In-Process")
             {
              orderstatusid=1;
              orderstatus_class="warning";
          }
          else if (orderstatus=="Completed")
          {
              orderstatusid=2;
              orderstatus_class="black";
              //$('#completion_date_div').show();
              $('#completion_date_div').trigger('click');
          }
          else if (orderstatus=="Under Review")
          {
              orderstatusid=3;
              orderstatus_class="important";
          }
          else if (orderstatus=="Approved")
          {
              orderstatusid=4;
              orderstatus_class="gray";
              $('#recurringpopup').modal('show');
          }
          else if (orderstatus=="Cancelled")
          {
              orderstatusid=5;
              orderstatus_class="black";
          }
  
  
  
  
  
  
  
          $('.orderstatus').text(orderstatus);
  
          var completion_date=$('#completion_date').val();
          var under_review_notes=$('#under_review_notes').val();
  
          if(orderstatusid==3 && under_review_notes=="")
          {
  
             $('#under_review_notes').focus();
             $('#under_review_notes_section').show();
             $('#overlay').remove();
             $('#errorMessage').focus();
             $('#errorMessage').addClass("alert alert-success");
             $('#errorMessage').slideUp('slow');
             $('#errorMessage').html("Please enter reason for under review").hide();
             $('#errorMessage').slideDown('slow');
  
  
         }
         else if(orderstatusid==2 && completion_date=="")
  
         {
  
             $('#completion_date').focus();
             $('#overlay').remove();
             $('#errorMessage').focus();
             $('#errorMessage').addClass("alert alert-success");
             $('#errorMessage').slideUp('slow');
             $('#errorMessage').html("Please select completion date first and then select complete again").hide();
             $('#errorMessage').slideDown('slow');
  
  
         }else{
  
  
          $.ajax({
              type: 'Post',
              url:  baseurl +'/change-status',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: {
                  order_id: order_id,
                  orderstatusid: orderstatusid,
                  orderstatus_class:orderstatus_class,
                  orderstatus_text:orderstatus,
                  totalRequestedServices:totalRequestedServices
  
  
  
              },
              
              success: function(response) {
               $('#overlay').remove();
               $('#errorMessage').focus();
               $('#errorMessage').addClass("alert alert-success");
               $('#errorMessage').slideUp('slow');
               $('#errorMessage').html(response).hide();
               $('#errorMessage').slideDown('slow');
               $('#edit-qty').show();
  
  
           }
       });
      }
  
  
  }
  });
  
  
      });
  
  // $("#property_address").change(function() {
  
  //     var zip=  $('#zip').val();
  //     var state=  $('#state_id option:selected').text();
  //     var city=  $('#city_id option:selected').text();
  //     var property_address=  $('#property_address').val();
  
  //     var over = '<div id="overlay">' +
  //     '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
  //     '</div>';
  //     $(over).appendTo('body');
  
  
  //     $.ajax({
  //         type: 'Post',
  //         url: 'get-asset-map',
  
  //         data: {
  //             zip: zip,
  //             state: state,
  //             city: city,
  //             property_address: property_address,
  
  //         },
  
  //         success: function(response) {
  //          $('#overlay').remove();
  //          $("#maparea").html(response);
  
  //      }
  //  });
  
  
  // });
  
  
  $("#zip").change(function() {
      var zip= $('#zip').val(); 
      var state=  $('#state_id option:selected').text();
      var city=  $('#city_id option:selected').text();
      var property_address=  $('#property_address').val();
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/get-asset-map',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          data: {
              zip: zip,
              state: state,
              city: city,
              property_address: property_address,
  
          },
          
          success: function(response) {
             $('#overlay').remove();
             $("#maparea").html(response);
  
         },
         error: function(response){
          $('#overlay').remove();
          alert('failure');
        }
     });
  
  
  });
  
  $('.request-services').on('submit', function(e) {
      var list_of_services = $('#list_of_services').html();
      var asset_number = $('#asset_number').val();
      var service_id = $('#service_ids').val();
      var bid_flag=$('#bid_flag').val();
      if(bid_flag!="")
      {
  
  
          service_id="flag";
      }
      if (asset_number == '' || service_id == null) {
          $('#add_asset_alert').html('Asset number and at least one service is required.').slideDown('slow');
          return false;
      } else {
          if (list_of_services == "") {
  
              $('#add_asset_alert').html('Service detail is required.').slideDown('slow');
              $('#service_ids').val('Select some options');
              $('#service_ids').trigger('liszt:updated');
              return false;
          } else {
  
              return true;
          }
  
      }
  
  
  
  
  
  
  
  });
  
  $('#generate_asset_number').click(function() {
  
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/generate-asset-number',
          cache: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
              var asset_number = response;
              $('#asset_number').val(asset_number);
          }
      });
  });
  
  $('#state_id').change(function() {
          //e.preventDefault();

  
          var state_id = this.value;
          var options = '';
          $.ajax({
              type: 'POST',
              url: baseurl +'/get-cities-by-state-id',
              data: {
                  state_id: state_id
              },
              cache: false,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                  var obj = JSON.parse(response);
                  for (var i = 0; i < obj.length; i++) {
                      options += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
                  }
                  $("#city_id").html(options);
  
                  $('#city_id').trigger('liszt:updated');
              }
          });
      });
  
  
  $('#asset_number').change(function(e) {
      $('#add_asset_alert').slideUp('slow');
  
      $('#review_order_property').html($('#asset_number option:selected').html());
      $(this).closest('.control-group').find('.nxtStep').trigger('click');
      var asset_id = this.value;
      $.ajax({
          type: 'Post',
          url: baseurl +'/ajax-get-asset-by-asset-id',
          data: {
              asset_id: asset_id
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          success: function(response) {
  
              $('#showServiceid').html(response);           
  
          }
      });
  });


  $('body').on('click', '.view_asset_information', function(){
      var asset_id = this.id;
  
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/ajax-get-asset-by-asset-id',
          data: {
              asset_id: asset_id
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          success: function(response) {
              $('#asset_information .modal-body').html(response);
          }
      });
  
      $('#asset_information').modal('toggle');
  });
  
  
  
  $('#viewassets').click(function() {
      var asset_number = $('#asset_number').val();
      if (asset_number == '') {
          $('#add_asset_alert').html('Please Select asset.').slideDown('slow');
  
      } else {
          $('#showServiceid').modal('show');
      }
  
  
  });
  
      // Set the dialog to open when mySelect changes
  
      /*
       * While Service is selected in add request page
       * Start here
       */
  
  
  
  
       {
          $('select#service_ids').on('change', function(event, params) {

              if (params.selected)
  
              {
                  var service_id = $(this).val();
                  if(service_id=="flagother")
                  {
  
                      $('#bidrequest').show();
                      $('#bid_flag').val(1);
  
                  }
                  else {
                     $('#bidrequest').hide();
                     $('#bid_flag').val(0);
                     var asset_number = $('#asset_number').val();
  
                     var current_service = params.selected;
                  //        var current_service_id=service_id[service_id.length - 1];
                  $.ajax({
                      type: 'Post',
                      url: baseurl+'/ajax-service-information-popup',
                      data: {
                          service_id: current_service,
                          asset_number: asset_number
                      },
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      cache: false,
                      success: function(response) {
                          if (response == 'error') {
                              $('#add_asset_alert').html('Please Select asset.');
                              $('#add_asset_alert').slideDown('slow');
                              $('#service_ids').val('Select some options');
                              $('#service_ids').trigger('liszt:updated');
  
                              // $('#service_ids').append('<option></option>');
                              // $( "#allservices" ).load('/includejs.php');
  
                          } else {
  
                              $('#allservices').html(response);
                              $('#add_asset_alert').slideUp('slow');
                              $('#' + current_service).modal({
                                  backdrop: 'static',
                                  keyboard: true
                              });
                              $('#' + current_service).modal('toggle');
                              $('.datepicker').datepicker();
                          }
                      }
                  }); }
              }
  
              else
              {
                  var service_id = params.deselected;
  
                  $('#services_list_' + service_id).remove();
                  $('#revieworderservice_' + service_id).remove();
  
  
  
              }
  
              // $('#addcontenthere').load('view-customer-assets');
  
          });
      }
  
  
      {
          $('select#vendor_service_ids').on('change', function(event, params) {
              if (params.selected)
  
              {
                  var asset_number = $('#asset_number').val();
                  var service_id = $(this).val();
                  var current_service = params.selected;
                  //        var current_service_id=service_id[service_id.length - 1];
                  $.ajax({
                      type: 'Post',
                      url: baseurl +'/ajax-vendor-service-information-popup',
                      data: {
                          service_id: service_id,
                          asset_number: asset_number
                      },
                      cache: false,
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      success: function(response) {
                          if (response == 'error') {
                              $('#add_asset_alert').html('Please Select asset.');
                              $('#add_asset_alert').slideDown('slow');
                              $('#service_ids').val('Select some options');
                              $('#service_ids').trigger('liszt:updated');
  
                              // $('#service_ids').append('<option></option>');
                              // $( "#allservices" ).load('/includejs.php');
  
                          } else {
                              $('#allservices').html(response);
                              $('#add_asset_alert').slideUp('slow');
                              $('#' + current_service).modal({
                                  backdrop: 'static',
                                  keyboard: true
                              });
                              $('#' + current_service).modal('show');
                              $('.datepicker').datepicker();
                          }
                      }
                  });
              }
  
              else
              {
                  var service_id = params.deselected;
  
                  $('#services_list_' + service_id).remove();
  
  
              }
  
              // $('#addcontenthere').load('view-customer-assets');
  
          });
      }
  
  
  
  
  
  //    $('select#service_ids').change(function() {
  //        var asset_number = $('#asset_number').val();
  //
  //        var service_id = $(this).val();
  //        var current_service_id = service_id[service_id.length - 1];
  //        $.ajax({
  //            type: 'Post',
  //            url: 'ajax-service-information-popup',
  //            data: {
  //                service_id: service_id,
  //                asset_number: asset_number
  //            },
  //            cache: false,
  //            success: function(response) {
  //                if (response == 'error') {
  //                    $('#add_asset_alert').html('Please Select asset.');
  //                    $('#add_asset_alert').slideDown('slow');
  //                    $('#service_ids').val('Select some options');
  //                    $('#service_ids').trigger('liszt:updated');
  //
  //                    // $('#service_ids').append('<option></option>');
  //                    // $( "#allservices" ).load('/includejs.php');
  //
  //                } else {
  //                    $('#allservices').html(response);
  //                    $('#add_asset_alert').slideUp('slow');
  //                    $('#' + current_service_id).modal('show');
  //                    $('.datepicker').datepicker();
  //                }
  //
  //            }
  //        });
  //        // $('#addcontenthere').load('view-customer-assets');
  //
  //    });
  
      /*
       * While Service is selected in add request page
       * Start End
       */
  
  
  
      ////////////////////////////////////////////// Vendor //////////////////////////////////
  
  
  
      {
          $('#changePassword').click(function() {
              if ($(this).is(':checked')) {
                  $('#changePasswordControll').slideDown();
              }
              else {
                  $('#changePasswordControll').slideUp();
              }
          });
  
          $('#editFirstName').click(function() {
              $('firstName').removeAttr('readonly');
          })
  
      }
  
  
  
      {
          // Set the dialog to open when mySelect changes
          $('#changePassword').click(function() {
              if ($(this).is(':checked')) {
                  $('#changePasswordControll').slideDown();
              }
              else {
                  $('#changePasswordControll').slideUp();
              }
          });
          $('#editPruvanUsername').click(function() {
              $('#pruvanUsername').removeAttr('readonly');
          })
          $('#editEmail').click(function() {
              $('#editEmail').removeAttr('readonly');
          })
          $('#editFirstName').click(function() {
              $('#firstName').removeAttr('readonly');
          })
          $('#editLastName').click(function() {
              $('#lastName').removeAttr('readonly');
          })
          $('#editUsername').click(function() {
              $('#username').removeAttr('readonly');
          })
          $('#editEmail').click(function() {
              $('#email').removeAttr('readonly');
          })
          $('#editCompany').click(function() {
              $('#company').removeAttr('readonly');
          })
          $('#editPhone').click(function() {
              $('#phone').removeAttr('readonly');
          })
          $('#editFirstName').click(function() {
              $('#firstName').removeAttr('readonly');
          })
          $('#editAddress1').click(function() {
              $('#address1').removeAttr('readonly');
          })
          $('#editAddress2').click(function() {
              $('#address2').removeAttr('readonly');
          })
          $('#editZipcode').click(function() {
              $('#zipcode').removeAttr('readonly');
          })
  
      }
  
  
  
  //    {
  //        $('#state_id').change(function() {
  //            var state_id = this.value;
  //            var options = '';
  //            $.ajax({
  //                type: 'POST',
  //                url: 'get-cities-by-state-id',
  //                data: {
  //                    state_id: state_id
  //                },
  //                cache: false,
  //                success: function(response) {
  //
  //                    alert(response);
  //                    var obj = JSON.parse(response);
  //                    for (var i = 0; i < obj.length; i++) {
  //                        options += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
  //                    }
  //
  //                    $("select#city_id").html(options);
  //                }
  //            });
  //        });
  //    }
  
  
  
  {
      $("#profileEditForm").on('submit', (function(e) {
  
  
  
          e.preventDefault();
          var over = '<div id="overlay">' +
          '<img id="loading" src="public/'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
  
          $.ajax(
          {
              url: baseurl +'/save-profile',
              type: 'POST',
              data: new FormData(this),
              contentType: false,
              cache: false,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              processData: false,
              success: function(data)
              {
  
                  if (data.indexOf("Update Profile Successfully") > -1)
                  {
                      $('#overlay').remove();
  
                      $('#profileValidationErrorMessage').slideUp('slow')
                      $('#profileSuccessMessage').html(data).hide();
                      $('#profileSuccessMessage').slideDown('slow');
                  }
                  else
                  {
                      $('#overlay').remove();
                      $('#profileSuccessMessage').slideUp('slow')
                      $('#profileValidationErrorMessage').html(data).hide();
                      $('#profileValidationErrorMessage').slideDown('slow');
                  }
              },
              error: function()
              {
  
                  $('#overlay').remove();
                  $('#profileErrorMessage').slideDown();
              }
          });
      }));
  }
  
  
  
  
  
  {
      $("#UserEditForm").on('submit', (function(e) {
  
          var id = $('#user_id').val();
  
  
          e.preventDefault();
          var over = '<div id="overlay">' +
          '<img id="loading" src="' + '/public/'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
          $.ajax(
          {
              url: baseurl +'/save-profile-admin/' + id,
              type: 'POST',
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              success: function(data)
              {
  
                  if (data.indexOf("Update Profile Successfully") > -1)
                  {
                      $('#overlay').remove();
  
                      $('#profileValidationErrorMessage').slideUp('slow')
                      $('#profileSuccessMessage').html(data).hide();
                      $('#profileSuccessMessage').slideDown('slow');
                  }
                  else
                  {
                      $('#overlay').remove();
                      $('#profileSuccessMessage').slideUp('slow')
                      $('#profileValidationErrorMessage').html(data).hide();
                      $('#profileValidationErrorMessage').slideDown('slow');
                  }
              },
              error: function()
              {
  
                  $('#overlay').remove();
                  $('#profileErrorMessage').slideDown();
              }
          });
      }));
  }
  
  
  {
      $("#addVendorForm").on('submit', (function(e) {  

  
         e.preventDefault();
         $.ajax(
         {
          url: baseurl +'/process-add-vendor',
          type: 'POST',
          data: new FormData(this),
          contentType: false,
          cache: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          processData: false,
          success: function(data)
          {
              if (data.indexOf("Vendor Created!") > -1)
              {
                  window.location.href="/list-vendors";  
              }
              else
              {
                  $('#addVendorValidationErrorMessage').html(data).hide();
                  $('#addVendorValidationErrorMessage').slideDown('slow');
              }
          },
          error: function()
          {
              $("#overlay").remove();
              $('#addVendorErrorMessage').slideDown();
          }
      });
     }));
  }
  
  
  
  
  {
      $("#addSpecialPriceForm").on('submit', (function(e) {
          e.preventDefault();
          $.ajax(
          {
              url: baseurl +'/add-special-prices',
              type: 'POST',
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              success: function(data)
              {
                  if (data.indexOf("Successfully ! Added Special Price!") > -1)
                  {
                      $('#addSpecialPriceValidationErrorMessage').slideUp('slow')
                      $('#addSpecialPriceSuccessMessage').html(data).hide();
                      $('#addSpecialPriceSuccessMessage').slideDown('slow');
  
                  }
                  else
                  {
                      $('#addSpecialPriceSuccessMessage').slideUp('slow')
                      $('#addSpecialPriceValidationErrorMessage').html(data).hide();
                      $('#addSpecialPriceValidationErrorMessage').slideDown('slow');
                  }
              },
              error: function()
              {
                  $('#addSpecialPriceErrorMessage').slideDown();
              }
          });
      }));
  }
  
  
  
  {
      $("#editSpecialPriceForm").on('submit', (function(e) {
          var special_price_id = $('.submit_form').attr('id');
          e.preventDefault();
          $.ajax(
          {
              url: baseurl +'/edit-special-price/' + special_price_id,
              type: 'POST',
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              success: function(data)
              {
                  if (data.indexOf("Successfully ! Added Special Price!") > -1)
                  {
  
                      $('#editSpecialPriceValidationErrorMessage').slideUp('slow')
                      $('#editSpecialPriceSuccessMessage').html(data).hide();
                      $('#editSpecialPriceSuccessMessage').slideDown('slow');
  
                  }
                  else
                  {
                      $('#editSpecialPriceSuccessMessage').slideUp('slow')
                      $('#editSpecialPriceValidationErrorMessage').html(data).hide();
                      $('#editSpecialPriceValidationErrorMessage').slideDown('slow');
                  }
              },
              error: function()
              {
                  $('#editSpecialPriceErrorMessage').slideDown();
              }
          });
  
      }));
  }
  
  {
      $('#submenuVendorDashboard').click(function() {
          $.ajax({
              type: "GET",
              url: baseurl +"/vendor-dashboard",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              success: function(data) {
                  $('#content').empty();
                  $('#content').html(data);
                  return false;
              }
          })
      })
  }
  
  
  
  //    {
  //        $('.uploadbeforeimages').click(function(e){
  //            var myvar= e.currentTarget.attributes['data-target'].value;
  //            var form=$(myvar+' form').attr('id');
  //            $("#"+form).dropzone(
  //            {
  //                autoProcessQueue: false,
  //                init: function() {
  //                var submitButton = document.querySelector(".submit-all")
  //                    myDropzone = this; // closure
  //
  //                submitButton.addEventListener("click", function() {
  //                    alert('hello'+form);
  //                  myDropzone.processQueue(); // Tell Dropzone to process all queued files.
  //                });
  //
  //                // You might want to show the submit button only when
  //                // files are dropped here:
  //                this.on("addedfile", function() {
  //                  // Show submit button here and/or inform user to click it.
  //                });
  //
  //              }
  //            });
  //        })
  //    }
  //    {
  //
  //       Dropzone.options.myDropzone = {
  //
  //             autoProcessQueue: false,
  //
  //              init: function() {
  //                var submitButton = document.querySelector("#submit-all")
  //                    myDropzone = this; // closure
  //
  //                submitButton.addEventListener("click", function() {
  //                    alert('hello');
  //                  myDropzone.processQueue(); // Tell Dropzone to process all queued files.
  //                });
  //
  //                // You might want to show the submit button only when
  //                // files are dropped here:
  //                this.on("addedfile", function() {
  //                  // Show submit button here and/or inform user to click it.
  //                });
  //
  //              }
  //          };
  //
  //    }
  
  $("#cancelAdditionalbuttoncustomer").click(function(){
    window.location.reload();
  });
  
  
  });
  
  jQuery(document).ready(function() {
  
      "use strict";
      var options = {
          common: {
              onLoad: function() {
  
              },
              onKeyUp: function(evt, data) {
  
              }
          },
          rules: {},
          ui: {
              showPopover: false,
              showErrors: true,
              bootstrap2: true,
          }
      };
  
    //   $('#password').pwstrength(options);
  
  
  });
       function ShowAdditionalServiceForm(service_id,asset_number,asset,job_type){
        $(".popUpOvrlay").modal();
        $("#cancelbuttonadmin").fadeToggle("slow");
        var title = $("#servicename option:selected").text();
        $("#title_test").change(function(){
          var service_id = $("#servicename option:selected").val();
          var over = '<div id="overlay">' +'<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +'</div>';
          $(over).appendTo('body');
          $.ajax({
              method: "POST",
              data: {service_id:service_id,asset: asset,job_type:job_type,asset_number:asset_number },
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
          }).done(function(msg){
              $('#overlay').remove();
              $('#getpopup').html(msg);
              $('#'+service_id).modal({backdrop: 'static',keyboard: true});
              $('#'+service_id).modal('show');
              $('.datepicker').datepicker();
          }).fail(function(msg){
              $('#overlay').remove();
          });
  
        });
      }
       
  // function ShowAdditionalServiceForm(){
      //additional form js starts here the appearing form and ajax checking all services
  
  
      $("#showform").click(function(){
          $("#additionalserviceitems").fadeToggle("slow");
      });
  
      $("#servicename").change(function(){
         var title = $("#servicename option:selected").text();
         var id = $("#servicename option:selected").val();
         
         // console.log(title.length);
         if (title.length == '6') {
  
          $('#serviceTitle').show();
              //$('#title_test').hide();
              $('#title_test_chzn').hide();
          }
          var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
          $.ajax({
              method: "POST",
              url: baseurl +'/additional-service-title-change',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              data: { title: title,id:id }
          })
          .done(function( msg ) {
  
              $('#overlay').remove();
              $('#desc').val(msg[0]);
              $('#rate').val(msg[1]);
              $('#customer_additonal_price').val(msg[2]); 
  
  
  
  
          }).fail(function( msg ) {
              $('#overlay').remove();
  
  
          });
      });
  
      function SubmitAdditionalItem(auth_id) {
       var title = $("#servicename option:selected").html();
       if (title == "") {
           title =  $('#serviceTitle').val();
       }
       var description = $('#desc').val();
       var additional_vendors_notes = $("#v_notes").val();
       var admin_quantity = $('#admin_qty').val()
       var quantity = $("#qty").val();
       var rate = $('#rate').val();
       var customer_price = $('#customer_additonal_price').val();
       var order_id = $("#order_id_custom").val();
       var vendor_id = $("#vendor_id").val(); 
       if (admin_quantity == 0) {
        if (quantity == 0 ) {
          admin_quantity = 1;
        }else{
          admin_quantity = quantity;
        }  
      }
      if (auth_id == 1 && customer_price.length == 0 ) {
  
          $("#additional_flash_dan").slideDown();
          setInterval(function() {
             $("#additional_flash_dan").slideUp();
         }, 5000); 
          
      }else if(title.length == 0 || rate <= 0 || quantity <= 0 ){
          $("#additional_flash_dan").slideDown();
          setInterval(function() {
             $("#additional_flash_dan").slideUp();
         }, 5000); 
      }else{
          var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');   
  
          $.ajax({
              type: "POST",
              url: "add-additional-service",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              data:{customer_price:customer_price,title:title,rate:rate,description:description,quantity,quantity,
                  vendor_id:vendor_id,order_id:order_id,additional_vendors_notes:additional_vendors_notes},
                  success: function(data) {
                  //alert(title);
                  $('#overlay').remove();  
                   location.reload();
                  $("#additional_flash").slideDown('slow');
                  setInterval(function() {
                   $("#additional_flash").slideUp('slow'); 
  
               }, 3000);
  
              }
          });
      }
  } 
  
  function updateRequestedService(id){
      var vendors_price = $("#vendor_price").val();
      var customer_price = $('#customer_price').val();
      var admin_quantity = $('#adminquantity_edit').val();
      var quantity = $('#quantity_edit').val();
      var customers_notes = $('#customers_notes').val();
      var vendors_notes = $('#vendors_note').val();
      var notes_for_vendors= $('#notes_for_vendors').val();
      
       if (admin_quantity == 0 ) {
              admin_quantity = 1;
              }
            
           if (quantity == 0) {
          quantity = 1;
         }
          if (vendor_price <= 0 || quantity <= 0 ) {
            $(".modelForm").scrollTop(0);
           $("#additional_flash_danger").slideDown();
            setInterval(function() {
                  $("#additional_flash_danger").slideUp();
             }, 5000); 
  
         }else{
  
         $.ajax({
          type: "POST",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: baseurl +"/add-requested-service/"+id,
          data:{vendors_price:vendors_price,customer_price:customer_price,admin_quantity:admin_quantity,quantity:quantity,
              customers_notes:customers_notes,vendors_notes:vendors_notes,notes_for_vendors:notes_for_vendors},
              success: function(data) {
                window.location.reload();
              }
          });
         }
    }
    function updateAdditionalitem(id){
      var description = $("#description_"+id).val();
      var admin_quantity = $("#admin_quantity_"+id).val();
      var quantity = $("#quantity_"+id).val();
      var rate = $("#rate_"+id).val();
      var customer_price = $("#customer_price_"+id).val();
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
      if (admin_quantity == 0) {
            $(".modelForm").scrollTop(0);
           $("#additional_flash_modal_dan").slideDown();
            setInterval(function() {
          
                  $("#additional_flash_modal_dan").slideUp();
             }, 5000); 
             return false;
         }
      if( rate <= 0 || quantity <= 0 ||customer_price <= 0){
        $(".modelForm").scrollTop(0);
          $("#additional_flash_modal_dan").slideDown();
          setInterval(function() {
             $("#additional_flash_modal_dan").slideUp();
         }, 5000); 
      }else{
         $.ajax({
          type: "POST",
          url: baseurl +"/update-additional-service/"+id,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          data:{description:description,admin_quantity:admin_quantity,quantity:quantity,rate:rate,customer_price:customer_price},
          success: function(data) {
                  //console.log(data);
                  location.reload();
                  $('#overlay').remove();
                  $("#additional_flash_modal").slideDown('slow');
                  setInterval(function() {
  
                   $("#additional_flash_modal").slideUp('slow'); 
  
               }, 3000);
  
              }
          });
     }
  }
  function viewAsset(asset_id)
  {
  
  
      $.ajax({
          type: "GET",
          url: baseurl +"/asset-view/" + asset_id,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(data) {
              $('#showServiceid').modal('toggle');
              $('#showServiceid .modal-body').html(data);
              return false;
          }
      });
  
  }
  
  
  function showMaintenanceServices(maintenance_request_id)
  {
     $.ajax({
      type: "GET",
      url: baseurl +"/show-maintenance-services/" + maintenance_request_id,
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      success: function(data) {
        $('#overlay').remove();
        $('#showVendorList').modal('toggle');
        $('#showVendorList .modal-body').html(data);
        return false;
    }
  })
  
  }
  
  function showBidServices(maintenance_request_id,service_id)
  {
  
     var over = '<div id="overlay">' +
     '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
     '</div>';
     $(over).appendTo('body');
     if(!service_id || service_id == "")
     {
        var service_id = $("#services_ids").val();
    }
  
    $.ajax({
      type: "GET",
      url: baseurl +"/show-bid-services/" + maintenance_request_id,
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      success: function(data) {
  
        $('#overlay').remove();
        $('#showVendorList').html('');
        $('#showVendorList').modal('show')
  
        $('#showVendorList').html(data);
        return false;
    }
  })
  
  }
  
  
  function showBidServicesWorkOrder(maintenance_request_id,flagworkorder,requestedServiceBidId)
  {
  
      var customer_bid_price=$('#customer_bid_price').val();
      var vendor_bid_price=$('#vendor_bid_price').val();
      var duedatebid=$('#duedatebid').val();
  
      if(vendor_bid_price=="" || customer_bid_price=="")
      {
          alert("Please insert customer and vendor price first");
          $('#customer_bid_price').focus();
      }
      else
      {
  
         var over = '<div id="overlay">' +
         '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
         '</div>';
         $(over).appendTo('body');
  
  
         var res = duedatebid.replace("/", "-");
         var res2 = res.replace("/", "-");
  
         $.ajax({
          type: "GET",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          url: baseurl +"/show-bid-services/" + maintenance_request_id+"/"+flagworkorder+"/"+customer_bid_price+"/"+vendor_bid_price+"/"+requestedServiceBidId+"/"+res2,
          success: function(data) {
  
            $('#overlay').remove();
            $('#showVendorList').html('');
            $('#showVendorList').modal('show')
  
            $('#showVendorList').html(data);
            return false;
        }
    })
     }
  }
  
  
  $(document).ready(function() {
      $("[rel='popover']").popover({
          html: 'true',
          content: '<div id="popOverBox">1) Should contain minimum 10 characters. <br> 2)Should contain 2 special characters. <br> 3) Should contain 2 upper case.</div>'
  
      });
  });
  
  //Assign services by admin
  function assign_request()
  {

        $("input[name='vendor_id']").trigger("choosen:updated");
      var request_id = $('input[name="request_id"]').val();
      var vendor_id = $('input[name="vendor"]:checked').val();
      var services_ids = $('input[name="services[]"]:checked').map(function() {
          return $(this).val();
      }).get();
      if ($('input[name="vendor"]:checked').length <= 0)
      {
  
         $('#errorMessage').addClass("alert alert-alert");
         $('#errorMessage').slideUp('slow');
         $('#errorMessage').html("Please select vendor").hide();
         $('#errorMessage').slideDown('slow');
  
     }
     else if ($('input[name="services[]"]:checked').length <= 0)
     {
  
         $('#errorMessage').addClass("alert alert-alert");
         $('#errorMessage').slideUp('slow');
         $("#errorMessage").html("Please select at least one service");
         $('#errorMessage').slideDown('slow');
  
     }
     else
     {
      $("#assignRequest").submit();
  }
  
  
  }

  function deleteOrderAllImages(order_id, before_image)
  {
      $.ajax({
          type: 'POST',
          url: baseurl +'/delete-order-all-before-image',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          data: {order_id: order_id, before_image: before_image},
          dataType: 'html',
          success: function(data) {
              alert('success');
          }
      });
  }
  function removeImage(order_id, order_detail_id, obj, type)
  {
  
      var filename = $(obj).data('value');
      $.ajax({
          type: 'POST',
          url: baseurl +'/delete-before-image-id',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          data: {order_id: order_id, order_detail_id: order_detail_id, filename: filename, type: type},
          dataType: 'html',
          success: function(data) {
              var imageData = $(obj).parent('div');
              imageData.slideUp();
          }
      });
  }
  
  function removeAdditionalImage(additional_service_id, obj, type)
  {
  
      var filename = $(obj).data('value');
      $.ajax({
          type: 'POST',
          url: baseurl +'/delete-before-additional-image-id',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          data: {additional_service_id: additional_service_id, filename: filename, type: type},
          dataType: 'html',
          success: function(data) {
              // alert(data);
              var imageData = $(obj).parent('div');
              imageData.slideUp();
          }
      });
  }
  
  function ExportAdditonalitempdf(id)
  {
      var val = []; 
      $(':checkbox:checked').each(function(i){
            //val[i] = $(this).val();
            val.push($(this).val());
        });
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/download-seleted-additional-images',
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          data: {
              val
          },
          success: function(data) {
              $("#printdata1").empty()
  
              $("#printdata1").append(data);
  
              printDiv("printdata"); 
              window.location.reload();
              $(id).removeClass('in');
              $('.modal-backdrop').remove();
  
  
          }
      });
  
  }
  function Exportpdf()
  {
  
      var val = []; 
      $(':checkbox:checked').each(function(i){
            //val[i] = $(this).val();
            val.push($(this).val());
        });
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/download-seleted-images',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          data: {
              val
          },
          success: function(data) {
              $("#printdata1").empty()
              $("#printdata1").append(data);
  
              printDiv("printdata"); 
              window.location.reload();
  
  
          }
      });
  
  }
  
  
  
  function popModalAdditionalItemExport(additional_service_id, type)
  {   
     var over = '<div id="overlay" class="cstmOverlay">'+'<img id="loading" src="'+baseurl+'/assets/img/loader.gif">'+'</div>';
     $(over).appendTo('#export_modal_additional_image_'+additional_service_id);    
  
     $.ajax({
      type: 'POST',
      url: baseurl +'/display-additional-export-images',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      data: {additional_service_id: additional_service_id, type: type},
      dataType: 'html',
      success: function(data) {
          if (data)
          {
            $('#export_modal_additional_image_'+additional_service_id).html(data);
  
  
  
        }
  
    }
  });
  
  } 
  function popModalExport(order_id, order_detail_id, type)
  {
      var over = '<div id="overlay" class="cstmOverlay">'+'<img id="loading" src="'+baseurl+'/assets/img/loader.gif">'+'</div>';
      //$(over).appendTo('#export_view_images');  
      $('#export_view_images').css("overflow", "hidden");
      $.ajax({
          type: 'POST',
          url: baseurl +'/display-export-images',
          data: {order_id: order_id, order_detail_id: order_detail_id, type: type},
          dataType: 'html',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(data) {
              if (data)
              {
                $('#export_modal_image').html(data);
                $('.img-thumbnail').on('load', function(){
                  $('#overlay').css('display', 'none');
                  $('#export_view_images').css("overflow", "auto");
                  // $('#export_view_images').removeClass('cstmOvr');
              }); 
            }
  
        }
    });
  
  }
  function popModalAdditionalItem(additional_service_id,type)
  {
      $.ajax({
          type: 'POST',
          url: baseurl +'/display-order-additional-items-images',
          data: {additional_service_id: additional_service_id, type: type},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          dataType: 'html',
          success: function(data) {
              if (data)
              {
                  if (type == 'before')
                  {
                      $('#before_view_modal_image_' + additional_service_id).html(data);
                  }
                  else if(type == 'during')
                  {
                      $('#during_view_modal_image_' + additional_service_id).html(data);
                  }
  
                  else
                  {
                      $('#after_view_modal_image_' + additional_service_id).html(data);
                  }
  
              }
              else
              {
                  if (type == 'before')
                  {
                      $('#before_view_modal_image_' + additional_service_id).html('<h1>There is No Images</h1>');
                  }
                  else
                  {
                      $('#after_view_modal_image_' + additional_service_id).html('<h1>There is No Images</h1>');
                  }
  
              }
          }
      });
  }
  
  function popModal(order_id, order_detail_id, type)
  {
    
      $.ajax({
          type: 'POST',
          url:  baseurl +'/display-order-images',
          data: {order_id: order_id, order_detail_id: order_detail_id, type: type},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          dataType: 'html',
          success: function(data) {
              if (data)
              {
                  if (type == 'before')
                  {
                      $('#before_view_modal_image_' + order_id).html(data);
                  }
                  else if(type == 'during')
                  {
                      $('#during_view_modal_image_' + order_id).html(data);
                  }
  
                  else
                  {
                      $('#after_view_modal_image_' + order_id).html(data);
                  }
  
              }
              else
              {
                  if (type == 'before')
                  {
                      $('#before_view_modal_image_' + order_id).html('<h1>There is No Images</h1>');
                  }
                  else
                  {
                      $('#after_view_modal_image_' + order_id).html('<h1>There is No Images</h1>');
                  }
  
              }
          }
      });
  }
  
  
  function popViewModal(order_id, order_detail_id, type)
  {
      $.ajax({
          type: 'POST',
          url: baseurl +'/display-order-images-view',
          data: {order_id: order_id, order_detail_id: order_detail_id, type: type},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          dataType: 'html',
          success: function(data) {
              if (data)
              {
                  if (type == 'before')
                  {
                      $('#before_view_modal_image_' + order_detail_id).html(data);
                  }
                  else
                  {
                      $('#after_view_modal_image_' + order_detail_id).html(data);
                  }
  
              }
              else
              {
                  if (type == 'before')
                  {
                      $('#before_view_modal_image_' + order_detail_id).html('<h1>There is No Images</h1>');
                  }
                  else
                  {
                      $('#after_view_modal_image_' + order_detail_id).html('<h1>There is No Images</h1>');
                  }
  
              }
          }
      });
  }
  
  
  function popModalBid(id,type)
  {
      $.ajax({
          type: 'POST',
          url: baseurl +'/display-bid-images',
          data: {id: id,type: type},
          dataType: 'html',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(data) {
              if (data)
              {
                  if (type == 'before')
                  {
                      $('#before_view_modal_image_' + id).html(data);
                  }
                  else if(type == 'during')
                  {
                      $('#during_view_modal_image_' + id).html(data);
                  }
  
                  else
                  {
                      $('#after_view_modal_image_' + id).html(data);
                  }
  
              }
              else
              {
                  if (type == 'before')
                  {
                      $('#before_view_modal_image_' + id).html('<h1>There is No Images</h1>');
                  }
                  else
                  {
                      $('#after_view_modal_image_' + id).html('<h1>There is No Images</h1>');
                  }
  
              }
          }
      });
  }
  
  
  function saveVendorNote(order_id, order_detail_id)
  {
      var vendor_note = $('#vendor-note-' + order_id + '-' + order_detail_id).val();
      if (vendor_note == '')
      {
          $('#vendor-note-empty-error-' + order_detail_id).slideDown("slow");
          return false;
      }
      else
      {
          $.ajax({
              type: 'POST',
              url: baseurl +'/save-vendor-note',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              data: {order_id: order_id, order_detail_id: order_detail_id, vendor_note: vendor_note},
              dataType: 'html',
              success: function(data) {
                  data += ('<br><button class="btn btn-primary" id="edit-vendor-note-button-' + order_id + '-' + order_detail_id + '" onclick="editVendorNoteButton(' + order_id + ',' + order_detail_id + ')">Edit Note </button>');
                  $('#vendor-note-empty-error-' + order_detail_id).slideUp("slow");
                  $('#vendor-note-empty-success-' + order_detail_id).slideDown("slow");
  //               $('#vendor-note-'+order_id+'-'+order_detail_id).fadeOut("slow");
  
  $('#textarea-vendor-note-' + order_id + '-' + order_detail_id).fadeOut("slow");
  $('#show-vendor-note-' + order_id + '-' + order_detail_id).html(data);
  $('#show-vendor-note-' + order_id + '-' + order_detail_id).html(data).slideDown('slow');
  }
  });
      }
  
  }
  
  
  function saveAdditionalVendorNote(additional_id)
  {
      var additional_vendors_notes = $('#vendor-additional-note-' + additional_id).val();
  
      if (additional_vendors_notes == '')
      {
          $('#vendor-additional-note-empty-error-' + additional_id).slideDown("slow");
          return false;
      }
      else
      {
              
          $.ajax({
              type: 'POST',
              url: baseurl +'/save-additional-vendor-note',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              data: {additional_id: additional_id, additional_vendors_notes: additional_vendors_notes},
              dataType: 'html',
              success: function(data) {
                  window.location.reload();
  }
  });
      }
  
  }
  
  
  function saveBillingNote(order_id)
  {
      var billing_note = $('#billing-note-' + order_id).val();
      if (billing_note == '')
      {
          $('#billing-note-empty-error').slideDown("slow");
          return false;
      }
      else
      {
          $.ajax({
              type: 'POST',
              url: baseurl +'/save-billing-note',
              data: {order_id: order_id,  billing_note: billing_note},
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              dataType: 'html',
              success: function(data) {
                data += ('<br><button class="btn btn-primary" id="edit-billing-note-button-' + order_id + '" onclick="editBillingNoteButton(' + order_id + ')">Edit Note </button>');
                  $('#billing-note-empty-error' ).slideUp("slow");
                  $('#billing-note-empty-success').slideDown("slow");
  //               $('#vendor-note-'+order_id+'-'+order_detail_id).fadeOut("slow");
  
  $('html,body').animate({scrollTop:$('#comehere').offset().top},1500);
  $('#textarea-billing-note-' + order_id).fadeOut("slow");
  $('#show-billing-note-' + order_id ).html(data);
  $('#show-billing-note-' + order_id).html(data).slideDown('slow');
  //$( "#comehere ").scrollTop(0);
  
  
  
  }
  });
      }
  
  }
  function saveAdminNote(order_id, order_detail_id)
  {
      var vendor_note = $('#vendor-note-' + order_id + '-' + order_detail_id).val();
      if (vendor_note == '')
      {
          $('#vendor-note-empty-error-' + order_detail_id).slideDown("slow");
          return false;
      }
      else
      {
          $.ajax({
              type: 'POST',
              url: baseurl +'/save-admin-note',
              data: {order_id: order_id, order_detail_id: order_detail_id, vendor_note: vendor_note},
              dataType: 'html',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              success: function(data) {
                  data += ('<br><button class="btn btn-primary" id="edit-vendor-note-button-' + order_id + '-' + order_detail_id + '" onclick="editVendorNoteButton(' + order_id + ',' + order_detail_id + ')">Edit Note </button>');
                  $('#vendor-note-empty-error-' + order_detail_id).slideUp("slow");
                  $('#vendor-note-empty-success-' + order_detail_id).slideDown("slow");
  //               $('#vendor-note-'+order_id+'-'+order_detail_id).fadeOut("slow");
  
  $('#textarea-vendor-note-' + order_id + '-' + order_detail_id).fadeOut("slow");
  $('#show-vendor-note-' + order_id + '-' + order_detail_id).html(data);
  $('#show-vendor-note-' + order_id + '-' + order_detail_id).html(data).slideDown('slow');
  }
  });
      }
  
  }
  
  function saveCustomerNote(order_id, order_detail_id)
  {
      var vendor_note = $('#vendor-note-' + order_id + '-' + order_detail_id).val();
      if (vendor_note == '')
      {
          $('#vendor-note-empty-error-' + order_detail_id).slideDown("slow");
          return false;
      }
      else
      {
          $.ajax({
              type: 'POST',
              url: baseurl +'/save-customer-note',
              data: {order_id: order_id, order_detail_id: order_detail_id, vendor_note: vendor_note},
              dataType: 'html',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              success: function(data) {
                  data += ('<br><button class="btn btn-primary" id="edit-vendor-note-button-' + order_id + '-' + order_detail_id + '" onclick="editVendorNoteButton(' + order_id + ',' + order_detail_id + ')">Edit Note </button>');
                  $('#vendor-note-empty-error-' + order_detail_id).slideUp("slow");
                  $('#vendor-note-empty-success-' + order_detail_id).slideDown("slow");
  //               $('#vendor-note-'+order_id+'-'+order_detail_id).fadeOut("slow");
  
  $('#textarea-vendor-note-' + order_id + '-' + order_detail_id).fadeOut("slow");
  $('#show-vendor-note-' + order_id + '-' + order_detail_id).html(data);
  $('#show-vendor-note-' + order_id + '-' + order_detail_id).html(data).slideDown('slow');
  }
  });
      }
  
  }
  function editBillingNoteButton(order_id)
  {
      //$('#vendor-note-empty-success-' + order_detail_id).slideUp("slow");
      $('#show-billing-note-' + order_id).slideUp('slow');
      $('#textarea-billing-note-' + order_id).fadeIn("slow");
      
  }
  
  function editVendorNoteButton(order_id, order_detail_id)
  {
      $('#vendor-note-empty-success-' + order_detail_id).slideUp("slow");
      $('#show-vendor-note-' + order_id + '-' + order_detail_id).slideUp('slow');
      $('#textarea-vendor-note-' + order_id + '-' + order_detail_id).fadeIn("slow");
  }
  function editAdditionalVendorNoteButton(additional_item_id)
  {
      $('#vendor-additional-note-empty-success-' + additional_item_id).slideUp("slow");
      $('#show-additional-vendor-note-' + additional_item_id).slideUp('slow');
      $('#textarea-additional-vendor-note-' + additional_item_id).fadeIn("slow");
      $('#save-vendor-notes-button-' + additional_item_id).fadeIn("slow"); 
  }
  
  function editVendorQuantityButton(order_id, order_detail_id)
  {
      $('#vendor-note-empty-success-' + order_detail_id).slideUp("slow");
      $('#show-vendor-qty-' + order_id + '-' + order_detail_id).slideUp('slow');
      $('#input-vendor-qty-' + order_id + '-' + order_detail_id).fadeIn("slow");
  }
  
  function saveAdminQuantity(order_id, order_detail_id)
  {
      var vendor_qty = $('#vendor-qty-' + order_id + '-' + order_detail_id).val();
      if (vendor_qty == '')
      {
          $('#vendor-note-empty-error-' + order_detail_id).slideDown("slow");
          return false;
      }
      else
      {
          $.ajax({
              type: 'POST',
              url: baseurl +'/save-admin-qty',
              data: {order_id: order_id, order_detail_id: order_detail_id, vendor_qty: vendor_qty},
              dataType: 'html',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              success: function(data) {
                  $('#show-vendor-qty').html(data);
                  data += ('&nbsp;&nbsp;<button class="btn btn-primary" id="edit-vendor-qty-button-' + order_id + '-' + order_detail_id + '" onclick="editVendorQuantityButton(' + order_id + ',' + order_detail_id + ')">Edit Quantity </button>');
                  $('#vendor-note-empty-error-' + order_detail_id).slideUp("slow");
                  $('#vendor-note-empty-success-' + order_detail_id).slideDown("slow");
                  $('#input-vendor-qty-' + order_id + '-' + order_detail_id).fadeOut("slow");
                  $('#show-vendor-qty-' + order_id + '-' + order_detail_id).html(data);                
                  $('#show-vendor-qty-' + order_id + '-' + order_detail_id).html(data).slideDown('slow');
                  window.location="edit-order/"+order_id;
              }
          });
      }
  
  }
  
  //Decline services by Vendor
  function decline_request(request_id, vendor_id)
  {
      var declined_notes="";
  
  
  
      if($('#declinenote').val()==""){
          $('#declinedNotes').modal();
  
      }else {                  
          declined_notes=$('#declinenote').val();
          $('#declinedNotes').modal('hide');
          $(".btn-success").attr("disabled","disabled");
          $(".btn-danger").attr("disabled","disabled"); 
          $.ajax({
              type: 'Post',
              url: baseurl +'/ecline-request',
              data: {
                  request_id: request_id,
                  vendor_id: vendor_id,
                  declined_notes:declined_notes
              },
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              cache: false,
              success: function(response) {
  
  
  
                  $('#errorMessage').focus();
                  $('#errorMessage').addClass("alert alert-error");
                  $('#errorMessage').slideUp('slow');
                  $('#errorMessage').html(response).hide();
                  $('#errorMessage').slideDown('slow');
  
  
              }
          });
      }
  
  }
  //Accept by Vendor
  function accept_request(request_id, vendor_id)
  {
    $(".btn-success").attr("disabled","disabled");
    $(".btn-danger").attr("disabled","disabled"); 
  
    $.ajax({
      type: 'Post',
      url: baseurl +'/accept-request',
      data: {
          request_id: request_id,
          vendor_id: vendor_id
      },
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      cache: false,
      success: function(response) {
  
          $('#errorMessage').focus();
          $('#errorMessage').addClass("alert alert-success");
          $('#errorMessage').slideUp('slow');
          $('#errorMessage').html(response).hide();
          $('#errorMessage').slideDown('slow');
  
          setTimeout(function(){         window.location= '/vendor-assigned-requests';}, 2000);
  
  
      }
  });
  
  
  }
  //Bid Accept by Customer
  function accept_bid_request(request_id, vendor_id)
  {
    $(".btn-success").attr("disabled","disabled");
    $(".btn-danger").attr("disabled","disabled"); 
  
    $.ajax({
      type: 'Post',
      url: baseurl +'/accept-bid-request',
      data: {
          request_id: request_id,
          vendor_id: vendor_id
      },
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      cache: false,
      success: function(response) {
          $('#errorMessage').focus();
          $('#errorMessage').addClass("alert alert-success");
          $('#errorMessage').slideUp('slow');
          $('#errorMessage').html(response).hide();
          $('#errorMessage').slideDown('slow');
  
          
  
      }
  });
  
  
  }
  
  //Bid decline by Customer
  function decline_bid_request (request_id, vendor_id)
  {
      $(".btn-success").attr("disabled","disabled");
      $(".btn-danger").attr("disabled","disabled"); 
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/decline-bid-request',
          data: {
              request_id: request_id,
              vendor_id: vendor_id
          },
          cache: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(response) {
             $('#errorMessage').focus();
             $('#errorMessage').addClass("alert alert-error");
             $('#errorMessage').slideUp('slow');
             $('#errorMessage').html(response).hide();
             $('#errorMessage').slideDown('slow');
  
  
         }
     });
  
  
  }
  
  //Bid Accept by Customer
  function admin_accept_bid_request(request_id, vendor_id)
  {
  
  
    var customer_price = $('input[name^=customer_price]').map(function(idx, elem) {
      return $(elem).val();
  }).get();
  
    var vendor_price = $('input[name^=vendor_price]').map(function(idx, elem) {
      return $(elem).val();
  }).get();
  
    for(i=0;i<customer_price.length;i++)
    {
      if(customer_price[i]=="")
      {
        alert("Please insert customer price");
        return false;
  
    }
  }
  
  
  for(i=0;i<vendor_price.length;i++)
  {
      if(vendor_price[i]=="")
      {
        alert("Please insert vendor price");
        return false;
    }
  }
  
  $(".btn-success").attr("disabled","disabled");
  $(".btn-danger").attr("disabled","disabled"); 
  $.ajax({
      type: 'Post',
      url: baseurl +'/admin-accept-bid-request',
      data: {
          request_id: request_id,
          vendor_id: vendor_id,
          customer_price:customer_price,
          vendor_price:vendor_price
      },
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      cache: false,
      success: function(response) {
  
         $('#errorMessage').focus();
         $('#errorMessage').addClass("alert alert-success");
         $('#errorMessage').slideUp('slow');
         $('#errorMessage').html(response).hide();
         $('#errorMessage').slideDown('slow');
  
  
     }
  });
  
  
  }
  
  //Bid decline by Customer
  function admin_decline_bid_request (request_id, vendor_id)
  {
  
   var decline_notes = $("#decline_notes").val(); 
  
   if(decline_notes=="")
   {
  
     $("#decline_notes_section").show();
  }
  else
  {
      $("#decline_notes_section").hide();
      $(".btn-success").attr("disabled","disabled");
      $(".btn-danger").attr("disabled","disabled"); 
      $.ajax({
          type: 'Post',
          url: baseurl +'/admin-decline-bid-request',
          data: {
              decline_notes:decline_notes,
              request_id: request_id,
              vendor_id: vendor_id
          },
          cache: false,
          success: function(response) {
             $('#errorMessage').focus();
             $('#errorMessage').addClass("alert alert-error");
             $('#errorMessage').slideUp('slow');
             $('#errorMessage').html(response).hide();
             $('#errorMessage').slideDown('slow');
  
  
         }
     });
  }
  }
  
  //Change Status to Read for Notifications
  function ChangeNotificationStatus(notification_id,notification_url,notification_messages)
  {
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/change-notification-status',
          data: {
              notification_id: notification_id,
              notification_url:notification_url
              
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          cache: false,
          success: function(response) {
           var urlid="";
  
           var newArray = notification_messages.split(' ');
           for (var i = 0; i < newArray.length; i++) {
  
             if(  $.isNumeric(newArray[i])){
              urlid=newArray[i];
          }
  
      }
  
      window.location=notification_url+"?url="+urlid;
  
  
  }
  });
  }
  
  function showDetails(prefix)
  {
      if($('#' + prefix).is(":checked"))
      {
       $('#'+prefix+'_configuration').show();
   }
   else
   {
      $('#'+prefix+'_configuration').hide();
  }
  
  }
  
  function replicateValues(fieldID,objectvalue)
  {
  
      $("#"+fieldID).val($(objectvalue).val());
  
  }
  
  function appendValues(fieldID,objectvalue)
  {
      if($(objectvalue).is(":checked"))
      {
          $("#"+fieldID).val($("#"+fieldID).val()+","+$(objectvalue).val());
      }
      else
      {
          var $stringData=$("#"+fieldID).val();
  
          var $newDATA = $stringData.replace(","+$(objectvalue).val(), "");
  
          $("#"+fieldID).val($newDATA);
      }
  }
  
  function printDiv(divName) {
   var printContents = document.getElementById(divName).innerHTML;
   var originalContents = document.body.innerHTML;
  
   document.body.innerHTML = printContents;
  
   window.print();
  
   document.body.innerHTML = originalContents;
  }
  
  //Accept by Vendor
  function accept_single_request(request_id, vendor_id,service_id)
  {
    $(".btn-success").attr("disabled","disabled");
    $(".btn-danger").attr("disabled","disabled"); 
  
    $.ajax({
      type: 'Post',
      url: baseurl +'/accept-single-request',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      data: {
          request_id: request_id,
          vendor_id : vendor_id,
          service_id: service_id
      },
      cache: false,
      success: function(response) {
  
          $('#errorMessage').focus();
          $('#errorMessage').addClass("alert alert-success");
          $('#errorMessage').slideUp('slow');
          $('#errorMessage').html(response).hide();
          $('#errorMessage').slideDown('slow');
  
  
  
      }
  });
  
  
  }
  
  //Admin notes when viewing osr details on admin side.
  function adminNotesOsr(valueObj,request_id)
  {
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/admin-notes-osr',
          data: {
              admin_notes: valueObj.value,
              request_id:request_id
              
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          cache: false,
          success: function(response) {
  
             $('#overlay').remove();
  
  
         }
     });
  
  
  
  }
  
  
  //Customer notes when viewing osr details on admin side.
  function customerNotesOsr(valueObj,request_id)
  {
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/customer-notes-osr',
          data: {
              admin_notes: valueObj.value,
              request_id:request_id
              
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          cache: false,
          success: function(response) {
  
             $('#overlay').remove();
  
  
         }
     });
  
  
  
  }
  
  
  
  function adminNotes(valueObj,request_id)
  {
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/admin-notes',
          data: {
              admin_notes: valueObj.value,
              request_id:request_id
              
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          cache: false,
          success: function(response) {
  
             $('#overlay').remove();
  
  
         }
     });
  
  
  
  }
  function adminNotesBid(valueObj,request_id)
  {
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/admin-notes-bid',
          data: {
              admin_notes: valueObj.value,
              request_id:request_id
              
          },
          cache: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(response) {
  
             $('#overlay').remove();
  
  
         }
     });
  
  
  
  }
  function vendorNotes(valueObj,request_id)
  {
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/vendor-notes',
          data: {
              vendor_notes: valueObj.value,
              request_id:request_id
              
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          cache: false,
          success: function(response) {
  
             $('#overlay').remove();
  
  
         }
     });
  
  
  
  }
  
  function publicNotes(valueObj,service_id)
  {
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/public-notes',
          data: {
              public_notes: valueObj.value,
              service_id:service_id
              
          },
          cache: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(response) {
  
             $('#overlay').remove();
  
  
         }
  
  
     });
  
  
  
  
  }
  
  
  function publicNotesBid(valueObj,service_id)
  {
  
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/public-notes-bid',
          data: {
              public_notes: valueObj.value,
              service_id:service_id
              
          },
          cache: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(response) {
  
  
  
  
          }
  
  
      });
  
  
  
  
  }
  
  
  
  function customerNotesBid(valueObj,service_id)
  {
  
  
  
      $.ajax({
          type: 'Post',
          url: baseurl +'/customer-notes-bid',
          data: {
              customer_notes_bid: valueObj.value,
              service_id:service_id
              
          },
          cache: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(response) {
  
  
  
  
          }
  
  
      });
  
  
  
  
  }
  
  
  $('body').delegate( ".btn-minimize", "click", function(){
      $('.requestServices .accordion-group').slideUp(0);
      $(this).closest('.listSlide').find('.accordion-group').slideDown();
  });
  
  $('.fieldBox .accrodSlid').hide();
  $('.fieldBox .frstStep').show();
  $('body').delegate( ".fieldBox .box-header", "click", function(){
      $('.fieldBox .accrodSlid').slideUp(0);
      $(this).next('.accrodSlid').slideDown();
  });
  
  function updateInvoice ()
  {
  
   var invoice_price=  $('#invoice_price').val();
   var invoice_id=  $('#invoice_id').val();
  
  
   $.ajax({
      type: 'Post',
      url:  baseurl +'/change-price',
      data: { invoice_price:invoice_price,
          invoice_id:invoice_id
      },
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      success: function(response) {
         $('#errorMessage').focus();
         $('#errorMessage').addClass("alert alert-success");
         $('#errorMessage').slideUp('slow');
         $('#errorMessage').html(response).hide();
         $('#errorMessage').slideDown('slow');
  
  
     }
  });
  
  }
  
  function completionDate()
  {
  
      var order_id=$("#order_id_custom").val();
      var completion_date=  $('#completion_date').val();
      
      
  
      $.ajax({
          type: 'Post',
          url:  baseurl +'/completion-date',
          data: { completion_date:completion_date,
              order_id:order_id
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
  
          success: function(response) {
               // $('#errorMessage').focus();
               // $('#errorMessage').addClass("alert alert-success");
               // $('#errorMessage').slideUp('slow');
               // $('#errorMessage').html(response).hide();
               // $('#errorMessage').slideDown('slow');
  
  
           }
       });
  
  }
  
  function populateCompany(id)
  {

  
      $.ajax({
          type: 'Post',
          url:  baseurl +'/admin-get-customer-company',
          data: { id:id },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
  
          success: function(response) {
  
            $('#brokage').val(response);

  
        }
    });
  }
  
  
  
  
  function ajaxDashboardGridRequests(id,statusshow)
  {
    
  
      $.ajax({
          type: 'Post',
          url:  baseurl +'/ajax-dashoboard-grid-requests',
          data: { id:id,statusshow:statusshow },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(response) {
  
            $('#datatabledashboard').html(response);
            $('#overlay').remove();
            
        }
    });
  }
  
  function ajaxDashboardGridOrders(id,statusshow)
  {


      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url:  baseurl +'/ajax-dashoboard-grid-orders',
          data: { id:id,statusshow:statusshow },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          success: function(response) {
  
            $('#datatabledashboard').html(response);
            document.getElementById('overlay').remove();
            $('.tooltip').tooltip();
            
        }
    });
  }
  function DeleteServiceRequest(request_id,service_id,id)
  {
  
      var result = confirm("Want to delete service?");
      if (result) {
      //Logic to delete the item
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
          type: 'Post',
          url:  baseurl +'/ajax-delete-service-request',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          data: { request_id:id,service_id:service_id },
  
          success: function(response) {
  
           $('#overlay').remove();
           window.location="admin-edit-service-request/"+id;
       }
   });
  }
  }
  function closeWorkOrderOrContinue(order_id,status_id)
  {
    var over = '<div id="overlay">' +
    '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
    '</div>';
    $(over).appendTo('body');
    $.ajax({
      type: 'Post',
      url:  baseurl +'/close-property-status',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      data: { order_id:order_id,status_id:status_id },
  
      success: function(response) {
  
       window.location.reload();
  
   }
  });
  }
  
  
  function changevendorname()
  {
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
      var recurring_id=$('#uniquerecurringid').val();
      var vendorid=$('#uniquevendorid').val();
      $.ajax({
       type: 'Post',
       url:  baseurl +'/change-vendor-name',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: { recurring_id:recurring_id,vendorid:vendorid},
       success: function(response) {
  
        $('#overlay').remove();
        $('#success').show();
        $('#success').html(response);
  
  
    }
  });
  }
  
  function changeVendorOrder()
  {
  
   var over = '<div id="overlay">' +
   '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
   '</div>';
   $(over).appendTo('body');
   var order_id=$('#order_id_custom').val();
   var vendorid=$('#vendorsAssigned').val();
   $.ajax({
       type: 'Post',
       url:  baseurl +'/change-vendor-order',
       data: { order_id:order_id,vendorid:vendorid},
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       success: function(response) {
  
        $('#overlay').remove();
        $('#vendorChangeMsg').show();
        $('#vendorChangeMsg').html(response);
  
  
    }
  });
  
  }
  
  function under_review_notes(vendorid)
  {
  
  
    var over = '<div id="overlay">' +
    '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
    '</div>';
    $(over).appendTo('body');
    var order_id=$('#order_id_custom').val();
    var under_review_notes=$('#under_review_notes').val();
    $.ajax({
       type: 'Post',
       url:  baseurl +'/saving_under_reviewing_notes',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {under_review_notes:under_review_notes, order_id:order_id,vendorid:vendorid},
       success: function(response) {
  
        $('#overlay').remove();
        $('#under_review_notes_section').hide();
  
        $('.mystatusclass li.underreview > a').trigger("click");
  
  
  
    }
  });
  
  
  
  }
  
  function loadServiceOnJobType()
  {
      var client_type=  $("#client_type_unic").val();
      var job_type=  $("#job_type").val()
      $('#review_order_job_type').html($('#job_type option:selected').html());
      $('#tabCntrl2').find('.nxtStep').trigger('click');

    
    $.ajax({
       type: 'POST',
       url:  baseurl +'/load-service-on-job-type',
       data: {job_type:job_type,client_type:client_type},
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
       success: function(response) {
  
        if(response=="")
        {
          alert("Selected job type does not have any service.")
  
          $("#list_of_services").html("");
      }
      $("#service_ids").html(response);
      $('#service_ids').trigger('liszt:updated');
  
  
  }
  });
  }
  
  
  {
      $(".btn-addbid").on('click', (function(e) {
  
         var over = '<div id="overlay">' +
         '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
         '</div>';
         $(over).appendTo('body');
  
  
         e.preventDefault();
  
         var title=$('#title').val();
         var service_code=$('#service_code').val();
         var bid_flag=$('#bid_flag').val();
         var submitted=$('#submitted').val();
         var job_type_id=$('#job_type').val();
  
         $.ajax({
           type: 'Post',
           url:  baseurl +'/do-request',
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           data: { title:title,service_code:service_code,bid_flag:bid_flag, submitted:submitted,job_type_id:job_type_id },
           success: function(response) {
  
            $('#overlay').remove();
            $('#job_type').trigger('change');
  
            setTimeout(function(){
  
  
               $('#service_ids').val(response);
               $('#service_ids').trigger('liszt:updated');
  
  
  
               var service_id = response;
               if(service_id=="flagother")
               {
  
                  $('#bidrequest').show();
  
              }
              else {
                 $('#bidrequest').hide();
                 var asset_number = $('#asset_number').val();
  
                 var current_service = response;
                  //        var current_service_id=service_id[service_id.length - 1];
                  $.ajax({
                      type: 'Post',
                      url: baseurl +'/ajax-service-information-popup',
                      data: {
                          service_id: current_service,
                          asset_number: asset_number
                      },
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      cache: false,
                      success: function(response) {
                          if (response == 'error') {
                              $('#add_asset_alert').html('Please Select asset.');
                              $('#add_asset_alert').slideDown('slow');
                              $('#service_ids').val('Select some options');
                              $('#service_ids').trigger('liszt:updated');
  
                              // $('#service_ids').append('<option></option>');
                              // $( "#allservices" ).load('/includejs.php');
  
                          } else {
                              $('#allservices').html(response);
                              $('#add_asset_alert').slideUp('slow');
                              $('#' + current_service).modal({
                                  backdrop: 'static',
                                  keyboard: true
                              });
                              $('#' + current_service).modal('show');
                              $('.datepicker').datepicker();
                          }
                      }
                  }); }
  
  
  
  
  
  
  
  
  
  
              }, 3000);
  
  
  
  
        }
    });
  
  
     }));
  }
  
  function saveBidPrice(id)
  {
  
      var customer_bid_price=  $("#customer_bid_price").val()
      var vendor_bid_price=  $("#vendor_bid_price").val()
  
      var remindMehidden =$("#vendor_bid_price").val();
      if(customer_bid_price=="")
      {
          alert("Please insert the customer price");
      }else{
          $('#remindMehidden').val(id);
   //$('#remainderByPass').modal('show');
   $("#remainderByPass button").trigger("click");
      $('#bypassornot').val(1);  //By Pass Vendor
  }
  }
  
  
  function saveBidPriceSend()
  {
  
      $('#remainderByPass').modal('hide');
      var id = $('#remindMehidden').val();
      var datepickerremainder= $('#datepickerremainder').val();
      var bypassornot= $('#bypassornot').val();
  
      var customer_bid_price=  $("#customer_bid_price").val()
      var vendor_bid_price=  $("#vendor_bid_price").val()
  
  // var remindMehidden =$("#datepickerremainder").val();
  if(customer_bid_price=="")
  {
      alert("Please insert the customer price");
  }else{
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
      $.ajax({
       type: 'Post',
       url:  baseurl +'/save-bid-price',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {bypassornot:bypassornot,datepickerremainder:datepickerremainder,vendor_bid_price:vendor_bid_price,customer_bid_price:customer_bid_price,id:id},
       success: function(response) {
  
        $('#overlay').remove();
        window.location=  'list-bidding-request';
  
  
  
    }
  });
  }
  }
  
  
  function saveBidPriceDirectSendWithoutReminder(id)
  {
  
  
      var datepickerremainder= $('#datepickerremainder').val();
      var bypassornot= $('#bypassornot').val();
  
      var customer_bid_price=  $("#customer_bid_price").val()
      var vendor_bid_price=  $("#vendor_bid_price").val()
  
      var remindMehidden =$("#vendor_bid_price").val();
      if(customer_bid_price=="")
      {
          alert("Please insert the customer price");
      }else{
          var over = '<div id="overlay">' +
          '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
          '</div>';
          $(over).appendTo('body');
  
          $.ajax({
           type: 'Post',
           url:  baseurl +'/save-bid-price',
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           data: {bypassornot:bypassornot,datepickerremainder:datepickerremainder,vendor_bid_price:vendor_bid_price,customer_bid_price:customer_bid_price,id:id},
           success: function(response) {
  
            $('#overlay').remove();
            window.location=  'list-bidding-request';
  
  
  
        }
    });
      }
  }
  
  function saveBidPriceVendor(id)
  {
  
      var vendor_bid_price=  $("#vendor_bid_price").val()
      var vendor_note_for_bid=  $("#vendor_note_for_bid").val()
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
    //alert(baseurl);
    $.ajax({
       type: 'GET',
       url:  baseurl +'/save-bid-price-vendor',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {vendor_bid_price:vendor_bid_price,id:id,vendor_note_for_bid:vendor_note_for_bid},
       success: function(response) {
  
        $('#overlay').remove();
  
        window.location='vendor-assigned-bids';
  
  
  
    }
  });
  }
  //For vendor
  function markupcustomer(vendorid,customerid)
  { 
   var markupoption =$('#markupoption').val();
  
   var customer_price= parseFloat($('#'+customerid).val());
  
   var additional_price=(customer_price*markupoption)/100;
  
   var fifty_percent=customer_price-additional_price;
  
  
  
   $('#'+vendorid).val(fifty_percent);
  
  
  
  
  }
  //for customer
  function markup(vendorid,customerid)
  { 
   var markupoption =$('#markupoption').val();
  
   var vendor_price= parseFloat($('#'+vendorid).val());
  
   var additional_price=(vendor_price*markupoption)/100;
  
   var fifty_percent=vendor_price+additional_price;
  
  
  
   $('#'+customerid).val(fifty_percent);
  
  
  
  
  }
  
  function savePhotoTaging()
  {
  
      var order_image_id= $('#order_image_id').val();
      var x1= $('#x1').val();
      var x2= $('#x2').val();
      var y1= $('#y1').val();
      var y2= $('#y2').val();
      var w= $('#w').val();
      var h= $('#h').val();
      var comment=$('#comment').val();
  
  
  
      $.ajax({
       type: 'Post',
       url:  baseurl +'/add-photo-tagging',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {x1:x1,x2:x2,y1:y1,y2:y2,w:w,h:h,order_image_id:order_image_id,comment:comment},
       success: function(response) {
  
         var htmlli ='<li  id="uniq'+response+'" style="background: url(assets/images/tag_hotspot_62x62.png) no-repeat;top: '+y1+'px;left: '+x1+'px;width: '+w+'px;height: '+h+'px;"><a href="javascript:;"><span class="titleDs">'+comment+' </span></a><a href="javascript:;" onclick="deletePhotoTag('+response+')" class="removeBtn">X</a></li>';
  
         if( $('.map'+order_image_id+" .map").length >0)
         {
             $('.map'+order_image_id+" .map").append(htmlli);
             $("#uniq"+response).find('span.titleDs').stop(true,true).fadeToggle('fast');
             $("#uniq"+response).find('a.removeBtn').stop(true,true).fadeToggle('fast');
         }
         else
         {
             $('.mapunique .map').append(htmlli);
             $("#uniq"+response).find('span.titleDs').stop(true,true).fadeToggle('fast');
             $("#uniq"+response).find('a.removeBtn').stop(true,true).fadeToggle('fast');
  
         }
         $("#comment").val('');
         $("#title_container").addClass('hide');
     }
  });
  }
  
  function deletePhotoTag(imageID)
  {
  
      $.ajax({
       type: 'Post',
       url:  baseurl +'/delete-photo-tagging',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {imageID:imageID},
       success: function(response) {
  
  
           $("#uniq"+imageID).hide(); 
  
  
       }
   });
  }
  
  function changePrice(vendor_id,assignid)
  {
    vendorPrice=$("#"+vendor_id).val();
  
    var over = '<div id="overlay">' +
    '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
    '</div>';
    $(over).appendTo('body');
    $.ajax({
       type: 'Post',
       url:  baseurl +'/change-price-vendor',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {
         assignid:assignid,
         vendorPrice:vendorPrice
     },
     success: function(response) {
  
  
      $('#overlay').remove();
  
  
  }
  });
  }
  
  function changeDueDate(order_id)
  {
  
      $('#changeButton').show();
  }
  
  function SaveDueDate(requestedID)
  {
     var duedatechange=$("#duedatechange").val();
  
     var over = '<div id="overlay">' +
     '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
     '</div>';
     $(over).appendTo('body');
     $.ajax({
       type: 'Post',
       url:  baseurl +'/change-due-date',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {
         requestedID:requestedID,
         duedatechange:duedatechange
     },
     success: function(response) {
  
  
      $('#overlay').remove();
  
  
  }
  });
  }
  function setBidStatus(bid_dropdown)
  {
  
  
   $("#bid_dropdown_hidden").val(bid_dropdown);
   if(bid_dropdown==0)
   { 
    $("#RequestType").show();
    $("#bidRequest").hide();
  
  
    $("#cancelbuttoncustomer").attr('href','list-customer-requested-services');
    $("#cancelbuttonadmin").attr('href','list-maintenance-request');
  }
  else
  {
     $("#bidRequest").show();
     $("#RequestType").hide();
  
     $("#cancelbuttoncustomer").attr('href','list-customer-requested-bids');
     $("#cancelbuttonadmin").attr('href','list-bidding-request');
  
  }
  
  
  
  }
  //When customer Approve Bid Request
  function approveBidRequest(id,vendor_id)
  {
  
      var date_completion_appears=$("#date_completion_appears").val();
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
  
  
      if(vendor_id=="")
      {
  
               // $('#overlay').remove();
  
               // $('#errorMessage').focus();
               // $('#errorMessage').addClass("alert alert-error");
               // $('#errorMessage').slideUp('slow');
               // $('#errorMessage').html("No Vendor Assigned Yet").hide();
               // $('#errorMessage').slideDown('slow');
  
               
  
               $.ajax({
                   type: 'Post',
                   url:  baseurl +'/ajax-approve-bid-request-status-changed',
                   headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                   data: {
                     request_id:id,
                     vendor:vendor_id,
                     completion_date:date_completion_appears
                 },
                 success: function(response) {
  
  
                     window.location='list-customer-requested-bids';
  
  
  
                 }
             });
  
           }else{
  
              if(date_completion_appears=="")
              {
  
                  $("#date_completion_appears").show();
              // $("#date_completion_appears").focus();
  
              $('#overlay').remove();
  
              $('#errorCalender').focus();
              $('#errorCalender').addClass("alert alert-error");
              $('#errorCalender').slideUp('slow');
              $('#errorCalender').html("Please fill due date").hide();
              $('#errorCalender').slideDown('slow');
          }
          else
          {
  
  
            $.ajax({
               type: 'Post',
               url:  baseurl +'/ajax-approve-bid-request',
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
               data: {
                 request_id:id,
                 vendor:vendor_id,
                 completion_date:date_completion_appears
             },
             success: function(response) {
  
  
              $('#overlay').remove();
  
              $('#errorMessage').focus();
              $('#errorMessage').addClass("alert alert-success");
              $('#errorMessage').slideUp('slow');
              $('#errorMessage').html("Work Order has been Generated").hide();
              $('#errorMessage').slideDown('slow');
  
              window.location='list-customer-requested-bids';
  
  
  
          }
      });
        }
    }
  }
  
  
  
  function declineBidRequestNotes(id)
  {
  
  
      $('#declinebidNotes').modal('show');
      $('#declinenoteshidden').val(id);
  
  
  
  }
  function declineBidRequest()
  {
  
  
      $('#declinebidNotes').modal('hide');
  
      var id             =   $('#declinenoteshidden').val();
      var declinebidnotes=   $('#declinebidnotes').val();
  
  
      var over = '<div id="overlay">' +
      '<img id="loading" src="'+baseurl+'/assets/img/loader.gif">' +
      '</div>';
      $(over).appendTo('body');
      $.ajax({
       type: 'Post',
       url:  baseurl +'/ajax-decline-bid-request',
       headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       data: {
         request_id:id,declinebidnotes:declinebidnotes
  
     },
     success: function(response) {
  
      $('#approvebutton').attr('disabled','disabled');
      $('#overlay').remove();
  
      $('#errorMessage').focus();
      $('#errorMessage').addClass("alert alert-error");
      $('#errorMessage').slideUp('slow');
      $('#errorMessage').html("Bid has been declined");
      $('#errorMessage').slideDown('slow');
      window.location='list-customer-requested-bids';
  
  }
  });
  
  }
  $('.tabSection .tabWrapper .nxtStep').css('visibility','hidden');
  $('#tabCntrl3  .nxtStep').css('visibility','visible');
  
  $('.bidRequestBtn').click(function(){
      $('h2.bidRqst').show();
      $(this).closest('.pnlBtns').hide();
      $(this).closest('fieldset').find('.tabSection').fadeIn();
  });
  $('.btnRequestType').click(function(){
      $('h2.srvcRqst').show();
      $(this).closest('.pnlBtns').hide();
      $(this).closest('fieldset').find('.tabSection').fadeIn();
  });
  
  
  $('#editRevieworder').click(function(){
  
      $('#step4click').trigger("click");
      $('.bnchBtn a.rgstrBtn').trigger("click");
  });
  
  $('.bnchBtn a.rgstrBtn').click(function(){
      $(this).parent().hide();
      $(this).closest('.control-group').find('.tabArea').hide();
      $(this).closest('.control-group').find('#prprTable').fadeIn();
  });
  $('#tabCntrl4 .bnchBtn a.reviewOrdr').click(function(){
      $(this).closest('.control-group').hide();
      $('#tabCntrl3').find('#prprTable').fadeIn();
      $(this).closest('.control-group').find('.nxtStep').trigger('click');
  });
  
  $('#prprTable .edtBtn').click(function(){
      $('#prprTable').hide();
  
      $(this).closest('.control-group').find('.tabArea').fadeIn();
      $('#tabCntrl4').find("textarea").prop("readonly", false);
      $(this).closest('.control-group').find('.nxtStep').trigger('click');
      
  
  });
  
  // $('#prprTable .edtBtn').click(function(){
  //     $("#textarea2").prop("readonly", false);
  // });
  
  function orderReivewsubmit ()
  {
    var  bid_dropdown_hidden=$("#bid_dropdown_hidden").val();
  
    if(bid_dropdown_hidden==1)
    {
     $('#bid_flag').val(1);
  
     $('.request-services').submit();
  
  }
  else
  {
     $('#bid_flag').val(0);
  
     $('.request-services').submit();
  }
  }
  function changeReviewDueDate(getFieldinfo)
  {  
  
   var duedatename=  $(getFieldinfo).attr('name');
   $("input[name="+duedatename+"]").val( $(getFieldinfo).val());
  
  
  }
  function changeReviewNotes(getFieldinfo)
  {  
  
   var duedatename=  $(getFieldinfo).attr('id');
   $("#"+duedatename).val( $(getFieldinfo).val());
  
  
  
  }