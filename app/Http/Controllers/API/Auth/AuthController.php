<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\Controller;
use App\Model\Auth\User;
use App\Validator\Auth\UserLoginValidator;
use App\Validator\Auth\UserRegisterValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request) : JsonResponse
    {
        $fields = [
            'name',
            'email',
            'password',
        ];

        $data = $request->only($fields);

        UserRegisterValidator::validate($data);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // todo token to event 'created'
        // todo password to event 'created'

        $user->generateToken();

        return response()->json([
            'token' => $user->token,
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request) : JsonResponse
    {
        $credentials = $request->only([
            'email',
            'password',
        ]);

        UserLoginValidator::validate($credentials);

        $user = User::where('email', '=', $credentials['email'])->first();

        if ($user instanceof User) {
            if (Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'token' => $user->token,
                ], Response::HTTP_ACCEPTED);
            }
        }

        throw new BadRequestHttpException();
    }
}
