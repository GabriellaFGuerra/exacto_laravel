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
        $document_types = DocumentType::paginate(10);
        return view('document_types.index', compact('document_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('document_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        DocumentType::create($request->all());
        return redirect()->route('document_types.index')->with('success', 'Document Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        return view('document_types.show', compact('documentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        return view('document_types.edit', compact('documentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $documentType->update($request->all());
        return redirect()->route('document_types.index')->with('success', 'Document Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        return redirect()->route('document_types.index')->with('success', 'Document Type deleted successfully.');
    }
}