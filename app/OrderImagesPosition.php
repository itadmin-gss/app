<?php

namespace App;

class OrderImagesPosition extends BaseTenantModel
{



    protected $table = 'order-images-positions';

    protected $fillable = ['id', 'comment','order_image_id', 'x1', 'x2','y1','y2','h','w','created_at', 'updated_at'];
}
