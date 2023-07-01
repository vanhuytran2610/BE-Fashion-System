<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchTrait
{
    public function scopeSearch($query, $searchTerm)
    {
        if ($searchTerm) {
            $query->where(function (Builder $query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%$searchTerm%")
                    ->orWhere('description', 'LIKE', "%$searchTerm%")
                    ->orWhereHas('category', function (Builder $query) use ($searchTerm) {
                        $query->where('category', 'LIKE', "%$searchTerm%");
                    }, '>', 0) // Add this condition to ensure at least one related category exists
                    ->orWhereHas('color', function (Builder $query) use ($searchTerm) {
                        $query->where('color', 'LIKE', "%$searchTerm%");
                    }, '>', 0); // Add this condition to ensure at least one related color exists
            });
        }

        return $query;
    }
}
