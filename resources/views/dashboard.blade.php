<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="pago">Pago</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Método de Pagamento:</label>
                                <select name="metodo_pagamento" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="boleto">Boleto</option>
                                    <option value="pix">Pix</option>
                                    <option value="credit_card">Cartão de Crédito</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Data Início:</label>
                                <input type="date" name="data_inicio" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Data Fim:</label>
                                <input type="date" name="data_fim" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Método</th>
                            <th>Data</th>
                            <th>Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pagamentos as $pagamento)
                            <tr>
                                <td>{{ $pagamento->id }}</td>
                                <td>{{ $pagamento->user->name }}</td>
                                <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                                <td>{{ ucfirst($pagamento->status) }}</td>
                                <td>{{ ucfirst($pagamento->metodo_pagamento) }}</td>
                                <td>{{ $pagamento->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('pagamento.show', ['id' => $pagamento->id])  }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
