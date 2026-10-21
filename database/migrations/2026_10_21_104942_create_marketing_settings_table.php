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
        Schema::create('marketing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_mode')->default('direct');
            $table->string('google_tag_id')->nullable();
            $table->string('google_analytics_measurement_id')->nullable();
            $table->string('google_tag_manager_container_id')->nullable();
            $table->string('google_ads_tag_id')->nullable();
            $table->string('google_ads_booking_conversion_label')->nullable();
            $table->decimal('google_ads_booking_conversion_value', 10, 2)->nullable();
            $table->string('google_ads_booking_conversion_currency', 3)->default('GBP');
            $table->string('google_ads_phone_conversion_label')->nullable();
            $table->decimal('google_ads_phone_conversion_value', 10, 2)->nullable();
            $table->string('google_ads_phone_conversion_currency', 3)->default('GBP');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_settings');
    }
};
