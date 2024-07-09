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
        Schema::table('services', function (Blueprint $table) {
            $table->double('value')->after('name')->nullable();
            $table->double('titular_value')->after('name')->nullable();
            $table->double('dependent_value')->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('value');
            $table->dropColumn('titular_value');
            $table->dropColumn('dependent_value');
        });
    }
};
