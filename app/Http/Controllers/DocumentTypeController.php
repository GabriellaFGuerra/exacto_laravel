<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentTypes = DocumentType::paginate(10);
        return view('document-types.index', compact('documentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('document-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
        DocumentType::create($request->all());
        return redirect()->route('document_types.index')->with('success', 'Tipo de documento criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        return view('document-types.show', compact('documentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        return view('document-types.edit', compact('documentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
        $documentType->update($request->all());
        return redirect()->route('document_types.index')->with('success', 'Tipo de documento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        return redirect()->route('document_types.index')->with('success', 'Tipo de documento excluído com sucesso.');
    }
}