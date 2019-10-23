<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{


    public function index()
    {
        $properties = Property::all();

//        return view('index', compact('propertiesObj'));
        return response()->json($properties);
    }

    public function getUserInfo()
    {
        if (Auth::check()) {
            return response()->json(Auth::user());
        } else {
            return response()->json([]);
        }
    }


    public function searchAvailableProperty(Request $request)
    {



        if (isset($_COOKIE['booking_request'])) {
            unset($_COOKIE['booking_request']);
            setcookie('booking_request', null, -1, '/');
        }

//        $request->validate([
//            'checkin' => 'required',
//            'checkout' => 'required'
//        ]);

        $properties = [];

//        $propertiesObj = Property::all();

        $propertiesArr = (new Property())->getIDs();

        $cottage = $request->cottage == 'all' ? $propertiesArr : $request->cottage;
        $checkIn = $request->checkin;
        $checkOut = $request->checkout;
        $adults = $request->adults;
        $children = $request->children;
        $infants = $request->infants;

        $persons = $adults + $children;

        if (is_array($cottage)) { // If user choose all cottages

            foreach ($cottage as $id) {

                $returned_content = $this->get_data("https://api.supercontrol.co.uk/xml/property_avail.asp?siteID=" . env('STRIPE_SECRET') . "&propertycode={$id}&startdate={$checkIn}&enddate={$checkOut}&basic_details=1&sleeps={$persons}");
                $properties[] = $this->getResults($returned_content, $id);
            }

        } else {
            $returned_content = $this->get_data("https://api.supercontrol.co.uk/xml/property_avail.asp?siteID=" . env('STRIPE_SECRET') . "&propertycode={$cottage}&startdate={$checkIn}&enddate={$checkOut}&basic_details=1&sleeps={$persons}");

            $properties[] = $this->getResults($returned_content, $cottage);
        }


        $sumCapacity = 0;
        $sumInfants = 0;

        foreach ($properties as $key => $items) {
            foreach ($items as $property_id => $item) {

                if (!$item['available']) {
                    unset($properties[$key]);
                    continue;
                }

                $propertyDetails = Property::where('property_id', $property_id)->first();


                $sumCapacity += $propertyDetails->details->capacity_adults + $propertyDetails->details->capacity_children;
                $sumInfants += $propertyDetails->details->capacity_infants;
            }

        }


        if ($sumCapacity < $persons || $sumInfants < $infants)
        {
            $properties = [];
        }

        if (empty($properties)) {
            Log::info('[' . date('Y-m-d H:i:s') . '] SearchController:searchAvailableProperty::
                No available properties on dates ' . $request->checkin . ' - ' . $request->checkout .
                ' or presons sum: Adults - ' . $adults . ', Children - ' . $children . ', Infants - ' . $infants );
        }


        $urls = [
            '356234' => 'https://www.uppercourt.co.uk/accommodation/coach-house/',
            '356882' => 'https://www.uppercourt.co.uk/accommodation/courtyard-cottage/',
            '357752' => 'https://www.uppercourt.co.uk/accommodation/stables/',
            '357944' => 'https://www.uppercourt.co.uk/accommodation/the-dovecote/'
        ];

        $propertiesObj = [];

        if (!empty($properties)) {
            foreach ($properties as $items) {

                foreach ($items as $property_id => $item) {

                    $itemPrice = '';

                    $propertyDetails = Property::where('property_id', $property_id)->first();

                    $checkInDate = str_replace('/', '.', $request->checkin);
                    $price = PropertyPrice::where('property_id', '=', $property_id)
                        ->where('start_date', '<', date('Y-m-d', strtotime($checkInDate)))
                        ->where('end_date', '>', date('Y-m-d', strtotime($checkInDate)))
                        ->first();
                    if ($price) {
                        switch (request('holiday_type')) {
                            case '3':
                                $itemPrice = $price->mid_week_price;
                                break;
                            case '4':
                                $itemPrice = $price->mid_week_price;
                                break;
                            case '7':
                                $itemPrice = $price->week_price;
                                break;
                            case '14':
                                $itemPrice = (((float)str_replace(',', '', $price->week_price)) * 2);
                                break;
                        }
                    }

                    $propertiesObj[] = [
                        'id' => $property_id,
                        'name' => $propertyDetails->name,
                        'img' => $propertyDetails->details->img,
                        'bedrooms' => $propertyDetails->details->bedrooms,
                        'bathrooms' => $propertyDetails->details->bathrooms,
                        'adults' => $propertyDetails->details->capacity_adults,
                        'children' => $propertyDetails->details->capacity_children,
                        'infants' => $propertyDetails->details->capacity_infants,
                        'description' => $propertyDetails->details->description,
                        'url' => isset($urls[$property_id]) ? $urls[$property_id] : '',
                        'price'  => $itemPrice
                    ];
                }
            }
        }

        return response()->json(compact('propertiesObj'));


    }

    private function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function getResults($data, $cottage_id)
    {
        $xml = simplexml_load_string($data);

        $result = [];
        $statuses = [
            'availability' => [],
            'allowedarrival' => ''
        ];


        for ($i = 0; $i < count($xml->dates->date); $i++) {

            foreach ($xml->dates->date[$i]->attributes() as $a => $b) {

                $statuses['availability'][] = (string)$xml->dates->date[$i]->attributes()->status;

                if ((bool)$xml->dates->date[$i]->attributes()->allowedarrival) {

                    if (!empty($statuses['allowedarrival'])) { // check most recent date

                        $statuses['allowedarrival'] = strtotime($statuses['allowedarrival']) < strtotime((string)$xml->dates->date[$i]) ? $statuses['allowedarrival'] : (string)$xml->dates->date[$i];

                    } else {
                        $statuses['allowedarrival'] = $xml->dates->date[$i];
                    }
                }
            }
        }



        $result = [
            $cottage_id => [
                'name' => '',
                'available' => in_array('Booked', $statuses['availability']) ? false : true,
                'allowedarrival' => $statuses['allowedarrival']
            ]
        ];

        return $result;

    }

}
