<?php
class CustomerType extends BaseTenantModel
{

    protected $table = 'customer_types';
    protected $fillable = ['id','title','created_at','updated_at'];
}
