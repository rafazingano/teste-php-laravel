<form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">Enviar</button>
</form>
