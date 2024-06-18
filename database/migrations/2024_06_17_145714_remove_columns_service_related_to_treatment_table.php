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
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropForeign('treatments_service_id_foreign');
            $table->dropIndex('treatments_service_id_foreign');
            $table->dropColumn('service_id');
            $table->dropColumn('value');
            $table->dropColumn('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->double('value')->nullable();
            $table->double('quantity')->nullable();
        });
    }
};
