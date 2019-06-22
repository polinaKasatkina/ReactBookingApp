<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class BookingPayment extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_payment';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id', 'stripe_id', 'amount'
    ];

    public function getPaymentDate($booking_id)
    {
        if ($payment = $this->where('booking_id', $booking_id)->first()) {
            return $payment->created_at;
        } else {
            return false;
        }
    }
}
