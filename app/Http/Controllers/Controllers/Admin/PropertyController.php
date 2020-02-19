<?php

namespace App\Http\Controllers\Admin;

use App\Models\Property;
use App\Models\PropertyDetails;
use App\Models\PropertyPrice;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $properties = Property::all();

        return view('admin.property.list', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function parseProperties()
    {

        // parser
        $returned_content = $this->get_data("https://api.supercontrol.co.uk/xml/filter3.asp?siteID=" . env('SUPERCONTROL_SITE_ID') . "&propertycode_only=1");

        $properties = $this->getResults($returned_content);

        foreach ($properties as $property) {

            $prices = $this->getPrices($property['property_id']);

            $content = $this->get_data("https://api.supercontrol.co.uk/xml/property_xml.asp?id=" . $property['property_id'] . "&siteID=" . env('SUPERCONTROL_SITE_ID'));
            $propertyDetails = $this->getPropertyDetails($content, $property['property_id']);


            $propertyObj = Property::where('property_id', $property['property_id'])->get();

            if (!$propertyObj->isEmpty()) {

                Property::where('property_id', $property['property_id'])
                    ->update([
                        'name' => $propertyDetails['name'],
                        'enabled' => $property['enabled']
                    ]);


                PropertyDetails::where('property_id', $property['property_id'])
                ->update([
                    'property_id' => $property['property_id'],
                    'capacity_adults' => $propertyDetails['capacity_adults'],
                    'capacity_children' => $propertyDetails['capacity_children'],
                    'capacity_infants' => $propertyDetails['capacity_infants'],
                    'bedrooms' => $propertyDetails['bedrooms'],
                    'bathrooms' => $propertyDetails['bathrooms'],
                    'description' => $propertyDetails['description'],
                    'deposit' => $propertyDetails['deposit'],
                    'img' => $this->getImgSrc($propertyDetails['img'])
                ]);

                if (!empty($prices)) {

                    PropertyPrice::where('property_id', $property['property_id'])->delete();
                }


            } else {


                Property::create([
                    'name' => $propertyDetails['name'],
                    'enabled' => $property['enabled'],
                    'property_id' => $property['property_id']
                ]);

                PropertyDetails::create([
                    'property_id' => $property['property_id'],
                    'capacity_adults' => $propertyDetails['capacity_adults'],
                    'capacity_children' => $propertyDetails['capacity_children'],
                    'capacity_infants' => $propertyDetails['capacity_infants'],
                    'bedrooms' => $propertyDetails['bedrooms'],
                    'bathrooms' => $propertyDetails['bathrooms'],
                    'description' => $propertyDetails['description'],
                    'deposit' => $propertyDetails['deposit'],
                    'img' => $this->getImgSrc($propertyDetails['img'])
                ]);

            }

            if (!empty($prices)) {
                foreach ($prices as $price) {
                    PropertyPrice::create([
                        'property_id' => $property['property_id'],
                        'start_date' => $price['start_date'],
                        'end_date' => $price['end_date'],
                        'week_price' => $price['week_price'],
                        'mid_week_price' => $price['mid_week_price']
                    ]);
                }
            }


        }

        return redirect('/');

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


    private function getResults($data)
    {

        $xml = simplexml_load_string($data, null
            , LIBXML_NOCDATA);

        $result = [];

        foreach ($xml as $value) {

            if ($value->enabled == 0) continue;


            $result[] = [
                'property_id' => (integer)$value->propertycode,
                'enabled'     => (integer)$value->enabled
            ];

        }

        return $result;

    }

    private function getPropertyDetails($data, $property_id)
    {
        $xml = simplexml_load_string($data);

        $result = [
            'name' => $xml->property->propertyname ? $this->clearString($xml->property->propertyname) : '',  //str_replace(array('<![CDATA[', ']]>'), array('',''), $xml->property->propertyname)
            'capacity_adults' => $xml->property->capacity ? (int)$xml->property->capacity->adults : 0,
            'capacity_children' => $xml->property->capacity ? (int)$xml->property->capacity->children : 0,
            'capacity_infants' => $xml->property->capacity ? (int)$xml->property->capacity->infants : 0,
            'bedrooms' => $xml->property->bedrooms_new ? (int)$xml->property->bedrooms_new : 0,
            'bathrooms' => $xml->property->bathrooms_new ? (int)$xml->property->bathrooms_new : 0,
            'description' => $xml->property->shortdescription ? $this->clearString($xml->property->shortdescription) : '',
            'deposit' => $xml->property->deposit ? $this->clearString($xml->property->deposit) : '',
            'img' => $xml->property->photos ? $this->clearString($xml->property->photos->img->main) : ''
        ];

        return $result;
    }


    private function clearString($string)
    {
        return str_replace(array('<![CDATA[', ']]>'), array('',''), $string);
    }

    private function getImgSrc($img)
    {

        preg_match_all( '@src="([^"]+)"@' , $img, $match );

        $src = array_pop($match);

        return !empty($src) ? $src[0] : $img;
    }

    public function getPrices($property_id)
    {
        $returned_content = $this->get_data("https://api.supercontrol.co.uk/tariff/all_price.asp?cottageID=" . $property_id . "&siteID=" . env('SUPERCONTROL_SITE_ID'));

        $prices = $this->getPropertyPrices($returned_content);

        return $prices;
    }

    private function getPropertyPrices($data)
    {
        $xml = simplexml_load_string($data);
        $result = [];

        foreach ($xml->prices->price as $priceData)
        {

            $result[] = [
                'start_date'      => date('Y-m-d', strtotime($priceData->startdate)),
                'end_date'        => date('Y-m-d', strtotime($priceData->enddate)),
                'week_price'      => (string)$priceData->weekrate,
                'mid_week_price'  => (string)$priceData->sb_midweek_4
            ];

        }

        return $result;
    }
}
