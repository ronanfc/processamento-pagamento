<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pagamento
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <section>

                        <div class="text-center">
                            <h1>Número do pedido: {{ $paymentResource['invoiceNumber'] }}</h1>
                        </div>

                        @if($paymentResource['status'] === 'CONFIRMED')
                            <p class="success">Seu pagamento foi confirmado com sucesso!</p>
                        @elseif($paymentResource['status'] === 'PENDING')
                            <p class="success">Seu pagamento está pendente. Siga as instruções abaixo.</p>
                        @else
                            <p class="error">O pagamento não foi aprovado. Por favor, tente novamente.</p>
                        @endif

                        @if($paymentResource['billingType'] === 'BOLETO' && $paymentResource['invoiceUrl'])
                            <p><strong>Pagamento via Boleto</strong></p>
                            <a href="{{ $paymentResource['invoiceUrl'] }}" target="_blank">
                                <button class="btn btn-outline-secondary mb-2">Baixar Boleto</button>
                            </a>
                        @elseif($paymentResource['billingType'] === 'PIX')
                            @if($paymentResource['pixQrCode'] && $paymentResource['pixCopiaCola'])
                                <p><strong>Pagamento via PIX</strong></p>
                                <div class="qr-code">
                                    <img src="data:image/png;base64,{{ $paymentResource['pixQrCode'] }} "
                                         alt="QR Code do PIX" style="max-width: 150px;">
                                </div>
                                <p><strong>Copia e Cola:</strong></p>
                                <div class="p-2 border-1 bg-light mb-3">{{ $paymentResource['pixCopiaCola'] }}</div>
                            @else
                                <p class="text-danger">Ocorreu um erro ao gerar o QR Code do PIX.</p>
                            @endif
                        @elseif($paymentResource['billingType'] === 'CREDIT_CARD' && $paymentResource['status'] !== 'CONFIRMED')
                            <p class="text-danger">Seu pagamento foi recusado. Tente novamente com outro cartão ou outra forma
                                de pagamento.</p>
                        @endif

                        <p><strong>Valor:</strong> R$ {{ number_format($paymentResource['value'], 2, ',', '.') }}</p>

                        <p><a class="btn btn-primary mt-3" href="{{ route('checkout.index') }}">Realizar nova compra</a>
                        </p>

                    </section>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
