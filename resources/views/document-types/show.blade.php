<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Tipo de Documento: ') }}{{ $documentType->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('document_types.edit', $documentType) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="{{ route('document_types.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Informações Básicas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Informações Gerais</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">ID:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $documentType->id }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Nome:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $documentType->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Status:</span>
                                    <span
                                        class="px-2 py-1 ml-2 text-xs font-semibold rounded-full 
                                        {{ $documentType->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $documentType->status ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Datas</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Criado em:</span>
                                    <span
                                        class="text-sm text-gray-900 ml-2">{{ $documentType->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Atualizado em:</span>
                                    <span
                                        class="text-sm text-gray-900 ml-2">{{ $documentType->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    @if($documentType->description)
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Descrição</h3>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $documentType->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Estatísticas de Uso -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Estatísticas de Uso</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $documentType->documents->count() }}
                                </div>
                                <div class="text-sm text-gray-600">Total de Documentos</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $documentType->documents->where('status', 'approved')->count() }}</div>
                                <div class="text-sm text-gray-600">Documentos Aprovados</div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-yellow-600">
                                    {{ $documentType->documents->where('status', 'pending')->count() }}</div>
                                <div class="text-sm text-gray-600">Documentos Pendentes</div>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Relacionados -->
                    @if($documentType->documents->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos Recentes</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nome</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Relacionado</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Data</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($documentType->documents->take(10) as $document)
                                                                    <tr>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                            {{ $document->id }}</td>
                                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                                            <div class="text-sm font-medium text-gray-900">
                                                                                {{ $document->file_name ?? 'N/A' }}</div>
                                                                        </td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                            @if($document->budget_id)
                                                                                <a href="{{ route('budgets.show', $document->budget) }}"
                                                                                    class="text-blue-600 hover:text-blue-900">
                                                                                    Orçamento #{{ $document->budget_id }}
                                                                                </a>
                                                                            @elseif($document->infraction_id)
                                                                                <a href="{{ route('infractions.show', $document->infraction) }}"
                                                                                    class="text-blue-600 hover:text-blue-900">
                                                                                    Infração #{{ $document->infraction_id }}
                                                                                </a>
                                                                            @else
                                                                                N/A
                                                                            @endif
                                                                        </td>
                                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                                            <span
                                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                        {{ $document->status == 'approved' ? 'bg-green-100 text-green-800' :
                                            ($document->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                                                {{ ucfirst($document->status ?? 'pending') }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                            {{ $document->created_at->format('d/m/Y') }}
                                                                        </td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                            <a href="{{ route('documents.show', $document) }}"
                                                                                class="text-blue-600 hover:text-blue-900">Ver</a>
                                                                        </td>
                                                                    </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($documentType->documents->count() > 10)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('documents.index', ['document_type_id' => $documentType->id]) }}"
                                        class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        Ver todos os {{ $documentType->documents->count() }} documentos
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="border-t border-gray-200 pt-6">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0118 28c2.624 0 4.928 1.006 6.713 2.714"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum documento encontrado</h3>
                                <p class="mt-1 text-sm text-gray-500">Este tipo de documento ainda não foi utilizado.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Ações Rápidas -->
                    <div class="border-t border-gray-200 pt-6 mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ações Rápidas</h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('document_types.edit', $documentType) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Editar Tipo
                            </a>
                            <a href="{{ route('documents.create', ['document_type_id' => $documentType->id]) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Novo Documento
                            </a>
                            <a href="{{ route('documents.index', ['document_type_id' => $documentType->id]) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Ver Documentos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>