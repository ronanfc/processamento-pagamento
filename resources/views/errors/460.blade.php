@extends('layouts.app-sem-menu')

@push('styles')
    <link rel="stylesheet" href="{{asset("assets/vendor/css/pages/page-misc.css")}}"/>
@endpush

@section('title_postfix', config('app.name', 'Adminstrador Sistema').' - Acesso negado')

@section('content')
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem"></h1>
        <h4 class="mb-2 mx-2">Franquia não localizada! 🕵️‍♀ </h4>
        <p class="mb-6 mx-2">Não foi localizada franquia para este usuário, favor realizar o login novamente!</p>
        <a class="btn btn-primary" href="#"  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
            <small class="align-middle">Login</small>
            <i class="ti ti-logout ms-2 ti-14px"></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
@endsection

