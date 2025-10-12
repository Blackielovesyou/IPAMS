<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_info', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('version_code');
            $table->string('payment_number')->nullable()->after('payment_method');
            $table->string('payment_qr')->nullable()->after('payment_number');
        });
    }

    public function down(): void
    {
        Schema::table('app_info', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_number', 'payment_qr']);
        });
    }
};
