<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class Property extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'properties';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'enabled', 'property_id'
    ];

    function details() {

        return $this->belongsTo(PropertyDetails::class, 'property_id', 'property_id');

    }

    function price() {

        return $this->belongsToMany(PropertyPrice::class, 'property_id', 'property_id');

    }

    public function getIDs()
    {

        $properties = Property::all();
        $ids = [];

        foreach ($properties as $property) {
            $ids[] = $property->property_id;
        }

        return $ids;
    }

}
