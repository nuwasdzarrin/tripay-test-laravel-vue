<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['sku', 'name', 'price', 'reference'];

    public function getFiltered(array $filters): Collection
    {
        return $this->filter($filters, '', '', '')
            ->when(array_key_exists('offset', $filters), function ($q) use ($filters) {
                $q->offset($filters['offset'])->limit($filters['limit']);
            })
            ->orderBy($filters['sort'] ?? 'created_at', $filters['order'] ?? 'desc')
            ->get();
    }

    public function scopeFilter($query, array $filters, string $key, string $relation, string $column)
    {
        return $query->when(array_key_exists($key, $filters), function ($q) use ($filters, $relation, $column, $key) {
            $q->whereRelation($relation, $column, $filters[$key]);
        });
    }

    public function scopeSelectIdAndNameAsKeyValue()
    {
        return $this->query()->select('id as key', 'name as value')->get();
    }
}
