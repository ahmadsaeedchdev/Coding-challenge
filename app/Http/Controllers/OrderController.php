<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Traits\ErrorLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use ErrorLog;

    /**
     * @param OrderService $orderService
     */
    public function __construct
    (
        private OrderService $orderService
    ){}

    /**
     * @param OrderRequest $orderRequest
     * @return JsonResponse
     */
    public function store(OrderRequest $orderRequest): JsonResponse
    {
        try {
            /**
             * @description Get the authenticated user and merge it into order payload
             */
            return $this->getResponse(
                $this->orderService->create(
                    array_merge($orderRequest->validated(), ['user_id' => $orderRequest->user()->id]),
                ),
                'Order is created'
            );
        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Controller',
                'Order',
                __function__,
                $exception->getMessage()
            ));

            return $this->getResponse(
                null,
                $exception->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
