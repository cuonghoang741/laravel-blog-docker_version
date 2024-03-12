<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('city_id')->references('id')->on('cities')->onDelete('cascade');;
            $table->bigInteger('category_id')->references('id')->on('location_categories')->onDelete('cascade');
            $table->string('location_id')->nullable();
            $table->string('name')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('num_reviews')->nullable();
            $table->string('timezone')->nullable();
            $table->string('location_string')->nullable();
            $table->text('photo')->nullable();
            $table->boolean('is_blessed')->nullable();
            $table->dateTime('uploaded_date')->nullable();
            $table->string('caption')->nullable();
            $table->string('helpful_votes')->nullable();
            $table->dateTime('published_date')->nullable();
            $table->json('user')->nullable();
            $table->string('api_detail_url')->nullable();
            $table->json('awards')->nullable();
            $table->string('location_subtype')->nullable();
            $table->string('doubleclick_zone')->nullable();
            $table->string('preferred_map_engine')->nullable();
            $table->string('raw_ranking')->nullable();
            $table->string('ranking_geo')->nullable();
            $table->string('ranking_geo_id')->nullable();
            $table->string('ranking_position')->nullable();
            $table->string('ranking_denominator')->nullable();
            $table->string('ranking_category')->nullable();
            $table->string('ranking_subcategory')->nullable();
            $table->string('subcategory_ranking')->nullable();
            $table->string('ranking')->nullable();
            $table->string('distance')->nullable();
            $table->string('distance_string')->nullable();
            $table->string('bearing')->nullable();
            $table->string('rating')->nullable();
            $table->boolean('is_closed')->nullable();
            $table->string('open_now_text')->nullable();
            $table->boolean('is_long_closed')->nullable();
            $table->json('neighborhood_info')->nullable();
            $table->text('description')->nullable();
            $table->string('web_url')->nullable();
            $table->string('write_review')->nullable();
            $table->json('ancestors')->nullable();
//            $table->json('category');
//            $table->json('subcategory');
            $table->string('parent_display_name')->nullable();
            $table->boolean('is_jfy_enabled')->nullable();
            $table->json('nearest_metro_station')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->json('address_obj')->nullable();
            $table->string('address')->nullable();
            $table->json('hours')->nullable();
            $table->boolean('is_candidate_for_contact_info_suppression')->nullable();
            $table->json('subtype')->nullable();
            $table->json('booking')->nullable();
            $table->json('offer_group')->nullable();
            $table->string('fee')->nullable();
            $table->json('animal_welfare_tag')->nullable();
            $table->json('tags')->nullable();
            $table->string('cluster_id')->nullable();
            $table->string('color_code')->nullable();
            $table->timestamps();
        });

        Schema::create('location_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('location_subcategories', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('name');
            $table->timestamps();
        });


        Schema::create('location_location_subcategories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id')->references('id')->on('location_categories')->onDelete('cascade');
            $table->bigInteger('location_subcategory_id')->references('id')->on('location_subcategories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
