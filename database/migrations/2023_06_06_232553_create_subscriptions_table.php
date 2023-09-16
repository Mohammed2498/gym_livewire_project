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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->decimal('subscription_price', 8, 2);
            $table->integer('payment_amount');
            $table->enum('payment_status', ['full', 'partial', 'not_paid']);
            $table->string('remaining_payment');
            $table->enum('status', ['active', 'expired']);
            $table->integer('duration')->default(1);
            $table->integer('modify_days')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
