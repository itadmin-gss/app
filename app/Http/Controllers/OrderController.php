<?php

namespace App\Http\Controllers;

use App\AdditionalServiceItem;
use App\AdditionalServiceItemImage;
use App\Asset;
use App\Invoice;
use App\MaintenanceRequest;
use App\Order;
use App\OrderCustomData;
use App\OrderDetail;
use App\OrderImage;
use App\OrderImagesPosition;
use App\OrderReviewNote;
use App\RequestedService;
use App\Service;
use App\SpecialPrice;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function editOrder($order_id)
    {




        if (!Auth::check()) {
            return redirect('');
        }
        $order_req = Order::where('id', '=', $order_id)->pluck('request_id');
        $services_asset_id= MaintenanceRequest::where('id', '=', $order_req)->pluck('asset_id');


        //$job_type =MaintenanceRequest::where('id','=',$order_req)->pluck('job_type');
        
        $client_id = Asset::getAssetInformationById($services_asset_id);
       // print_r($services_asset_id);
       // exit("asdl");
        $allservices = Service::getServicesByClientId($client_id->customer_type);
                // echo "<pre>";
                // print_r($allservices);
            
           $orderFLAG = Order::getOrderByID($order_id);
        
        $vendorsDATA=User::where('type_id', '=', 3)->get();
        $OrderReviewNote=OrderReviewNote::where('order_id', '=', $order_id)->get();

        if ($orderFLAG->status==0 && Auth::user()->type_id==3) {
            Order::where('id', $order_id)
            ->update(['status' => 1,'status_text' => "In-Process",'status_class'=>"warning"]);
        }
        //Show dashboard of customer
        $submitted = Request::get('submitted');
        if ($submitted) {
        } else {
            $order = Order::getOrderByID($order_id);
            
            $order_details = $order->orderDetail;
            
            $before_image_flag=OrderImage::where('order_id', '=', $order_id)->where('type', '=', 'before')->first();
            
            if ($before_image_flag) {
                $before_image=1;
            } else {
                $before_image=0;
            }

            $edit_order="edit_order";

            if ($order->bid_flag==1) {
                      $edit_order="edit_bidorder";
            }
            // $testvariable = User::where("id","=",$order->customer_id)->pluck('id');

            // $service_ids = RequestedService::where('id','=',$testvariable)->pluck('service_id');
            // $service_titles = Service::where('id','=',$service_ids)->pluck("title");
            // print_r($testvariable);
            // exit();
            
          
            $items = AdditionalServiceItem::where('order_id', '=', $order_id)->get();

            if (OrderCustomData::where('order_id', '=', $order_id)->count() > 0) {
                $customData = OrderCustomData::where('order_id', '=', $order_id)->get();
            } else {
                $customData[1] = "lol";
            }
            

                // print_r($edit_order);
            //$allservices = Service::getAllServices();
            return view('common.'. $edit_order)
            ->with('order', $order)
            ->with('order_details', $order_details)
            ->with('before_image', $before_image)
            ->with('vendorsDATA', $vendorsDATA)
            ->with('OrderReviewNote', $OrderReviewNote)
            ->with('items', $items)
            ->with('allservices', $allservices)
            ->with('customData', $customData);
        }
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

        $before_image_flag=OrderImage::where('order_id', '=', $order_id)->first();
         $view_order="view_order";
        if ($order->bid_flag==1) {
                    $view_order="view_bidorder";
        }
        return view('common.'.$view_order)->with('order', $order)->with('order_details', $order_details)
        ->with('message', $view_message);
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

                     $output .= 'background:url("'.URL::to('/').'/public/assets/images/tag_hotspot_62x62.png") no-repeat;';

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

                     $output .= 'background:url("'.URL::to('/').'/public/assets/images/tag_hotspot_62x62.png") no-repeat;';

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
            //           $output .= 'background:url("'.URL::to('/').'/public/assets/images/tag_hotspot_62x62.png") no-repeat;';
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

        $images=OrderImage::where('order_id', '=', $order_id)->where('order_details_id', '=', $order_details_id)->where('type', '=', $type)->get();
        
      
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
                    $output .= 'background:url("'.URL::to('/').'/public/assets/images/tag_hotspot_62x62.png") no-repeat;';
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

               Email::send($userDAta->email, 'Subject - '.$order_id .' marked Completed', 'emails.customer_registered', $email_data);
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
        $data = Order::where("id", "=", $order_id)->pluck("vendor_submitted");
        if (empty($data)) {
              $current_data = date("m/d/Y");
               $orderdata = [
            'vendor_submitted' => $current_data,
            'completion_date'       =>    $completion_date
               ];
        } else {
            $orderdata = [
            'completion_date'  => $completion_date
            ];
        }
       

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
