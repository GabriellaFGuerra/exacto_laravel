<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::where('user_type', 'admin')->get();
            return view('admin.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao recuperar usuários: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.create');
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
            'email' => 'required|string|email|max:255|unique:users',
            'login' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'notification' => 'required|boolean',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'municipality_id' => 'required|exists:municipalities,id',
            'zip_code' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
            'cpf' => 'required|unique:users|string|max:14',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'login' => $request->login,
                'password' => Hash::make($request->password),
                'user_type' => 'admin',
                'status' => 1,
                'notification' => $request->notification,
                'address' => $request->address,
                'number' => $request->number,
                'complement' => $request->complement,
                'neighborhood' => $request->neighborhood,
                'municipality_id' => $request->municipality_id,
                'zip_code' => $request->zip_code,
                'phone' => $request->phone,
                'cpf' => $request->cpf,
            ]);

            return redirect()->route('admin.index')->with('success', 'Usuário criado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao criar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            return view('admin.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao recuperar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try {
            return view('admin.edit', compact('user'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao carregar o formulário de edição: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'login' => 'required|string|max:255|unique:users,login,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
            'notification' => 'required|boolean',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'municipality_id' => 'required|exists:municipalities,id',
            'zip_code' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
            'cpf' => 'required|unique:users,cpf,' . $user->id . '|string|max:14',
            'status' => 'required|boolean',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'login' => $request->login,
                'notification' => $request->notification,
                'address' => $request->address,
                'number' => $request->number,
                'complement' => $request->complement,
                'neighborhood' => $request->neighborhood,
                'municipality_id' => $request->municipality_id,
                'zip_code' => $request->zip_code,
                'phone' => $request->phone,
                'cpf' => $request->cpf,
                'status' => $request->status,
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            ]);

            return redirect()->route('admin.index')->with('success', 'Usuário atualizado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.index')->with('success', 'Usuário excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao excluir usuário: ' . $e->getMessage());
        }
    }
}