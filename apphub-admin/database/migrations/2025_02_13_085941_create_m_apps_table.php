<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_apps', function (Blueprint $table) {
            $table->id();
            $table->string('app_name', 255);
            $table->string('app_desc', 255)->nullable();
            $table->integer('app_order')->default(0);
            $table->boolean('is_active')->nullable();
            $table->string('status')->default('active');                // active | inactive (toggled in the admin list)
            $table->string('created_by', 255)->nullable();
            $table->string('modified_by', 255)->nullable();
            $table->string('app_link', 255)->nullable();
            $table->binary('app_logo')->nullable();                      // image bytes (bytea on PostgreSQL, blob elsewhere)
            $table->unsignedBigInteger('website_type_id')->nullable()->index();
            $table->unsignedBigInteger('business_category_id')->nullable(); // legacy single category; see app_business_categories
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_apps');
    }
};
