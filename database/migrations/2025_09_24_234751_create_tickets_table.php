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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->text('description');
            $table->integer('priority_id')->nullable(false);
            $table->integer('category_id')->nullable(false);
            $table->integer('assigned_to_id')->nullable(true);
            $table->integer('assigned_by')->nullable(true);
            $table->enum('status', ['open', 'assigned', 'closed'])
            ->default('open');
            $table->timestamp('resolved_at')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
