@extends('layouts.app')

@section('title', 'Processamento da Fila')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 w-100">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Processamento da Fila</h2>
            <form action="{{ route('import.start.processing') }}" method="POST">
                @csrf
                @method('POST')
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block">Iniciar</button>
                </div>
                <div class="text-end">
                    <a href="{{ route('import.index') }}" class="btn btn-link">Formulário de Importação</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection