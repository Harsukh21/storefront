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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->index();
            $table->string('provider', 60);
            $table->string('transaction_id', 120)->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status', 30);
            $table->timestamp('processed_at')->nullable();
            $table->jsonb('raw_response')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->string('provider', 60);
            $table->string('provider_reference', 120);
            $table->string('brand', 60)->nullable();
            $table->string('last4', 4)->nullable();
            $table->date('expires_on')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payment_id')->index();
            $table->decimal('amount', 12, 2);
            $table->text('reason')->nullable();
            $table->string('status', 30);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->index();
            $table->string('shipment_number', 40)->unique();
            $table->string('carrier', 60)->nullable();
            $table->string('service', 60)->nullable();
            $table->string('tracking_number', 120)->nullable();
            $table->string('status', 30);
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('shipment_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('shipment_id')->index();
            $table->bigInteger('order_item_id')->index();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_items');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('payments');
    }
};
