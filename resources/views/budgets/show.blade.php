<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Orçamento') }} #{{ $budget->id }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('budgets.edit', $budget) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="{{ route('budgets.index') }}"
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

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Informações Básicas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cliente</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $budget->customer->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tipo de Serviço</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $budget->serviceType->name ?? $budget->custom_service_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $budget->status == 'approved' ? 'bg-green-100 text-green-800' :
    ($budget->status == 'rejected' ? 'bg-red-100 text-red-800' :
        ($budget->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($budget->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Progresso</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $budget->progress ?? '0' }}%</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Usuário Responsável</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $budget->responsibleUser->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Gestor Responsável</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $budget->responsibleManager->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Prazo</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $budget->deadline ? $budget->deadline->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Data de Aprovação</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $budget->approval_date ? $budget->approval_date->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Criado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $budget->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($budget->observation)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-500">Observação</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $budget->observation }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Planilhas -->
            @if($budget->spreadsheet)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Planilhas</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @if(is_array($budget->spreadsheets))
                                @foreach($budget->spreadsheets as $index => $file)
                                    <div class="flex items-center justify-between p-3 border rounded-lg">
                                        <span class="text-sm text-gray-900">Arquivo {{ $index + 1 }}</span>
                                        <a href="{{ route('budgets.download', [$budget->id, $index]) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            Download
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center justify-between p-3 border rounded-lg">
                                    <span class="text-sm text-gray-900">Planilha Principal</span>
                                    <span class="text-sm text-gray-500">{{ $budget->spreadsheet }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Fornecedores -->
            @if($budget->providers->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Fornecedores</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Fornecedor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Observação</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anexo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($budget->providers as $provider)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $provider->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                R$ {{ number_format($provider->pivot->value ?? 0, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $provider->pivot->observation ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($provider->pivot->attachment)
                                                    <a href="{{ route('budgets.provider.download', [$budget->id, $provider->id]) }}"
                                                        class="text-blue-600 hover:text-blue-900">
                                                        Download
                                                    </a>
                                                @else
                                                    <span class="text-gray-500">Sem anexo</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Documentos -->
            @if($budget->documents->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data de
                                            Emissão</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data de
                                            Vencimento</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($budget->documents as $document)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $document->documentType->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $document->issue_date ? $document->issue_date->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $document->expiration_date ? $document->expiration_date->format('d/m/Y') : 'N/A' }}
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
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>