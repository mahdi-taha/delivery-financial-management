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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_num')->nullable()->unique();
            $table->date('date');
            $table->string('type');
            $table->decimal('amount', 15, 2);
            $table->decimal('amount_base', 15, 2);
            $table->foreignId('currency_id')
                ->constrained();
            $table->decimal('exchange_rate', 15, 6);
            $table->string('direction');
            $table->string('status');
            $table->text('notes')
                ->nullable();
            $table->foreignId('driver_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('contract_company_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('collection_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('settlement_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('payment_method_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
