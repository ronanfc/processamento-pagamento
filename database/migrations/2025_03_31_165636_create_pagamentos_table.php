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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cpf_cnpj')->nullable();
            $table->string('telefone')->nullable();
            $table->string('asaas_id')->unique();
            $table->decimal('valor', 10, 2);
            $table->string('status')->default('pending');
            $table->enum('metodo_pagamento', ['boleto', 'pix', 'credit_card']);
            $table->string('link_pagamento')->nullable();
            $table->text('qrcode_pix')->nullable();
            $table->text('copia_cola_pix')->nullable();
            $table->json('detalhes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
