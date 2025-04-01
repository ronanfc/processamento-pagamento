<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Pagamento extends Model
    {
        use HasFactory, SoftDeletes;

        protected $fillable = [
            'user_id',
            'cpf_cnpj',
            'telefone',
            'asaas_id',
            'valor',
            'status',
            'metodo_pagamento',
            'link_pagamento',
            'qrcode_pix',
            'copia_cola_pix',
            'detalhes',
        ];

        protected $casts = [
            'detalhes' => 'array',
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
