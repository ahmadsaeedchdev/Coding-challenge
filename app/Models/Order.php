<?php

namespace App\Models;

use App\Traits\ErrorLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory, ErrorLog;

    /**
     * @var string[]
     */
    protected $fillable = ['user_id'];



    /**
     * @return BelongsToMany
     * @throws \Throwable
     */
    public function products(): BelongsToMany
    {
        try {
            return $this->belongsToMany(
                Product::class,
                'order_product',
                'order_id',
                'product_id'
            )->withPivot('quantity');
        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Model',
                'Order',
                __function__,
                $exception->getMessage()
            ));
        }
    }

}
