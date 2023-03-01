<?php
/**
 * @author  Ahmad Saeed
 */
namespace App\Services;

use App\Models\Order;
use App\Traits\ErrorLog;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class IngredientService
{
    use ErrorLog;


    /**
     * @param Order $order
     * @return void
     * @throws \Throwable
     * @description Update Each ingredient price according to order product quantity
     */
    public function updateIngredientsStockUsingOrder(Order $order): void
    {
        try {
            $order->products->each(function ($product) {
                $product->ingredients->each(function ($ingredient) use ($product) {
                    $current_amount = $ingredient->current_amount - ($ingredient->pivot->amount * $product->pivot->quantity);

                    if ($current_amount < 0) {
                        throw new BadRequestException("{$ingredient->name} is out of stock.");
                    }

                    $ingredient->update(['current_amount' => $current_amount]);
                });
            });

        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Service',
                'Ingredient',
                __function__,
                $exception->getMessage()
            ));
        }
    }
}
