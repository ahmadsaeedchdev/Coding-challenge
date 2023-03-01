<?php

namespace App\Models;

use App\Traits\ErrorLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory;
    use ErrorLog;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsToMany
     * @throws \Throwable
     */
    public function ingredients(): BelongsToMany
    {
        try {
            return $this->belongsToMany(
                Ingredient::class,
                'ingredient_product',
                'product_id',
                'ingredient_id'
            )->withPivot('amount');

        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Model',
                'Product',
                __function__,
                $exception->getMessage()
            ));
        }
    }
}
