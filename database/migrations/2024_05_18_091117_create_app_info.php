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
        Schema::create('app_info', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('app_name')->nullable();
            $table->string('banner')->nullable();
            $table->longText('mission_vission')->nullable();
            $table->longText('guidelines')->nullable();
            $table->longText('terms_and_condition')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->longText('about_us')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->integer('version_code')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_info');
    }
};
