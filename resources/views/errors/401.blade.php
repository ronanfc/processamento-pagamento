@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{asset("assets/vendor/css/pages/page-misc.css")}}"/>
@endpush

@section('title_postfix', config('app.name', 'Adminstrador Sistema').' - Acesso negado')

@section('content')
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem">401</h1>
        <h4 class="mb-2 mx-2">Você não está autorizado! 🔐</h4>
        <p class="mb-6 mx-2">Você não tem permissão para acessar esta página!</p>
        <a href="{{route('home')}}" class="btn btn-primary">Voltar</a>
    </div>
@endsection

