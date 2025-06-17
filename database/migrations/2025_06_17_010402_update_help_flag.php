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
        Schema::table('player_help_flags', function (Blueprint $table) {
            $table->dropColumn('level');
            $table->foreignId('level_id')
                ->constrained('levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_help_flags', function (Blueprint $table) {
            $table->dropForeign('player_help_flags_level_id_foreign');
            $table->dropColumn('level_id');
            $table->string('level');
        });
    }
};
