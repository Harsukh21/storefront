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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->string('name', 120)->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('wishlist_id')->index();
            $table->bigInteger('product_id')->nullable()->index();
            $table->bigInteger('product_variant_id')->nullable()->index();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->index();
            $table->bigInteger('user_id')->index();
            $table->smallInteger('rating');
            $table->string('title', 191)->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('review_helpfulness', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('review_id')->index();
            $table->bigInteger('user_id')->index();
            $table->boolean('is_helpful');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('product_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->index();
            $table->bigInteger('user_id')->nullable()->index();
            $table->text('question');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('product_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('question_id')->index();
            $table->bigInteger('admin_id')->nullable()->index();
            $table->bigInteger('user_id')->nullable()->index();
            $table->text('answer');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_answers');
        Schema::dropIfExists('product_questions');
        Schema::dropIfExists('review_helpfulness');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlists');
    }
};
