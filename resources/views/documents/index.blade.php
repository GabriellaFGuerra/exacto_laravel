<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Documentos') }}
            </h2>
            <a href="{{ route('documents.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Novo Documento
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
                                placeholder="Nome do documento, cliente..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="document_type_id" class="block text-sm font-medium text-gray-700">Tipo de
                                Documento</label>
                            <select name="document_type_id" id="document_type_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos os tipos</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="user_id" id="user_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos os clientes</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Filtrar
                            </button>
                            <a href="{{ route('documents.index') }}"
                                class="w-full text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Documentos -->
            <x-exacto-table>
                <x-slot name="header">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Orçamento</th>
                        <th>Infração</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </x-slot>
                
                <x-slot name="body">
                    @forelse($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>
                            <div>{{ $document->name ?? 'Documento' }}</div>
                            @if($document->description)
                            <div class="text-gray-500">{{ Str::limit($document->description, 30) }}</div>
                            @endif
                        </td>
                        <td>{{ $document->documentType->name ?? 'N/A' }}</td>
                        <td>
                            <div>{{ $document->user->name ?? 'N/A' }}</div>
                            <div class="text-gray-500">{{ $document->user->email ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @if($document->budget)
                            <a href="{{ route('budgets.show', $document->budget) }}" class="text-primary hover:text-primary-dark">
                                #{{ $document->budget->id }}
                            </a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td>
                            @if($document->infraction)
                            <a href="{{ route('infractions.show', $document->infraction) }}" class="text-primary hover:text-primary-dark">
                                #{{ $document->infraction->id }}
                            </a>
                            @else
                            N/A
                            @endif
                        </td>
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
                            <div class="flex space-x-2">
                                @if($document->file_path)
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="action-link download">Baixar</a>
                                @endif
                                <a href="{{ route('documents.show', $document) }}" class="action-link view">Ver</a>
                                <a href="{{ route('documents.edit', $document) }}" class="action-link edit">Editar</a>
                                <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este documento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link delete">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Nenhum documento encontrado.</td>
                    </tr>
                    @endforelse
                </x-slot>
                
                <x-slot name="pagination">
                    {{ $documents->appends(request()->query())->links('pagination.exacto') }}
                </x-slot>
            </x-exacto-table>
        </div>
    </div>
</x-app-layout>