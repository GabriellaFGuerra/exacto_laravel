<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        try {
            $documents = Document::all();
            return view('documents.index', compact('documents'));
        } catch (\Exception $e) {
            Log::error('Erro ao buscar documentos: ' . $e->getMessage());
            return redirect()->back()->withErrors('Falha ao carregar os documentos.');
        }
    }

    public function create()
    {
        try {
            return view('documents.create');
        } catch (\Exception $e) {
            Log::error('Erro ao exibir o formulário de criação: ' . $e->getMessage());
            return redirect()->back()->withErrors('Falha ao exibir o formulário de criação.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'customer_id' => 'required|exists:customers,id',
                'budget_id' => 'nullable|exists:budgets,id',
                'document_type_id' => 'required|exists:document_types,id',
                'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'issue_date' => 'required|date',
                'periodicity' => 'nullable|integer|in:1,6,12,60',
                'expiration_date' => 'nullable|date|after_or_equal:issue_date',
                'observation' => 'nullable|string|max:1000',
            ]);

            $file = $request->file('attachment');
            $titleSnakeCase = Str::snake($request->input('title'));
            $dateSuffix = now()->format('Ymd_His');
            $extension = $file->getClientOriginalExtension();
            $fileName = "{$titleSnakeCase}_{$dateSuffix}.{$extension}";
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $document = Document::create([
                'title' => $request->title,
                'customer_id' => $request->customer_id,
                'budget_id' => $request->budget_id,
                'document_type_id' => $request->document_type_id,
                'attachment' => $filePath,
                'issue_date' => $request->issue_date,
                'periodicity' => $request->periodicity,
                'expiration_date' => $request->expiration_date,
                'observation' => $request->observation,
            ]);

            return redirect()->route('documents.index')->with('success', 'Documento criado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao criar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Falha ao criar o documento.')->withInput();
        }
    }

    public function show(Document $document)
    {
        try {
            return view('documents.show', compact('document'));
        } catch (\Exception $e) {
            Log::error('Erro ao exibir documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Falha ao exibir o documento.');
        }
    }

    public function edit(Document $document)
    {
        try {
            return view('documents.edit', compact('document'));
        } catch (\Exception $e) {
            Log::error('Erro ao exibir o formulário de edição: ' . $e->getMessage());
            return redirect()->back()->withErrors('Falha ao exibir o formulário de edição.');
        }
    }

    public function update(Request $request, Document $document)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'customer_id' => 'required|exists:customers,id',
                'budget_id' => 'nullable|exists:budgets,id',
                'document_type_id' => 'required|exists:document_types,id',
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'issue_date' => 'required|date',
                'periodicity' => 'nullable|integer|in:1,6,12,60',
                'expiration_date' => 'nullable|date|after_or_equal:issue_date',
                'observation' => 'nullable|string|max:1000',
            ]);

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $titleSnakeCase = Str::snake($request->input('title'));
                $dateSuffix = now()->format('Ymd_His');
                $extension = $file->getClientOriginalExtension();
                $fileName = "{$titleSnakeCase}_{$dateSuffix}.{$extension}";
                $filePath = $file->storeAs('documents', $fileName, 'public');
            } else {
                $filePath = $document->attachment;
            }

            $document->update([
                'title' => $request->title,
                'customer_id' => $request->customer_id,
                'budget_id' => $request->budget_id,
                'document_type_id' => $request->document_type_id,
                'attachment' => $filePath,
                'issue_date' => $request->issue_date,
                'periodicity' => $request->periodicity,
                'expiration_date' => $request->expiration_date,
                'observation' => $request->observation,
            ]);

            return redirect()->route('documents.index')->with('success', 'Documento atualizado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Falha ao atualizar o documento.')->withInput();
        }
    }

    public function destroy(Document $document)
    {
        try {
            // Apaga o arquivo do documento
            if ($document->attachment && Storage::disk('public')->exists($document->attachment)) {
                Storage::disk('public')->delete($document->attachment);
            }
            $document->delete();
            return redirect()->route('documents.index')->with('success', 'Documento excluído com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Falha ao excluir o documento.');
        }
    }
}