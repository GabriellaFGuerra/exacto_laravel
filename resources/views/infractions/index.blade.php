<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cidade</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Placa</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Valor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($infractions as $infraction)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $infraction->id }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $infraction->user->name ?? 'N/A' }}</div>
                                                            <div class="text-sm text-gray-500">{{ $infraction->user->email ?? 'N/A' }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $infraction->type ?? 'N/A' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $infraction->city ?? 'N/A' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $infraction->date ? $infraction->date->format('d/m/Y') : 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $infraction->license_plate ?? 'N/A' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            R$ {{ number_format($infraction->amount ?? 0, 2, ',', '.') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $infraction->status == 'active' ? 'bg-yellow-100 text-yellow-800' :
                                ($infraction->status == 'paid' ? 'bg-green-100 text-green-800' :
                                    ($infraction->status == 'appealed' ? 'bg-blue-100 text-blue-800' :
                                        ($infraction->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                                                {{ ucfirst($infraction->status ?? 'pending') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                            <div class="flex space-x-2">
                                                                <a href="{{ route('infractions.show', $infraction) }}"
                                                                    class="text-blue-600 hover:text-blue-900">Ver</a>
                                                                <a href="{{ route('infractions.edit', $infraction) }}"
                                                                    class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                                                <form method="POST" action="{{ route('infractions.destroy', $infraction) }}"
                                                                    class="inline"
                                                                    onsubmit="return confirm('Tem certeza que deseja excluir esta infração?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="text-red-600 hover:text-red-900">Excluir</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Nenhuma infração encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($infractions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        {{ $infractions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>