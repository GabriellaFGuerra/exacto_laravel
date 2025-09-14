<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meus Orçamentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <x-exacto-table>
                <x-slot name="header">
                    <tr>
                        <th>ID</th>
                        <th>Tipo de Serviço</th>
                        <th>Status</th>
                        <th>Progresso</th>
                        <th>Prazo</th>
                        <th>Responsável</th>
                        <th>Ações</th>
                    </tr>
                </x-slot>
                
                <x-slot name="body">
                    @forelse($budgets as $budget)
                    <tr>
                        <td>{{ $budget->id }}</td>
                        <td>{{ $budget->serviceType->name ?? $budget->custom_service_type ?? 'N/A' }}</td>
                        <td>
                            @php
                            $statusClass = match ($budget->status) {
                            'approved' => 'status-success',
                            'rejected' => 'status-danger',
                            'pending' => 'status-warning',
                            default => 'status-default'
                            };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ ucfirst($budget->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full progress-bar" 
                                         data-progress="{{ $budget->progress ?? 0 }}"></div>
                                </div>
                                <span class="ml-2">{{ $budget->progress ?? 0 }}%</span>
                            </div>
                        </td>
                        <td>{{ $budget->deadline ? $budget->deadline->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $budget->responsibleUser->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('budgets.show', $budget) }}" class="action-link view">Ver Detalhes</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Nenhum orçamento encontrado.</td>
                    </tr>
                    @endforelse
                </x-slot>
                
                <x-slot name="pagination">
                    {{ $budgets->appends(request()->query())->links('pagination.exacto') }}
                </x-slot>
            </x-exacto-table>
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