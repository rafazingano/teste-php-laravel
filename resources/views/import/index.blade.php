@extends('layouts.app')

@section('title', 'Formulário de Importação')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 w-100">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Importação</h2>
            <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" class="form-control" name="file" id="file">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection