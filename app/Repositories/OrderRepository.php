<?php
/**
 * @author  Ahmad Saeed
 */
namespace App\Repositories;

use App\Models\Order;
use App\Traits\ErrorLog;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    use ErrorLog;

    /**
     * @param Order $model
     */
    public function __construct
    (
        private Order $model,
    ){}

    /**
     * @param array $data
     * @return Order
     * @throws \Throwable
     */
    public function create(array $data): Order
    {
        try {
            return $this->model->create($data);
        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Repository',
                'Order',
                __function__,
                $exception->getMessage()
            ));
            throw $exception;
        }
    }
}
