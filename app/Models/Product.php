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

    public function adjustQuantity(int $adjustment): bool
    {
        if ($adjustment === 0) {
            return false;
        }

        $query = static::query()->whereKey($this->getKey());

        if ($adjustment < 0) {
            $updated = $query->where('quantity', '>=', abs($adjustment))
                ->decrement('quantity', abs($adjustment));
        } else {
            $updated = $query->increment('quantity', $adjustment);
        }

        if ($updated === 0) {
            return false;
        }

        $this->refresh();

        return true;
    }
}
