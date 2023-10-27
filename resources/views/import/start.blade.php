@extends('layouts.app')

@section('title', 'Processamento da Fila')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 w-100">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Processamento da Fila</h2>
            <form id="processingForm" action="{{ route('import.start.processing') }}" method="POST">
                @csrf
                @method('POST')
                <div class="text-center">
                    <button type="submit" id="startProcessing" class="btn btn-primary btn-block">Iniciar</button>
                </div>
                <div class="text-end">
                    <a href="{{ route('import.index') }}" class="btn btn-link">Formulário de Importação</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#processingForm").submit(function(e) {
            e.preventDefault();
            $("#startProcessing").prop("disabled", true).text('Processando...');
            $.ajax({
                type: "POST",
                url: "{{ route('import.start.processing') }}",
                data: $("#processingForm").serialize(),
                success: function(response) {
                    alert("Processamento iniciado com sucesso!");
                    $("#startProcessing").prop("disabled", false).text('Iniciar');
                },
                error: function(error) {
                    alert("Ocorreu um erro ao iniciar o processamento.");
                    $("#startProcessing").prop("disabled", false).text('Iniciar');
                }
            });
        });
    });
</script>

@endsection