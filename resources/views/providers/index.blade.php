<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Fornecedores') }}
            </h2>
            <x-exacto-button href="{{ route('providers.create') }}">
                Novo Fornecedor
            </x-exacto-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="exacto-card mb-6">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Nome, email, CNPJ..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos os status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="exacto-btn-primary w-full mr-2">
                                Filtrar
                            </button>
                            <a href="{{ route('providers.index') }}"
                                class="exacto-btn-secondary w-full text-center">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Fornecedores -->
            <x-exacto-table>
                <x-slot name="header">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>CNPJ</th>
                        <th>Serviços</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </x-slot>
                
                <x-slot name="body">
                    @forelse($providers as $provider)
                        <tr>
                            <td>{{ $provider->id }}</td>
                            <td>
                                <div>{{ $provider->name }}</div>
                                @if($provider->company_name)
                                    <div class="text-gray-500">{{ $provider->company_name }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $provider->email ?? 'N/A' }}</div>
                                <div class="text-gray-500">{{ $provider->phone ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $provider->cnpj ?? 'N/A' }}</td>
                            <td>
                                {{ $provider->services->count() }} serviço{{ $provider->services->count() === 1 ? '' : 's' }}
                            </td>
                            <td>
                                <span class="status-badge {{ $provider->status ? 'status-active' : 'status-inactive' }}">
                                    {{ $provider->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('providers.show', $provider) }}" class="action-link view">Ver</a>
                                    <a href="{{ route('providers.edit', $provider) }}" class="action-link edit">Editar</a>
                                    <form method="POST" action="{{ route('providers.destroy', $provider) }}" class="inline"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link delete">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhum fornecedor encontrado.</td>
                        </tr>
                    @endforelse
                </x-slot>
                
                <x-slot name="pagination">
                    {{ $providers->appends(request()->query())->links('pagination.exacto') }}
                </x-slot>
            </x-exacto-table>
        </div>
    </div>
</x-app-layout>