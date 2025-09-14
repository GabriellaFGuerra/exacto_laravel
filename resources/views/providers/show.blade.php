<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Fornecedor') }} - {{ $provider->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('providers.edit', $provider) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="{{ route('providers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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

            <!-- Informações Básicas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nome</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Razão Social</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->company_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Telefone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">CNPJ</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->cnpj ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $provider->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $provider->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cadastrado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($provider->observation)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500">Observações</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->observation }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Endereço -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Endereço</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Endereço</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Número</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Complemento</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->complement ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Bairro</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->neighborhood ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">CEP</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->zip_code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cidade</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Estado</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $provider->state ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serviços Oferecidos -->
            @if($provider->providerServices && $provider->providerServices->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Serviços Oferecidos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($provider->providerServices as $providerService)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $providerService->serviceType->name ?? 'N/A' }}</h4>
                                    @if($providerService->price)
                                        <p class="text-sm text-gray-600">
                                            Preço: R$ {{ number_format($providerService->price, 2, ',', '.') }}
                                        </p>
                                    @endif
                                    @if($providerService->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ $providerService->description }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-2">
                                        Status: 
                                        <span class="px-2 inline-flex text-xs leading-4 font-semibold rounded-full 
                                            {{ $providerService->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $providerService->status ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Orçamentos Relacionados -->
            @if($provider->budgets && $provider->budgets->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Orçamentos</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo de Serviço</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($provider->budgets as $budget)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $budget->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $budget->customer->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $budget->serviceType->name ?? $budget->custom_service_type ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $budget->status == 'approved' ? 'bg-green-100 text-green-800' : 
                                                       ($budget->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                                                       ($budget->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst($budget->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                R$ {{ number_format($budget->amount ?? 0, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('budgets.show', $budget) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
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
