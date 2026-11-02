<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const STATUSES = ['active', 'inactive', 'expired'];

    protected $fillable = [
        'name',
        'quantity',
        'description',
        'expiration_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'expiration_date' => 'date',
        ];
    }

    public function increaseQuantity(int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }

        $updated = static::query()->whereKey($this->getKey())
            ->increment('quantity', $quantity);

        if ($updated === 0) {
            return false;
        }

        $this->refresh();

        return true;
    }

    public function decreaseQuantity(int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }

        $updated = static::query()->whereKey($this->getKey())
            ->where('quantity', '>=', $quantity)
            ->decrement('quantity', $quantity);

        if ($updated === 0) {
            return false;
        }

        $this->refresh();

        return true;
    }
}
