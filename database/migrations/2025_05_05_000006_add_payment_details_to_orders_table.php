<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('card_type')->nullable();
            $table->string('card_number')->nullable();
            $table->string('expiry')->nullable();
            $table->string('cvv')->nullable();
            $table->string('mobile_payment')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['card_type', 'card_number', 'expiry', 'cvv', 'mobile_payment']);
        });
    }
};
