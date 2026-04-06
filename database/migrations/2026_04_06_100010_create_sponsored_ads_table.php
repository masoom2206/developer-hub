<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsored_ads', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('banner_url');
            $table->string('target_url');
            $table->enum('placement', ['header', 'sidebar', 'in-article']);
            $table->date('starts_at');
            $table->date('ends_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsored_ads');
    }
};
