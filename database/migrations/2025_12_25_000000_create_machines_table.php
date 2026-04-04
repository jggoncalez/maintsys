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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('name');
            $table->string('model');
            $table->string('location');
            $table->enum('status', ['operational', 'maintenance', 'critical', 'offline'])->default('operational');
            $table->date('installed_at');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('last_reading_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
