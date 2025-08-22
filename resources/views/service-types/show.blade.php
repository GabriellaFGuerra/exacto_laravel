<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tipo de Serviço') }} - {{ $serviceType->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('service-types.edit', $serviceType) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="{{ route('service-types.index') }}"
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

            <!-- Informações do Tipo de Serviço -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $serviceType->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nome</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $serviceType->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $serviceType->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $serviceType->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Criado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $serviceType->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Atualizado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $serviceType->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total de Orçamentos</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $serviceType->budgets->count() }}</p>
                        </div>
                    </div>

                    @if($serviceType->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500">Descrição</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $serviceType->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Orçamentos Relacionados -->
            @if($serviceType->budgets && $serviceType->budgets->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Orçamentos</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Fornecedor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($serviceType->budgets->take(10) as $budget)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $budget->id }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    {{ $budget->customer->name ?? 'N/A' }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    {{ $budget->provider->name ?? 'N/A' }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    R$ {{ number_format($budget->amount ?? 0, 2, ',', '.') }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <span
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                {{ $budget->status == 'approved' ? 'bg-green-100 text-green-800' :
                                        ($budget->status == 'rejected' ? 'bg-red-100 text-red-800' :
                                            ($budget->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                                        {{ ucfirst($budget->status) }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    {{ $budget->created_at->format('d/m/Y') }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                    <a href="{{ route('budgets.show', $budget) }}"
                                                                        class="text-blue-600 hover:text-blue-900">Ver</a>
                                                                </td>
                                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($serviceType->budgets->count() > 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">Mostrando 10 de {{ $serviceType->budgets->count() }}
                                    orçamentos.</p>
                                <a href="{{ route('budgets.index', ['service_type_id' => $serviceType->id]) }}"
                                    class="text-blue-600 hover:text-blue-900">Ver todos</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Fornecedores que oferecem este serviço -->
            @if($serviceType->providerServices && $serviceType->providerServices->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Fornecedores</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($serviceType->providerServices as $providerService)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $providerService->provider->name ?? 'N/A' }}</h4>
                                    @if($providerService->provider->company_name)
                                        <p class="text-sm text-gray-600">{{ $providerService->provider->company_name }}</p>
                                    @endif
                                    @if($providerService->price)
                                        <p class="text-sm text-green-600 font-medium">
                                            Preço: R$ {{ number_format($providerService->price, 2, ',', '.') }}
                                        </p>
                                    @endif
                                    @if($providerService->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($providerService->description, 60) }}
                                        </p>
                                    @endif
                                    <div class="mt-2 flex items-center justify-between">
                                        <span
                                            class="px-2 inline-flex text-xs leading-4 font-semibold rounded-full 
                                                    {{ $providerService->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $providerService->status ? 'Ativo' : 'Inativo' }}
                                        </span>
                                        @if($providerService->provider)
                                            <a href="{{ route('providers.show', $providerService->provider) }}"
                                                class="text-blue-600 hover:text-blue-900 text-sm">Ver fornecedor</a>
                                        @endif
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