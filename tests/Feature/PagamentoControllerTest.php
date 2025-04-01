<?php

    namespace Tests\Feature;

    use App\Models\Pagamento;
    use App\Models\User;
    use App\Services\AsaasService;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;
    use Mockery;

    class PagamentoControllerTest extends TestCase
    {
        use RefreshDatabase;

        protected $asaasServiceMock;
        protected $controller;
        protected $user;

        protected function setUp(): void
        {
            parent::setUp();

            // Criando um mock do serviço Asaas
            $this->asaasServiceMock = Mockery::mock(AsaasService::class);

            // Criando um usuário fake
            $this->user = User::factory()->create();
            $this->actingAs($this->user);
        }

        /**
         * @test
         * @testdox Deve criar pagamento com sucesso
         */
        public function deve_criar_pagamento_com_sucesso()
        {
            $dadosPagamento = [
                'cpf' => '12312312387',
                'telefone' => '(35) 98753-2908',
                'amount' => 100.00,
                'payment_type' => 'BOLETO',
            ];

            $customer = ['id' => 'cus_123'];
            $payment = ['id' => 'pay_456', 'invoiceNumber' => '1234', 'value' => 100.00, 'billingType' => 'PIX'];


            // Definição do comportamento do mock
            $this->asaasServiceMock->shouldReceive('getOrCreateCustomer')
                ->once()
                ->with(
                    Mockery::on(fn($user) => $user instanceof User), // Garante que o primeiro argumento é um User
                    Mockery::any() // Aceita qualquer segundo argumento
                )
                ->andReturn($customer);

            // Mock para criar o pagamento
            $this->asaasServiceMock->shouldReceive('createPayment')
                ->once()
                ->with(
                    Mockery::on(fn($id) => is_string($id) && !empty($id)), // ID do cliente
                    Mockery::on(fn($amount) => is_numeric($amount) && $amount > 0), // Valor
                    'BOLETO' // Método de pagamento
                )
                ->andReturn($payment);

            // Injetando o mock no container do Laravel
            $this->app->instance(AsaasService::class, $this->asaasServiceMock);

            // Chamar o método e verificar resposta
            $response = $this->post(route('checkout.pagamento'), $dadosPagamento);


            // Verificar se o pagamento foi salvo corretamente no banco
            $this->assertDatabaseHas(Pagamento::class, [
                'user_id' => $this->user->id,
                'cpf_cnpj' => '12312312387',
                'telefone' => '(35) 98753-2908',
                'asaas_id' => 'pay_456',
                'valor' => 100.00,
                'metodo_pagamento' => 'pix',
            ]);

            // Verificar se a view correta foi retornada
            $response->assertViewIs('checkout.obrigado');
        }

        /** @test */
        public function deve_retornar_erro_se_cliente_nao_for_criado()
        {
            $user = User::factory()->create();

            $this->actingAs($user);

            $dadosInvalidos = [
                'cpf' => '123', // CPF inválido
                'telefone' => '99999999', // Telefone inválido
                'amount' => 0, // Valor inválido
                'payment_type' => 'DINHEIRO', // Tipo inválido
            ];

            $response = $this->post(route('checkout.pagamento'), $dadosInvalidos);

            $response->assertSessionHasErrors(['cpf', 'telefone', 'amount', 'payment_type']);

        }

        /** @test */
        public function deve_retornar_erro_se_pagamento_nao_for_criado()
        {
            // Dados de pagamento válidos
            $dadosPagamento = [
                'cpf' => '12312312387',
                'telefone' => '(35) 98753-2908',
                'amount' => 100.00,
                'payment_type' => 'PIX',
            ];


            // Mock do serviço Asaas
            $this->asaasServiceMock->shouldReceive('getOrCreateCustomer')
                ->once()
                ->with(
                    Mockery::on(fn($user) => $user instanceof User), // Garante que o primeiro argumento é um User
                    Mockery::any() // Aceita qualquer segundo argumento
                )
                ->andReturn(['id' => 'cus_123']);

            $this->asaasServiceMock->shouldReceive('createPayment')
                ->once()
                ->with('cus_123', 100.00, 'PIX')
                ->andReturn([]); // Retorno vazio para simular falha

            $this->app->instance(AsaasService::class, $this->asaasServiceMock);

            // Fazer a requisição POST
            $response = $this->post(route('checkout.pagamento'), $dadosPagamento);


            // Verificar se foi redirecionado para a página anterior
            $response->assertRedirect();

            // Verificar se a sessão contém o erro esperado
            $response->assertSessionHas('flash_notification');
        }

        protected function tearDown(): void
        {
            Mockery::close();
            parent::tearDown();
        }
    }

