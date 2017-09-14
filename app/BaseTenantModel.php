<?php

use Illuminate\Database\Eloquent\Model;

class BaseTenantModel extends Model {

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        if(Session::has('swapdb')){
        	$this->setConnection(BaseTenantModel::configureConnectionByName(Session::get('swapdb')));
        }
    }

    public static function configureConnectionByName($con)
	{
	    App::make('config')->set('database.default', $con);
	}

}