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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("city_ascii");
            $table->float("lat");
            $table->float("lng");
            $table->string("country");
            $table->string("iso2");
            $table->string("iso3");
            $table->string("admin_name");
            $table->string("capital");
            $table->integer("population");
            $table->bigInteger("trip_advisor_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
