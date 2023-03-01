<?php
/**
 * @author  Ahmad Saeed
 */
namespace App\Repositories;

use App\Models\User;
use App\Traits\ErrorLog;
use Illuminate\Support\Facades\Log;

class UserRepository
{
    use ErrorLog;

    /**
     * @param User $model
     */
    public function __construct
    (
        private User $model
    ){}

    /**
     * @param string $attribute
     * @param string|int $value
     * @param string $operator
     * @return User|null
     */
    public function firstWhere(string $attribute, string|int $value, string $operator = '='): ?User
    {
        try {
            return $this->model->where($attribute, $operator, $value)->first();
        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Repository',
                'Order',
                __function__,
                $exception->getMessage()
            ));
        }
    }
}
