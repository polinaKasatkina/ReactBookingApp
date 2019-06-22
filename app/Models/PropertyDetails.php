<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class PropertyDetails extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_details';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id', 'capacity_adults', 'capacity_children', 'capacity_infants', 'bedrooms', 'bathrooms', 'description', 'deposit', 'img'
    ];

    public function payments()
    {
        return $this->hasOne(Property::class);
    }

}
