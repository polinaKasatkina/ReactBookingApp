<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class Emails extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_log';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
//        'name', 'enabled', 'property_id'
    ];

}
