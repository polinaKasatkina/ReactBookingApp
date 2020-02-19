<?php

namespace App\Helpers;

use App\Models\PropertyPrice;

class PriceHelper {

    static function getPropertyPrice($property_id, $booking)
    {
        $fullPrice = 0;

        $price = \App\Models\PropertyPrice::where('property_id', '=', $property_id)
            ->where('start_date', '<', $booking->start_date)
            ->where('end_date', '>', $booking->start_date)
            ->first();

        if ($price) {
            switch ($booking->holiday_type) {
                case '3';
                case '4':
                    $fullPrice = (float)str_replace(',', '', $price->mid_week_price);
                    break;
                case '7':
                    $fullPrice = (float)str_replace(',', '', $price->week_price);
                    break;
                case '14':
                    $fullPrice = ((float)str_replace(',', '', $price->week_price) * 2);
                    break;
            }
        }

        return $fullPrice;
    }

    static function getTotalPrice($booking_request, $format = true)
    {

        $totalPrice = 0;

        $properties = isset($booking_request->productIDs) ? $booking_request->productIDs : json_decode($booking_request->property_ids);

        foreach ($properties as $property) {

            $checkInDate = isset($booking_request->checkIn) ? $booking_request->checkIn : $booking_request->start_date;
            $checkInDate = str_replace('/', '.', $checkInDate);

            $price = PropertyPrice::where('property_id', '=', $property)
                ->where('start_date', '<', date('Y-m-d', strtotime($checkInDate)))
                ->where('end_date', '>', date('Y-m-d', strtotime($checkInDate)))
                ->first();


            if ($price) {
                switch ($booking_request->holiday_type) {
                    case '3':
                        $totalPrice += (float)str_replace(',', '', $price->mid_week_price);
                        break;
                    case '4':
                        $totalPrice += (float)str_replace(',', '', $price->mid_week_price);
                        break;
                    case '7':
                        $totalPrice += (float)str_replace(',', '', $price->week_price);
                        break;
                    case '14':
                        $totalPrice += (((float)str_replace(',', '', $price->week_price)) * 2);
                        break;
                }

            }


        }

        return $format ? static::formatPrice($totalPrice) : $totalPrice;

    }


    static function formatPrice($price)
    {

        $price = number_format((float)$price, 2, '.', ',');

        if (preg_match('/.00$/', $price)) {
            $price = str_replace('.00', '', $price);
        }


        return $price;
    }


}
