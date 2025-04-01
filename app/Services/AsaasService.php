<?php
    /*
     * Copyright (c) 2025. Ronan Cândido
     */

    namespace App\Services;

    use Illuminate\Support\Facades\Http;

    class AsaasService
    {
        protected $apiKey;
        protected $baseUrl;

        public function __construct()
        {
            $this->apiKey = env('ASAAS_API_KEY');
            $this->baseUrl = env('ASAAS_API_URL', 'https://sandbox.asaas.com/api/v3/');
        }

        private function request($method, $endpoint, $data = [])
        {
            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->$method($this->baseUrl.$endpoint, $data);

            return $response->json();
        }

        public function getCustomerByEmail($email)
        {
            $response = $this->request('GET', '/customers', [
                'email' => $email
            ]);

            return $response['data'][0] ?? null;
        }

        public function createCustomer($user, $data)
        {
            return $this->request('post', 'customers', [
                'name' => $user->name,
                'email' => $user->email,
                'cpfCnpj' => $data->cpf ?? null,
                'phone' => $data->telefone ?? null,
            ]);
        }

        public function getOrCreateCustomer($user, $data)
        {
            $customer = $this->getCustomerByEmail($user->email);

            if ($customer) {
                return $customer;
            }

            return $this->createCustomer($user, $data);
        }

        public function createPayment($customerId, $value, $paymentType)
        {
            $data = [
                'customer' => $customerId,
                'billingType' => strtoupper($paymentType), // 'BOLETO', 'CREDIT_CARD', 'PIX'
                'value' => $value,
                'dueDate' => now()->addDays(3)->format('Y-m-d'),
            ];

            if (strtoupper($paymentType) === 'PIX') {
                $pixKey = $this->getPixAddressKeys();
                if ($pixKey) {
                    $data['pixAddressKey'] = $pixKey;
                }
            }

            $response = $this->request('post', 'payments', $data);

            if (
                !empty($response['id'])
                && strtoupper($paymentType) === 'PIX'
                && empty($response['pixQrCode'])
            ) {
                sleep(3);
                $pixData = $this->getPixQrCode($response['id']);

                if ($pixData['success']) {
                    $response['pixQrCode']['encodedImage'] = $pixData['encodedImage'];
                    $response['pixQrCode']['payload'] = $pixData['payload'];
                }
            }

            return $response;

        }

        public function getPixAddressKeys()
        {
            $addressKeys = $this->request('GET', '/pix/addressKeys');
            if (!empty($addressKeys['data'])) {
                return $addressKeys['data'][0]['key'];
            }
            return null;
        }

        public function getPagamento($paymentId)
        {
            $response = $this->request('GET', "/payments/{$paymentId}");

            if (
                strtoupper($response['billingType']) === 'PIX'
                && empty($response['pixQrCode'])
            ) {

                $pixData = $this->getPixQrCode($response['id']);

                if ($pixData['success']) {
                    $response['pixQrCode']['encodedImage'] = $pixData['encodedImage'];
                    $response['pixQrCode']['payload'] = $pixData['payload'];
                }

            }

            return $response;

        }

        public function getPixQrCode($paymentId)
        {
            $response = $this->request('get', "payments/{$paymentId}/pixQrCode");

            if (empty($response['encodedImage'])) {
                return [
                    'success' => false,
                    'message' => 'QR Code do PIX ainda não foi gerado. Tente novamente mais tarde.',
                ];
            }

            return $response;
        }

    }
