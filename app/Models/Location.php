<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'location_id',
        'name',
        'latitude',
        'longitude',
        'num_reviews',
        'timezone',
        'location_string',
        'photo',
        'is_blessed',
        'uploaded_date',
        'caption',
        'helpful_votes',
        'published_date',
        'user',
        'api_detail_url',
        'awards',
        'category_id',
        'location_subtype',
        'doubleclick_zone',
        'preferred_map_engine',
        'raw_ranking',
        'ranking_geo',
        'ranking_geo_id',
        'ranking_position',
        'ranking_denominator',
        'ranking_category',
        'ranking_subcategory',
        'subcategory_ranking',
        'ranking',
        'distance',
        'distance_string',
        'bearing',
        'rating',
        'is_closed',
        'open_now_text',
        'is_long_closed',
        'neighborhood_info',
        'description',
        'web_url',
        'write_review',
        'ancestors',
        'category',
        'subcategory',
        'parent_display_name',
        'is_jfy_enabled',
        'nearest_metro_station',
        'phone',
        'website',
        'email',
        'address_obj',
        'address',
        'hours',
        'is_candidate_for_contact_info_suppression',
        'subtype',
        'booking',
        'offer_group',
        'fee',
        'animal_welfare_tag',
        'tags',
        'cluster_id',
        'color_code',
    ];

    public function category(){
        return $this->belongsTo(LocationCategory::class,"category_id");
    }

    public function subCategories(){
        return $this->belongsToMany(LocationSubcategory::class,"location_location_subcategories","location_id","location_subcategory_id");
    }
}
