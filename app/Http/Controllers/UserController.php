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

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
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

        $user = User::where('phone', $request->get('phone'))->first();
        if ($user === null or $user->password !== $request->get('password'))
        {
            $errorResponse = [
                "error" =>
                        ["code" => 401,
                        "message" => "Unauthorized",
                        "errors" => [
                            "phone" => ["phone or password incorrect"]
                            ]
                        ]
                    ];
            return response()->json($errorResponse, 401);
        }
        else
        {
            $data = [
                "data" => [
                    "token" => $this->getToken($user)
                ]
            ];
            return response()->json($data, 200);
        }
    }

    protected function getToken($user)
    {
        // генерируем токен
        $token = md5($user->phone . $user->password . time()); // str_random(60);

        // заносим токен в базу данных (для этого пользователя)
        $user->api_token = $token;
        $user->save();

        return $token;
    }

    public function user(Request $request)
    {
        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();
        if ($user === null)
        {
            $errorResponse = [
                "error" =>
                        ["code" => 401,
                        "message" => "Unauthorized"
                        ]
                    ];
            return response()->json($errorResponse, 401);
        }
        return response()->json($user->toArray(), 200);;
    }
}


