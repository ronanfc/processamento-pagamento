<?php

    namespace App\Http\Controllers;

    use App\Data\PagamentoData;
    use App\Http\Resources\PagamentoResource;
    use App\Models\Pagamento;
    use App\Services\AsaasService;
    use Illuminate\Support\Facades\Session;
    use Laracasts\Flash\Flash;

    class PagamentoController extends Controller
    {
        protected AsaasService $asaasService;

        public function __construct(AsaasService $asaasService)
        {
            $this->asaasService = $asaasService;
        }

        public function index()
        {
            return view('checkout.index');
        }

        public function pagamento(PagamentoData $request)
        {
            $user = auth()->user(); // Usuário autenticado


            $customer = $this->asaasService->getOrCreateCustomer($user, $request);


            if (!isset($customer['id'])) {
                flash()->error('Não foi possível relalizar o pagamento, tente novamente mais tarde!')->important();
                return redirect()->back();
            }

            $payment = $this->asaasService->createPayment($customer['id'], $request->amount, $request->payment_type);

            if (!isset($payment['id'])) {
                flash()->error('Erro ao criar pagamento!')->important();
                return redirect()->back();
            }

            $paymentResource = new PagamentoResource($payment);

            $paymentResource = $paymentResource->toArray(request());

            $request->criarPagamento($user, $paymentResource, $payment);

            // Redireciona para a página de obrigado com os dados do pagamento
            return view('checkout.obrigado', compact('paymentResource'));
        }


        public function show($paymentId)
        {

            $pagamento = Pagamento::query()->find($paymentId);

            if (!$pagamento) {
                flash()->error('Pagamento não localizado!')->important();
                return redirect()->back();
            }

            $payment = $this->asaasService->getPagamento($pagamento->asaas_id);

            if (!isset($payment['id'])) {
                flash()->error('Pagamento não localizado!')->important();
                return redirect()->back();
            }

            $paymentResource = new PagamentoResource($payment);
            $paymentResource = $paymentResource->toArray(request());

            return view('checkout.pagamento-pendente', compact('paymentResource'));
        }

        public function getPixQrCode($paymentId)
        {
            $response = $this->asaasService->getPixQrCode($paymentId);

            return $response ?? null;
        }

    }
