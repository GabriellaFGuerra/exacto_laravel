<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerentes') }}
            </h2>
            <a href="{{ route('managers.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Novo Gerente
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

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Nome, email..."
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
                                class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Filtrar
                            </button>
                            <a href="{{ route('managers.index') }}"
                                class="w-full text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Gerentes -->
            <x-exacto-table>
                <x-slot name="header">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>Orçamentos Gerenciados</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </x-slot>
                
                <x-slot name="body">
                    @forelse($managers as $manager)
                        <tr>
                            <td>{{ $manager->id }}</td>
                            <td>{{ $manager->name }}</td>
                            <td>
                                <div>{{ $manager->email ?? 'N/A' }}</div>
                                <div class="text-gray-500">{{ $manager->phone ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $manager->budgets->count() }}</td>
                            <td>
                                <span class="status-badge {{ $manager->status ? 'status-active' : 'status-inactive' }}">
                                    {{ $manager->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('managers.show', $manager) }}" class="action-link view">Ver</a>
                                    <a href="{{ route('managers.edit', $manager) }}" class="action-link edit">Editar</a>
                                    <form method="POST" action="{{ route('managers.destroy', $manager) }}"
                                        class="inline"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este gerente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link delete">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum gerente encontrado.</td>
                        </tr>
                    @endforelse
                </x-slot>
                
                <x-slot name="pagination">
                    {{ $managers->appends(request()->query())->links('pagination.exacto') }}
                </x-slot>
            </x-exacto-table>
        </div>
    </div>
</x-app-layout>