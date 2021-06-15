<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone' => ['required', 'unique:users,phone', 'string'],
            'password' => ['required', 'string'],
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

        $user = User::create($request->all());
        return response()->json($user, 204);
    }
}


