<?php



class OrderImage extends BaseTenantModel
{



    protected $table = 'order_images';

    protected $fillable = ['id', 'order_id', 'order_details_id', 'type','address','created_at', 'updated_at'];

    

    public function orderDetail()
    {

        return $this->belongsTo('OrderDetail', 'order_details_id');
    }

    

    public function order()
    {

        return $this->belongsTo('Order', 'order_id');
    }

    

    public static function createImage($data)
    {

        

//        $data['address'] = 'Address over herer';

//        $data['order_id'] = 1;

//        $data['order_details_id'] = 1;

        $image = self::create($data);

        return $image;
    }

    

//    public function requestedService($order_id,$order_detail_id,$filename) {

//        self::where('order_id', '=', $order_id)->where('order_detail_id', '=', $order_detail_id)->where('filename', '=', $filename)->delete();

//    }
}
