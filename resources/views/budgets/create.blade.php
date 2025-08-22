<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Novo Orçamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('budgets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Cliente -->
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Cliente
                                    *</label>
                                <select name="customer_id" id="customer_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Selecione um cliente</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tipo de Serviço -->
                            <div>
                                <label for="service_type_id" class="block text-sm font-medium text-gray-700">Tipo de
                                    Serviço *</label>
                                <select name="service_type_id" id="service_type_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Selecione um tipo de serviço</option>
                                    @foreach($serviceTypes as $serviceType)
                                        <option value="{{ $serviceType->id }}" {{ old('service_type_id') == $serviceType->id ? 'selected' : '' }}>
                                            {{ $serviceType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tipo de Serviço Customizado -->
                            <div>
                                <label for="custom_service_type" class="block text-sm font-medium text-gray-700">Tipo de
                                    Serviço Customizado</label>
                                <input type="text" name="custom_service_type" id="custom_service_type"
                                    value="{{ old('custom_service_type') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Usuário Responsável -->
                            <div>
                                <label for="responsible_user_id" class="block text-sm font-medium text-gray-700">Usuário
                                    Responsável *</label>
                                <select name="responsible_user_id" id="responsible_user_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Selecione um usuário responsável</option>
                                    @foreach($responsibleUsers as $user)
                                        <option value="{{ $user->id }}" {{ old('responsible_user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Gestor Responsável -->
                            <div>
                                <label for="responsible_manager_id"
                                    class="block text-sm font-medium text-gray-700">Gestor Responsável</label>
                                <select name="responsible_manager_id" id="responsible_manager_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Selecione um gestor</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}" {{ old('responsible_manager_id') == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Progresso -->
                            <div>
                                <label for="progress" class="block text-sm font-medium text-gray-700">Progresso
                                    *</label>
                                <input type="text" name="progress" id="progress" value="{{ old('progress', '0') }}"
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Prazo -->
                            <div>
                                <label for="deadline" class="block text-sm font-medium text-gray-700">Prazo</label>
                                <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Data de Aprovação -->
                            <div>
                                <label for="approval_date" class="block text-sm font-medium text-gray-700">Data de
                                    Aprovação</label>
                                <input type="date" name="approval_date" id="approval_date"
                                    value="{{ old('approval_date') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                <select name="status" id="status" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Aberto</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente
                                    </option>
                                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Aprovado
                                    </option>
                                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejeitado
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Planilhas -->
                        <div class="mt-6">
                            <label for="spreadsheets" class="block text-sm font-medium text-gray-700">Planilhas</label>
                            <input type="file" name="spreadsheets[]" id="spreadsheets" multiple
                                accept=".xlsx,.xls,.csv,.pdf,.doc,.docx" class="mt-1 block w-full">
                            <p class="mt-1 text-sm text-gray-500">Selecione múltiplos arquivos (Excel, CSV, PDF, Word)
                            </p>
                        </div>

                        <!-- Observação -->
                        <div class="mt-6">
                            <label for="observation" class="block text-sm font-medium text-gray-700">Observação</label>
                            <textarea name="observation" id="observation" rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('observation') }}</textarea>
                        </div>

                        <!-- Fornecedores -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Fornecedores</h3>
                            <div id="providers-container">
                                <div class="provider-item border p-4 rounded-lg mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Fornecedor</label>
                                            <select name="providers[0][provider_id]"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Selecione um fornecedor</option>
                                                @foreach($providers as $provider)
                                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Valor</label>
                                            <input type="number" step="0.01" name="providers[0][value]"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Anexo</label>
                                            <input type="file" name="providers[0][attachment]"
                                                accept=".pdf,.doc,.docx,.xls,.xlsx" class="mt-1 block w-full">
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="block text-sm font-medium text-gray-700">Observação</label>
                                        <input type="text" name="providers[0][observation]"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div class="mt-2">
                                        <button type="button"
                                            class="remove-provider text-red-600 hover:text-red-900">Remover
                                            Fornecedor</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-provider"
                                class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Adicionar Fornecedor
                            </button>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-4">
                            <a href="{{ route('budgets.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Criar Orçamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let providerIndex = 1;

        document.getElementById('add-provider').addEventListener('click', function () {
            const container = document.getElementById('providers-container');
            const newProvider = document.querySelector('.provider-item').cloneNode(true);

            // Update the indices
            newProvider.querySelectorAll('select, input').forEach(function (element) {
                if (element.name) {
                    element.name = element.name.replace('[0]', '[' + providerIndex + ']');
                    element.value = '';
                }
            });

            container.appendChild(newProvider);
            providerIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-provider')) {
                const providers = document.querySelectorAll('.provider-item');
                if (providers.length > 1) {
                    e.target.closest('.provider-item').remove();
                }
            }
        });
    </script>
</x-app-layout>