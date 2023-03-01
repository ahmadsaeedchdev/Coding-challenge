<?php
/**
 *
 */
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ErrorLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginService
{
    use ErrorLog;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct
    (
        private UserRepository $userRepository
    )
    {
    }

    /**
     * @param array $data
     * @return User|null
     */
    public function login(array $data): ?User
    {
        try {
            $user = $this->userRepository->firstWhere('email', $data['email']);

            if ($user && (Hash::check($data['password'], $user->getAuthPassword()))) {
                $user->token = $user->createToken('user token')->plainTextToken;
                return $user;
            }

            throw new \Exception('Invalid Credentials.');
        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Service',
                'Login',
                __function__,
                $exception->getMessage()
            ));
            throw $exception;
        }
    }

}
