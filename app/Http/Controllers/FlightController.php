<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    public function flight(Request $request)
    {
        $data = ['data' => [ 'flights_to' => [], 'flights_back' => [] ]];
        $errorResponse = ["error" => ["code" => 422,
                                      "message" => "Validation error",
                                      "errors" => []]];

        if ($request->has('from') && Airport::where('iata', $request['from'])->first() !== null)
        {
            $from = $request['from'];
        }
        else
        {
            array_push($errorResponse['error']['errors'], ['from' => 'Обязательное поле, должно существовать']);
        }

        if ($request->has('to') && Airport::where('iata', $request['to'])->first() !== null)
        {
            $to = $request['to'];
        }
        else
        {
            array_push($errorResponse['error']['errors'], ['to' => 'Обязательное поле, должно существовать']);
        }

        if ($request->has('date1'))
        {
            if (preg_match("/\d\d\d\d-\d\d-\d\d/i", $request['date1']) === 1)
            {
                $date1 = $request['date1'];
            }
            else
            {
                array_push($errorResponse['error']['errors'], ['date1' => 'Поддерживаемый формат: YYYY-MM-DD']);
            }
        }
        else
        {
            array_push($errorResponse['error']['errors'], ['date1' => 'Обязательное поле']);
        }

        if ($request->has('date2'))
        {
            if (preg_match("/\d\d\d\d-\d\d-\d\d/i", $request['date2']) === 1)
            {
                $date2 = $request['date2'];
            }
            else
            {
                array_push($errorResponse['error']['errors'], ['date2' => 'Поддерживаемый формат: YYYY-MM-DD']);
            }
        }

        if ($request->has('passengers') && preg_match("/[1-8]/i", $request['passengers']) === 1)
        {
            $passengers = $request['passengers'];
        }
        else
        {
            array_push($errorResponse['error']['errors'], ['passengers' => 'Обязательное поле, от 1 до 8 включительно']);
        }

        if (!empty($errorResponse['error']['errors']))
        {
            return response()->json($errorResponse, 422);
        }

        echo $from . $to . $date1 . $date2 . $passengers;

       // рейсы у которых from_id (iata) = from и to_id (iata) = to
       $flisht_from_to = DB::table('flights')
       ->join('airports', 'flights.from_id', '=', 'airports.id')
       ->select('flights.*', 'airports.*')
       ->first();
       print_r($flisht_from_to);
    }
}
