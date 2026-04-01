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
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->unsignedInteger('state_code')->nullable()->after('phone');
            $table->unsignedInteger('district_code')->nullable()->after('state_code');
            $table->unsignedInteger('subdistrict_code')->nullable()->after('district_code');
            $table->unsignedInteger('block_code')->nullable()->after('subdistrict_code');

            $table->index('state_code');
            $table->index('district_code');
            $table->index('subdistrict_code');
            $table->index('block_code');

            $table->foreign('state_code')
                ->references('state_code')
                ->on('lgd_states')
                ->nullOnDelete();

            $table->foreign(['state_code', 'district_code'])
                ->references(['state_code', 'district_code'])
                ->on('lgd_districts')
                ->nullOnDelete();

            $table->foreign(['district_code', 'subdistrict_code'])
                ->references(['district_code', 'subdistrict_code'])
                ->on('lgd_subdistricts')
                ->nullOnDelete();

            $table->foreign(['district_code', 'block_code'])
                ->references(['district_code', 'block_code'])
                ->on('lgd_blocks')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropForeign(['state_code']);
            $table->dropForeign(['state_code', 'district_code']);
            $table->dropForeign(['district_code', 'subdistrict_code']);
            $table->dropForeign(['district_code', 'block_code']);

            $table->dropColumn(['state_code', 'district_code', 'subdistrict_code', 'block_code']);
        });
    }
};
