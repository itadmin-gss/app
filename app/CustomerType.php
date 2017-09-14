<?php
class CustomerType extends BaseTenantModel {

    protected $table = 'customer_types';
    protected $fillable = array('id','title','created_at','updated_at');
}
?>