<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Processamento de Pagamento
        </h2>
    </x-slot>

    @include('flash::message')
    @include('errors.erros')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <section>

                        {{ html()->form('POST', route('checkout.pagamento'))->attribute('enctype', 'multipart/form-data')->open() }}

                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">CPF:</label>
                                {{ html()->text('cpf')->class('mb-1 form-control')->required() }}
                            </div>
                            <div class="col-md-6">
                                <label for="name">Telefone:</label>
                                {{ html()->text('telefone')->class('mb-1 form-control')->required() }}
                            </div>

                            <div class="col-md-6">
                                <label for="amount">Valor:</label>
                                {{ html()->number('amount')->class('mb-1 form-control')->attribute('step', '0.01')->required() }}
                            </div>
                            <div class="col-md-6">
                                <label for="payment_type">Forma de Pagamento:</label>
                                {{ html()->select('payment_type')->class('mb-1 form-control')
                                                ->options([
                                                     "BOLETO" => 'Boleto',
                                                     "CREDIT_CARD" => 'Cartão de Crédito',
                                                     "PIX" => 'PIX'
                                                    ])
                                                ->required()
                                }}
                            </div>
                        </div>

                        <button class="btn btn-primary mt-2" id="pay-button">Pagar</button>

                        {{ html()->form()->close() }}

                    </section>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

