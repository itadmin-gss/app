<?php

namespace App;


class PruvanVendors extends BaseTenantModel
{

    protected $primaryKey = 'vendor_id';
    protected $table = 'pruvan_vendors';
    protected $fillable = ['vendor_id', 'username', 'email_address'];


}
