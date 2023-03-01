<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use App\Traits\ErrorLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use ErrorLog;

    /**
     * @param LoginService $loginService
     */
    public function __construct
    (
        private LoginService $loginService
    ){}

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            return $this->getResponse(
                $this->loginService->login($request->validated()),
                'User has logged In.'
            );
        } catch (\Throwable $exception) {

            Log::error($this->message(
                'Controller',
                'Login',
                __function__,
                $exception->getMessage()
            ));

            return $this->getResponse(
                null,
                $exception->getMessage(),
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
