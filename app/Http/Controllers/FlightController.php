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
        else
        {
            $date2 = null;
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

       $flights_from = DB::table('flights as f')
                        ->join('airports as a', 'f.from_id', '=', 'a.id')
                        ->join('airports as aa', 'f.to_id', '=', 'aa.id')
                        ->where('a.iata', '=', "{$from}")
                        ->where('aa.iata', '=', "{$to}")
                        ->select(['f.id as id', 'f.flight_code as code',
                                  'a.name as from_airport', 'a.city as from_city', 'a.iata as from_iata',
                                  'f.time_from', 'f.cost',
                                  'aa.name as to_airport', 'aa.city as to_city', 'aa.iata as to_iata', 'f.time_to'])
                        ->get();

        foreach ($flights_from as $flight)
        {
            $flight_from = Flight::find($flight->id);

            array_push($data['data']['flights_to'],
                      ['flight_id' => $flight->id, 'flight_code' => $flight->code,
                      'from' => [
                          'city' => $flight->from_city, 'airport' => $flight->from_airport,
                          'iata' => $flight->from_iata, 'date' => $date1, 'time' => $flight->time_from
                        ],
                      'to' => [
                          'city' => $flight->to_city, 'airport' => $flight->to_airport,
                          'iata' => $flight->to_iata, 'date' => $date1, 'time' => $flight->time_to
                        ],
                      'cost' => $flight->cost, 'availability' =>  $flight_from->countAvaibleSeat($date1) ]);
        }

        if ($date2 !== null)
        {
            $flights_to = DB::table('flights as f')
                            ->join('airports as a', 'f.from_id', '=', 'a.id')
                            ->join('airports as aa', 'f.to_id', '=', 'aa.id')
                            ->where('a.iata', '=', "{$to}")
                            ->where('aa.iata', '=', "{$from}")
                            ->select(['f.id as id', 'f.flight_code as code',
                                      'a.name as from_airport', 'a.city as from_city', 'a.iata as from_iata',
                                      'f.time_from', 'f.cost',
                                      'aa.name as to_airport', 'aa.city as to_city', 'aa.iata as to_iata', 'f.time_to'])
                            ->get();

            foreach ($flights_to as $flight)
            {
                $flight_back = Flight::find($flight->id);

                array_push($data['data']['flights_back'],
                           ['flight_id' => $flight->id, 'flight_code' => $flight->code,
                           'from' => [
                               'city' => $flight->from_city, 'airport' => $flight->from_airport,
                               'iata' => $flight->from_iata, 'date' => $date2, 'time' => $flight->time_from
                            ],
                           'to' => [
                               'city' => $flight->to_city, 'airport' => $flight->to_airport,
                               'iata' => $flight->to_iata, 'date' => $date2, 'time' => $flight->time_to
                            ],
                           'cost' => $flight->cost, 'availability' => $flight_back->countAvaibleSeat($date2, false)]);
            }
        }
        return response()->json($data, 200);
    }
}
