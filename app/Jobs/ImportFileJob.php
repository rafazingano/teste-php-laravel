<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Obtenha o conteúdo do arquivo JSON do armazenamento
        $fileContents = Storage::get($this->filePath);

        // Decodifique o JSON para um array associativo
        $data = json_decode($fileContents, true);

        // Verifique se a decodificação foi bem-sucedida
        if ($data === null) {
            // Lidar com um erro na decodificação, como um JSON inválido
            return;
        }

        // Aplique a função normalizeKeys em todos os arrays
        $documentos = array_map([$this, 'normalizeKeys'], $data['documentos']);

        // Itere sobre os registros no arquivo JSON e adicione-os à tabela 'documents'
        foreach ($documentos as $documento) {
            if ($category = Category::where("name", $documento['categoria'])->first()) {
                $category->documents()->create([
                    'title' => $documento['titulo'],
                    'contents' => $documento['conteudo'],
                ]);
            }
        }

        // Após a importação vamos excluir o arquivo, se necessário
        Storage::delete($this->filePath);
    }

    // Função para aplicar o slug nas chaves do array normalizando assim s chaves
    function normalizeKeys($item)
    {
        // Use array_map para aplicar Str::slug em todas as chaves do array
        return array_combine(array_map(fn ($key) => Str::slug($key, '_'), array_keys($item)), $item);
    }
}
