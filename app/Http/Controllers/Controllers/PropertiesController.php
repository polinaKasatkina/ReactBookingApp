<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\PropertyPrice;

class PropertiesController extends Controller
{
    public function propertiesList()
    {

        $properties = Property::all();

        return response()->json($properties);
    }

    public function getPropertiesById(Request $request)
    {

        $result = [
            'properties' => [],
            'totalPrice' => 0
        ];
        $totalPrice = 0;
        $data = json_decode(request()->getContent(), true);

        foreach ($data['propertiesIds'] as $property) {

            $checkInDate = str_replace('/', '.', '11/11/2019'); // TODO save form details in state and push to fetch

            $price = PropertyPrice::where('property_id', '=', $property)
                ->where('start_date', '<', date('Y-m-d', strtotime($checkInDate)))
                ->where('end_date', '>', date('Y-m-d', strtotime($checkInDate)))
                ->first();


            if ($price) {
                switch (4) { // TODO save form details in state and push to fetch
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

        $result['totalPrice'] = $totalPrice;

        foreach ($data['propertiesIds'] as $property_id) {
            $result['properties'][]  = Property::where('property_id', $property_id)->first();
        }

        return response()->json($result);
    }

}
