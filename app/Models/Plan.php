<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "id",
        "image_url",
        "json_data",
        "json_data_result",
        "author_id",
    ];

    public function city(): HasOne
    {
        return $this->hasOne(Plan::class, 'city_id');
    }
}
