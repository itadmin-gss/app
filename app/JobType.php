<?php

namespace App;

class JobType extends BaseTenantModel
{

    protected $table = 'job_types';
    protected $fillable = ['id','title','created_at','updated_at'];
}
