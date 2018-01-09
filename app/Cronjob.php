<?php

namespace App;

class Cronjob extends BaseTenantModel
{

    protected $table = 'cronjobs';
    protected $fillable = ['id', 'created_at', 'updated_at', 'recurring_id'];
}
