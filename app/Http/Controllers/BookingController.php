<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Airport;
use App\Models\Passanger;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Mockery\Generator\StringManipulation\Pass\Pass;

class BookingController extends Controller
{
    public function booking(Request $request)
    {
        foreach ($request['passengers'] as $passenger) {
            $validator = Validator::make($passenger, [
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'birth_date' => ['required', 'date_format:Y-m-d'],
                'document_number' => ['required', 'string', 'max:10']
            ]);

            if ($validator->fails()) {
                $errors = [];

                foreach (json_decode($validator->errors(), true) as $key => $value) {
                    $errors[$key] = $value;
                }
                $errorResponse = [
                    "error" =>
                    [
                        "code" => 422,
                        "message" => "Validation error",
                        "errors" => $errors
                    ]
                ];
                return response()->json($errorResponse, 422);
            }
        }

        $flight_from = Flight::find($request['flight_from']['id']);
        $flight_back = Flight::find($request['flight_back']['id']);

        $countAvaibleSeatFrom =   $flight_from->countAvaibleSeat($request['flight_from']['date']);
        $countAvaibleSeatBack =   $flight_back->countAvaibleSeat($request['flight_back']['date'], false);

        if ((Booking::chackPossibilityBooking($countAvaibleSeatFrom, count($request['passengers'])) !== true) ||
            (Booking::chackPossibilityBooking($countAvaibleSeatBack, count($request['passengers'])) !== true)
        ) {
            return response()->json(['data' => ['message' => 'Нет свободных мест']], 200);
        }

        $code =  $this->getCode();
        $booking = Booking::create([
            'flight_from' => $request['flight_from']['id'],
            'flight_back' => $request['flight_back']['id'],
            'date_from' => $request['flight_from']['date'],
            'date_back' => $request['flight_back']['date'],
            'code' => $code
        ]);

        foreach ($request['passengers'] as $passanger) {
            Passanger::create([
                'booking_id' => $booking->id, 'first_name' => $passanger['first_name'],
                'last_name' => $passanger['last_name'], 'birth_date' => $passanger['birth_date'],
                'document_number' => $passanger['document_number'], null, null
            ]);
        }
        return response()->json(['data' => ['code' => $code]], 201);
    }

    protected function getCode()
    {
        return Str::random(5);
    }

    public function getBookingInfo(Request $request, $code)
    {
        /* Полностью через ORM

            $flight_from = Flight::where('id', $bookingRecord->flight_from)->first();
            $airport_from_flight = $flight_from->airport_from;
            print_r($flight_from->toArray());
            print_r($airport_from_flight->toArray());

        */

        $bookingRecord  =  DB::table('bookings')->where('code', '=', $code)->first();

        $flight_from = DB::table('flights as f')->where('f.id', '=', $bookingRecord->flight_from)
            ->join('airports as a', 'f.from_id', '=', 'a.id')
            ->join('airports as aa', 'f.to_id', '=', 'aa.id')
            ->select([
                'f.id as id', 'f.flight_code as code',
                'a.name as from_airport', 'a.city as from_city', 'a.iata as from_iata',
                'f.time_from', 'f.cost',
                'aa.name as to_airport', 'aa.city as to_city', 'aa.iata as to_iata', 'f.time_to'
            ])->get();

        $flight_back = DB::table('flights as f')->where('f.id', '=', $bookingRecord->flight_back)
            ->join('airports as a', 'f.from_id', '=', 'a.id')
            ->join('airports as aa', 'f.to_id', '=', 'aa.id')
            ->select([
                'f.id as id', 'f.flight_code as code',
                'a.name as from_airport', 'a.city as from_city', 'a.iata as from_iata',
                'f.time_from', 'f.cost',
                'aa.name as to_airport', 'aa.city as to_city', 'aa.iata as to_iata', 'f.time_to'
            ])->get();

        $data = [
            'data' => [
                'code' => $code,
                'flights' => [],
                'passangers' => [],
                'cost' => '0'
            ]
        ];

        $cost = 0;
        foreach ($flight_from as $flight) {
            $flight_info = Flight::find($flight->id);
            array_push(
                $data['data']['flights'],
                [
                    'flight_id' => $flight->id, 'flight_code' => $flight->code,
                    'from' => [
                        'city' => $flight->from_city, 'airport' => $flight->from_airport,
                        'iata' => $flight->from_iata, 'date' => $bookingRecord->date_from, 'time' => $flight->time_from
                    ],
                    'to' => [
                        'city' => $flight->to_city, 'airport' => $flight->to_airport,
                        'iata' => $flight->to_iata, 'date' => $bookingRecord->date_from, 'time' => $flight->time_to
                    ],
                    'cost' => $flight->cost, 'availability' =>  $flight_info->countAvaibleSeat($bookingRecord->date_from)
                ]
            );
            $cost += $flight->cost;
        }

        foreach ($flight_back as $flight) {
            $flight_info = Flight::find($flight->id);

            array_push(
                $data['data']['flights'],
                [
                    'flight_id' => $flight->id, 'flight_code' => $flight->code,
                    'from' => [
                        'city' => $flight->from_city, 'airport' => $flight->from_airport,
                        'iata' => $flight->from_iata, 'date' => $bookingRecord->date_back, 'time' => $flight->time_from
                    ],
                    'to' => [
                        'city' => $flight->to_city, 'airport' => $flight->to_airport,
                        'iata' => $flight->to_iata, 'date' => $bookingRecord->date_back, 'time' => $flight->time_to
                    ],
                    'cost' => $flight->cost, 'availability' =>  $flight_info->countAvaibleSeat($bookingRecord->date_back)
                ]
            );
            $cost += $flight->cost;
        }

        $data['data']['cost'] = $cost;

        $passangers = Passanger::where('booking_id', $bookingRecord->id)->get();
        foreach ($passangers as $passanger) {
            array_push($data['data']['passangers'], $passanger->toArray());
        }
        return response()->json($data, 200);
    }

    public function getОccupiedSeat(Request $request, $code)
    {
        $data = [
            'data' => [
                'occupied_from' => [],
                'occupied_back' => []
            ]
        ];

        $bookingRecord = Booking::where('code', $code)->first();
        $passangers = Passanger::where('booking_id', $bookingRecord->id)->get();

        foreach ($passangers as $passanger) {
            if ($passanger->place_from !== null) {
                array_push($data['data']['occupied_from'], [
                    'passenger_id' => $passanger->id,
                    'place' =>  $passanger->place_from
                ]);
            }

            if ($passanger->place_back !== null) {
                array_push($data['data']['occupied_back'], [
                    'passenger_id' => $passanger->id,
                    'place' =>  $passanger->place_back
                ]);
            }
        }
        return response()->json($data, 200);
    }

    public function choiceSeat(Request $request, $code)
    {
        $validationError = ['error' => ['code' => 422, 'message' => 'Validation error', 'errors' => []]];
        $forbidden = ['error' => ['code' => 403, 'message' => 'Passenger does not apply to booking']];
        $seatIsOccupied = ['error' => ['code' => 422, 'message' => 'Seat is occupied']];

        if ($request->has("passenger")) {
            $passanger = $request['passenger'];
        } else {
            array_push($validationError['error']['errors'], ['passenger' => 'Обязательное поле']);
        }

        if ($request->has("seat")) {
            $seat = $request['seat'];
        } else {
            array_push($validationError['error']['errors'], ['seat' => 'Обязательное поле']);
        }

        if ($request->has("type")) {
            if ($request['type'] === 'from' || $request['type'] === 'back') {
                $type = $request['type'];
            } else {
                array_push($validationError['error']['errors'], ['type' => 'Допустимые значения: from/back']);
            }
        } else {
            array_push($validationError['error']['errors'], ['type' => 'Обязательное поле']);
        }

        if (!empty($validationError['error']['errors'])) {
            return response()->json($validationError, 422);
        }

        $bookingRecord = Booking::where('code', $code)->first();
        $searchedPassager = Passanger::where('booking_id', $bookingRecord->id)->first();

        if ($searchedPassager === null || $searchedPassager->id != $passanger) {
            return response()->json($forbidden, 403);
        }

        $occupiedSeatsFrom = Passanger::where('booking_id', $bookingRecord->id)->get('place_from')->toArray();
        $occupiedSeatsBack = Passanger::where('booking_id', $bookingRecord->id)->get('place_back')->toArray();

        $isOccupied = false;

        if ($type === 'from') {
            foreach ($occupiedSeatsFrom as $item) {
                if ($item['place_from'] === $seat) {
                    $isOccupied = true;
                    break;
                }
            }
        } else {
            foreach ($occupiedSeatsBack as $item) {
                if ($item['place_back'] === $seat) {
                    $isOccupied = true;
                    break;
                }
            }
        }

        if ($isOccupied) {
            return response()->json($seatIsOccupied, 422);
        }

        if ($type === 'from') {
            $searchedPassager->place_from = $seat;
        } else {
            $searchedPassager->place_back = $seat;
        }
        $searchedPassager->save();

        return response()->json(['data' => $searchedPassager->toArray()], 200);
    }

    public function getUserBookingInfo(Request $request)
    {
        $data = [
            'data' => [
                'items' => [
                    'code' => '',
                    'cost' => '',
                    'flights' => [],
                    'passangers' => []
                ]
            ]
        ];

        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();
        if ($user === null) {
            $errorResponse = [
                "error" =>
                [
                    "code" => 401,
                    "message" => "Unauthorized"
                ]
            ];
            return response()->json($errorResponse, 401);
        }

        $passangerRecord = Passanger::where('document_number', $user->document_number)->first();
        $bookingRecord = Booking::find($passangerRecord->booking_id);

        $data['data']['items']['code'] = $bookingRecord->code;

        $flight_from = $bookingRecord->flight_f;
        $flight_back = $bookingRecord->flight_b;

        $airport_flight_from = $flight_from->airport_from;
        $airport_flight_back = $flight_from->airport_to;

        $airport_flight_from_to = $flight_back->airport_from;
        $airport_flight_back_to = $flight_back->airport_to;

        $bookingPassangers = Passanger::where('booking_id', $bookingRecord->id)->get()->toArray();

        $data['data']['items']['cost'] = $flight_from->cost + $flight_back->cost;

        array_push($data['data']['items']['flights'], [
            'flight_id' => $flight_from->id,
            'flight_code' => $flight_from->flight_code,
            'from' => [
                'city' =>  $airport_flight_from->city,
                'airport' =>  $airport_flight_from->name,
                'iata' =>  $airport_flight_from->iata,
                'date' => $bookingRecord->date_from,
                'time' => $flight_from->time_from
            ],
            'to' => [
                'city' =>  $airport_flight_back->city,
                'airport' =>  $airport_flight_back->name,
                'iata' =>  $airport_flight_back->iata,
                'date' => $bookingRecord->date_from,
                'time' => $flight_from->time_to
            ],
            'cost' => $flight_from->cost,
            'availability' => $flight_from->countAvaibleSeat($bookingRecord->date_from)
        ]);

        array_push($data['data']['items']['flights'], [
            'flight_id' => $flight_back->id,
            'flight_code' => $flight_back->flight_code,
            'from' => [
                'city' =>  $airport_flight_from_to->city,
                'airport' =>  $airport_flight_from_to->name,
                'iata' =>  $airport_flight_from_to->iata,
                'date' => $bookingRecord->date_back,
                'time' => $flight_back->time_from
            ],
            'to' => [
                'city' =>  $airport_flight_back_to->city,
                'airport' =>  $airport_flight_back_to->name,
                'iata' =>  $airport_flight_back_to->iata,
                'date' => $bookingRecord->date_back,
                'time' => $flight_back->time_to
            ],
            'cost' => $flight_back->cost,
            'availability' => $flight_back->countAvaibleSeat($bookingRecord->date_back, false)
        ]);

        foreach ($bookingPassangers as $singlePassanger) {
            array_push($data['data']['items']['passangers'], $singlePassanger);
        }
        return response()->json($data, 200);
    }
}
