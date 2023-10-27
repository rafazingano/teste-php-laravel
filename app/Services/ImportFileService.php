<?php

namespace App\Services;

use App\Jobs\ImportFileJob;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ImportFileService
{

    public function generateJobs(Request $request)
    {
        // Salve o arquivo no armazenamento
        $path = $request->file('file')->store('imported_files');

        // Obtemos o conteúdo do arquivo JSON do armazenamento
        $fileContents = Storage::get($path);

        // Decodifique o JSON para um array associativo
        $data = json_decode($fileContents, true);

        // Verifique se a decodificação foi bem-sucedida
        if ($data === null || empty($data)) {
            throw new HttpException(400, 'Arquivo inválido ou vazio');
        }

        // Aplique a função normalizeKeys em todos os arrays
        $documentos = array_map([$this, 'normalizeKeys'], $data['documentos']);

        // Itere sobre os registros no arquivo JSON e adicione-os à tabela 'documents'
        foreach ($documentos as $documento) {

            // Envie o trabalho para a fila
            ImportFileJob::dispatch($documento);
        }

        // Após a importação vamos excluir o arquivo
        Storage::delete($path);
    }

    /**
     * Importa um documento para a categoria existente.
     *
     * @param array $document
     * @return bool True se a importação for bem-sucedida, false caso contrário.
     */
    public function importDocument(array $document)
    {
        // Valide os dados de entrada
        if (empty($document) || !isset($document['categoria'], $document['titulo'], $document['conteudo'])) {
            return false; // Dados inválidos
        }

        $category = Category::where("name", $document['categoria'])->first();

        if ($category) {
            // Crie o documento na categoria existente
            $category->documents()->create([
                'title' => $document['titulo'],
                'contents' => $document['conteudo'],
            ]);

            return true; // Importação bem-sucedida
        }

        return false; // Categoria não encontrada
    }

    // Função para aplicar o slug nas chaves do array normalizando assim s chaves
    function normalizeKeys($item)
    {
        // Use array_map para aplicar Str::slug em todas as chaves do array
        return array_combine(array_map(fn ($key) => Str::slug($key, '_'), array_keys($item)), $item);
    }
}
