<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use App\Models\Booking;

class BookingController extends Controller
{
    public function booking(Request $request)
    {
        foreach ($request['passengers'] as $passenger)
        {
            $validator = Validator::make($passenger, [
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'birth_date' => ['required', 'date_format:Y-m-d'],
                'document_number' => ['required', 'string', 'max:10']
            ]);

              if ($validator->fails())
              {
                $errors = [];

                foreach ( json_decode($validator->errors(), true) as $key => $value)
                {
                    $errors[$key] = $value;

                }
                $errorResponse = [
                    "error" =>
                            ["code" => 422,
                            "message" => "Validation error",
                            "errors" => $errors
                            ]
                        ];

                return response()->json($errorResponse, 422);
              }
        }

          // TODO проверка на свободные места
          $code =  $this->getCode();
          $booking = Booking::create(['flight_from' => $request['flight_from']['id'],
                                      'flight_back' => $request['flight_back']['id'],
                                      'date_from' => $request['flight_from']['date'],
                                      'date_back' => $request['flight_back']['date'],
                                      'code' => $code]);

         return response()->json(['data' => ['code' => $code]], 201);
    }

    protected function getCode()
    {
        return Str::random(5);
    }
}
