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
        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->string('status', 30)->default('new')->after('message');
            $table->timestamp('handled_at')->nullable()->after('status');
            $table->foreignId('customer_id')->nullable()->after('handled_at')->constrained()->nullOnDelete();
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropColumn(['status', 'handled_at', 'customer_id']);
        });
    }
};
