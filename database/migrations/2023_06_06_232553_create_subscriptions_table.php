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
            $table->decimal('price', 10, 2)->default(20.00);
            $table->decimal('payment_amount', 10, 2);
            $table->string('payment_status');
            $table->string('remaining_payment');
            $table->enum('status', ['active', 'expired']);
            $table->integer('duration')->default(1);
            $table->string('subscription_type')->default('specified');
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
