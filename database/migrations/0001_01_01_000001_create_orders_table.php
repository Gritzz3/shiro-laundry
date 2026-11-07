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
            $table->string('nomor_nota', 20)->unique();
            $table->string('nama_pelanggan', 100);
            $table->string('no_hp', 20);
            $table->foreignId('outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status_cucian', [
                'antrean',
                'proses_cuci',
                'setrika_packing',
                'selesai',
            ])->default('antrean');
            $table->timestamps();

            // Explicit index for query performance on tracking lookups
            $table->index('nomor_nota', 'idx_orders_nomor_nota');
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
