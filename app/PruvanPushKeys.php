<?php

namespace App;


class PruvanPushKeys extends BaseTenantModel
{

    protected $primaryKey = 'vendor_id';
    protected $table = 'pruvan_push_keys';
    protected $fillable = ['vendor_id', 'pushkey', 'application'];


}
