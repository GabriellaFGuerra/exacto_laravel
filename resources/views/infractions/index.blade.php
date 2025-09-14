<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Infrações') }}
            </h2>
            <a href="{{ route('infractions.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nova Infração
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
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Nome do cliente, tipo, cidade..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="type" id="type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos os tipos</option>
                                <option value="velocidade" {{ request('type') == 'velocidade' ? 'selected' : '' }}>
                                    Velocidade</option>
                                <option value="semaforo" {{ request('type') == 'semaforo' ? 'selected' : '' }}>Semáforo
                                </option>
                                <option value="estacionamento" {{ request('type') == 'estacionamento' ? 'selected' : '' }}>Estacionamento</option>
                                <option value="outros" {{ request('type') == 'outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">Cidade</label>
                            <input type="text" name="city" id="city" value="{{ request('city') }}"
                                placeholder="Nome da cidade"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Filtrar
                            </button>
                            <a href="{{ route('infractions.index') }}"
                                class="w-full text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Infrações -->
            <x-exacto-table>
                <x-slot name="header">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Cidade</th>
                        <th>Data</th>
                        <th>Placa</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </x-slot>
                
                <x-slot name="body">
                    @forelse($infractions as $infraction)
                        <tr>
                            <td>{{ $infraction->id }}</td>
                            <td>
                                <div>{{ $infraction->user->name ?? 'N/A' }}</div>
                                <div class="text-gray-500">{{ $infraction->user->email ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $infraction->type ?? 'N/A' }}</td>
                            <td>{{ $infraction->city ?? 'N/A' }}</td>
                            <td>{{ $infraction->date ? $infraction->date->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $infraction->license_plate ?? 'N/A' }}</td>
                            <td>R$ {{ number_format($infraction->amount ?? 0, 2, ',', '.') }}</td>
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
                                <div class="flex space-x-2">
                                    <a href="{{ route('infractions.show', $infraction) }}" class="action-link view">Ver</a>
                                    <a href="{{ route('infractions.edit', $infraction) }}" class="action-link edit">Editar</a>
                                    <form method="POST" action="{{ route('infractions.destroy', $infraction) }}"
                                        class="inline"
                                        onsubmit="return confirm('Tem certeza que deseja excluir esta infração?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link delete">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Nenhuma infração encontrada.</td>
                        </tr>
                    @endforelse
                </x-slot>
                
                <x-slot name="pagination">
                    {{ $infractions->appends(request()->query())->links('pagination.exacto') }}
                </x-slot>
            </x-exacto-table>
        </div>
    </div>
</x-app-layout>