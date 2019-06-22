<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class Booking extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookings';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_ids', 'start_date', 'end_date', 'adults', 'children', 'arrival_time', 'departure_time', 'options', 'notes', 'user_id', 'infants', 'pet', 'holiday_type', 'status'
    ];

    public function payments()
    {
        return $this->hasOne(BookingPayment::class);
    }

}
