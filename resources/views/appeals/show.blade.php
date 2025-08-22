<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Recurso #') }}{{ $appeal->id }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('appeals.edit', $appeal) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="{{ route('appeals.index') }}"
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
                    <!-- Status e Informações Básicas -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Status</h3>
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                {{ $appeal->status == 'approved' ? 'bg-green-100 text-green-800' :
    ($appeal->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($appeal->status ?? 'pending') }}
                            </span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Data de Criação</h3>
                            <p class="text-sm text-gray-600">{{ $appeal->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Data de Análise</h3>
                            <p class="text-sm text-gray-600">
                                {{ $appeal->decision_date ? $appeal->decision_date->format('d/m/Y H:i') : 'Não analisado' }}
                            </p>
                        </div>
                    </div>

                    <!-- Informações da Infração -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Infração Relacionada</h3>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">ID da Infração</h4>
                                    <p class="text-sm text-gray-600">
                                        <a href="{{ route('infractions.show', $appeal->infraction) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            #{{ $appeal->infraction->id ?? 'N/A' }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Tipo</h4>
                                    <p class="text-sm text-gray-600">{{ $appeal->infraction->type ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Placa</h4>
                                    <p class="text-sm text-gray-600">{{ $appeal->infraction->license_plate ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Valor</h4>
                                    <p class="text-sm text-gray-600">
                                        R$ {{ number_format($appeal->infraction->fine_amount ?? 0, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Cliente -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cliente</h3>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">Nome</h4>
                                    <p class="text-sm text-gray-600">
                                        <a href="{{ route('customers.show', $appeal->user) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            {{ $appeal->user->name ?? 'N/A' }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">E-mail</h4>
                                    <p class="text-sm text-gray-600">{{ $appeal->user->email ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Telefone</h4>
                                    <p class="text-sm text-gray-600">{{ $appeal->user->phone ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">CPF/CNPJ</h4>
                                    <p class="text-sm text-gray-600">{{ $appeal->user->document ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Motivo do Recurso -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Motivo do Recurso</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">
                                {{ $appeal->reason ?? 'Não informado' }}</p>
                        </div>
                    </div>

                    <!-- Comentários da Decisão -->
                    @if($appeal->decision_comments)
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Comentários da Decisão</h3>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $appeal->decision_comments }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Documentos de Apoio -->
                    @if($appeal->supporting_documents)
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos de Apoio</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @php
                                    $documents = is_string($appeal->supporting_documents)
                                        ? json_decode($appeal->supporting_documents, true)
                                        : $appeal->supporting_documents;
                                @endphp
                                @if(is_array($documents))
                                    @foreach($documents as $document)
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ basename($document) }}</p>
                                                    <a href="{{ Storage::url($document) }}" target="_blank"
                                                        class="text-sm text-blue-600 hover:text-blue-900">
                                                        Baixar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500">Nenhum documento anexado</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Histórico de Atualizações -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Histórico</h3>
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Recurso criado</p>
                                        <p class="text-sm text-gray-500">{{ $appeal->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <span
                                        class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">Criação</span>
                                </div>
                            </div>

                            @if($appeal->updated_at != $appeal->created_at)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Recurso atualizado</p>
                                            <p class="text-sm text-gray-500">{{ $appeal->updated_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Atualização</span>
                                    </div>
                                </div>
                            @endif

                            @if($appeal->decision_date)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Decisão tomada</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $appeal->decision_date->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <span
                                            class="px-2 py-1 text-xs font-medium 
                                                {{ $appeal->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded">
                                            {{ ucfirst($appeal->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>