<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Document;
use App\Models\Infraction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with latest activities.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Contagem total de registros
        $budgetCount = Budget::count();
        $infractionCount = Infraction::count();
        $documentCount = Document::count();

        // Obter os últimos 5 registros de cada tipo
        $recentBudgets = Budget::with(['customer', 'serviceType'])
            ->latest()
            ->take(5)
            ->get();

        // Adicionar status default para infrações se necessário
        $recentInfractions = Infraction::with(['customer'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($infraction) {
                // Se não existir o status, definimos como 'active' como padrão
                if (!isset($infraction->status)) {
                    $infraction->status = 'active';
                }
                return $infraction;
            });

        // Adicionar status default para documentos se necessário
        $recentDocuments = Document::with(['customer', 'documentType'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($document) {
                // Se não existir o status, definimos como 'pending' como padrão
                if (!isset($document->status)) {
                    $document->status = 'pending';
                }
                return $document;
            });

        return view('dashboard', compact(
            'budgetCount',
            'infractionCount',
            'documentCount',
            'recentBudgets',
            'recentInfractions',
            'recentDocuments'
        ));
    }
}
