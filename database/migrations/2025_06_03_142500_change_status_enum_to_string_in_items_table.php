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
        // 🔥 convert ENUM to VARCHAR(255)
        DB::statement("ALTER TABLE items MODIFY status VARCHAR(255) NOT NULL");
    }

    public function down(): void
    {
        // Optional: revert ENUM if needed
    }
};
