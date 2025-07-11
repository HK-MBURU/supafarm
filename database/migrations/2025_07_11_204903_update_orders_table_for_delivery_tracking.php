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
        Schema::table('orders', function (Blueprint $table) {
            // Update payment_method enum to remove unused options
            $table->enum('payment_method', ['mpesa', 'cash_on_delivery'])
                  ->change();
            
            // Add delivery-related fields
            $table->decimal('delivery_latitude', 10, 8)->nullable()->after('shipping_address');
            $table->decimal('delivery_longitude', 11, 8)->nullable()->after('delivery_latitude');
            $table->text('delivery_instructions')->nullable()->after('delivery_longitude');
            
            // Add estimated delivery time
            $table->timestamp('estimated_delivery_at')->nullable()->after('delivery_instructions');
            
            // Add delivery status tracking
            $table->enum('delivery_status', [
                'pending', 
                'assigned', 
                'picked_up', 
                'in_transit', 
                'delivered', 
                'failed'
            ])->default('pending')->after('estimated_delivery_at');
            
            // Add delivery person/rider info
            $table->string('delivery_person_name')->nullable()->after('delivery_status');
            $table->string('delivery_person_phone')->nullable()->after('delivery_person_name');
            
            // Add order tracking fields
            $table->timestamp('confirmed_at')->nullable()->after('delivery_person_phone');
            $table->timestamp('prepared_at')->nullable()->after('confirmed_at');
            $table->timestamp('dispatched_at')->nullable()->after('prepared_at');
            
            // Add delivery distance and zone for pricing
            $table->decimal('delivery_distance_km', 8, 2)->nullable()->after('dispatched_at');
            $table->string('delivery_zone')->nullable()->after('delivery_distance_km');
            
            // Add indexes for better performance
            $table->index(['delivery_status', 'created_at']);
            $table->index(['status', 'delivery_status']);
            $table->index(['estimated_delivery_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove the added columns
            $table->dropColumn([
                'delivery_latitude',
                'delivery_longitude',
                'delivery_instructions',
                'estimated_delivery_at',
                'delivery_status',
                'delivery_person_name',
                'delivery_person_phone',
                'confirmed_at',
                'prepared_at',
                'dispatched_at',
                'delivery_distance_km',
                'delivery_zone'
            ]);
            
            // Remove indexes
            $table->dropIndex(['delivery_status', 'created_at']);
            $table->dropIndex(['status', 'delivery_status']);
            $table->dropIndex(['estimated_delivery_at']);
            
            // Restore original payment_method enum
            $table->enum('payment_method', [
                'credit_card', 
                'mpesa', 
                'bank_transfer', 
                'cash_on_delivery'
            ])->change();
        });
    }
};