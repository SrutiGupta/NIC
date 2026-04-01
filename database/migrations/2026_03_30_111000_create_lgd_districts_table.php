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
        Schema::create('lgd_districts', function (Blueprint $table) {
            $table->unsignedInteger('state_code');
            $table->string('state_name');
            $table->unsignedInteger('district_code')->primary();
            $table->string('district_name');
            $table->string('census_2001_code')->nullable();
            $table->string('census_2011_code')->nullable();

            $table->unique(['state_code', 'district_code']);
            $table->index('district_name');

            $table->foreign('state_code')
                ->references('state_code')
                ->on('lgd_states')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgd_districts');
    }
};
