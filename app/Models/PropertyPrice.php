<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class PropertyPrice extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_price';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id', 'start_date', 'end_date', 'week_price', 'mid_week_price'
    ];

    public function payments()
    {
        return $this->hasOne(Property::class);
    }

}
