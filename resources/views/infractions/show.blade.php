<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Detalhes da Infração') }} #{{ $infraction->id }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('infractions.edit', $infraction) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                @if($infraction->status == 'active')
                    <a href="{{ route('appeals.create', ['infraction_id' => $infraction->id]) }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Recursar
                    </a>
                @endif
                <a href="{{ route('infractions.index') }}"
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

            <!-- Informações da Infração -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Infração</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $infraction->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tipo</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($infraction->type) ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $infraction->status == 'active' ? 'bg-yellow-100 text-yellow-800' :
    ($infraction->status == 'paid' ? 'bg-green-100 text-green-800' :
        ($infraction->status == 'appealed' ? 'bg-blue-100 text-blue-800' :
            ($infraction->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                    {{ ucfirst($infraction->status ?? 'pending') }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Data</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $infraction->date ? $infraction->date->format('d/m/Y') : 'N/A' }}
                                @if($infraction->time)
                                    às {{ $infraction->time }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cidade</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Local</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->location ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Placa</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->license_plate ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Valor</label>
                            <p class="mt-1 text-sm text-gray-900 font-semibold text-lg">
                                R$ {{ number_format($infraction->amount ?? 0, 2, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Número do Auto</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->auto_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cadastrado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($infraction->observation)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500">Observações</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->observation }}</p>
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
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Telefone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $infraction->user->phone ?? 'N/A' }}</p>
                        </div>
                        @if($infraction->user && ($infraction->user->cnpj || $infraction->user->cpf))
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Documento</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $infraction->user->cnpj ?? $infraction->user->cpf ?? 'N/A' }}
                                </p>
                            </div>
                        @endif
                    </div>
                    @if($infraction->user)
                        <div class="mt-4">
                            <a href="{{ route('customers.show', $infraction->user) }}"
                                class="text-blue-600 hover:text-blue-900">
                                Ver perfil completo do cliente
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recursos (Appeals) -->
            @if($infraction->appeals && $infraction->appeals->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recursos</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($infraction->appeals as $appeal)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    {{ $appeal->created_at->format('d/m/Y H:i') }}
                                                                </td>
                                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                                    {{ Str::limit($appeal->reason ?? 'N/A', 50) }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <span
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                {{ $appeal->status == 'approved' ? 'bg-green-100 text-green-800' :
                                        ($appeal->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                                        {{ ucfirst($appeal->status ?? 'pending') }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                    <a href="{{ route('appeals.show', $appeal) }}"
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

            <!-- Documentos Relacionados -->
            @if($infraction->documents && $infraction->documents->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($infraction->documents as $document)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $document->name ?? 'Documento' }}</h4>
                                    <p class="text-sm text-gray-600">{{ $document->documentType->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Enviado em: {{ $document->created_at->format('d/m/Y H:i') }}
                                    </p>
                                    <div class="mt-2">
                                        @if($document->file_path)
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-900 text-sm">
                                                Ver documento
                                            </a>
                                        @endif
                                        <a href="{{ route('documents.show', $document) }}"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm ml-2">
                                            Detalhes
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>