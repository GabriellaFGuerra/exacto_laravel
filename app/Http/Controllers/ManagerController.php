<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $managers = Manager::all();
            return view('managers.index', compact('managers'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao recuperar gestores: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('managers.create');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao carregar o formulário de criação: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        try {
            Manager::create($request->all());
            return redirect()->route('managers.index')->with('success', 'Gestor criado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao criar gestor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Manager $manager)
    {
        try {
            return view('managers.show', compact('manager'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao recuperar gestor: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manager $manager)
    {
        try {
            return view('managers.edit', compact('manager'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao carregar o formulário de edição: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manager $manager)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        try {
            $manager->update($request->all());
            return redirect()->route('managers.index')->with('success', 'Gestor atualizado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao atualizar gestor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manager $manager)
    {
        try {
            $manager->delete();
            return redirect()->route('managers.index')->with('success', 'Gestor excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao excluir gestor: ' . $e->getMessage());
        }
    }
}