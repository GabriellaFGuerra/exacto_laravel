<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Orçamentos') }}
            </h2>
            <a href="{{ route('budgets.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Novo Orçamento') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-exacto-table>
                <x-slot name="header">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Tipo de Serviço</th>
                        <th>Status</th>
                        <th>Progresso</th>
                        <th>Prazo</th>
                        <th>Ações</th>
                    </tr>
                </x-slot>
                
                <x-slot name="body">
                    @forelse($budgets as $budget)
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
                        <td>{{ $budget->deadline ? $budget->deadline->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('budgets.show', $budget) }}" class="action-link view">Ver</a>
                                <a href="{{ route('budgets.edit', $budget) }}" class="action-link edit">Editar</a>
                                <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link delete"
                                        onclick="return confirm('Tem certeza que deseja excluir este orçamento?')">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            Nenhum orçamento encontrado.
                        </td>
                    </tr>
                    @endforelse
                </x-slot>
                
                <x-slot name="pagination">
                    {{ $budgets->appends(request()->query())->links('pagination.exacto') }}
                </x-slot>
            </x-exacto-table>
        </div>
    </div>
</x-app-layout>