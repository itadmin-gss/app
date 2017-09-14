<?php

namespace App;

class Remainder extends BaseTenantModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'remainders';
    protected $fillable = ['id', 'date', 'model','type', 'remainder_text', 'user_id','request_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}
