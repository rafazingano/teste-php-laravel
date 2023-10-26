<?php

namespace App\Http\Controllers;

use App\Jobs\ImportFileJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function process(Request $request)
    {
        // Valide o arquivo enviado
        $request->validate([
            'file' => 'required|file|mimes:json', // Ajuste os requisitos conforme necessário
        ]);

        // Salve o arquivo no armazenamento
        $path = $request->file('file')->store('imported_files');

        // Envie o trabalho para a fila
        ImportFileJob::dispatch($path);

        return redirect()->route('import.index')->with('success', 'Arquivo enviado para processamento.');
    }

    public function start()
    {
        return view('import.start');
    }

    public function startProcessing()
    {
        // Dispare o job para iniciar o processamento da fila
        Artisan::call('queue:work');

        return redirect()->route('import.index')->with('success', 'Processamento da fila iniciado.');
    }
}
