<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_number');
            $table->decimal('amount', 10, 2);
            $table->unsignedSmallInteger('month');
            $table->unsignedSmallInteger('year');
            $table->string('description', 255);

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->foreignId('category_id')->constrained();

            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
