<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
        });
    }
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'payment_status', 'delivery_address', 'contact_phone', 'subtotal', 'delivery_charge', 'coupon_code', 'discount'
            ]);
        });
    }
};
