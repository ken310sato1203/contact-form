<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'detail',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('contacts.last_name', 'like', '%' . $keyword . '%')
                ->orWhere('contacts.first_name', 'like', '%' . $keyword . '%')
                ->orWhere('contacts.email', 'like', '%' . $keyword . '%');
        }
    }

    public function scopeCategorySearch($query, $category_id)
    {
        if (!empty($category_id)) {
            $query->where('contacts.category_id', $category_id);
        }
    }

    public function scopeGenderSearch($query, $gender)
    {
        if (!empty($gender)) {
            $query->where('contacts.gender', $gender);
        }
    }

    public function scopeDateSearch($query, $date)
    {
        if (!empty($date)) {
            $query->whereDate('contacts.created_at', $date)
                ->orWhereDate('contacts.updated_at', $date);
        }
    }
}
