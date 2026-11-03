<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_business_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('business_category_id');
            $table->primary(['app_id', 'business_category_id']);
        });

        Schema::create('password_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_histories');
        Schema::dropIfExists('app_business_categories');
    }
};
