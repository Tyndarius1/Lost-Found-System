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
         Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // <<<<<< THIS
            $table->enum('type', ['lost', 'found']);
            $table->string('item_name');
            $table->string('category');
            $table->text('description');
            $table->string('location');
            $table->date('date');
            $table->string('image')->nullable();
            $table->enum('status', ['open', 'claimed', 'returned'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
