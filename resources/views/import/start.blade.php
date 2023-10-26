<form action="{{ route('import.start.processing') }}" method="POST">
    @csrf
    @method('POST')
    <button type="submit" class="btn btn-primary">Iniciar Processamento da Fila</button>
</form>
