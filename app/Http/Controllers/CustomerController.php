<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::where('user_type', 'customer')->get();
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'cnpj' => 'required|string|max:14|unique:users,cnpj',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'municipality' => 'required|integer|exists:municipalities,municipality_id',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 'customer',
                'cnpj' => $request->cnpj,
                'phone' => $request->phone,
                'address' => $request->address,
                'number' => $request->number,
                'municipality_id' => $request->municipality,
                'complement' => $request->complement,
                'neighborhood' => $request->neighborhood,
                'zip_code' => $request->zip_code,
                'photo' => $request->file('photo') ? $request->file('photo')->store('profile', 'public') : null,
            ]);
            return redirect()->route('customers.index')->with('success', 'Cliente cadastrado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro ao cadastrar cliente: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $customer = $user;
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $customer = $user;
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
            'cnpj' => 'required|string|max:14|unique:users,cnpj,' . $user->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'municipality' => 'required|integer|exists:municipalities,municipality_id',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'cnpj' => $request->cnpj,
                'phone' => $request->phone,
                'address' => $request->address,
                'number' => $request->number,
                'municipality_id' => $request->municipality,
                'complement' => $request->complement,
                'neighborhood' => $request->neighborhood,
                'zip_code' => $request->zip_code,
                'photo' => $request->file('photo') ? $request->file('photo')->store('profile', 'public') : $user->photo,
            ]);
            return redirect()->route('customers.index')->with('success', 'Cliente atualizado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro ao atualizar cliente: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('customers.index')->with('success', 'Cliente removido com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro ao remover cliente: ' . $e->getMessage()]);
        }
    }
}