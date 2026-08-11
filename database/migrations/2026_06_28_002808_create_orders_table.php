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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_num')->nullable()->unique();
            $table->decimal('delivery_fee', 10, 2);
            $table->decimal('delivery_fee_base', 15, 2);
            $table->integer('contract_company_percentage')->nullable();
            $table->integer('contract_company_fixed')->nullable();
            $table->decimal('contract_company_amount', 15, 2);
            $table->decimal('driver_amount', 15, 2);
            $table->decimal('contract_company_amount_base', 15, 2);
            $table->decimal('driver_amount_base', 15, 2);
            $table->decimal('exchange_rate', 15, 6);
            $table->decimal('company_amount', 15, 2);  //Added this line to the migration file
            $table->decimal('company_amount_base', 15, 2); //
            $table->foreignId('currency_id')
                ->constrained();
            $table->foreignId('driver_id')
                ->constrained();
            $table->foreignId('contract_company_id')
                ->nullable()
                ->constrained('contract_companies');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
