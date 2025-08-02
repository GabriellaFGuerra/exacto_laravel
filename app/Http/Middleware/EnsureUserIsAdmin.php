<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está logado
        if (!Auth::check()) {
            return $this->unauthorized($request);
        }

        $user = Auth::user();

        // Verifica se o usuário é admin e está ativo
        // Usando os getters diretamente ao invés de chamar métodos
        if ($user->user_type !== 'admin' || $user->status !== true) {
            return $this->unauthorized($request);
        }

        return $next($request);
    }

    /**
     * Handle unauthorized response
     */
    private function unauthorized($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Não autorizado. Acesso apenas para administradores.'], 403);
        }

        return redirect()->route('home')
            ->with('error', 'Não autorizado. Acesso apenas para administradores.');
    }
}