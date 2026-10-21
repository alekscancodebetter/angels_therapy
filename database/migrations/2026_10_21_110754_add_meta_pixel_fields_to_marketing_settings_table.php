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
        Schema::table('marketing_settings', function (Blueprint $table) {
            $table->string('meta_pixel_id')->nullable()->after('google_ads_phone_conversion_currency');
            $table->string('meta_booking_event_name')->default('Schedule')->after('meta_pixel_id');
            $table->decimal('meta_booking_event_value', 10, 2)->nullable()->after('meta_booking_event_name');
            $table->string('meta_booking_event_currency', 3)->default('GBP')->after('meta_booking_event_value');
            $table->string('meta_phone_event_name')->default('Contact')->after('meta_booking_event_currency');
            $table->decimal('meta_phone_event_value', 10, 2)->nullable()->after('meta_phone_event_name');
            $table->string('meta_phone_event_currency', 3)->default('GBP')->after('meta_phone_event_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_settings', function (Blueprint $table) {
            $table->dropColumn([
                'meta_pixel_id',
                'meta_booking_event_name',
                'meta_booking_event_value',
                'meta_booking_event_currency',
                'meta_phone_event_name',
                'meta_phone_event_value',
                'meta_phone_event_currency',
            ]);
        });
    }
};
