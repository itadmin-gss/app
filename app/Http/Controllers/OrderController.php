<?php

namespace App\Http\Controllers;

use App\AdditionalServiceItem;
use App\AdditionalServiceItemImage;
use App\Asset;
use App\City;
use App\Helpers\Email;
use App\Helpers\Pruvan;
use App\Invoice;
use App\MaintenanceRequest;
use App\Order;
use App\OrderCustomData;
use App\OrderDetail;
use App\OrderImage;
use App\OrderImagesPosition;
use App\OrderReviewNote;
use App\PruvanVendors;
use App\RequestedService;
use App\Service;
use App\SpecialPrice;
use App\State;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use JeroenDesloovere\Geolocation\Geolocation;


class OrderController extends Controller
{

    public function editOrderAssetPage()
    {
        $totalPriceCustomer     = 0;
        $total                  = 0;
        $totalPriceVendor       = 0;
        $totalPrice             = 0;
        $totalRequestedServices = 0;
        $RecurringFlag          = 0;
        $htmlToSend             = "";

        $order_id   = Request::get('order_id');
        $data       = Order::getOrderByID($order_id);

        $order_details      = $data->orderDetail;
        $vendorsDATA        = User::where('type_id', '=', 3)->get();
        $items              = AdditionalServiceItem::where('order_id', '=', $order_id)->get();
        $OrderReviewNote    = OrderReviewNote::where('order_id', '=', $order_id)->get();

        if (OrderCustomData::where('order_id', '=', $order_id)->count() > 0)
        {
            $customData = OrderCustomData::where('order_id', '=', $order_id)->get();
        }
        else
        {
            $customData[1] = "lol";
        }

        $order_req          = Order::where('id', '=', $order_id)->pluck('request_id');
        $services_asset_id  = MaintenanceRequest::where('id', '=', $order_req)->pluck('asset_id');
        $property_details   =  Asset::where('id', $services_asset_id)->get();
        $client_id          = Asset::getAssetInformationById($services_asset_id);

        if (isset($client_id->customer_type))
        {
            $allservices = Service::getServicesByClientId($client_id->customer_type);
        }
        else
        {
            $allservices = Service::getAllServices();
        }

        //Get City/State Information
        $city = City::where('id', $property_details[0]->city_id)->get()[0]->name;
        $state = State::where('id', $property_details[0]->state_id)->get()[0]->name;
        //Get all cities from city
        $cities = City::getAllCities();
        //Get all states from city
        $states = State::getAllStates();

        $geolocation_result = (new Geolocation)->getCoordinates($property_details[0]->property_address, '', $city, $property_details[0]->zip, 'USA');


        foreach($order_details as $order_detail)
        {
            foreach($customData as $custom)
            {
                if (isset($order_detail->requestedService->service->title))
                {
                    $htmlToSend .= "<div class=\"row\">
                                        <div class=\"col-md-12 col-lg-12 col-sm-12\">
                                            <div class=\"card\">
                                                <div class=\"card-header\">
                                                <label class=\"table-label\">".$order_detail->requestedService->service->title."</label>
                                                <button data-toggle=\"modal\" class=\"myBtnImg\"  data-target=\"#edit_request_service\" data-backdrop=\"static\" >
                                                    <i class=\"halflings-icon edit myBtnImg\" ></i>
                                                    Edit Service</button>
                                                <div class=\"pull-right\">";
                    if ($order_detail->requestedService->recurring == 1)
                    {
                        $RecurringFlag = 1;
                    }

                    $totalRequestedServices++;

                    if (Auth::user()->type_id == 3)
                    {
                        $SpecialPriceVendor = \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                            ->where('customer_id', Auth::user()->id)
                            ->where('type_id', 3)
                            ->get();

                        $vendor_priceFIND = 0;

                        if (isset($SpecialPriceVendor[0]) && !empty($SpecialPriceVendor[0]))
                        {
                            if (isset($custom->vendors_price) && isset($custom->quantity))
                            {
                                $htmlToSend .= "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                            }
                            else
                            {
                                $htmlToSend .= "Price: $".$SpecialPriceVendor[0]->special_price * $order_detail->requestedService->quantity;
                            }
                        }
                        else
                        {
                            if (isset($custom->vendors_price) && isset($custom->quantity))
                            {
                                if( $custom->vendors_price !== NULL && $custom->quantity !== NULL)
                                {
                                    $vendor_priceFIND += $custom->vendors_price * $custom->quantity;
                                    $htmlToSend .= "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                }
                            }

                            else
                            {
                                $vendor_priceFIND = $order_detail->requestedService->service->vendor_price * $order_detail->requestedService->quantity;
                                $htmlToSend .= "Vendor Price: ".$order_detail->requestedService->service->vendor_price * $order_detail->requestedService->quantity;
                            }
                        }

                        $totalPrice += $vendor_priceFIND;

                    }
                    else if (Auth::user()->type_id==2)
                    {

                        $SpecialPriceVendor=  \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                            ->where('customer_id', Auth::user()->id)
                            ->where('type_id', 2)
                            ->get();

                        $vendor_priceFIND = 0;
                        if (!empty($SpecialPriceVendor[0]))
                        {
                            if ($custom->vendors_price !== NULL && $custom->quantity !== NULL)
                            {
                                $vendor_priceFIND = $custom->vendors_price * $custom->quantity;
                                $htmlToSend .= "Price: $".$vendor_priceFIND;
                            }
                            else
                            {
                                $vendor_priceFIND = $SpecialPriceVendor[0]->special_price * $order_detail->requestedService->quantity;
                                $htmlToSend .= "Price: $".$vendor_priceFIND;
                            }
                        }
                        else
                        {
                            if (isset($custom->vendors_price) && isset($custom->quantity))
                            {
                                if( $custom->vendors_price !== NULL && $custom->quantity !== NULL)
                                {
                                    $vendor_priceFIND += $custom->vendors_price * $custom->quantity;
                                    $htmlToSend .= "Vendor Price: $".$custom->vendors_price * $custom->quantity;
                                }
                            }
                        }

                        $totalPrice += $vendor_priceFIND;

                    }
                    else
                    {



                        $SpecialPriceCustomer=  \App\SpecialPrice::where('service_id', $order_detail->requestedService->service->id)
                            ->where('customer_id', $order->customer->id)
                            ->where('type_id', 2)
                            ->get();

                        $customer_priceFIND = 0;

                        if (!empty($SpecialPriceCustomer[0]))
                        {
                            if (!empty($custom->customer_price))
                            {
                                $totalPriceCustomer += $custom->customer_price * $custom->admin_quantity;
                                $htmlToSend .= "Customer Price: $".$custom->customer_price * $custom->admin_quantity;
                            }
                            else
                            {
                                $totalPriceCustomer += $SpecialPriceCustomer[0]->special_price;
                                $htmlToSend .= "Customer Price: $".$SpecialPriceCustomer[0]->special_price;
                            }
                        }
                        else
                        {
                            if (isset($custom->customer_price) && $custom->customer_price !== NULL)
                            {
                                $totalPriceCustomer += $custom->customer_price * $custom->admin_quantity;
                                $htmlToSend .= "Customer Price: $".$custom->customer_price * $custom->admin_quantity;
                            }
                            else
                            {
                                $totalPriceCustomer = $order_detail->requestedService->service->customer_price;
                                $htmlToSend .= "Customer Price: $".$order_detail->requestedService->service->customer_price;
                            }
                        }

                        $SpecialPriceVendor=  \App\SpecialPrice::where('service_id','=',$order_detail->requestedService->service->id)
                            ->where('customer_id','=',$order->vendor->id)
                            ->where('type_id','=',3)
                            ->get();

                        if (!empty($SpecialPriceVendor[0]))
                        {
                            if ($custom->vendors_price !== null && $custom->quantity !== null)
                            {
                                $totalPriceVendor += $custom->vendors_price * $custom->quantity;
                                $htmlToSend .= " | Vendor Price: $".$custom->vendors_price * $custom->quantity;
                            }
                            else
                            {
                                $totalPriceVendor += $SpecialPriceVendor[0]->special_price;
                                $htmlToSend .= " | Vendor Pri mystatusclassce: $".$SpecialPriceVendor[0]->special_price;
                            }
                        }
                        else
                        {
                            if (isset($custom->vendors_price) && $custom->vendors_price !== null && $custom->quantity !== NULL)
                            {
                                $totalPriceVendor += $custom->vendors_price * $custom->quantity;
                                $htmlToSend .= " | Vendor Price: $".$custom->vendors_price * $custom->quantity;
                            }
                            else
                            {
                                $totalPriceVendor += $order_detail->requestedService->service->vendor_price;
                                $htmlToSend .= " | Vendor Price: $".$order_detail->requestedService->service->vendor_price;
                            }
                        }

                    }

                    $htmlToSend .= "</div>
                                </div>
                                <div class=\"card-body\">
                                    <div id=\"vendor-note-empty-error-".$order_detail->id."\" class=\"hide\">
                                        <div class=\"alert alert-error\">Vendor Note Can not be Empty</div>
                                    </div>
                                    <div id=\"vendor-note-empty-success-".$order_detail->id."\" class=\"hide\">
                                        <div class=\"alert alert-success\">Saved Successful</div>
                                    </div>

                                    <div id=\"billing-note-empty-success\" class=\"hide\">
                                        <div class=\"alert alert-success\">Saved Successful</div>
                                    </div>

                                    <div id=\"billing-note-empty-error\" class=\"hide\">
                                        <div class=\"alert alert-success\">Billing Note Can not be Empty</div>
                                    </div>

                                    <table class=\"table table-bordered\">";

                    if( Auth::user()->type_id == 1 || Auth::user()->type_id == 4) {

                        $htmlToSend .= "<tr>";

                        if (isset($custom->customers_notes))
                        {
                            $htmlToSend .= '<td colspan=\"2\" class=\"center\"><label class=\"table-label\">Customer Note: </label><p>'.$custom->customers_notes.'</p></td>';
                        }
                        else
                        {
                            $htmlToSend .= '<td colspan=\"2\" class=\"center\"><label class=\"table-label\">Customer Note: </label><p>'.$order_detail->requestedService->customer_note.'</p></td>';
                        }
                        $htmlToSend .= "</tr>";

                        $htmlToSend .= "<tr>";
                        if(isset($custom->notes_for_vendors))
                        {
                            $htmlToSend .= '<td colspan=\"2\" class=\"center\"><label class=\"table-label\">Note for Vendor: </label><p>'.$custom->notes_for_vendors.'</p></td>';
                        }
                        else
                        {
                            $htmlToSend .= '<td colspan=\"2\" class=\"center\"><label class=\"table-label\">Note for Vendor: </label><p>'.$order_detail->requestedService->public_notes.'</p></td>';
                        }
                        $htmlToSend .= "</tr>";

                        $htmlToSend .= "<tr>";
                        if (isset($custom->vendors_notes))
                        {
                            $htmlToSend .= '<td colspan=\"2\" class=\"center\"><label class=\"table-label\">Vendor Note: </label><p>'.$custom->vendors_notes.'</p></td>';
                        }
                        else
                        {
                            $htmlToSend .= '<td colspan=\"2\" class=\"center\"><label class=\"table-label\">Vendor Note: </label><p>'.$order_detail->requestedService->vendor_note.'</p>';
                        }
                        $htmlToSend .= "</tr>";



                    }
                    else if (Auth::user()->type_id == 3)
                    {
                        $htmlToSend .= '<tr><td colspan=\"2\" class=\"center\"><label class=\"table-label\">Note for Vendor:</label>';
                        if (isset($custom->notes_for_vendors))
                        {
                            $htmlToSend .= '<p>'.$custom->notes_for_vendors.'</p>';
                        }
                        else
                        {
                            $htmlToSend .= '<p>'.$order_detail->requestedService->public_notes.'</p>';
                        }
                    }

                        $htmlToSend .= '<tr>
                                                <td colspan=\"9\">
                                                    <div class=\"row\" style=\"margin-bottom:30px;\">
                                                        <div class=\"col-md-12\">
                                                            <div style=\"display:inline-block;\">
                                                                <label class=\"table-label\" style=\"display:inline;\">Order Images: </label>
                                                                <input type=\"hidden\" id=\"order_id\" value=\"'.$order->id.'\">
                                                                <span>
                                                                     <button class=\"btn btn-primary export-all-photos\" type=\"button\">Export All Images</button>
                                                                </span>
                                                                <span>
                                                                    <button class=\"btn btn-primary export-selected-photos\" type=\"button\">Export Selected Images</button>
                                                                </span>
                                                                <span>
                                                                     <button type=\"button\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#delete-confirmation\">Delete Selected</button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=\"row\">
                                                        <div class=\"col-md-4 col-lg-4 col-sm-12\">


                                                            <div class=\"center-div\">
                                                                <label class=\"table-label\">Before Images</label>
                                                            </div>
                                                            <hr>
                                                            <div class=\"order-photo-div center-div\">
                                                                <div class=\"order-photo-item upload-order-photo upload-before\">
                                                                    <i class=\"fa fa-2x fa-upload\"></i>
                                                                    <p>Drag Or Click To Upload</p>
                                                                </div>';


                                                                    $images = \App\OrderImage::where('order_id', $order->id)->where('type', 'before')->orderBy('id', 'desc')->get();
                                                                    foreach($images as $image)
                                                                    {
                                                                        $check = config('app.order_images_before').$image->address;
                                                                        if (file_exists($check))
                                                                        {
                                                                            $htmlToSend .= '<div class=\"order-photo-item\" id=\"photo-id-'.$image->id.'\"><span class=\"photo-export-checkbox\"><input data-id=\"'.$image->id.'\" type=\"checkbox\" class=\"form-control export-before-checkbox\"></span><img data-id=\"'.$image->id.'\" data-image-type=\"before\" class=\"order-photo-img\" src=\"'.config('app.url').'/'.config('app.order_images_before').$image->address.'\"></div>';
                                                                        }
                                                                     }
                                                $htmlToSend .= '
                                                            </div>
                                                            <div class=\"center-div export-button-group\">
                                                                <button type=\"button\" class=\"btn btn-primary export-before-all\">Export All Before Images</button>
                                                            </div>

                                                        </div>
                                                        <div class=\"col-md-4 col-lg-4 col-sm-12\">
                                                            <div class=\"center-div\">
                                                                <label class=\"table-label\">During Images</label>
                                                            </div>
                                                            <hr>
                                                            <div class=\"order-photo-div center-div\">
                                                                <div class=\"order-photo-item upload-order-photo upload-during\">
                                                                    <i class=\"fa fa-2x fa-upload\"></i>
                                                                    <p>Drag Or Click To Upload</p>
                                                                </div>';

                                                                $images = \App\OrderImage::where('order_id', $order->id)->where('type', 'during')->orderBy('id', 'desc')->get();

                                                                foreach($images as $image)
                                                                {
                                                                    $check = config('app.order_images_during').$image->address;
                                                                    if (file_exists($check))
                                                                    {
                                                                        $htmlToSend .= '<div class=\"order-photo-item\" id=\"photo-id-'.$image->id.'\"><span class=\"photo-export-checkbox\"><input data-id=\"'.$image->id.'\" type=\"checkbox\" class=\"form-control export-during-checkbox\"></span><img data-id=\"'.$image->id.'\" class=\"order-photo-img\" data-image-type=\"during\" src=\"'.config('app.url').'/'.config('app.order_images_during').$image->address.'\"></div>';
                                                                    }
                                                                }
                                                $htmlToSend .= '               
                                                            </div>
                                                            <div class=\"center-div export-button-group\">
                                                                <button type=\"button\" class=\"btn btn-primary export-during-all\">Export All During Images</button>
                                                            </div>
                                                        </div>
                                                        <div class=\"col-md-4 col-lg-4 col-sm-12\">
                                                            <div class=\"center-div\">
                                                                <label class=\"table-label\">After Images</label>
                                                            </div>
                                                            <hr>
                                                            <div class=\"order-photo-div center-div\">
                                                                <div class=\"order-photo-item upload-order-photo upload-after\">
                                                                    <i class=\"fa fa-2x fa-upload\"></i>
                                                                    <p>Drag Or Click To Upload</p>
                                                                </div>';

                                                                $images = \App\OrderImage::where('order_id', $order->id)->where('type', 'after')->orderBy('id', 'desc')->get();
                                                                foreach($images as $image)
                                                                {
                                                                    $check = config('app.order_images_after').$image->address;
                                                                    if (file_exists($check))
                                                                    {
                                                                        $htmlToSend .= '<div class=\"order-photo-item\" id=\"photo-id-'.$image->id.'\"><span class=\"photo-export-checkbox\"><input data-id=\"'.$image->id.'\" type=\"checkbox\" class=\"form-control export-after-checkbox\"></span><img data-id=\"'.$image->id.'\" class=\"order-photo-img\" data-image-type=\"before\" src=\"'.config('app.url').'/'.config('app.order_images_after').$image->address.'\"></div>';
                                                                    }
                                                                }
                                                $htmlToSend .= '
                                                            </div>
                                                            <div class=\"center-div export-button-group\">
                                                                <button type=\"button\" class=\"btn btn-primary export-after-all\">Export All After Images</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>';



                                        if( Auth::user()->type_id == 3 ) {
                                            $htmlToSend = '
                                        <tr>
                                            <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Vendor Note:</label>';

                                            if ($order_detail->requestedService->vendor_note) {
                                                $htmlToSend .= '<span id=\"show-vendor-note-' . $order->id . '-' . $order_detail->id . '\">' . $order_detail->requestedService->vendor_note . '<br><button class=\"btn btn-primary\" id=\"edit-vendor-note-button-' . $order->id . '-' . $order_detail->id . '\" onclick=\"editVendorNoteButton(' . $order->id . ',' . $order_detail->id . ')\"> Edit Note </button> </span >
                                                    <span class=\"hide\" id=\"textarea-vendor-note-' . $order->id . '-' . $order_detail->id . '\">
                                                    <textarea class="span" name="vendor_note" id="vendor-note-' . $order->id . '-' . $order_detail->id . '">' . $order_detail->requestedService->vendor_note . '</textarea>';
                                            } else {
                                                $htmlToSend .= '<span id=\"show-vendor-note-' . $order->id . '-' . $order_detail->id . '\">' . $order_detail->requestedService->vendor_note . '<br><button class=\"btn btn-primary\" id=\"edit-vendor-note-button-' . $order->id . '-' . $order_detail->id . '\" onclick=\"editVendorNoteButton(' . $order->id . ',' . $order_detail->id . ')\"> Edit Note </button> </span >
                                                    <span class=\"hide\" id=\"textarea-vendor-note-' . $order->id . '-' . $order_detail->id . '\">
                                                    <textarea class="span" name="vendor_note" id="vendor-note-' . $order->id . '-' . $order_detail->id . '"></textarea>';
                                            }
                                            $htmlToSend .= '
                                        </tr>
                                        <tr>
                                            <td class=\"center\" colspan=\"2\"><button class=\"btn btn-large btn-warning pull-right\"';

                                            if (Auth::user()->type_id == 3 && $order->status == 4) {
                                                $htmlToSend .= ' disabled="disabled" ';
                                            }

                                            $htmlToSend .= 'onclick=\"saveVendorNote(' . $order->id . ', ' . $order_detail->id . ')\">Save ' . $order_detail->requestedService->service->title . '</button></td>

                                        </tr>';

                                        }
                                        else if (Auth::user()->type_id == 2)
                                        {
                                            $htmlToSend .= '
                                            <tr>
                                                <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Customers Note:</label>';

                                            if ($order_detail->requestedService->customer_note)
                                            {

                                            }
                                        }


                                                    @if($order_detail->requestedService->customer_note)
                                                        <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!$order_detail->requestedService->custumer_note!!}<br><button class=\"btn btn-primary\" id=\"edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}\" onclick=\"editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})\"> Edit Note </button> </span >
                                                        <span class=\"hide\" id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea(\'custumer_note\', $order_detail->requestedService->customer_note ,array(\'class\'=>\'span\',\'id\'=>\'vendor-note-\'.$order->id.\'-\'.$order_detail->id))!!}</span></td>
                                                @else
                                                    <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\"></span >
                                                    <span id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea(\'custumer_note\',\'\',array(\'class\'=>\'span\',\'id\'=>\'vendor-note-\'.$order->id.\'-\'.$order_detail->id))!!}</span></td>
                                                @endif
                                            </tr>
                                        ';
                                        }


                                        <tr>
                                            <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Customers Note:</label>
                                                @if($order_detail->requestedService->customer_note)
                                                    <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!$order_detail->requestedService->custumer_note!!}<br><button class=\"btn btn-primary\" id=\"edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}\" onclick=\"editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})\"> Edit Note </button> </span >
                                                    <span class=\"hide\" id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('custumer_note', $order_detail->requestedService->customer_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @else
                                                <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\"></span >
                                                <span id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('custumer_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class=\"center\" colspan=\"2\"><button class=\"btn btn-large btn-warning pull-right\"  onclick=\"saveCustomerNote({!!$order->id!!}, {!!$order_detail->id!!})\">Save {!!$order_detail->requestedService->service->title!!}</button></td>

                                        </tr>

                                        <?php } else if( Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ||Auth::user()->user_role_id == 5 ||Auth::user()->user_role_id == 6||Auth::user()->user_role_id == 8 ) {
                                        ?>

                                        <tr>
                                            <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Admin Note:</label>
                                                @if($order_detail->requestedService->admin_note)

                                                    <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\"><p>{!!$order_detail->requestedService->admin_note!!}</p><br><button class=\"btn btn-primary\" id=\"edit-vendor-note-button-{!!$order->id!!}-{!!$order_detail->id!!}\" onclick=\"editVendorNoteButton({!!$order->id!!},{!!$order_detail->id!!})\"> Edit Note </button> </span >
                                                    <span class=\"hide\" id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('admin_note', $order_detail->requestedService->admin_note ,array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @else
                                                <span id=\"show-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\"></span >
                                                <span id=\"textarea-vendor-note-{!!$order->id!!}-{!!$order_detail->id!!}\">{!!Form::textarea('admin_note','',array('class'=>'span','id'=>'vendor-note-'.$order->id.'-'.$order_detail->id))!!}</span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class=\"center\" colspan=\"2\"><button class=\"btn btn-large btn-warning pull-right\" onclick=\"saveAdminNote({!!$order->id!!}, {!!$order_detail->id!!})\">Save Admin Note</button></td>

                                        </tr>
                                        <?php } ?>
                                        <?php if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4 ) { ?>
                                        <tr>


                                            <td colspan=\"2\" class=\"center\"><label class=\"table-label\">Billing Note:</label>
                                                @if($order->billing_note)
                                                    <span id=\"show-billing-note-{!!$order->id!!}\">{!!$order->billing_note!!}<br>
                                                        <button class=\"btn btn-primary\" id=\"edit-billing-note-button-{!!$order->id!!}\" onclick=\"editBillingNoteButton({!!$order->id!!})\"> Edit Note </button>
                                                            </span >
                                                    <span class=\"hide\" id=\"textarea-billing-note-{!!$order->id!!}\">{!!Form::textarea('admin_note', $order->billing_note ,array('class'=>'span','id'=>'billing-note-'.$order->id))!!}
                                                        <button class=\"btn btn-large btn-warning pull-right \" id=\"bill-btn\" onclick=\"saveBillingNote({!!$order->id!!})\">Save Billing Note</button></span>
                                            </td>
                                            @else
                                                <span id=\"show-billing-note-{!!$order->id!!}\"></span >
                                                <span id=\"textarea-billing-note-{!!$order->id!!}\">{!!Form::textarea('admin_note','',array('class'=>'span','id'=>'billing-note-'.$order->id))!!}
                                                    <button class=\"btn btn-large btn-warning pull-right\" onclick=\"saveBillingNote({!!$order->id!!})\">Save Billing Note</button></span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class=\"center\" colspan=\"2\"><!-- <button class=\"btn btn-large btn-warning pull-right\" onclick=\"saveBillingNote({!!$order->id!!})\">Save Billing Note</button> --></td>

                                        </tr>
                                        <?php  } ?>

                                        <tr><td><label class=\"table-lable\">Service Details</label></td><td></td></tr>
                                        @if($order_detail->requestedService->required_date!=\"\")
                                            <tr><td><span>Required Date</span></td>
                                                <td><span>{!! date('m/d/Y', strtotime($order_detail->requestedService->required_date)) !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if( $order_detail->requestedService->due_date!=\"\")
                                            <tr><td><span>Due Date</span></td>
                                                <td>
                                                    <span> {!! date('m/d/Y', strtotime($order_detail->requestedService->due_date)) !!}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->quantity!=\"\")
                                            <tr><td><span>Quantity</span></td>
                                                <td>
                                                    <span id=\"show-vendor-qty\">{!! $order_detail->requestedService->quantity !!}</span>
                                                </td>
                                            </tr>
                                        @endif



                                        @if($order_detail->requestedService->service_men!=\"\")
                                            <tr><td><span>Service men</span></td>
                                                <td><span>{!!$order_detail->requestedService->service_men !!}</span>

                                                </td>
                                            </tr>
                                        @endif
                                        @if($order_detail->requestedService->service_note!=\"\")
                                            <tr><td><span>Service note</span></td>
                                                <td><span>{!!$order_detail->requestedService->service_note !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->verified_vacancy!=\"\")
                                            <tr><td><span>Verified vacancy</span></td>
                                                <td><span>{!!$order_detail->requestedService->verified_vacancy !!}</span>

                                                </td>
                                            </tr>
                                        @endif
                                        @if($order_detail->requestedService->cash_for_keys!=\"\")
                                            <tr><td><span>Cash for keys</span></td>
                                                <td><span>{!!$order_detail->requestedService->cash_for_keys !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->cash_for_keys_trash_out!=\"\")
                                            <tr><td><span>Cash for keys Trash Out</span></td>
                                                <td><span>{!!$order_detail->requestedService->cash_for_keys_trash_out !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->trash_size!=\"\")
                                            <tr><td><span>trash size</span></td>
                                                <td><span>{!!$order_detail->requestedService->trash_size !!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->storage_shed!=\"\")
                                            <tr><td><span>storage shed</span></td>
                                                <td><span>{!!$order_detail->requestedService->storage_shed !!}</span></td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->lot_size!=\"\")
                                            <tr><td><span>lot size</span></td>
                                                <td><span>{!!$order_detail->requestedService->lot_size !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->set_prinkler_system_type!=\"\")
                                            <tr><td><span>set prinkler system type</span></td>
                                                <td><span>{!!$order_detail->requestedService->set_prinkler_system_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->install_temporary_system_type!=\"\")
                                            <tr><td><span>install temporary system type</span></td>
                                                <td><span>{!!$order_detail->requestedService->install_temporary_system_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif



                                        @if($order_detail->requestedService->pool_service_type!=\"\")
                                            <tr><td><span>pool service type</span></td>
                                                <td><span>{!!$order_detail->requestedService->pool_service_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->carpet_service_type!=\"\")
                                            <tr><td><span>carpet service type</span></td>
                                                <td><span>{!!$order_detail->requestedService->carpet_service_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->boarding_type!=\"\")
                                            <tr><td><span>boarding type</span></td>
                                                <td><span>{!!$order_detail->requestedService->boarding_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif



                                        @if($order_detail->requestedService->spruce_up_type!=\"\")
                                            <tr><td><span>spruce up type</span></td>
                                                <td><span>{!!$order_detail->requestedService->spruce_up_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif



                                        @if($order_detail->requestedService->constable_information_type!=\"\")
                                            <tr><td><span>constable information type</span></td>
                                                <td><span>{!!$order_detail->requestedService->constable_information_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->remove_carpe_type!=\"\")
                                            <tr><td><span>remove carpe type<span></td>
                                                <td><span>{!!$order_detail->requestedService->remove_carpe_type!!}</span>

                                                </td>
                                            </tr>
                                        @endif


                                        @if($order_detail->requestedService->remove_blinds_type!=\"\")
                                            <tr><td><span>remove blinds type</span></td>
                                                <td><span>{!!$order_detail->requestedService->remove_blinds_type !!}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($order_detail->requestedService->remove_appliances_type!=\"\")
                                            <tr><td><span>remove appliances type</span></td>
                                                <td><span>{!!$order_detail->requestedService->remove_appliances_type !!}</span>

                                                </td>
                                            </tr>
                                        @endif



                                    </table>
                                </div>";


                }
            }
        }
        return json_encode([
            "order_details" => $order_details,
            "city"          => $city,
            "geolocation"   => $geolocation_result,
            "state"         => $state,
            "cities"        => $cities,
            "states"        => $states,
            "property_details" => $property_details,
            "vendorsDATA"   => $vendorsDATA,
            "order"         => $data,
            "items"         => $items,
            "OrderReviewNote" => $OrderReviewNote,
            "allservices"   => $allservices,
            "customData"    => $customData
        ]);
    }


    public function editOrder($order_id)
    {


        $data = Order::getOrderByID($order_id);

        $order_details = $data->orderDetail;
        $vendorsDATA=User::where('type_id', '=', 3)->get();
        $items = AdditionalServiceItem::where('order_id', '=', $order_id)->get();
        $OrderReviewNote=OrderReviewNote::where('order_id', '=', $order_id)->get();
        if (OrderCustomData::where('order_id', '=', $order_id)->count() > 0) {
            $customData = OrderCustomData::where('order_id', '=', $order_id)->get();
        } else {
            $customData[1] = "lol";
        }
        $order_req = Order::where('id', '=', $order_id)->pluck('request_id');
        $services_asset_id= MaintenanceRequest::where('id', '=', $order_req)->pluck('asset_id');
        $property_details =  Asset::where('id', $services_asset_id)->get();


        //$job_type =MaintenanceRequest::where('id','=',$order_req)->pluck('job_type');

        $client_id = Asset::getAssetInformationById($services_asset_id);
        if (isset($client_id->customer_type)) {
            $allservices = Service::getServicesByClientId($client_id->customer_type);
        } else {
            $allservices = Service::getAllServices();
        }

        //Get City/State Information
        $city = City::where('id', $property_details[0]->city_id)->get()[0]->name;
        $state = State::where('id', $property_details[0]->state_id)->get()[0]->name;
        //Get all cities from city
        $cities = City::getAllCities();
        //Get all states from city
        $states = State::getAllStates();

        $geolocation_result = (new Geolocation)->getCoordinates($property_details[0]->property_address, '', $city, $property_details[0]->zip, 'USA');


        return view('common.edit_order')
            ->with('order_details', $order_details)
            ->with('city', $city)
            ->with('geolocation', $geolocation_result)
            ->with('state', $state)
            ->with('cities', $cities)
            ->with('states', $states)
            ->with('property_details', $property_details[0])
            ->with('vendorsDATA', $vendorsDATA)
            ->with('order', $data)
            ->with('items', $items)
            ->with('OrderReviewNote', $OrderReviewNote)
            ->with('allservices', $allservices)
            ->with('customData', $customData);

    }




    public function orderImage($order_id)
    {
        $orderimages = OrderImage::where('order_id', '=', $order_id)->get();


        $ImagesArray=[];
        $ImagesArray['before']=0;
        $ImagesArray['after']=0;
        $ImagesArray['during']=0;
        foreach ($orderimages as $img) {
            if ($img['type']=='before') {
                $ImagesArray['before']++;
            } elseif ($img['type']=='after') {
                $ImagesArray['after']++;
            } elseif ($img['type']=='during') {
                $ImagesArray['during']++;
            }
        }

        return $ImagesArray;
    }




    public function viewOrder($order_id)
    {
        $orderFLAG = Order::getOrderByID($order_id);
            $view_message = "";
        if ($orderFLAG->status==0 && Auth::user()->type_id==3) {
            $view_message = "Thank You. Your Work Order is now In-Process. ";
            Order::where('id', $order_id)
            ->update(['status' => 1,'status_text' => "In-Process",'status_class'=>"warning"]);
        }
        //Show dashboard of customer
        $order = Order::getOrderByID($order_id);
        $order_details = $order->orderDetail;

         $view_order="view_order";

        return view('common.'.$view_order)->with('order', $order)->with('order_details', $order_details)
        ->with('message', $view_message);
    }

    public function uploadWorkOrderPhoto()
    {
        if (!empty($_FILES))
        {
            $data       = Request::all();
            $cat        = $data["cat"];
            $order_id   = $data["order_id"];
            $dest_path  = false;

            switch ($cat)
            {
                case "before":
                    $dest_path = config('app.order_images_before');
                    break;

                case "during":
                    $dest_path = config('app.order_images_during');
                    break;

                case "after":
                    $dest_path = config('app.order_images_after');
                    break;
            }

            if (!$dest_path)
            {
                return "failed";
            }

            $tmp_file   = $_FILES['file']['tmp_name'];
            $file_name  = $order_id."-".date("YmdHis").rand(1,100).".jpg";
            $file_path  = $dest_path.$file_name;

            $img_info   = getimagesize($tmp_file);
            $image      = false;
            switch($img_info['mime'])
            {
                case "image/jpeg":
                    $image = imagecreatefromjpeg($tmp_file);
                    break;

                case "image/gif":
                    $image = imagecreatefromgif($tmp_file);
                    break;

                case "image/png":
                    $image = imagecreatefrompng($tmp_file);
                    break;
            }

            if (!$image)
            {
                return "failed";
            }

            $newFile = imagejpeg($image, $file_path,80);
            if (!$newFile)
            {
                return "failed";
            }

            imagedestroy($image);

            $data =
                [
                  'order_id' => $order_id,
                    'address' => $file_name,
                    'type' => $cat,
                ];

            $save = OrderImage::createImage($data);
            if ($save)
            {
                return json_encode(["image_id" => $save->id, "address" => $dest_path.$file_name]);
            }

        }
    }
    public function addBeforeImages()
    {

        $destinationPath = config('app.order_images_before');   //2
        if (!empty($_FILES)) {
            $data=Request::all();
            $order_id=$data['order_id'];
            $order_details_id=$data['order_details_id'];
            $type=$data['type'];
            $tempFile = $_FILES['file']['tmp_name'];          //3
            $targetPath = $destinationPath;  //4
            $originalFile=$_FILES['file']['name'];
            $changedFileName=$order_id.'-'.$order_details_id.'-'.$originalFile;
            $targetFile = $targetPath . $changedFileName;  //5

            $info = getimagesize($tempFile);

            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($tempFile);
            } elseif ($info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($tempFile);
            } elseif ($info['mime'] == 'image/png') {
                $image = imagecreatefrompng($tempFile);
            }

            $targetFile = imagejpeg($image, $targetFile, 80);

            $moved=move_uploaded_file($tempFile, $targetFile); //6
            if ($moved) {
                $data['address']=$changedFileName;
                unset($data['file']);
                unset($data['_token']);

                setcookie('order_id', $order_id);
                setcookie('order_details_id', $order_details_id);
                setcookie('type', $type);
                $save=OrderImage::createImage($data);
                if ($save) {
                    $image='<img id="'.$save->order_id.'-'.$save->order_details_id.'-'.$save->address.'" src="'.config('app.url').'/'.config('app.order_images_before').$save->address.'" width="80px" height="80px" style="padding: 10px" class="img-thumbnail" alt="">';
                    echo $image;
                }
            }
        }
    }

    public function addAdditionalBeforeImages()
    {

        $destinationPath = config('app.order_additional_images_before');   //2
        if (!empty($_FILES)) {
            $data=Request::all();

            $additional_service_id=$data['additional_service_id'];
            $type=$data['type'];
            $tempFile = $_FILES['file']['tmp_name'];          //3
            $targetPath = $destinationPath;  //4
            $originalFile=$_FILES['file']['name'];
            $changedFileName=$additional_service_id.'-'.$originalFile;
            $targetFile = $targetPath . $changedFileName;  //5

            $info = getimagesize($tempFile);

            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($tempFile);
            } elseif ($info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($tempFile);
            } elseif ($info['mime'] == 'image/png') {
                $image = imagecreatefrompng($tempFile);
            }

            $targetFile = imagejpeg($image, $targetFile, 80);

            $moved=move_uploaded_file($tempFile, $targetFile); //6
            if ($moved) {
                $data['address']=$changedFileName;
                unset($data['file']);
                unset($data['_token']);

                setcookie('additional_service_id', $additional_service_id);
                setcookie('type', $type);
                $save=  AdditionalServiceItemImage::createImage($data);
                if ($save) {
                    $image='<img id="'.$save->additional_service_id.'-'.$save->address.'" src="'.config('app.url').'/'.config('app.order_additional_images_before').$save->address.'" width="80px" height="80px" style="padding: 10px" class="img-thumbnail" alt="">';
                    echo $image;
                }
            }
        }
    }

    public function addAdditionalDuringImages()
    {

        $destinationPath = config('app.order_additional_images_during');   //2
        if (!empty($_FILES)) {
            $data=Request::all();
            $additional_service_id=$data['additional_service_id'];
            $type=$data['type'];
            $tempFile = $_FILES['file']['tmp_name'];          //3
            $targetPath = $destinationPath;  //4
            $originalFile=$_FILES['file']['name'];
            $changedFileName=$additional_service_id.'-'.$originalFile;
            $targetFile = $targetPath . $changedFileName;  //5

            $info = getimagesize($tempFile);

            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($tempFile);
            } elseif ($info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($tempFile);
            } elseif ($info['mime'] == 'image/png') {
                $image = imagecreatefrompng($tempFile);
            }

            $targetFile = imagejpeg($image, $targetFile, 80);

            $moved=move_uploaded_file($tempFile, $targetFile); //6
            if ($moved) {
                $data['address']=$changedFileName;
                unset($data['file']);
                unset($data['_token']);

                setcookie('additional_service_id', $additional_service_id);
                setcookie('type', $type);
                $save=AdditionalServiceItemImage::createImage($data);
                if ($save) {
                    echo 'success';
                }
            }
        }
    }

    public function addDuringImages()
    {

        $destinationPath = config('app.order_images_during');   //2
        if (!empty($_FILES)) {
            $data=Request::all();
            $order_id=$data['order_id'];
            $order_details_id=$data['order_details_id'];
            $type=$data['type'];
            $tempFile = $_FILES['file']['tmp_name'];          //3
            $targetPath = $destinationPath;  //4
            $originalFile=$_FILES['file']['name'];
            $changedFileName=$order_id.'-'.$order_details_id.'-'.$originalFile;
            $targetFile = $targetPath . $changedFileName;  //5

            $info = getimagesize($tempFile);

            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($tempFile);
            } elseif ($info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($tempFile);
            } elseif ($info['mime'] == 'image/png') {
                $image = imagecreatefrompng($tempFile);
            }

            $targetFile = imagejpeg($image, $targetFile, 80);

            $moved=move_uploaded_file($tempFile, $targetFile); //6
            if ($moved) {
                $data['address']=$changedFileName;
                unset($data['file']);
                unset($data['_token']);

                setcookie('order_id', $order_id);
                setcookie('order_details_id', $order_details_id);
                setcookie('type', $type);
                $save=OrderImage::createImage($data);
                if ($save) {
                    echo 'success';
                }
            }
        }
    }

    public function addAdditionalAfterImages()
    {

        $destinationPath = config('app.order_additional_images_after');   //2
        if (!empty($_FILES)) {
            $data=Request::all();
            $additional_service_id = $data['additional_service_id'];
            $type=$data['type'];
            $tempFile = $_FILES['file']['tmp_name'];          //3
            $targetPath = $destinationPath;  //4
            $originalFile=$_FILES['file']['name'];
            $changedFileName=$additional_service_id.'-'.$originalFile;
            $targetFile = $targetPath . $changedFileName;  //5

            $info = getimagesize($tempFile);

            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($tempFile);
            } elseif ($info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($tempFile);
            } elseif ($info['mime'] == 'image/png') {
                $image = imagecreatefrompng($tempFile);
            }

            $targetFile = imagejpeg($image, $targetFile, 80);

            $moved=move_uploaded_file($tempFile, $targetFile); //6
            if ($moved) {
                $data['address']=$changedFileName;
                unset($data['file']);
                unset($data['_token']);

                setcookie('additional_service_id', $additional_service_id);
                setcookie('type', $type);
                $save=AdditionalServiceItemImage::createImage($data);
                if ($save) {
                    echo 'success';
                }
            }
        }
    }

    public function addAfterImages()
    {

        $destinationPath = config('app.order_images_after');   //2
        if (!empty($_FILES)) {
            $data=Request::all();
            $order_id=$data['order_id'];
            $order_details_id=$data['order_details_id'];
            $type=$data['type'];
            $tempFile = $_FILES['file']['tmp_name'];          //3
            $targetPath = $destinationPath;  //4
            $originalFile=$_FILES['file']['name'];
            $changedFileName=$order_id.'-'.$order_details_id.'-'.$originalFile;
            $targetFile = $targetPath . $changedFileName;  //5

            $info = getimagesize($tempFile);

            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($tempFile);
            } elseif ($info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($tempFile);
            } elseif ($info['mime'] == 'image/png') {
                $image = imagecreatefrompng($tempFile);
            }

            $targetFile = imagejpeg($image, $targetFile, 80);

            $moved=move_uploaded_file($tempFile, $targetFile); //6
            if ($moved) {
                $data['address']=$changedFileName;
                unset($data['file']);
                unset($data['_token']);

                setcookie('order_id', $order_id);
                setcookie('order_details_id', $order_details_id);
                setcookie('type', $type);
                $save=OrderImage::createImage($data);
                if ($save) {
                    echo 'success';
                }
            }
        }
    }

    public function deleteOrderAllBeforeImages()
    {
        $data=Request::all();
        $order_id=$data['order_id'];
        if ($data['before_image']==0) {
            $images=OrderImage::where('order_id', '=', $order_id)->get();
            foreach ($images as $image) {
                $filename=$image->address;
                if ($image->type=='before') {
                    $destinationPath = config('app.order_images_before');   //2
                    unlink($destinationPath . $filename);
                } else {
                    $destinationPath = config('app.order_images_after');   //2
                    unlink($destinationPath . $filename);
                }
            }
            $delete=OrderImage::where('order_id', '=', $order_id)->delete();
        } else {
            $images=OrderImage::where('order_id', '=', $order_id)->where('type', '=', 'after')->get();
            foreach ($images as $image) {
                $filename=$image->address;
                if ($image->type=='before') {
                    $destinationPath = config('app.order_images_before');   //2
                    unlink($destinationPath . $filename);
                } else {
                    $destinationPath = config('app.order_images_after');   //2
                    unlink($destinationPath . $filename);
                }
            }
            $delete=OrderImage::where('order_id', '=', $order_id)->where('type', '=', 'after')->delete();
        }
    }

    public function deleteAfterImages()
    {
        $data = Request::all();
        $order_id = $data['order_id'];
        $order_details_id = $data['order_details_id'];
        $type = $data['type'];
        $filename = $order_id . '-' . $order_details_id . '-' . $data['filename'];
        $delete = OrderImage::where('order_id', '=', $order_id)->where('order_details_id', '=', $order_details_id)->where('type', '=', $type)->where('address', '=', $filename)->delete();
        if ($delete) {
            echo 'delete success';
        }
        $destinationPath = config('app.order_images_after');   //2
        unlink($destinationPath . $filename);
    }



    public function deleteDuringImages()
    {
        $data = Request::all();
        $order_id = $data['order_id'];
        $order_details_id = $data['order_details_id'];
        $type = $data['type'];
        $filename = $order_id . '-' . $order_details_id . '-' . $data['filename'];
        $delete = OrderImage::where('order_id', '=', $order_id)->where('order_details_id', '=', $order_details_id)->where('type', '=', $type)->where('address', '=', $filename)->delete();
        if ($delete) {
            echo 'delete success';
        }
        $destinationPath = config('app.order_images_during');   //2
        unlink($destinationPath . $filename);
    }

    public function downloadSeletedAdditionalItemImages()
    {

        $data =  Request::all();
        $result = [];
        foreach ($data as $key => $value) {
            foreach ($value as $key => $val) {
                $result[] = AdditionalServiceItemImage::where('id', '=', $val)->get();
            }
        }
        $popDiv='';
        $beforeimages = '';
        $duringimages = '';
        $afterimages = '';
        $app_path="";


        foreach ($result as $key => $value) {
            foreach ($value as $key => $image) {
                if ($image->type=="after") {
                    $app_path="order_additional_images_after";

                    $filecheck=  config('app.'.$app_path).$image->address; //its for live

                 // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                    if (file_exists($filecheck)) {
                        $afterimages.='<div class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"><h3>After Images</h3> <img src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" style="height:300px; width:300px;   " class="img-thumbnail" alt="'.$image->address.'" ></div>';
                    } else {
                           $afterimages.= '<div class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"> <h3>After Images</h3> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'"  style="height:300px; width:300px;  "   class="img-thumbnail" alt="'.$image->address.'"></div>';
                    }
                } elseif ($image->type=="before") {
                    $app_path="order_additional_images_before";

                    $filecheck=  config('app.'.$app_path).$image->address; //its for live

                     // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                    if (file_exists($filecheck)) {
                        $beforeimages.='<div  class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"> <h3>Before Images</h3> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" style="height:300px; width:300px;   "  class="img-thumbnail" alt="'.$image->address.'"></div>';
                    } else {
                           $beforeimages.= '<div  class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="50%"   class="img-thumbnail" alt="'.$image->address.'"> </div>';
                    }
                } elseif ($image->type=="during") {
                     $app_path="order_additional_images_during";

                    $filecheck=  config('app.'.$app_path).$image->address; //its for live

                     // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                    if (file_exists($filecheck)) {
                        $duringimages.='<div  class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"><h3>During Images</h3> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'"  style="height:300px; width:300px;   "class="img-thumbnail" alt="'.$image->address.'"> </div>';
                    } else {
                           $duringimages.= '<div  class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="50%"   class="img-thumbnail" alt="'.$image->address.'"> <p>During Images</p></div>';
                    }
                }
            }
        }
        $images = '';
        $images = $beforeimages ." ". $duringimages." ".$afterimages;

        return $images;
    }
    public function downloadSeletedImages()
    {
        $data =  Request::all();
        $result = [];
        foreach ($data as $key => $value) {
            foreach ($value as $key => $val) {
                $result[] = OrderImage::where('id', '=', $val)->get();
            }
        }
        $popDiv='';
        $beforeimages = '';
        $duringimages = '';
        $afterimages = '';
        $app_path="";


        foreach ($result as $key => $value) {
            foreach ($value as $key => $image) {
                if ($image->type=="after") {
                    $app_path="order_images_after";

                    $filecheck=  config('app.'.$app_path).$image->address; //its for live

                 // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                    if (file_exists($filecheck)) {
                        $afterimages.='<div class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"><h3>After Images</h3> <img src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'"style="height:300px; width:300px;  " class="img-thumbnail" alt="'.$image->address.'" ></div>';
                    } else {
                           $afterimages.= '<div class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"> <h3>After Images</h3> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'"style="height:300px; width:300px;   "   class="img-thumbnail" alt="'.$image->address.'"></div>';
                    }
                } elseif ($image->type=="before") {
                    $app_path="order_images_before";

                    $filecheck=  config('app.'.$app_path).$image->address; //its for live

                     // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                    if (file_exists($filecheck)) {
                        $beforeimages.='<div  class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"> <h3>Before Images</h3> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" style="height:300px; width:300px;   "  class="img-thumbnail" alt="'.$image->address.'"></div>';
                    } else {
                           $beforeimages.= '<div  class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="50%"   class="img-thumbnail" alt="'.$image->address.'"> </div>';
                    }
                } elseif ($image->type=="during") {
                     $app_path="order_images_during";

                    $filecheck=  config('app.'.$app_path).$image->address; //its for live

                     // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                    if (file_exists($filecheck)) {
                        $duringimages.='<div  class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"><h3>During Images</h3> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'"  style="height:300px; width:300px;  "class="img-thumbnail" alt="'.$image->address.'"> </div>';
                    } else {
                           $duringimages.= '<div  class="" style="display:inline-block; vertical-align:top; padding:5px; text-align:center; height: auto;"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="50%"   class="img-thumbnail" alt="'.$image->address.'"> <p>During Images</p></div>';
                    }
                }
            }
        }
        $images = '';
        $images = $beforeimages ." ". $duringimages." ".$afterimages;

        return $images;
    }
    public function displayAdditionalExportImages()
    {
           $data = Request::all();

           $additional_service_id = $data['additional_service_id'];

           $type = $data['type'];

           $popDiv='';

           $app_path="";


           $images=AdditionalServiceItemImage::where('additional_service_id', '=', $additional_service_id)->get();

           $tag_counter = 1;

           $output="";
           $popDiv.= '<div class="table exportArea">
        <ul class="tabTgr">
            <li><a href="javascript:;" data-tab=".exprtTab">Before Images</a></li>
            <li><a href="javascript:;" data-tab=".exprtTab2">During Images</a></li>
            <li><a href="javascript:;" data-tab=".exprtTab3">After Images</a></li>
        </ul>
  <div class="tabBox clearfix">
    ';

        foreach ($images as $image) {
            if ($image->type == "before") {
                $app_path="order_additional_images_before";

                $filecheck=  config('app.'.$app_path).$image->address; //its for live

                 // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                if (file_exists($filecheck)) {
                      $popDiv.='<td><div class="imageFrame exprtTab active"><input type="checkbox" name="vehicle[]" value="'.$image->id.'" checked="checked"> <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a></div></td>';
                } else {
                    $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px"  class="img-thumbnail" alt="'.$image->address.'"></a></div>';
                }
            } elseif ($image->type == "during") {
                $app_path="order_additional_images_during";
                 $filecheck=  config('app.'.$app_path).$image->address; //its for live

                     // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                if (file_exists($filecheck)) {
                    $popDiv.='<td><div class="imageFrame exprtTab2"><input type="checkbox" name="vehicle[]" value="'.$image->id.'" checked="checked"><a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a></div></td>';
                } else {
                    $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px"  class="img-thumbnail" alt="'.$image->address.'"></a></div>';
                }
            } elseif ($image->type == "after") {
                 $app_path="order_additional_images_after";
                  $filecheck=  config('app.'.$app_path).$image->address; //its for live

                     // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                if (file_exists($filecheck)) {
                    //print_r(config('app.url').'/'.config('app.'.$app_path).$image->address);
                    $popDiv.='<td><div  class="imageFrame exprtTab3"><input type="checkbox" name="vehicle[]" value="'.$image->id.'" checked="checked"> <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a></div></td>';
                } else {
                        $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px"  class="img-thumbnail" alt="'.$image->address.'"></a></div>';
                }
            }

             //    $filecheck=  config('app.'.$app_path).$image->address; //its for live

             // // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

             //    if (file_exists($filecheck)) {

             //        '<div  class="imageFrame"><button><a href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" download >Download</a></button>  <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';



             //    }

             //    else

             //    {

             //        $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px"  class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';



             //    }



            $OrderImagesPosition     =OrderImagesPosition::where('order_image_id', '=', $image->id)->get();

            $OrderImagesPositionCount=OrderImagesPosition::where('order_image_id', '=', $image->id)->count();





            $tag_counter = 1;



          //Build output



            foreach ($OrderImagesPosition as $tag) {
                if ($tag_counter ==1) {
                    $output .= '<style type="text/css">';

                    $output .=  '.map'.$tag->order_image_id.' { display:none;}';
                }





                     $output .=  '.map'.$tag->order_image_id.' .map .tag_'.$tag_counter.$tag->order_image_id.' { ';

                    // $output .= 'border:1px solid #000;';

                     $output .= 'background:url("'.URL::to('/').'/assets/images/tag_hotspot_62x62.png") no-repeat;';

                     $output .= 'top:'.$tag['y1'].'px;';

                     $output .= 'left:'.$tag['x1'].'px;';

                     $output .= 'width:'.$tag['w'].'px;';

                     $output .= 'height:'.$tag['h'].'px;';



                     $output .= '}';





                     $tag_counter++;
            }

            if ($tag_counter !=1) {
                $output .= '</style>';
            }



            $tag_counter = 1;



            if ($OrderImagesPositionCount>0) {
                foreach ($OrderImagesPosition as $tag) {
                    if ($tag_counter ==1) {
                        $output.= '<div class="map'.$tag->order_image_id.'"><ul class="map">';
                    }

                    $output.=  '<li class="tag_'.$tag_counter.$tag->order_image_id.'" id="uniq'.$tag->id.'"><a  href="javascript:;"><span class="titleDs">'.$tag['comment'].' </span></a><a href="javascript:;" class="removeBtn" onclick="deletePhotoTag('.$tag->id.')">X</a></li>';





                    $tag_counter++;
                }
            } else {
                     $output.= '<div class="mapunique"><ul class="map">';

                     $output.= "</ul></div>";
            }





            if ($tag_counter !=1) {
                     $output.= "</ul></div>";
            }
        }
        $popDiv.= '</div>
<script type="text/javascript">

$(".example6").fancybox({

    onStart: function(element){

        var jquery_element=$(element);



        $("#order_image_id").val(jquery_element.data("image_id"));

        setTimeout(function(){



            var output=\''.$output.'\';



            $("#fancybox-content").append(output); 

            $(".map"+jquery_element.data("image_id")).show();

        }, 1000);





    },

    "titlePosition"    : "outside",

    "overlayColor"      : "#000",

    "overlayOpacity"    : "0.9"



}); 

</script>';

        return $popDiv;
    }

    public function displayExportImages()
    {
           $data = Request::all();

           $order_id = $data['order_id'];



           $order_details_id = $data['order_detail_id'];

           $type = $data['type'];

           $popDiv='';

           $app_path="";


           $images=OrderImage::where('order_id', '=', $order_id)->where('order_details_id', '=', $order_details_id)->get();

           $tag_counter = 1;


           $output="";
           $popDiv.= '<div class="table exportArea">
        <ul class="tabTgr">
            <li><a href="javascript:;" data-tab=".exprtTab">Before Images</a></li>
            <li><a href="javascript:;" data-tab=".exprtTab2">During Images</a></li>
            <li><a href="javascript:;" data-tab=".exprtTab3">After Images</a></li>
        </ul>
  <div class="tabBox clearfix">
    ';

        foreach ($images as $image) {
            if ($image->type == "before") {
                $app_path="order_images_before";

                $filecheck=  config('app.'.$app_path).$image->address; //its for live

                 // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                if (file_exists($filecheck)) {
                      $popDiv.='<td><div class="imageFrame exprtTab active"><input type="checkbox" name="vehicle[]" value="'.$image->id.'" checked="checked"> <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a></div></td>';
                } else {
                    $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/img/'.$image->address.'" width="120px" height="120px"  class="img-thumbnail" alt="'.$image->address.'"></a></div>';
                }
            } elseif ($image->type == "during") {
                $app_path="order_images_during";
                 $filecheck=  config('app.'.$app_path).$image->address; //its for live

                     // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                if (file_exists($filecheck)) {
                    $popDiv.='<td><div class="imageFrame exprtTab2"><input type="checkbox" name="vehicle[]" value="'.$image->id.'" checked="checked"><a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a></div></td>';
                } else {
                    $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/img/'.$image->address.'" width="120px" height="120px"  class="img-thumbnail" alt="'.$image->address.'"></a></div>';
                }
            } elseif ($image->type == "after") {
                 $app_path="order_images_after";
                  $filecheck=  config('app.'.$app_path).$image->address; //its for live

                     // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

                if (file_exists($filecheck)) {
                    $popDiv.='<td><div  class="imageFrame exprtTab3"><input type="checkbox" name="vehicle[]" value="'.$image->id.'" checked="checked"> <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a></div></td>';
                } else {
                        $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/img/'.$image->address.'" width="120px" height="120px"  class="img-thumbnail" alt="'.$image->address.'"></a></div>';
                }
            }

             //    $filecheck=  config('app.'.$app_path).$image->address; //its for live

             // // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;

             //    if (file_exists($filecheck)) {

             //        '<div  class="imageFrame"><button><a href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" download >Download</a></button>  <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';



             //    }

             //    else

             //    {

             //        $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px"  class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';



             //    }



            $OrderImagesPosition     =OrderImagesPosition::where('order_image_id', '=', $image->id)->get();

            $OrderImagesPositionCount=OrderImagesPosition::where('order_image_id', '=', $image->id)->count();





            $tag_counter = 1;



          //Build output



            foreach ($OrderImagesPosition as $tag) {
                if ($tag_counter ==1) {
                    $output .= '<style type="text/css">';

                    $output .=  '.map'.$tag->order_image_id.' { display:none;}';
                }





                     $output .=  '.map'.$tag->order_image_id.' .map .tag_'.$tag_counter.$tag->order_image_id.' { ';

                    // $output .= 'border:1px solid #000;';

                     $output .= 'background:url("'.URL::to('/').'/assets/images/tag_hotspot_62x62.png") no-repeat;';

                     $output .= 'top:'.$tag['y1'].'px;';

                     $output .= 'left:'.$tag['x1'].'px;';

                     $output .= 'width:'.$tag['w'].'px;';

                     $output .= 'height:'.$tag['h'].'px;';



                     $output .= '}';





                     $tag_counter++;
            }

            if ($tag_counter !=1) {
                $output .= '</style>';
            }



            $tag_counter = 1;



            if ($OrderImagesPositionCount>0) {
                foreach ($OrderImagesPosition as $tag) {
                    if ($tag_counter ==1) {
                        $output.= '<div class="map'.$tag->order_image_id.'"><ul class="map">';
                    }

                    $output.=  '<li class="tag_'.$tag_counter.$tag->order_image_id.'" id="uniq'.$tag->id.'"><a  href="javascript:;"><span class="titleDs">'.$tag['comment'].' </span></a><a href="javascript:;" class="removeBtn" onclick="deletePhotoTag('.$tag->id.')">X</a></li>';





                    $tag_counter++;
                }
            } else {
                     $output.= '<div class="mapunique"><ul class="map">';

                     $output.= "</ul></div>";
            }





            if ($tag_counter !=1) {
                     $output.= "</ul></div>";
            }
        }
        $popDiv.= '</div>
<script type="text/javascript">

$(".example6").fancybox({

    onStart: function(element){

        var jquery_element=$(element);



        $("#order_image_id").val(jquery_element.data("image_id"));

        setTimeout(function(){



            var output=\''.$output.'\';



            $("#fancybox-content").append(output); 

            $(".map"+jquery_element.data("image_id")).show();

        }, 1000);





    },

    "titlePosition"    : "outside",

    "overlayColor"      : "#000",

    "overlayOpacity"    : "0.9"



}); 

</script>';

        return $popDiv;
    }


    public function deleteBeforeImages()
    {
        $data = Request::all();
        $order_id = $data['order_id'];
        $order_details_id = $data['order_details_id'];
        $type = $data['type'];
        $filename = $order_id . '-' . $order_details_id . '-' . $data['filename'];
        $delete = OrderImage::where('order_id', '=', $order_id)->where('order_details_id', '=', $order_details_id)->where('type', '=', $type)->where('address', '=', $filename)->delete();
        if ($delete) {
            echo 'delete success';
        }
        $destinationPath = config('app.order_images_before');   //2
        unlink($destinationPath . $filename);
    }

    public function saveVendorNote()
    {
        $data = Request::all();
        $vendor_note=['vendor_note'=>$data['vendor_note']];
        $order_details=OrderDetail::find($data['order_detail_id']);
        $requested_service=RequestedService::find($order_details->requestedService->id);
        $save=$requested_service->vendor_note=$data['vendor_note'];
        $requested_service->save();
        if ($save) {
            return $requested_service->vendor_note;
        }
    }
    public function saveAdditionalVendorNote()
    {
        $id = Request::get('additional_id');
        $additional_vendors_notes = Request::get('additional_vendors_notes');
        $save = AdditionalServiceItem::where('id', '=', $id)->update(['additional_vendors_notes' => $additional_vendors_notes]);

        if ($save) {
            return $additional_vendors_notes;
        }
    }
    public function saveBillingNote()
    {
         $data = Request::all();
        $billing_note=['billing_note'=>$data['billing_note']];
        $orders=Order::find($data['order_id']);
        $save=$orders->billing_note=$data['billing_note'];
        $orders->save();
        if ($save) {
            return $orders->billing_note;
        }
    }
    public function saveAdminNote()
    {
        $data = Request::all();
        $vendor_note=['vendor_note'=>$data['vendor_note']];
        $order_details=OrderDetail::find($data['order_detail_id']);
        $requested_service=RequestedService::find($order_details->requestedService->id);
        $save=$requested_service->admin_note=$data['vendor_note'];
        $requested_service->save();
        if ($save) {
            return $requested_service->admin_note;
        }
    }

    public function saveAdminQuantity()
    {
        $data = Request::all();
        $vendor_note=['vendor_qty'=>$data['vendor_qty']];
        $order_details=OrderDetail::find($data['order_detail_id']);
        $requested_service=RequestedService::find($order_details->requestedService->id);
        $save=$requested_service->quantity=$data['vendor_qty'];
        $requested_service->save();
        if ($save) {
            return $requested_service->quantity;
        }
    }
    public function saveCustomerNote()
    {
        $data = Request::all();
        $vendor_note=['vendor_note'=>$data['vendor_note']];
        $order_details=OrderDetail::find($data['order_detail_id']);
        $requested_service=RequestedService::find($order_details->requestedService->id);
        $save=$requested_service->customer_note=$data['vendor_note'];
        $requested_service->save();
        if ($save) {
            return $requested_service->customer_note;
        }
    }
    public function displayAdditonalItemImages()
    {
        $data = Request::all();
        $additional_service_id = $data['additional_service_id'];
        $type = $data['type'];
        $popDiv='';
        $app_path="";
        if ($type=="after") {
            $app_path="order_additional_images_after";
        } elseif ($type=="before") {
            $app_path="order_additional_images_before";
        } elseif ($type=="during") {
            $app_path="order_additional_images_during";
        }

        $images=AdditionalServiceItemImage::where('additional_service_id', '=', $additional_service_id)->where('type', '=', $type)->get();


        $tag_counter = 1;
        $output="";


        foreach ($images as $image) {
                $filecheck= config('app.'.$app_path).$image->address; //its for live

             // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;
            if (file_exists($filecheck)) {
                // $popDiv.= '<div  class="imageFrame"><button><a href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" download >Download</a></button>  <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';
                $popDiv.= '<div  class="imageFrame"><a class="dwnldBtn" href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" download ><i class="fa-icon-download" aria-hidden="true"></i></a>  <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeAdditionalImage('.$additional_service_id.',this,\''.$type.'\');" >X</a></div>';
            } else {
                $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url')."/".$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$additional_service_id.',this,\''.$type.'\');" >X</a></div>';
            }

            //           $OrderImagesPosition     =OrderImagesPosition::where('order_image_id','=',$image->id)->get();
            //           $OrderImagesPositionCount=OrderImagesPosition::where('order_image_id','=',$image->id)->count();


            //           $tag_counter = 1;

            // //Build output

            //           foreach ($OrderImagesPosition as $tag) {
            //               if($tag_counter ==1) { $output .= '<style type="text/css">';
            //               $output .=  '.map'.$tag->order_image_id.' { display:none;}';
            //           }


            //           $output .=  '.map'.$tag->order_image_id.' .map .tag_'.$tag_counter.$tag->order_image_id.' { ';
            //   // $output .= 'border:1px solid #000;';
            //           $output .= 'background:url("'.URL::to('/').'/assets/images/tag_hotspot_62x62.png") no-repeat;';
            //           $output .= 'top:'.$tag['y1'].'px;';
            //           $output .= 'left:'.$tag['x1'].'px;';
            //           $output .= 'width:'.$tag['w'].'px;';
            //           $output .= 'height:'.$tag['h'].'px;';

            //           $output .= '}';


            //           $tag_counter++;
            //       }
            //       if($tag_counter !=1)  { $output .= '</style>';}

            //       $tag_counter = 1;

            //       if( $OrderImagesPositionCount>0)
            //       {
            //           foreach($OrderImagesPosition as $tag) {
            //               if($tag_counter ==1)
            //               {
            //                   $output.= '<div class="map'.$tag->order_image_id.'"><ul class="map">';
            //               }
            //               $output.=  '<li class="tag_'.$tag_counter.$tag->order_image_id.'" id="uniq'.$tag->id.'"><a  href="javascript:;"><span class="titleDs">'.$tag['comment'].' </span></a><a href="javascript:;" class="removeBtn" onclick="deletePhotoTag('.$tag->id.')">X</a></li>';


            //               $tag_counter++;
            //           }
            //       }
            //       else
            //       {
            //        $output.= '<div class="mapunique"><ul class="map">';
            //        $output.= "</ul></div>";
            //    }


            //    if($tag_counter !=1) {
            //        $output.= "</ul></div>";
            //    }
        }



        $popDiv.= '<script type="text/javascript">
     $(".example6").fancybox({
        onStart: function(element){
            var jquery_element=$(element);

            $("#order_image_id").val(jquery_element.data("image_id"));
            setTimeout(function(){

                var output=\''.$output.'\';

                $("#fancybox-content").append(output); 
                $(".map"+jquery_element.data("image_id")).show();
            }, 1000);


        },
        "titlePosition"    : "outside",
        "overlayColor"      : "#000",
        "overlayOpacity"    : "0.9"

    }); 
</script>';
        return $popDiv;
    }

    public function displayImages()
    {
        $data = Request::all();
        $order_id = $data['order_id'];

        $order_details_id = $data['order_detail_id'];
        $type = $data['type'];
        $popDiv='';
        $app_path="";
        if ($type=="after") {
                $app_path="order_images_after";
        } elseif ($type=="before") {
                $app_path="order_images_before";
        } elseif ($type=="during") {
                   $app_path="order_images_during";
        }

        $images=OrderImage::where('order_id', '=', $order_id)->where('type', '=', $type)->get();


        $tag_counter = 1;
        $output="";


        foreach ($images as $image) {
                $filecheck= config('app.'.$app_path).$image->address; //its for live
             // $filecheck=  'C:\xampp\htdocs\phpnewlatest\\'.config('app.'.$app_path).$image->address;
            if (file_exists($filecheck)) {
                // $popDiv.= '<div  class="imageFrame"><button><a href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" download >Download</a></button>  <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';
                $popDiv.= '<div  class="imageFrame"><a class="dwnldBtn" href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" download ><i class="fa-icon-download" aria-hidden="true"></i></a>  <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';
            } else {
                $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6" rel="group1"> <img  src="'.config('app.url')."/img/".$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';
            }

            $OrderImagesPosition     =OrderImagesPosition::where('order_image_id', '=', $image->id)->get();
            $OrderImagesPositionCount=OrderImagesPosition::where('order_image_id', '=', $image->id)->count();


            $tag_counter = 1;

      //Build output

            foreach ($OrderImagesPosition as $tag) {
                if ($tag_counter ==1) {
                    $output .= '<style type="text/css">';
                    $output .=  '.map'.$tag->order_image_id.' { display:none;}';
                }


                    $output .=  '.map'.$tag->order_image_id.' .map .tag_'.$tag_counter.$tag->order_image_id.' { ';
                    // $output .= 'border:1px solid #000;';
                    $output .= 'background:url("'.URL::to('/').'/assets/images/tag_hotspot_62x62.png") no-repeat;';
                    $output .= 'top:'.$tag['y1'].'px;';
                    $output .= 'left:'.$tag['x1'].'px;';
                    $output .= 'width:'.$tag['w'].'px;';
                    $output .= 'height:'.$tag['h'].'px;';

                    $output .= '}';


                   $tag_counter++;
            }
            if ($tag_counter !=1) {
                $output .= '</style>';
            }

            $tag_counter = 1;

            if ($OrderImagesPositionCount>0) {
                foreach ($OrderImagesPosition as $tag) {
                    if ($tag_counter ==1) {
                            $output.= '<div class="map'.$tag->order_image_id.'"><ul class="map">';
                    }
                    $output.=  '<li class="tag_'.$tag_counter.$tag->order_image_id.'" id="uniq'.$tag->id.'"><a  href="javascript:;"><span class="titleDs">'.$tag['comment'].' </span></a><a href="javascript:;" class="removeBtn" onclick="deletePhotoTag('.$tag->id.')">X</a></li>';


                    $tag_counter++;
                }
            } else {
                   $output.= '<div class="mapunique"><ul class="map">';
                $output.= "</ul></div>";
            }


            if ($tag_counter !=1) {
                $output.= "</ul></div>";
            }
        }


         $popDiv.= '<script type="text/javascript">
            $(".example6").fancybox({
                    onStart: function(element){
                        var jquery_element=$(element);
                       
                        $("#order_image_id").val(jquery_element.data("image_id"));
                         setTimeout(function(){
                    
                    var output=\''.$output.'\';
                    
                    $("#fancybox-content").append(output); 
                $(".map"+jquery_element.data("image_id")).show();
            }, 1000);
                    
                 
                    },
                "titlePosition"    : "outside",
                "overlayColor"      : "#000",
                "overlayOpacity"    : "0.9"
                
            }); 
        </script>';
        return $popDiv;
    }

    public function displayViewImages()
    {
        $data = Request::all();
        $order_id = $data['order_id'];
        $order_details_id = $data['order_detail_id'];
        $type = $data['type'];
        if ($type == 'before') {
            $config_path = config('app.order_images_before');
        } elseif ($type == 'after') {
            $config_path = config('app.order_images_after');
        }
        $popDiv = '';
        $images=OrderImage::where('order_id', '=', $order_id)->where('order_details_id', '=', $order_details_id)->where('type', '=', $type)->get();
        foreach ($images as $image) {
            $popDiv.='<div class="imageFrame"><img src="'.config('app.url').'/'.$config_path.$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></div>';
        }
        return $popDiv;
    }

    public function deleteImageByImageId()
    {
        $data       = Request::all();
        $image_ids  = $data["image_ids"];

        foreach($image_ids as $image)
        {
            $image_data = OrderImage::findOrFail($image);
            $image_url  = $image_data->address;
            $image_type = $image_data->type;
            $file_loc   = false;

            switch($image_type)
            {
                case "before":
                    $file_loc = config('app.order_images_before').$image_url;
                    break;

                case "during":
                    $file_loc = config('app.order_images_during').$image_url;
                    break;

                case "after":
                    $file_loc = config('app.order_images_after').$image_url;
                    break;

            }

            if ($file_loc)
            {
                unlink($file_loc);
                OrderImage::destroy($image);
            }
        }


        return "success";
    }
    public function deleteImageById()
    {
        $data = Request::all();
        $order_id = $data['order_id'];
        $order_details_id = $data['order_detail_id'];
        $filename = $data['filename'];
        $type=$data['type'];
        $delete=OrderImage::where('order_id', '=', $order_id)->where('order_details_id', '=', $order_details_id)->where('address', '=', $filename)->where('type', '=', $type)->delete();
        if ($delete) {
            if ($type=='before') {
                $destinationPath = config('app.order_images_before');   //2
                unlink($destinationPath . $filename);
            } elseif ($type=='during') {
                $destinationPath = config('app.order_images_during');   //2
                unlink($destinationPath . $filename);
            } else {
                $destinationPath = config('app.order_images_after');   //2
                unlink($destinationPath . $filename);
            }
        }
        return $delete;
    }
    public function deleteImageByAdditionalItemId()
    {
        $data = Request::all();
        $additional_service_id = $data['additional_service_id'];
        $filename = $data['filename'];
        $type=$data['type'];
        $delete=AdditionalServiceItemImage::where('additional_service_id', '=', $additional_service_id)->where('address', '=', $filename)->where('type', '=', $type)->delete();
        if ($type=="after") {
            $app_path="order_additional_images_after";
        } elseif ($type=="before") {
            $app_path="order_additional_images_before";
        } elseif ($type=="during") {
            $app_path="order_additional_images_during";
        }
        if ($delete) {
            if ($type=='before') {
                $destinationPath = config('order_additional_images_before');   //2

                unlink();
            } elseif ($type=='during') {
                $destinationPath = config('order_additional_images_during');   //2
                unlink($destinationPath . $filename);
            } else {
                $destinationPath = config('order_additional_images_after');   //2
                unlink($destinationPath . $filename);
            }
        }
        return $delete;
    }

    /**
     *
     */
    public function changeStatus()
    {

        $order_id= Request::get('order_id');
        if (Auth::user()->type_id==3 && Request::get('orderstatusid')==2) {
            $totalRequestedServices = Request::get('totalRequestedServices');
            $orderimages=OrderImage::where('order_id', '=', $order_id)->count();

            if ($orderimages < $totalRequestedServices) {
                  echo "Sorry, no photos have been uploaded. Please upload your BEFORE and AFTER photos in order to complete the work order.";
                die;
            }
        }

        $data = Order::where("id", "=", $order_id)->pluck("approved_date");

        if (empty($data)) {
              $current_data = date("m/d/Y");
               $orderdata = [
            'status'       =>   Request::get('orderstatusid') ,
            'status_class' =>   Request::get('orderstatus_class') ,
            'status_text'  =>   Request::get('orderstatus_text'),
             'approved_date' => $current_data
               ];
        } else {
            $orderdata = [
            'status'       =>   Request::get('orderstatusid') ,
            'status_class' =>   Request::get('orderstatus_class') ,
            'status_text'  =>   Request::get('orderstatus_text')
            ];
        }
        $save = Order::where('id', '=', $order_id)
        ->update($orderdata);

     //send system notifications to customer
        $orders = Order::where('id', '=', $order_id)->get();

     //If order status is going to be approved then create invoice


        if (Request::get('orderstatusid')==1) {
             $serviceType="";
            $order_details = ($orders[0]->orderDetail);
            $vendor_price=0;
            $customer_price=0;
            foreach ($order_details as $order_detail) {
                $serviceType=$order_detail->requestedService->service->title;
            }


            $emailUrl="edit-order/".$order_id;
            $EmailDATA =   $order_id. " has been marked In Process. To view work order details <a href='".URL::to($emailUrl)."'> click here:</a>
<br/>Order ID: ".$order_id." <br/>
Property Address: ".$orders[0]->maintenanceRequest->asset->property_address." <br/>
Status: ".$orders[0]->status_text." <br/>
Service Type: ".$serviceType;
      //2.    Notification to Admin for New Request
            $userDAta=User::find($orders[0]->customer->id);
            $email_data = [
            'first_name' => $userDAta->first_name,
            'last_name' => $userDAta->last_name,
            'username' => $userDAta->username,
            'email' => $userDAta->email,
            'id' =>  $order_id,
            'user_email_template'=>$EmailDATA
               ];

               Email::send($userDAta->email, 'Subject - '.$order_id .' marked In Process', 'emails.customer_registered', $email_data);
        }

        if (Request::get('orderstatusid')==4) {
              $serviceType="";
            $order_details = ($orders[0]->orderDetail);
            $vendor_price=0;
            $customer_price=0;
            foreach ($order_details as $order_detail) {
                $serviceType=$order_detail->requestedService->service->title;

                        $SpecialPriceVendor=  SpecialPrice::where('service_id', '=', $order_detail->requestedService->service->id)
                             ->where('customer_id', '=', $orders[0]->vendor->id)
                             ->where('type_id', '=', 3)
                             ->get();

                if (!empty($SpecialPriceVendor[0])) {
                    if ($order_detail->requestedService->quantity=="" || $order_detail->requestedService->quantity==0) {
                        $vendor_price+=$SpecialPriceVendor[0]->special_price;
                    } else {
                        $vendor_price+=$SpecialPriceVendor[0]->special_price*$order_detail->requestedService->quantity ;
                    }
                } else {
                    if ($order_detail->requestedService->quantity=="" || $order_detail->requestedService->quantity==0) {
                         $vendor_price+=$order_detail->requestedService->service->vendor_price ;
                    } else {
                        $vendor_price+=$order_detail->requestedService->service->vendor_price*$order_detail->requestedService->quantity ;
                    }
                }
                $SpecialPrice=  SpecialPrice::where('service_id', '=', $order_detail->requestedService->service->id)
                             ->where('customer_id', '=', $orders[0]->customer->id)
                             ->where('type_id', '=', 2)
                             ->get();
                if (!empty($SpecialPrice[0])) {
                    if ($order_detail->requestedService->quantity=="" || $order_detail->requestedService->quantity==0) {
                        $customer_price+= $SpecialPrice[0]->special_price;
                    } else {
                        $customer_price+= $SpecialPrice[0]->special_price*$order_detail->requestedService->quantity ;
                    }
                } else {
                    // $customer_price+=$order_detail->requestedService->service->customer_price;

                    if ($order_detail->requestedService->quantity=="" || $order_detail->requestedService->quantity==0) {
                        $customer_price+=$order_detail->requestedService->service->customer_price;
                    } else {
                        $customer_price+=$order_detail->requestedService->service->customer_price*$order_detail->requestedService->quantity ;
                    }
                }
            }


            Invoice::create([
            'order_id'=>$order_id,
            'total_amount'=>$vendor_price,
            'request_id'  =>$orders[0]->request_id,
            'user_id'  => $orders[0]->vendor->id,
            'user_type_id'  => $orders[0]->vendor->type_id,
            'status'=>1
                    ]);


            Invoice::create([
                                'order_id'=>$order_id,
                                'total_amount'=>   $customer_price,
                                'request_id'  =>$orders[0]->request_id,
                                'user_id'  => $orders[0]->customer->id,
                                'user_type_id'  => $orders[0]->customer->type_id,
                                'status'=>1]);
            $emailUrl="edit-order/".$order_id;
            $EmailDATA =   $order_id. " has been marked Approved. To view work order details <a href='".URL::to($emailUrl)."'> click here:</a>
<br/>Order ID: ".$order_id." <br/>
Property Address: ".$orders[0]->maintenanceRequest->asset->property_address." <br/>
Status: ".$orders[0]->status_text." <br/>
Service Type: ".$serviceType." <br/>
Completion Date: ".$orders[0]->completion_date;
      //2.    Notification to Admin for New Request
               $userDAta=User::find($orders[0]->customer->id);
               $email_data = [
               'first_name' => $userDAta->first_name,
               'last_name' => $userDAta->last_name,
               'username' => $userDAta->username,
               'email' => $userDAta->email,
               'id' =>  $order_id,
               'user_email_template'=>$EmailDATA
               ];

//               Email::send($userDAta->email, 'Subject - '.$order_id .' marked Completed', 'emails.customer_registered', $email_data);

               //Check if recurring order and schedule new order if applicable
                $order_detail = OrderDetail::where('order_id', $order_id)->get();
                if (count($order_detail) > 0)
                {
                    $od = $order_detail[0];
                    $requested_service_id = $od->requested_service_id;
                    $requested_service = RequestedService::where('id', $requested_service_id)->get();
                    if (isset($requested_service[0]->recurring) && $requested_service[0]->recurring == 1)
                    {
                        $duration = $requested_service[0]->duration;
                        $dt1 = new \DateTime($requested_service[0]->recurring_start_date);
                        $dt2 = new \DateTime($requested_service[0]->recurring_end_date);
                        $dt4 = new \DateTime();

                        $next_date = new \DateTime($requested_service[0]->completion_date);
                        $next_date->add(new \DateInterval('P'.$duration.'D'));

                        if ($dt4 >= $dt1 && $next_date <= $dt2)
                        {

                            //Schedule New Request/Order
                            $new_order = [
                                "request_id" => $orders[0]->request_id,
                                "vendor_id" => $orders[0]->vendor_id,
                                "total_amount" => $orders[0]->total_amount,
                                "status" => $orders[0]->status,
                                "status_class" => $orders[0]->status_class,
                                "status_text" => $orders[0]->status_text,
                                "customer_id" => $orders[0]->customer_id,
                                "close_property_status" => $orders[0]->close_property_status
                            ];

                            $new_id = Order::addOrder($new_order);

                            OrderDetail::addOrderDetails([
                                'requested_service_id' => $requested_service_id,
                                'order_id' => $new_id,
                                'status' => 1
                            ]);

                            //Send Work Order To Pruvan
                            $check_vendor = PruvanVendors::where('vendor_id', $orders[0]->vendor_id)->get();

                            if (count($check_vendor) > 0)
                            {
                                $pruvan_data = [
                                    'requested_service_id' => $requested_service_id,
                                    'request_id' => $orders[0]->request_id,
                                    'vendor_id' => $orders[0]->vendor_id,
                                    'customer_id' => $orders[0]->customer_id,
                                    'order_id' => $new_id

                                ];

                                $pruvan_result = Pruvan::pushWorkOrder($pruvan_data);

                            }

                        }

                    }
                }



        }

        if (Auth::user()->type_id==3) {
                //Send to admins
                $message =   "Vendor has changed the status of order to ".Request::get('orderstatus_text')." of order number ". $order_id;
                //send system notifications to admin users

                $recepient_id = User::getAdminUsersId();
            foreach ($recepient_id as $rec_id) {
                $notification = NotificationController::doNotification($rec_id, Auth::user()->id, $message, 2, [], "list-work-order-admin");
            }

                //Send to customer
                $recepient_id = $orders[0]->customer->id;

                 NotificationController::doNotification($recepient_id, Auth::user()->id, $message, 2, [], "customer-list-work-orders");
        } elseif (Auth::user()->type_id==2) {
              //Send to vendor
              $message =  "Customer has changed the status of order to ".Request::get('orderstatus_text')." of order number ". $order_id;
              $recepient_id = $orders[0]->vendor->id;

               NotificationController::doNotification($recepient_id, Auth::user()->id, $message, 2, [], "vendor-list-orders");
              //End comments
        } else {
          //Send to vendor
            $message =  "Admin has changed the status of order to ".Request::get('orderstatus_text')." of order number ". $order_id;
            $recepient_id = $orders[0]->vendor->id;

            NotificationController::doNotification($recepient_id, Auth::user()->id, $message, 2, [], "vendor-list-orders");
          //End comments
        }

        //Send to Pruvan
        $pruvan_data =
            [
                "order_id" => Request::get('order_id'),
                "orderstatusid" => Request::get('orderstatusid')
            ];

        Pruvan::updatePruvanStatus($pruvan_data);

        if (Request::get('orderstatusid')==2) {
            echo "Your work order has been completed! We will now process your order for approval. Once approved, an invoice will be generated on your behalf." ;
        } elseif (Request::get('orderstatusid')==4) {
            echo " Work order has been approved successfully! Invoice is now being generated.";
        } else {
            echo "Status has been changed to ".Request::get('orderstatus_text') ;
        }
    }

    function completionDate()
    {
        $completion_date= Request::get('completion_date');


        $order_id= Request::get('order_id');
        // $data = Order::where("id", $order_id)->pluck("vendor_submitted");
        // if (empty($data)) {
        //       $current_data = date("m/d/Y");
        //        $orderdata = [
        //     'vendor_submitted' => $current_data,
        //     'completion_date'       =>    $completion_date
        //        ];
        // } else {
            $orderdata = [
            'completion_date'  => $completion_date
            ];
        // }


        $save = Order::where('id', '=', $order_id)
        ->update($orderdata);

        echo "Completion date has been updated";
    }
    function closePropertyStatus()
    {
           $orderdata = [
            'close_property_status'       =>   Request::get('status_id')
            ];

           $save = Order::where('id', '=', Request::get('order_id'))
           ->update($orderdata);
           echo "Order Property Status has been updated";
    }

    public function updatevendorid()
    {
        $order_id=Request::get('order_id');
        $vendorid=Request::get('vendorid');
            $data['status'] = 0;
            $data['status_text'] = "New Work Order";
            $data['status_class'] = "green";
            $data['vendor_id'] =$vendorid;

        $vname=Order::where("id", $order_id)
        ->update($data);

        $check_vendor = PruvanVendors::where('vendor_id', $data['vendor_id'])->get();
        if (count($check_vendor) > 0)
        {
            Pruvan::updatePruvanVendor(["order_id" => $order_id, "vendor_id" => $vendorid]);
        }

        echo "Vender has been updated for the work order.";
    }


    public function underreviewnotes()
    {
        $order_id=Request::get('order_id');
        $vendorid=Request::get('vendorid');
        $under_review_notes=Request::get('under_review_notes');

            $data['order_id'] = $order_id;
            $data['vendor_id'] = $vendorid;
            $data['review_notes'] = $under_review_notes;


        $vname=OrderReviewNote::create($data);


        echo "Under Review note has been saved";
    }
    function photoTag()
    {
        $data = Request::all();


          $result= OrderImagesPosition::create($data);
        return $result->id;
    }

    function deleteTag()
    {
        $data = Request::all();
        $delete=OrderImagesPosition::where('id', '=', $data['imageID'])->delete();
    }

    public function statusReport()
    {
        $assets = Order::orderBy('id', 'desc')->get();

        return view('pages.admin.status-report')
        ->with([
        'assets_data' => $assets]);
    }
}
