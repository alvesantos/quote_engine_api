<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travelers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('birth_date');
            $table->integer('age');
            $table->decimal('subtotal', 10, 2);
            $table->json('addons');
            $table->json('addons_allowed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travelers');
    }
};