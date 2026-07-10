<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'reference', 'service_id', 'user_id',
    'customer_name', 'customer_email', 'customer_phone',
    'scheduled_at', 'duration_minutes', 'status',
    'notes', 'admin_notes', 'total_amount', 'down_payment',
    'payment_status', 'promo_code',
])]
class Appointment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Appointment $appointment): void {
            if (empty($appointment->reference)) {
                $appointment->reference = strtoupper('EM-'.Str::random(8));
            }
        });
    }

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'total_amount' => 'decimal:2',
            'down_payment' => 'decimal:2',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
