<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = Provider::paginate(10);
        return view('providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:providers,email',
            'cnpj' => 'required|string|max:14|unique:providers,cnpj',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'municipality' => 'required|integer|exists:municipalities,municipality_id',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'phone_2' => 'nullable|string|max:15'
        ]);

        try {
            Provider::create([
                'name' => $request->name,
                'email' => $request->email,
                'cnpj' => $request->cnpj,
                'phone' => $request->phone,
                'address' => $request->address,
                'number' => $request->number,
                'municipality_id' => $request->municipality,
                'complement' => $request->complement,
                'neighborhood' => $request->neighborhood,
                'zip_code' => $request->zip_code,
                'phone_2' => $request->phone_2,
            ]);
            return redirect()->route('providers.index')->with('success', 'Fornecedor criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao criar fornecedor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        return view('providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        return view('providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:providers,email,' . $provider->id,
            'cnpj' => 'required|string|max:14|unique:providers,cnpj,' . $provider->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'municipality' => 'required|integer|exists:municipalities,municipality_id',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'phone_2' => 'nullable|string|max:15'
        ]);

        try {
            $provider->update($request->all());
            return redirect()->route('providers.index')->with('success', 'Fornecedor atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao atualizar fornecedor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        try {
            $provider->delete();
            return redirect()->route('providers.index')->with('success', 'Fornecedor excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao excluir fornecedor: ' . $e->getMessage());
        }
    }
}