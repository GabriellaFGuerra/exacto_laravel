<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estatísticas Gerais -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="exacto-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2 text-primary">Orçamentos</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold">{{ $budgetCount }}</span>
                            <a href="{{ route('budgets.index') }}" class="exacto-btn-primary text-sm">Ver todos</a>
                        </div>
                    </div>
                </div>

                <div class="exacto-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2 text-primary">Infrações</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold">{{ $infractionCount }}</span>
                            <a href="{{ route('infractions.index') }}" class="exacto-btn-primary text-sm">Ver todas</a>
                        </div>
                    </div>
                </div>

                <div class="exacto-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2 text-primary">Documentos</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold">{{ $documentCount }}</span>
                            <a href="{{ route('documents.index') }}" class="exacto-btn-primary text-sm">Ver todos</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimos Orçamentos -->
            <div class="exacto-card mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-primary">Últimos Orçamentos</h3>
                    <div class="overflow-x-auto">
                        <table class="exacto-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Tipo de Serviço</th>
                                    <th>Status</th>
                                    <th>Progresso</th>
                                    <th>Criado em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBudgets as $budget)
                                    <tr>
                                        <td>{{ $budget->id }}</td>
                                        <td>{{ $budget->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $budget->serviceType->name ?? $budget->custom_service_type ?? 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge 
                                                {{ $budget->status == 'approved' ? 'status-success' : 
                                                ($budget->status == 'rejected' ? 'status-danger' : 
                                                    ($budget->status == 'pending' ? 'status-warning' : 'status-default')) }}">
                                                {{ ucfirst($budget->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-primary h-2 rounded-full progress-bar" 
                                                        data-progress="{{ $budget->progress ?? 0 }}"></div>
                                                </div>
                                                <span class="ml-2">{{ $budget->progress ?? '0' }}%</span>
                                            </div>
                                        </td>
                                        <td>{{ $budget->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('budgets.show', $budget) }}" class="action-link view">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum orçamento encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Últimas Infrações -->
            <div class="exacto-card mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-primary">Últimas Infrações</h3>
                    <div class="overflow-x-auto">
                        <table class="exacto-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Tipo</th>
                                    <th>Cidade</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInfractions as $infraction)
                                    <tr>
                                        <td>{{ $infraction->id }}</td>
                                        <td>{{ $infraction->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $infraction->type ?? 'N/A' }}</td>
                                        <td>{{ $infraction->city ?? 'N/A' }}</td>
                                        <td>{{ $infraction->date ? $infraction->date->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge 
                                                {{ $infraction->status == 'active' ? 'status-warning' : 
                                                ($infraction->status == 'paid' ? 'status-success' : 
                                                    ($infraction->status == 'appealed' ? 'status-info' : 
                                                    ($infraction->status == 'cancelled' ? 'status-danger' : 'status-default'))) }}">
                                                {{ ucfirst($infraction->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('infractions.show', $infraction) }}" class="action-link view">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhuma infração encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Últimos Documentos -->
            <div class="exacto-card">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-primary">Últimos Documentos</h3>
                    <div class="overflow-x-auto">
                        <table class="exacto-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>Cliente</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDocuments as $document)
                                    <tr>
                                        <td>{{ $document->id }}</td>
                                        <td>{{ $document->title ?? 'Documento' }}</td>
                                        <td>{{ $document->documentType->name ?? 'N/A' }}</td>
                                        <td>{{ $document->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="status-badge 
                                                {{ $document->status == 'approved' ? 'status-success' : 
                                                ($document->status == 'rejected' ? 'status-danger' : 
                                                    ($document->status == 'pending' ? 'status-warning' : 'status-default')) }}">
                                                {{ ucfirst($document->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('documents.show', $document) }}" class="action-link view">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum documento encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.progress-bar').forEach(function(bar) {
                const progress = bar.getAttribute('data-progress');
                bar.style.width = progress + '%';
            });
        });
    </script>
</x-app-layout>
