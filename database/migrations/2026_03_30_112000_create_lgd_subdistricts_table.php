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
        Schema::create('lgd_subdistricts', function (Blueprint $table) {
            $table->unsignedInteger('serial_no')->nullable();
            $table->unsignedInteger('state_code');
            $table->string('state_name');
            $table->unsignedInteger('district_code');
            $table->string('district_name');
            $table->unsignedInteger('subdistrict_code')->primary();
            $table->unsignedInteger('subdistrict_version')->nullable();
            $table->string('subdistrict_name');
            $table->string('census_2001_code')->nullable();
            $table->string('census_2011_code')->nullable();

            $table->unique(['district_code', 'subdistrict_code']);
            $table->index('subdistrict_name');

            $table->foreign('state_code')
                ->references('state_code')
                ->on('lgd_states')
                ->restrictOnDelete();

            $table->foreign(['state_code', 'district_code'])
                ->references(['state_code', 'district_code'])
                ->on('lgd_districts')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgd_subdistricts');
    }
};
