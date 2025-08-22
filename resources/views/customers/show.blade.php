<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Cliente') }} - {{ $customer->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('customers.edit', $customer) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="{{ route('customers.index') }}"
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

            <!-- Informações Básicas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nome/Razão Social</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Login</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->login ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">CNPJ</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->cnpj ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">CPF</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->cpf ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Telefone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $customer->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $customer->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Notificações</label>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $customer->notification ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $customer->notification ? 'Habilitadas' : 'Desabilitadas' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cadastrado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Endereço</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Endereço</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Número</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Complemento</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->complement ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Bairro</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->neighborhood ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">CEP</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->zip_code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Município</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->municipality->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto -->
            @if($customer->photo)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Foto</h3>
                        <div class="flex items-center">
                            <img src="{{ asset('storage/' . $customer->photo) }}" alt="Foto do Cliente"
                                class="h-32 w-32 object-cover rounded-lg">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Orçamentos do Cliente -->
            @if($customer->customerBudgets && $customer->customerBudgets->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Orçamentos</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo de
                                            Serviço</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Progresso</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prazo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customer->customerBudgets as $budget)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $budget->id }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    {{ $budget->serviceType->name ?? $budget->custom_service_type ?? 'N/A' }}
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
                                                                    {{ $budget->progress ?? '0' }}%</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    {{ $budget->deadline ? $budget->deadline->format('d/m/Y') : 'N/A' }}
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
                    </div>
                </div>
            @endif

            <!-- Infrações do Cliente -->
            @if($customer->infractions && $customer->infractions->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Infrações</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cidade
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customer->infractions as $infraction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $infraction->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $infraction->type ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $infraction->city ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $infraction->date ? $infraction->date->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('infractions.show', $infraction) }}"
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