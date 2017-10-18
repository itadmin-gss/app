<?php

namespace App;

class OrderReviewNote extends BaseTenantModel
{

    protected $table = 'order_review_notes';
    protected $fillable = ['id','vendor_id','review_notes','order_id','created_at','updated_at'];
}
