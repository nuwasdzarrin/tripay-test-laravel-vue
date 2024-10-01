<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'tripay_reference', 'buyer_email', 'buyer_phone', 'raw_response'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFiltered(array $filters, array $appends = []): Collection
    {
        return $this->filter($filters, '', '', '')
            ->when(array_key_exists('offset', $filters), function ($q) use ($filters) {
                $q->offset($filters['offset'])->limit($filters['limit']);
            })
            ->orderBy($filters['sort'] ?? 'created_at', $filters['order'] ?? 'desc')
            ->get()->append($appends);
    }

    public function scopeFilter($query, array $filters, string $key, string $relation, string $column)
    {
        return $query->when(array_key_exists($key, $filters), function ($q) use ($filters, $relation, $column, $key) {
            $q->whereRelation($relation, $column, $filters[$key]);
        });
    }

    public function getProductNameAttribute() {
        return $this->product->name;
    }
}
