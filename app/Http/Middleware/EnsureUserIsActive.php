<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
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

        // Verifica se o usuário está ativo
        // Usando o getter diretamente ao invés de chamar um método
        if ($user->status !== true) {
            return $this->unauthorized($request);
        }

        return $next($request);
    }

    /**
     * Handle unauthorized response
     */
    private function unauthorized($request)
    {
        // Verifica se o usuário está autenticado antes de fazer logout
        if (Auth::check()) {
            Auth::logout();

            // Verifica se a sessão está disponível antes de tentar usá-la
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Conta inativa. Por favor, contate o administrador.'], 403);
        }

        return redirect()->route('login')
            ->with('error', 'Sua conta está inativa. Por favor, contate o administrador.');
    }
}