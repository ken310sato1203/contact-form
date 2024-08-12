<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function scopeCategorySearch($query, $category_id)
    {
        if (!empty($category_id)) {
            $query->where('id', $category_id);
        }
    }
}
