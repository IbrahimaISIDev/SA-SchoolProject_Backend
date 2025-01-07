<?php

namespace App\Support\Traits;

trait Filtrable
{
    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $field => $value) {
            if (method_exists($this, 'scope' . ucfirst($field))) {
                $query->{$field}($value);
            } elseif (!empty($value)) {
                $query->where($field, $value);
            }
        }
        return $query;
    }
}