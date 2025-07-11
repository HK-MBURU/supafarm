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
            // Rename columns to match your model expectations
            $table->renameColumn('tax', 'tax_amount');
            $table->renameColumn('shipping', 'shipping_cost');
            $table->renameColumn('total', 'total_amount');
            
            // Add missing columns
            $table->string('payment_reference')->nullable()->after('payment_status');
            
            // Convert shipping_address and billing_address to JSON for storing arrays
            $table->json('billing_address_json')->nullable()->after('billing_country');
            $table->json('shipping_address_json')->nullable()->after('shipping_address');
        });
        
        // Copy existing address data to JSON columns
        DB::statement("
            UPDATE orders 
            SET billing_address_json = JSON_OBJECT(
                'name', billing_name,
                'email', billing_email, 
                'phone', billing_phone,
                'address', billing_address,
                'city', billing_city,
                'state', billing_state,
                'zipcode', billing_zipcode,
                'country', billing_country
            ),
            shipping_address_json = JSON_OBJECT(
                'full_address', shipping_address,
                'name', shipping_name,
                'email', shipping_email,
                'phone', shipping_phone,
                'city', shipping_city,
                'state', shipping_state,
                'zipcode', shipping_zipcode,
                'country', shipping_country,
                'latitude', delivery_latitude,
                'longitude', delivery_longitude
            )
        ");
        
        Schema::table('orders', function (Blueprint $table) {
            // Drop old individual address columns
            $table->dropColumn([
                'shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address',
                'shipping_city', 'shipping_state', 'shipping_zipcode', 'shipping_country',
                'billing_name', 'billing_email', 'billing_phone', 'billing_address',
                'billing_city', 'billing_state', 'billing_zipcode', 'billing_country'
            ]);
            
            // Rename JSON columns to final names
            $table->renameColumn('billing_address_json', 'billing_address');
            $table->renameColumn('shipping_address_json', 'shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('tax_amount', 'tax');
            $table->renameColumn('shipping_cost', 'shipping');
            $table->renameColumn('total_amount', 'total');
            
            $table->dropColumn('payment_reference');
            
            // Restore individual address columns
            $table->string('shipping_name')->after('currency');
            $table->string('shipping_email')->after('shipping_name');
            $table->string('shipping_phone')->nullable()->after('shipping_email');
            $table->string('shipping_address')->after('shipping_phone');
            $table->string('shipping_city')->after('delivery_zone');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_zipcode')->nullable()->after('shipping_state');
            $table->string('shipping_country')->after('shipping_zipcode');
            
            $table->string('billing_name')->nullable()->after('shipping_country');
            $table->string('billing_email')->nullable()->after('billing_name');
            $table->string('billing_phone')->nullable()->after('billing_email');
            $table->string('billing_address')->nullable()->after('billing_phone');
            $table->string('billing_city')->nullable()->after('billing_address');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_zipcode')->nullable()->after('billing_state');
            $table->string('billing_country')->nullable()->after('billing_zipcode');
            
            $table->dropColumn(['billing_address', 'shipping_address']);
        });
    }
};