<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pickaxes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('display_name')->nullable(false);
            $table->integer('level')->nullable(false);
            $table->integer('durability')->nullable(false);
            $table->integer('cost')->nullable(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickaxes');
    }
};
