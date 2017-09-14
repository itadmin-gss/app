<?php
class JobType extends BaseTenantModel
{

    protected $table = 'job_types';
    protected $fillable = array('id','title','created_at','updated_at');
}
