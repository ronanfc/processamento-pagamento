<?php

    namespace App\Data;

    use App\Models\Pagamento;
    use Spatie\LaravelData\Data;
    use Spatie\LaravelData\Support\Validation\ValidationContext;

    class PagamentoData extends Data
    {
        public string $cpf;
        public string $telefone;
        public float $amount;
        public string $payment_type;

        public static function rules(ValidationContext $context): array
        {
            return [
                'cpf' => [
                    'required',
                    'string',
                    'cpf',
                ],
                'telefone' => [
                    'required',
                    'string',
                    'celular_com_ddd',
                ],
                'amount' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],
                'payment_type' => [
                    'required',
                    'string',
                    'in:BOLETO,CREDIT_CARD,PIX',
                ],
            ];
        }

        public static function messages(...$args): array
        {
            return [
                'cpf.cpf' => 'CPF inválido.',
                'telefone.celular_com_ddd' => 'Telefone inválido.',
                'amount.min' => 'O valor mínimo é de R$ 0,01.',
                'payment_type.in' => 'Tipo de pagamento inválido.',
            ];
        }

        public function criarPagamento($user, $dadosPagamento, $pagamento)
        {
            Pagamento::create([
                'user_id' => $user->id,
                'cpf_cnpj' => $this->cpf,
                'telefone' => $this->telefone,
                'asaas_id' => $dadosPagamento['id'],
                'valor' => $dadosPagamento['value'],
                'status' => strtolower($dadosPagamento['status']),
                'metodo_pagamento' => strtolower($dadosPagamento['billingType']),
                'link_pagamento' => $dadosPagamento['invoiceUrl'] ?? null,
                'qrcode_pix' => $dadosPagamento['pixQrCode'] ?? null,
                'copia_cola_pix' => $dadosPagamento['pixQrCode'] ?? null,
                'detalhes' => json_encode($pagamento),
            ]);
        }
    }
