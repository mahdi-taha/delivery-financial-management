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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('collection_num')->nullable()->unique();
            $table->foreignId('payment_method_id')
                ->constrained();
            $table->foreignId('driver_id')
                ->constrained();
            $table->decimal('received_amount', 15, 2);
            $table->decimal('driver_amount', 15, 2);
            $table->decimal('company_amount', 15, 2);
            $table->decimal('received_amount_base', 15, 2);
            $table->decimal('driver_amount_base', 15, 2);
            $table->decimal('company_amount_base', 15, 2);
            $table->foreignId('currency_id')
                ->constrained();
            $table->decimal('exchange_rate', 15, 6);
            $table->string('status');
            $table->text('notes')
                ->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
