<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Gerente: ') }}{{ $manager->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('managers.edit', $manager) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="{{ route('managers.index') }}"
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

            <!-- Perfil do Gerente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
                        <!-- Foto do Perfil -->
                        <div class="flex-shrink-0 mb-6 md:mb-0">
                            @if($manager->photo)
                                <img class="h-32 w-32 rounded-full object-cover border-4 border-gray-200"
                                    src="{{ Storage::url($manager->photo) }}" alt="{{ $manager->name }}">
                            @else
                                <div
                                    class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-300">
                                    <svg class="h-16 w-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Informações Básicas -->
                        <div class="flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Pessoais</h3>
                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Nome:</span>
                                            <span class="text-sm text-gray-900 ml-2">{{ $manager->name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">E-mail:</span>
                                            <span
                                                class="text-sm text-gray-900 ml-2">{{ $manager->email ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Telefone:</span>
                                            <span
                                                class="text-sm text-gray-900 ml-2">{{ $manager->phone ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">CPF:</span>
                                            <span
                                                class="text-sm text-gray-900 ml-2">{{ $manager->document ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Status:</span>
                                            <span
                                                class="px-2 py-1 ml-2 text-xs font-semibold rounded-full 
                                                {{ $manager->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $manager->status ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Profissionais</h3>
                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Departamento:</span>
                                            <span
                                                class="text-sm text-gray-900 ml-2">{{ $manager->department ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Cargo:</span>
                                            <span
                                                class="text-sm text-gray-900 ml-2">{{ $manager->position ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Data de Admissão:</span>
                                            <span class="text-sm text-gray-900 ml-2">
                                                {{ $manager->hire_date ? $manager->hire_date->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Tempo de Casa:</span>
                                            <span class="text-sm text-gray-900 ml-2">
                                                @if($manager->hire_date)
                                                    {{ $manager->hire_date->diffForHumans(null, true) }}
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Criado em:</span>
                                            <span
                                                class="text-sm text-gray-900 ml-2">{{ $manager->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biografia -->
                    @if($manager->bio)
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Biografia</h3>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $manager->bio }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Estatísticas de Orçamentos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estatísticas de Orçamentos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $manager->budgets->count() }}</div>
                            <div class="text-sm text-gray-600">Total de Orçamentos</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $manager->budgets->where('status', 'approved')->count() }}</div>
                            <div class="text-sm text-gray-600">Aprovados</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ $manager->budgets->where('status', 'pending')->count() }}</div>
                            <div class="text-sm text-gray-600">Pendentes</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                R$ {{ number_format($manager->budgets->sum('total_amount') ?? 0, 2, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-600">Valor Total</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orçamentos Recentes -->
            @if($manager->budgets->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Orçamentos Recentes</h3>
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
                                            Serviço</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Valor</th>
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
                                    @foreach($manager->budgets->take(10) as $budget)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $budget->id }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <div class="text-sm font-medium text-gray-900">
                                                                        <a href="{{ route('customers.show', $budget->user) }}"
                                                                            class="text-blue-600 hover:text-blue-900">
                                                                            {{ $budget->user->name ?? 'N/A' }}
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <div class="text-sm text-gray-900">{{ $budget->serviceType->name ?? 'N/A' }}
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    R$ {{ number_format($budget->total_amount ?? 0, 2, ',', '.') }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <span
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                {{ $budget->status == 'approved' ? 'bg-green-100 text-green-800' :
                                        ($budget->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                                        {{ ucfirst($budget->status ?? 'pending') }}
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

                        @if($manager->budgets->count() > 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('budgets.index', ['manager_id' => $manager->id]) }}"
                                    class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    Ver todos os {{ $manager->budgets->count() }} orçamentos
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0118 28c2.624 0 4.928 1.006 6.713 2.714"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum orçamento encontrado</h3>
                            <p class="mt-1 text-sm text-gray-500">Este gerente ainda não foi responsável por nenhum
                                orçamento.</p>
                            <div class="mt-6">
                                <a href="{{ route('budgets.create', ['manager_id' => $manager->id]) }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Criar Orçamento
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ações Rápidas -->
            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('managers.edit', $manager) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar Gerente
                </a>
                <a href="{{ route('budgets.create', ['manager_id' => $manager->id]) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Novo Orçamento
                </a>
                <a href="{{ route('budgets.index', ['manager_id' => $manager->id]) }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Ver Orçamentos
                </a>
            </div>
        </div>
    </div>
</x-app-layout>