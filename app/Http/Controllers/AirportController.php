<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function airport(Request $request)
    {
        $data = ['data' => [ 'items' => [] ]];
        if ($request->has('query'))
        {
            $query = $request['query'];
            $queryset = Airport::where('city', 'regexp', "(.*?){$query}(.*?)")
                                        ->orWhere('name', 'regexp', "(.*?){$query}(.*?)")
                                        ->orWhere('iata', 'regexp', "{$query}")
                                        ->get();

            foreach ($queryset as $record)
            {
                array_push($data['data']['items'], $record);
            }
            return response()->json($data, 200);
        }
        else
        {
            return response()->json($data, 200);
        }
    }
}
