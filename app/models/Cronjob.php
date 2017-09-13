<?php

class Cronjob extends BaseTenantModel {

    protected $table = 'cronjobs';
    protected $fillable = array('id', 'created_at', 'updated_at', 'recurring_id');


}
?>