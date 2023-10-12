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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('picture')->nullable();
            $table->string('address')->nullable();
            $table->string('phone');
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['Pending', 'inReview', 'Active'])->default('Pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_email')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
