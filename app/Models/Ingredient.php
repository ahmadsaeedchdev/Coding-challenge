<?php

namespace App\Models;

use App\Mail\StockAlert;
use App\Traits\ErrorLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Ingredient extends Model
{
    use HasFactory, ErrorLog;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'total_amount',
        'current_amount',
        'is_alert_email_sent'
    ];

    /**
     * @return bool
     * @throws \Throwable
     */
    public function isEqualOrBelowThanHalf(): bool
    {
        try {
            return $this->current_amount <= ($this->total_amount / 2);
        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Model',
                'Ingredient',
                __function__,
                $exception->getMessage()
            ));
        }
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function updateEmailAlertBit(): void
    {
        try {
            if (!$this->is_alert_email_sent) {
                $this->update(['is_alert_email_sent' => 1]);
            }
        } catch (\Throwable $exception) {
            Log::error($this->message(
                'Model',
                'Ingredient',
                __function__,
                $exception->getMessage()
            ));
        }
    }

    /**
     * @return void
     * @description Event occur when ingredient is updating  so checked if limit equal or below than total stock then send email to vendor.
     */
    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::updating(function (Ingredient $ingredient) {
            if (
                $ingredient->isDirty('current_amount')
                && $ingredient->isEqualOrBelowThanHalf()
                && (!$ingredient->is_alert_email_sent)
            ) {
                // Send email to merchant about this ingredient. First this email is queue and send throw background job.
                Mail::to('vendor@gmail.com')->send(new StockAlert($ingredient));

                // Update the email bit.
                $ingredient->updateEmailAlertBit();
            }
        });
    }

}
