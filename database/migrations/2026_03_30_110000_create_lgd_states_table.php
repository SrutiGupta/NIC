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
        Schema::create('lgd_states', function (Blueprint $table) {
            $table->unsignedInteger('serial_no')->nullable();
            $table->unsignedInteger('state_code')->primary();
            $table->unsignedInteger('state_version')->nullable();
            $table->string('state_name');
            $table->string('state_name_repeat')->nullable();
            $table->string('census_2001_code')->nullable();
            $table->string('census_2011_code')->nullable();
            $table->string('state_or_ut', 2)->nullable();

            $table->index('state_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgd_states');
    }
};
