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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_percentage');
            $table->string('settlement_num')->nullable()->unique();
            $table->foreignId('driver_id')
                ->constrained();
            $table->date('date');
            $table->integer('total_orders')
                ->default(0);
            $table->decimal('driver_total', 15, 2)->default(0);  //
            // $table->decimal('amount', 15, 2);
            $table->decimal('delivery_total', 15, 2)->default(0); //
            $table->decimal('subtotal', 15, 2)->default(0); //
            $table->decimal('company_total', 15, 2)
                ->default(0); //
            $table->decimal('contract_company_total', 15, 2)
                ->default(0); //
            $table->text('notes')->nullable();
            $table->string('status')
                ->default('pending');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
