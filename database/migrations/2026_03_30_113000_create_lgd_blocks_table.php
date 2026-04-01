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
        Schema::create('lgd_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('serial_no')->nullable();
            $table->unsignedInteger('state_code');
            $table->string('state_name');
            $table->unsignedInteger('district_code');
            $table->string('district_name');
            $table->unsignedInteger('block_code');
            $table->unsignedInteger('block_version')->nullable();
            $table->string('block_name');
            $table->string('block_name_repeat')->nullable();

            $table->unique(['district_code', 'block_code']);
            $table->index('block_code');
            $table->index('block_name');

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
        Schema::dropIfExists('lgd_blocks');
    }
};
