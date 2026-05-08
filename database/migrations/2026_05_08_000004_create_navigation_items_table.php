<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('route_name')->nullable();
            $table->string('anchor')->nullable();
            $table->string('external_url')->nullable();
            $table->string('auth_state')->default('all');
            $table->boolean('is_cta')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_items');
    }
};
