<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportFileRequest;
use App\Services\ImportFileService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{

    protected $importFileService;

    public function __construct(ImportFileService $importFileService)
    {
        $this->importFileService = $importFileService;
    }

    public function index()
    {
        return view('import.index');
    }

    public function process(ImportFileRequest $request)
    {
        try {
            $this->importFileService->generateJobs($request);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('import.index')->with('danger', 'Opss!!! Algo inesperado aconteceu, tente novamente.');
        }

        return redirect()->route('import.index')->with('success', 'Arquivo enviado para processamento.');
    }

    public function start()
    {
        return view('import.start');
    }

    /*public function startProcessing()
    {
        try {
            // Dispare o job para iniciar o processamento da fila
            $rr = Artisan::call('queue:work --stop-when-empty', []);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('import.index')->with('danger', 'Opss!!! Algo inesperado aconteceu, tente novamente.');
        }
        
        return redirect()->route('import.index')->with('success', 'Processamento da fila iniciado.');
    }*/

    public function startProcessing()
    {
        try {
            // Dispare o job para iniciar o processamento da fila
            $rr = Artisan::call('queue:work --stop-when-empty', []);

            // Se o processo foi bem-sucedido, retorne uma resposta JSON
            return response()->json(['message' => 'Processamento da fila finalizado com sucesso.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            // Em caso de erro, retorne uma resposta JSON com status 500 (Erro Interno do Servidor)
            return response()->json(['error' => 'Algo inesperado aconteceu, tente novamente.'], 500);
        }
    }
}
