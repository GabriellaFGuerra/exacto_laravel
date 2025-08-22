<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Documento') }} - {{ $document->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('documents.edit', $document) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                @if($document->file_path)
                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Baixar
                </a>
                @endif
                <a href="{{ route('documents.index') }}"
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

            <!-- Informações do Documento -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Documento</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $document->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nome</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tipo</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->documentType->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $document->status == 'approved' ? 'bg-green-100 text-green-800' :
    ($document->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($document->status ?? 'pending') }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Data de Upload</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Última Atualização</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($document->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500">Descrição</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $document->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informações do Cliente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cliente</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nome</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Telefone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @if($document->user)
                    <div class="mt-4">
                        <a href="{{ route('customers.show', $document->user) }}"
                            class="text-blue-600 hover:text-blue-900">
                            Ver perfil completo do cliente
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Orçamento Relacionado -->
            @if($document->budget)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Orçamento Relacionado</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID do Orçamento</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $document->budget->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tipo de Serviço</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $document->budget->serviceType->name ?? $document->budget->custom_service_type ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $document->budget->status == 'approved' ? 'bg-green-100 text-green-800' :
                ($document->budget->status == 'rejected' ? 'bg-red-100 text-red-800' :
                    ($document->budget->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($document->budget->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('budgets.show', $document->budget) }}"
                            class="text-blue-600 hover:text-blue-900">
                            Ver orçamento completo
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Infração Relacionada -->
            @if($document->infraction)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Infração Relacionada</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID da Infração</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $document->infraction->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tipo</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->infraction->type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cidade</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->infraction->city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Data</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $document->infraction->date ? $document->infraction->date->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Valor</label>
                            <p class="mt-1 text-sm text-gray-900">
                                R$ {{ number_format($document->infraction->amount ?? 0, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('infractions.show', $document->infraction) }}"
                            class="text-blue-600 hover:text-blue-900">
                            Ver infração completa
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Arquivo -->
            @if($document->file_path)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Arquivo</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ basename($document->file_path) }}</p>
                            <p class="text-sm text-gray-500">
                                Tipo: {{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Visualizar/Baixar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Nenhum arquivo foi anexado a este documento.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>