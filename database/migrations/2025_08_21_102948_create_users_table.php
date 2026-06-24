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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 25)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->string('username', 20)->unique();
            $table->string('designation', 50)->nullable();
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'inactive']);
            $table->enum('role', ['end-user', 'admin']);
            $table->string('email_token')->nullable()->unique()->comment('This field is used for paystack payment gateway.');
            $table->string('customer_id')->nullable()->unique()->comment('Customer ID from Mollie Payment gateway.');
            $table->string('avatar')->nullable()->comment('User profile picture');
            $table->unsignedInteger('plan_id')->default(1)->index('plan_id');
            $table->string('plan_amount', 32)->nullable();
            $table->string('plan_currency', 12)->nullable();
            $table->string('plan_interval', 16)->nullable();
            $table->string('plan_payment_gateway', 32)->nullable();
            $table->string('plan_subscription_id', 128)->nullable();
            $table->string('plan_subscription_status', 32)->nullable();
            $table->timestamp('plan_created_at')->nullable();
            $table->timestamp('plan_recurring_at')->nullable();
            $table->timestamp('plan_trial_ends_at')->nullable();
            $table->timestamp('plan_ends_at')->nullable();
            $table->text('billing_information')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->smallInteger('default_stats')->default(1);
            $table->enum('tfa', ['active', 'inactive'])->default('inactive');
            $table->string('facebook_id')->nullable();
            $table->string('twitter_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('linkedin_id')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('country_id')->nullable()->index('users_country_id_foreign');
            $table->timestamp('last_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
