<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        // Filtros opcionais (data, status, método de pagamento)
        $query = Pagamento::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('metodo_pagamento')) {
            $query->where('metodo_pagamento', $request->metodo_pagamento);
        }

        if ($request->has('data_inicio') && $request->has('data_fim')) {
            $query->whereBetween('created_at', [$request->data_inicio, $request->data_fim]);
        }

        $pagamentos = $query->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('pagamentos'));

    }
}
