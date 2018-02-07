//$.noConflict();
$(document).ready(function() {

	$(".view-order-details").on("click", function(){
		var order_id = $(this).text();
        $.ajax({
            url: baseurl + "/get-edit-order-details",
            type: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {order_id: order_id},
        }).done(function(cb) {
            var data = JSON.parse(cb);
            var order_details = data.order_details;
            for(var i = 0; i<order_details.length; i++){
                console.log(order_details[i]);
            }
        });

        return false;
		var html;

		html = "<div class='row'>"+
					"<div class='col-md-12'>"+
						"<div class='card'>"+
							"<div class='card-header'>"+
								"<label class='table-label'>TITLEHERE</label>" +
								"<button data-toggle='modal' class='myBtnImg'  data-target='#edit_request_service' data-backdrop='static' >"+
									"<i class='halflings-icon edit myBtnImg' ></i>"+
										"Edit Service" +
								"</button>"+
								"<div class='pull-right'> Vendor Price / Customer Price </div>"+
								"<div class='card-body'>" +
									"<div id=\"vendor-note-empty-error-{!!$order_detail->id!!}\" class=\"hide\">" +
										"<div class=\"alert alert-error\">Vendor Note Can not be Empty</div>" +
									"</div>" +
									"<div id=\"vendor-note-empty-success-{!!$order_detail->id!!}\" class=\"hide\">" +
										"<div class=\"alert alert-success\">Saved Successful</div>" +
									"</div>" +
									"<div id=\"billing-note-empty-success\" class=\"hide\">" +
										"<div class=\"alert alert-success\">Saved Successful</div>" +
									"</div>" +
									"<div id=\"billing-note-empty-error\" class=\"hide\">" +
										"<div class=\"alert alert-success\">Billing Note Can not be Empty</div>" +
									"</div>" +
									"<table class=\"table table-bordered\">" +
            							"<tr>";
            "    @if(isset($custom->customers_notes))" +
            "        <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Customer Note: </label><p>{!!$custom->customers_notes!!}</p>   </td>" +
            "    @else" +
            "        <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Customer Note: </label><p>{!!$order_detail->requestedService->customer_note!!}</p>   </td>" +
            "    @endif" +
            "</tr>" +
            "<tr>" +
            "    @if(isset($custom->notes_for_vendors))" +
            "        <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Note for Vendor: </label><p>{!!$custom->notes_for_vendors!!}</p></td>" +
                        "    @else" +
            "        <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Note for Vendor: </label><p>{!!$order_detail->requestedService->public_notes!!}</p>" +
                        "        </td>" +
            "    @endif" +
            "</tr>" +
                        "<tr>" +
            "    @if(isset($custom->vendors_notes))" +
            "        <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Vendor Note: </label><p>{!!$custom->vendors_notes!!}</p></td>" +
            "    @else" +
            "        <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Vendor Note: </label><p>{!!$order_detail->requestedService->vendor_note!!}</p>" +
            "        </td>" +
            "    @endif" +
            "</tr>" +
                        "<?php }elseif( Auth::user()->type_id == 3 ) {" +
            "?>" +
                                    "<tr>" +
            "    <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Note for Vendor:</label>" +
                        "        @if(isset($custom->notes_for_vendors))" +
            "            <p>{!!$custom->notes_for_vendors!!}</p >" +
            "        @else" +
            "            <p>{!!$order_detail->requestedService->public_notes!!}</p >" +
            "    @endif" +
            "</tr>" +
            "<?php" +
            "}elseif( Auth::user()->type_id == 2 ) {" +
            "    ?>" +
                                    "<?php }?>" +
            "    <tr>" +
            "        <td colspan=\"9\">" +
            "            <div class=\"row\" style=\"margin-bottom:30px;\">" +
            "                <div class=\"col-md-12\">" +
            "                    <div style=\"display:inline-block;\">" +
            "                        <label class=\"table-label\" style=\"display:inline;\">Order Images: </label>" +
            "                        <input type=\"hidden\" id=\"order_id\" value=\"{!! $order->id !!}\">" +
            "                        <span>" +
            "                             <button class=\"btn btn-primary export-all-photos\" type=\"button\">Export All Images</button>" +
            "                        </span>" +
            "                        <span>" +
            "                            <button class=\"btn btn-primary export-selected-photos\" type=\"button\">Export Selected Images</button>" +
            "                        </span>" +
            "                        <span>" +
            "                             <button type=\"button\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#delete-confirmation\">Delete Selected</button>" +
            "                        </span>" +
            "                    </div>" +
            "                </div>" +
            "            </div>" +
            "            <div class=\"row\">" +
            "                <div class=\"col-md-4 col-lg-4 col-sm-12\">" +
                                    "                    <div class=\"center-div\">" +
            "                        <label class=\"table-label\">Before Images</label>" +
            "                    </div>" +
            "                    <hr>" +
            "                    <div class=\"order-photo-div center-div\">" +
            "                        <div class=\"order-photo-item upload-order-photo upload-before\">" +
            "                            <i class=\"fa fa-2x fa-upload\"></i>" +
            "                            <p>Drag Or Click To Upload</p>" +
            "                        </div>" +
            "                        <?php" +
            "                            $images = \\App\\OrderImage::where('order_id', $order->id)->where('type', 'before')->orderBy('id', 'desc')->get();" +
            "                            foreach($images as $image)" +
            "                            {" +
            "                                $check = config('app.order_images_before').$image->address;" +
            "                                if (file_exists($check))" +
            "                                {" +
            "                                    echo '<div class=\"order-photo-item\" id=\"photo-id-'.$image->id.'\"><span class=\"photo-export-checkbox\"><input data-id=\"'.$image->id.'\" type=\"checkbox\" class=\"form-control export-before-checkbox\"></span><img data-id=\"'.$image->id.'\" data-image-type=\"before\" class=\"order-photo-img\" src=\"'.config('app.url').'/'.config('app.order_images_before').$image->address.'\"></div>';" +
            "                                }" +
            "                             }" +
            "                        ?>" +
            "                    </div>" +
            "                    <div class=\"center-div export-button-group\">" +
            "                        <button type=\"button\" class=\"btn btn-primary export-before-all\">Export All Before Images</button>" +
            "                    </div>" +
                        "                </div>" +
            "                <div class=\"col-md-4 col-lg-4 col-sm-12\">" +
            "                    <div class=\"center-div\">" +
            "                        <label class=\"table-label\">During Images</label>" +
            "                    </div>" +
            "                    <hr>" +
            "                    <div class=\"order-photo-div center-div\">" +
            "                        <div class=\"order-photo-item upload-order-photo upload-during\">" +
            "                            <i class=\"fa fa-2x fa-upload\"></i>" +
            "                            <p>Drag Or Click To Upload</p>" +
            "                        </div>" +
            "                        <?php" +
            "                        $images = \\App\\OrderImage::where('order_id', $order->id)->where('type', 'during')->orderBy('id', 'desc')->get();" +
                        "                        foreach($images as $image)" +
            "                        {" +
            "                            $check = config('app.order_images_during').$image->address;" +
            "                            if (file_exists($check))" +
            "                            {" +
            "                                echo '<div class=\"order-photo-item\" id=\"photo-id-'.$image->id.'\"><span class=\"photo-export-checkbox\"><input data-id=\"'.$image->id.'\" type=\"checkbox\" class=\"form-control export-during-checkbox\"></span><img data-id=\"'.$image->id.'\" class=\"order-photo-img\" data-image-type=\"during\" src=\"'.config('app.url').'/'.config('app.order_images_during').$image->address.'\"></div>';" +
            "                            }" +
            "                        }" +
            "                        ?>" +
            "                    </div>" +
            "                    <div class=\"center-div export-button-group\">" +
            "                        <button type=\"button\" class=\"btn btn-primary export-during-all\">Export All During Images</button>" +
            "                    </div>" +
            "                </div>" +
            "                <div class=\"col-md-4 col-lg-4 col-sm-12\">" +
            "                    <div class=\"center-div\">" +
            "                        <label class=\"table-label\">After Images</label>" +
            "                    </div>" +
            "                    <hr>" +
            "                    <div class=\"order-photo-div center-div\">" +
            "                        <div class=\"order-photo-item upload-order-photo upload-after\">" +
            "                            <i class=\"fa fa-2x fa-upload\"></i>" +
            "                            <p>Drag Or Click To Upload</p>" +
            "                        </div>" +
                        "                        <?php" +
            "                        $images = \\App\\OrderImage::where('order_id', $order->id)->where('type', 'after')->orderBy('id', 'desc')->get();" +
            "                        foreach($images as $image)" +
            "                        {" +
            "                            $check = config('app.order_images_after').$image->address;" +
            "                            if (file_exists($check))" +
            "                            {" +
            "                                echo '<div class=\"order-photo-item\" id=\"photo-id-'.$image->id.'\"><span class=\"photo-export-checkbox\"><input data-id=\"'.$image->id.'\" type=\"checkbox\" class=\"form-control export-after-checkbox\"></span><img data-id=\"'.$image->id.'\" class=\"order-photo-img\" data-image-type=\"before\" src=\"'.config('app.url').'/'.config('app.order_images_after').$image->address.'\"></div>';" +
            "                            }" +
            "                        }" +
            "                        ?>" +
            "                    </div>" +
            "                    <div class=\"center-div export-button-group\">" +
            "                        <button type=\"button\" class=\"btn btn-primary export-after-all\">Export All After Images</button>" +
            "                    </div>" +
                        "                </div>" +
            "            </div>" +
            "        </td>" +
            "    </tr>" +
        "<!-- <tr>" +
            "  <td colspan=\"2\" class=\"center\"><label class=\"control-label\" for=\"typeahead\">Vendor Note:</label><textarea style=\"width: 100%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 139px;\" rows=\"6\" id=\"limit\"></textarea></td>" +
            "                                      </tr> -->" +
                        "<?php" +
            "if( Auth::user()->type_id == 3 ) {" +
            "?>" +
            "<tr>" +
            "    <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Vendor Note:</label>" +
            "        @if($order_detail->requestedService->vendor_note)" +
            "            <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!$order_detail->requestedService->vendor_note!!}<br><button class=\"btn btn-primary\" id=\"edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}\" onclick=\"editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})\"> Edit Note </button> </span >" +
            "            <span class=\"hide\" id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('vendor_note', $order_detail->requestedService->vendor_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>" +
            "    @else" +
            "        <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\"></span >" +
            "        <span id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('vendor_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>" +
            "    @endif" +
            "</tr>" +
            "<tr>" +
            "    <td class=\"center\" colspan=\"2\"><button class=\"btn btn-large btn-warning pull-right\"  @if(Auth::user()->type_id==3 && $order->status==4) disabled=\"disabled\"@endif onclick=\"saveVendorNote({!!$order->id!!}, {!!$order_detail->id!!})\">Save {!!$order_detail->requestedService->service->title!!}</button></td>" +
                        "</tr>" +
            "<?php } else if( Auth::user()->type_id == 2 ) {" +
            "?>" +
                        "<tr>" +
            "    <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Customers Note:</label>" +
            "        @if($order_detail->requestedService->customer_note)" +
            "            <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!$order_detail->requestedService->custumer_note!!}<br><button class=\"btn btn-primary\" id=\"edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}\" onclick=\"editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})\"> Edit Note </button> </span >" +
            "            <span class=\"hide\" id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('custumer_note', $order_detail->requestedService->customer_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>" +
            "    @else" +
            "        <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\"></span >" +
            "        <span id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('custumer_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>" +
            "    @endif" +
            "</tr>" +
            "<tr>" +
            "    <td class=\"center\" colspan=\"2\"><button class=\"btn btn-large btn-warning pull-right\"  onclick=\"saveCustomerNote({!!$order->id!!}, {!!$order_detail->id!!})\">Save {!!$order_detail->requestedService->service->title!!}</button></td>" +
                        "</tr>" +
                        "<?php } else if( Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ||Auth::user()->user_role_id == 5 ||Auth::user()->user_role_id == 6||Auth::user()->user_role_id == 8 ) {" +
            "?>" +
                        "<tr>" +
            "    <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Admin Note:</label>" +
            "        @if($order_detail->requestedService->admin_note)" +
                        "            <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\"><p>{!!$order_detail->requestedService->admin_note!!}</p><br><button class=\"btn btn-primary\" id=\"edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}\" onclick=\"editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})\"> Edit Note </button> </span >" +
            "            <span class=\"hide\" id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('admin_note', $order_detail->requestedService->admin_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>" +
            "    @else" +
            "        <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\"></span >" +
            "        <span id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('admin_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>" +
            "    @endif" +
            "</tr>" +
            "<tr>" +
            "    <td class=\"center\" colspan=\"2\"><button class=\"btn btn-large btn-warning pull-right\" onclick=\"saveAdminNote({!!$order->id!!}, {!!$order_detail->id!!})\">Save Admin Note</button></td>" +
                        "</tr>" +
            "<?php } ?>" +
            "<?php if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ) { ?>" +
            "<tr>" +
                                    "    <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Billing Note:</label>" +
            "        @if($order->billing_note)" +
            "            <span id=\"show-billing-note-{!!$order->id!!}\">{!!$order->billing_note!!}<br>" +
            "                <button class=\"btn btn-primary\" id=\"edit-billing-note-button-{!!$order->id!!}\" onclick=\"editBillingNoteButton({!!$order->id!!})\"> Edit Note </button>" +
            "                    </span >" +
            "            <span class=\"hide\" id=\"textarea-billing-note-{!!$order->id!!}\">{!!Form::textarea('admin_note', $order->billing_note ,array('class'=>'span','id'=>'billing-note-'.$order->id))!!}" +
            "                <button class=\"btn btn-large btn-warning pull-right \" id=\"bill-btn\" onclick=\"saveBillingNote({!!$order->id!!})\">Save Billing Note</button></span>" +
            "    </td>" +
            "    @else" +
            "        <span id=\"show-billing-note-{!!$order->id!!}\"></span >" +
            "        <span id=\"textarea-billing-note-{!!$order->id!!}\">{!!Form::textarea('admin_note','',array('class'=>'span','id'=>'billing-note-'.$order->id))!!}" +
            "            <button class=\"btn btn-large btn-warning pull-right\" onclick=\"saveBillingNote({!!$order->id!!})\">Save Billing Note</button></span></td>" +
            "    @endif" +
            "</tr>" +
            "<tr>" +
            "    <td class=\"center\" colspan=\"2\"><!-- <button class=\"btn btn-large btn-warning pull-right\" onclick=\"saveBillingNote({!!$order->id!!})\">Save Billing Note</button> --></td>" +
                        "</tr>" +
            "<?php  } ?>" +
                        "<tr><td><label class=\"table-lable\">Service Details</label></td><td></td></tr>" +
            "@if($order_detail->requestedService->required_date!=\"\")" +
            "    <tr><td><span>Required Date</span></td>" +
            "        <td><span>{!! date('m/d/Y', strtotime($order_detail->requestedService->required_date)) !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                        "@if( $order_detail->requestedService->due_date!=\"\")" +
            "    <tr><td><span>Due Date</span></td>" +
            "        <td>" +
            "            <span> {!! date('m/d/Y', strtotime($order_detail->requestedService->due_date)) !!}</span>" +
            "        </td>" +
            "    </tr>" +
            "@endif" +
                        "@if($order_detail->requestedService->quantity!=\"\")" +
            "    <tr><td><span>Quantity</span></td>" +
            "        <td>" +
            "            <span id=\"show-vendor-qty\">{!! $order_detail->requestedService->quantity !!}</span>" +
            "        </td>" +
            "    </tr>" +
            "@endif" +
        "@if($order_detail->requestedService->service_men!=\"\")" +
            "    <tr><td><span>Service men</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->service_men !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
            "@if($order_detail->requestedService->service_note!=\"\")" +
            "    <tr><td><span>Service note</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->service_note !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                        "@if($order_detail->requestedService->verified_vacancy!=\"\")" +
            "    <tr><td><span>Verified vacancy</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->verified_vacancy !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
            "@if($order_detail->requestedService->cash_for_keys!=\"\")" +
            "    <tr><td><span>Cash for keys</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->cash_for_keys !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                        "@if($order_detail->requestedService->cash_for_keys_trash_out!=\"\")" +
            "    <tr><td><span>Cash for keys Trash Out</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->cash_for_keys_trash_out !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                        "@if($order_detail->requestedService->trash_size!=\"\")" +
            "    <tr><td><span>trash size</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->trash_size !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                                    "@if($order_detail->requestedService->storage_shed!=\"\")" +
            "    <tr><td><span>storage shed</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->storage_shed !!}</span></td>" +
            "    </tr>" +
            "@endif" +
                                    "@if($order_detail->requestedService->lot_size!=\"\")" +
            "    <tr><td><span>lot size</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->lot_size !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                        "@if($order_detail->requestedService->set_prinkler_system_type!=\"\")" +
            "    <tr><td><span>set prinkler system type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->set_prinkler_system_type !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                                    "@if($order_detail->requestedService->install_temporary_system_type!=\"\")" +
            "    <tr><td><span>install temporary system type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->install_temporary_system_type !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
        "@if($order_detail->requestedService->pool_service_type!=\"\")" +
            "    <tr><td><span>pool service type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->pool_service_type !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                                    "@if($order_detail->requestedService->carpet_service_type!=\"\")" +
            "    <tr><td><span>carpet service type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->carpet_service_type !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                        "@if($order_detail->requestedService->boarding_type!=\"\")" +
            "    <tr><td><span>boarding type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->boarding_type !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
        "@if($order_detail->requestedService->spruce_up_type!=\"\")" +
            "    <tr><td><span>spruce up type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->spruce_up_type !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
        "@if($order_detail->requestedService->constable_information_type!=\"\")" +
            "    <tr><td><span>constable information type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->constable_information_type !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                                    "@if($order_detail->requestedService->remove_carpe_type!=\"\")" +
            "    <tr><td><span>remove carpe type<span></td>" +
            "        <td><span>{!!$order_detail->requestedService->remove_carpe_type!!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
                                    "@if($order_detail->requestedService->remove_blinds_type!=\"\")" +
            "    <tr><td><span>remove blinds type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->remove_blinds_type !!}</span>" +
            "        </td>" +
            "    </tr>" +
            "@endif" +
                        "@if($order_detail->requestedService->remove_appliances_type!=\"\")" +
            "    <tr><td><span>remove appliances type</span></td>" +
            "        <td><span>{!!$order_detail->requestedService->remove_appliances_type !!}</span>" +
                        "        </td>" +
            "    </tr>" +
            "@endif" +
        "                                    </table>"


		$("#property-work-orders-table").hide();
		$(".property-work-orders-div").fadeIn("fast");
	});

	$(".property-work-orders-back").on("click", function(){
        $(".property-work-orders-div").hide();
        $("#property-work-orders-table").fadeIn("fast");

    });
    var myDropZone;
    $(".upload-before").dropzone({

        url: baseurl + "/workorder-photo-upload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        params: {cat : "before", order_id : $("#order_id").val()},
        acceptedFiles: ".jpg, .jpeg, .gif, .png",
        createImageThumbnails: false,
        autoProcessQueue: true,
        clickable: true,
        success: function(cb){
            var response = cb.xhr.responseText;
            if (response !== "failed"){
                var data = JSON.parse(response);
                var image_id   = data.image_id;
                var image_url  = data.address;
                var html = "<div class='order-photo-item'>" +
                    "<span class='photo-export-checkbox'>" +
                    "<input data-id='"+image_id+"' type='checkbox' class='form-control export-before-checkbox'>" +
                    "</span>" +
                    "<img data-id='"+image_id+"' class='order-photo-img' data-image-type='before' src='"+baseurl+"/"+image_url+"'>" +
                    "</div>";
                $(".upload-before").after(html);
            }


        }
    });

	$(".export-before-all").on("click", function(){
        var data = [];
        $(".export-before-checkbox").each(function(){
            data.push($(this).data("id"));
        });
		selectedImagesAjax(data);
	});

	$(".export-during-all").on("click", function(){
        var data = [];
        $(".export-during-checkbox").each(function(){
            data.push($(this).data("id"));
        });
        selectedImagesAjax(data);
	});

	$(".export-after-all").on("click", function(){
        var data = [];
        $(".export-after-checkbox").each(function(){
            data.push($(this).data("id"));
        });
        selectedImagesAjax(data);
	});

	$(".export-selected-photos").on("click", function(){
		var data = [];
		$(".photo-export-checkbox > input:checked").each(function(){
			data.push($(this).data("id"));
		});
        selectedImagesAjax(data);
    });


	$(".delete-photos").on("click", function(){

		$(".delete-confirm-header").text("Deleting Photos");
        var data = [];
        $(".export-during-checkbox:checked").each(function(){
            data.push($(this).data("id"));
        });
        $(".export-after-checkbox:checked").each(function(){
            data.push($(this).data("id"));
        });
        $(".export-before-checkbox:checked").each(function(){
            data.push($(this).data("id"));
        });


        $.ajax({
            url: baseurl + "/delete-selected-images",
            type: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {image_ids: data}
        }).done(function(cb){
            for(var i=0; i < data.length; i++){
                var image_id = data[i];
                $("#photo-id-"+image_id).remove();
            }
            $("#delete-confirmation").modal("toggle");
            $(".delete-confirm-header").text("Delete selected photos?");
            return true;
        });

        $("#delete-confirmation").modal("toggle");

	});

	function selectedImagesAjax(data){
        $.ajax({
            type: 'Post',
            url: baseurl +'/download-seleted-images',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {data},
            success: function(data) {
                $("#printdata1").empty()
                $("#printdata1").append(data);

                printDiv("printdata");
                window.location.reload();
            }

        });
	}

    $(".export-all-photos").on("click", function(){
    	var data = [];
        $(".order-photo-img").each(function(){
			data.push($(this).data("id"));
        });

        selectedImagesAjax(data);
	});

    $(".upload-during").dropzone({

        url: baseurl + "/workorder-photo-upload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        params: {cat : "during", order_id : $("#order_id").val()},
        acceptedFiles: ".jpg, .jpeg, .gif, .png",
        createImageThumbnails: false,
        autoProcessQueue: true,
        clickable: true,
        success: function(cb){
            var response = cb.xhr.responseText;
            if (response !== "failed"){
                var data = JSON.parse(response);
                var image_id   = data.image_id;
                var image_url  = data.address;
                var html = "<div class='order-photo-item'>" +
                    "<span class='photo-export-checkbox'>" +
                    "<input data-id='"+image_id+"' type='checkbox' class='form-control export-during-checkbox'>" +
                    "</span>" +
                    "<img data-id='"+image_id+"' class='order-photo-img' data-image-type='during' src='"+baseurl+"/"+image_url+"'>" +
                    "</div>";
                $(".upload-during").after(html);
            }


        }
    });
    $(".upload-after").dropzone({

		url: baseurl + "/workorder-photo-upload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		params: {cat : "after", order_id : $("#order_id").val()},
        acceptedFiles: ".jpg, .jpeg, .gif, .png",
        createImageThumbnails: false,
		autoProcessQueue: true,
		clickable: true,
		success: function(cb){
            var response = cb.xhr.responseText;
            if (response !== "failed"){
                var data = JSON.parse(response);
				var image_id   = data.image_id;
				var image_url  = data.address;
				var html = "<div class='order-photo-item'>" +
								"<span class='photo-export-checkbox'>" +
									"<input data-id='"+image_id+"' type='checkbox' class='form-control export-after-checkbox'>" +
								"</span>" +
								"<img data-id='"+image_id+"' class='order-photo-img' data-image-type='after' src='"+baseurl+"/"+image_url+"'>" +
							"</div>";
				$(".upload-after").after(html);
            }


		}
    });




    // Dropzone -> Property Photo Upload Options
    if ($("#dropzone-preview-template").length > 0){
        Dropzone.options.dropzoneUpload = {
            dictDefaultMessage: "Drag your file or click to upload",
            url: baseurl + '/property-photo-upload',
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
	}

	$(".photo-export-checkbox > input[type='checkbox']").on("click", function(){
		$this = $(this);
		console.log($this.data("id"));
	});
    $("#add-customer-button").on("click", function(){
    	$this = $(this);
		$this.attr("disabled", true);
		var first_name = $("#first_name").val(),
			last_name = $("#last_name").val(),
			email = $("#email").val(),
			password = $("#password").val();

		if (first_name.length === 0 || last_name.length === 0 || email.length === 0 || password.length === 0)
		{
			alert("All fields required");
			$this.attr("disabled", false);
			return false;
		}

        var data = {
            first_name: first_name,
            last_name: last_name,
            email: email,
            password: password,
            role_id: 2,
        };

        $.ajax({
            url: baseurl+"/addUserFromModal",
			type:"post",
			data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(cb){
            if (typeof cb.id !== 'undefined') {
				var option_string = "<option value='"+cb.id+"' selected='selected'>";
				if (typeof cb.first_name !== 'undefined' && cb.first_name.length !== 0){
					option_string += cb.first_name+" ";
				}

				if (typeof cb.last_name !== 'undefined' && cb.last_name.length !== 0){
					option_string += cb.last_name;
				}

				option_string += "</option>";
            	$("#customer_id").append(option_string);
            	$("#add-customer-modal").modal("toggle");
            }

		});
    });


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

	$(".upload-order-photo")
	//Event Handler -> Property Photo Upload
	$(".property-photo-upload").on("click", function(){

		console.log("test");
		$("#photo-upload-modal").modal("toggle");
        myDropZone = Dropzone.forElement("#dropzone-upload");
        myDropZone.on("addedfile", function(file){
            $("#dropzone-upload").hide();
            $(".dropzone-control-buttons").fadeIn("fast");
        });
        myDropZone.on('sending', function(file, xhr, formData){
            formData.append('property_id', $("#property_id").val());
        });

        myDropZone.on("complete", function(test){
			var response = test.xhr.responseText;
			response = JSON.parse(response);

			if (response.error)
			{
				alert("there was an error uploading the photo");
			}
			else
			{
				$(".property-photo img").attr("src", response.file);
				$(".property-photo-placeholder").hide();
				$(".property-photo").show();
				$("#photo-upload-modal").modal("toggle");
			}
		});
	});

	$(".property-photo-select").on("click", function(){
		$.ajax({
			url: baseurl + "/available-property-photos",
			type: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			data: {property_id : $("#property_id").val()}
		}).done(function(cb){
			var cb = JSON.parse(cb);
			var html = "";
			for(var i= 0;i<cb.length;i++){
				html += "<div class='p-2'><div class='inner-padding available-photo'><img src='"+cb[i]+"'></div></div>";
				// html += "<div class='p-2'><div class='inner-padding available-photo'><img src='http://gssreskin.app/assets/uploads/20171109101755166.jpeg'></div></div>";
			}

			$(".addt-photos").html(html);
			$("#addt-photo-modal").modal("toggle");
		});
	});

	$("#save_asset_changes").on("click", function(){
		var property_number = $("#asset_number").val();
		var property_street_address = $("#property_address").val();
		var property_city = $("#city_id").val();
		var property_state = $("#state_id").val();
		var property_zip = $("#property_zip").val();
		var property_lockbox = $("#lock_box").val();
		var property_access = $("#access_code").val();
		var property_loan = $("#loan_number").val();
		var property_status = $("#property_status").val();
		var property_status_text = $("#property_status").text();
		var property_type = $("#property_type").val();

		var customer_fname = $("#first_name").val();
		var customer_lname = $("#last_name").val();
		var customer_email = $("#email").val();
		var customer_company = $("#company").val();


		$("#property_number_value").text(property_number);
		$("#property_address_value").html(property_street_address+"<br>"+$("#city_id option:selected").text()+", "+$("#state_id option:selected").text()+" "+property_zip);
		$("#property_type").val(property_type);
		$("#property_customer_value").text(customer_fname+" "+customer_lname);
		$("#property_email_value").text(customer_email);
		$("#property_company_value").text(customer_company);
		$("#property_lock_value").text(property_lockbox);
		$("#property_access_value").text(property_access);
		$("#property_loan_value").text(property_loan);
		$("#property_status_value").text(property_status.charAt(0).toUpperCase() + property_status.slice(1));

		var sendData = {
			"property_id" : $("#property_id").val(),
			"property_number" : property_number,
			"street_address" : property_street_address,
			"property_city" : property_city,
			"property_state" : property_state,
			"property_zip" : property_zip,
			"lockbox" : property_lockbox,
			"access_code" : property_access,
			"loan_number" : property_loan,
			"status" : property_status,
			"type" : property_type,
			"customer_fname" : customer_fname,
			"customer_lname": customer_lname,
			"customer_email" : customer_email,
			"company" : customer_company
		};

		$.ajax({
			url: baseurl+"/update-asset-details",
			type: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			data: sendData
		}).done(function(cb){
            $(".asset-details-inputs").hide();
            $(".asset-details-values").show();
            window.scrollTo(0,0);
		});



	});
	$("#edit-property-details").on("click", function(){
		$(".asset-details-values").hide();
		$(".asset-details-inputs").show();
	});
	$("#save-available-photo").on("click", function(){
		if ($(".selected-available-photo").length === 1){
			var imageUrl = $(".selected-available-photo > img").attr("src");
            $.ajax({
                url: baseurl+ "/save-available-photo",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {property_id : $("#property_id").val(), image_url : imageUrl}

            }).done(function(cb){
            	var cb = JSON.parse(cb);
            	if (cb["error"]){
            		return false;
				} else {
            		$(".property-photo img").attr("src", $(".selected-available-photo > img").attr("src"));
                    $(".property-photo-placeholder").hide();
                    $(".property-photo").show();
                    $("#addt-photo-modal").modal("toggle");
				}
			});
		}

	});

	$(document).on("click", ".available-photo", function(){
		$(".selected-available-photo").removeClass("selected-available-photo");
		$(this).addClass("selected-available-photo");
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

	var vendor_id;
	var dt;
	$(document).on("click", "#vendor-delete-button", function(){
		vendor_id 	= $("#vendor-delete-id").val();
		dt 			= $("#vendor-list-table").DataTable();
		$.ajax({
			url: baseurl + "/delete-record",
			type: "get",
			data: {type: "vendor", db_table : $("#vendor-delete-table").val(), id : vendor_id},

		}).done(function(cb){
			if (cb == 1)
			{

                $("#vendor-delete-modal").modal("toggle");
                dt.row("#tr-"+vendor_id).remove().draw();

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

	$(".add-service-back-step-1").on("click", function(){
		$(".step-3").hide();
		$(".step-2").fadeIn("fast");
	});

	$(".add-service-back-step-2").on("click", function(){
		$(".step-4").hide();
		$(".step-3").fadeIn('fast');
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
		$(".step-3").fadeIn('slow');
		setTimeout(function(){
			$(".hidden-chosen").chosen();
		},300);
	});

	$(".review-service-order").on("click", function(){
        var asset_id = $(this).data('asset-id');
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

		





	
	











