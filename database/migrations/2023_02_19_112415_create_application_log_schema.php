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
        Schema::create('application_log', function (Blueprint $table) {
            $table->id();

            $table->string('session_id')
                  ->nullable();

            $table->string('group')
                  ->nullable();

            $table->text('description')
                  ->nullable();

            $table->longText('properties')
                  ->nullable();

            $table->morphs('causable');

            $table->morphs('relatable');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_log_schema');
    }
};
