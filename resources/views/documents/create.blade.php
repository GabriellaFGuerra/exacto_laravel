<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Novo Documento') }}
            </h2>
            <a href="{{ route('documents.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
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
                    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nome do Documento -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome do
                                    Documento</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo de Documento -->
                            <div>
                                <label for="document_type_id" class="block text-sm font-medium text-gray-700">Tipo de
                                    Documento</label>
                                <select name="document_type_id" id="document_type_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                    <option value="">Selecione o tipo</option>
                                    @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('document_type_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cliente -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                <select name="user_id" id="user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                    <option value="">Selecione um cliente</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Orçamento (Opcional) -->
                            <div>
                                <label for="budget_id" class="block text-sm font-medium text-gray-700">
                                    Orçamento <span class="text-gray-500">(Opcional)</span>
                                </label>
                                <select name="budget_id" id="budget_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione um orçamento</option>
                                    @foreach($budgets as $budget)
                                    <option value="{{ $budget->id }}"
                                        {{ old('budget_id') == $budget->id ? 'selected' : '' }}>
                                        #{{ $budget->id }} -
                                        {{ $budget->serviceType->name ?? $budget->custom_service_type ?? 'N/A' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('budget_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Infração (Opcional) -->
                            <div>
                                <label for="infraction_id" class="block text-sm font-medium text-gray-700">
                                    Infração <span class="text-gray-500">(Opcional)</span>
                                </label>
                                <select name="infraction_id" id="infraction_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione uma infração</option>
                                    @foreach($infractions as $infraction)
                                    <option value="{{ $infraction->id }}"
                                        {{ old('infraction_id') == $infraction->id ? 'selected' : '' }}>
                                        #{{ $infraction->id }} - {{ $infraction->type ?? 'N/A' }} -
                                        {{ $infraction->city ?? 'N/A' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('infraction_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente
                                    </option>
                                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>
                                        Aprovado</option>
                                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejeitado</option>
                                </select>
                                @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Descrição do documento...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload de Arquivo -->
                        <div class="mb-6">
                            <label for="file" class="block text-sm font-medium text-gray-700">Arquivo</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Envie um arquivo</span>
                                            <input id="file" name="file" type="file" class="sr-only"
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.zip,.rar" required>
                                        </label>
                                        <p class="pl-1">ou arraste e solte</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF, PDF, DOC, DOCX, ZIP até 10MB
                                    </p>
                                </div>
                            </div>
                            @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview do arquivo selecionado -->
                        <div id="file-preview" class="mb-6 hidden">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900">Arquivo selecionado:</h4>
                                <div class="mt-2 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span id="file-name" class="ml-2 text-sm text-gray-900"></span>
                                    <span id="file-size" class="ml-2 text-sm text-gray-500"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('documents.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Salvar Documento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para preview do arquivo -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileSize.textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                filePreview.classList.remove('hidden');
            } else {
                filePreview.classList.add('hidden');
            }
        });

        // Filtro de orçamentos baseado no cliente selecionado
        const userSelect = document.getElementById('user_id');
        const budgetSelect = document.getElementById('budget_id');
        const infractionSelect = document.getElementById('infraction_id');

        if (userSelect) {
            userSelect.addEventListener('change', function() {
                const userId = this.value;

                // Filtrar orçamentos
                if (budgetSelect) {
                    Array.from(budgetSelect.options).forEach(option => {
                        if (option.value === '') return;
                        const optionUserId = option.dataset.userId;
                        if (userId === '' || optionUserId === userId) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                    budgetSelect.value = '';
                }

                // Filtrar infrações
                if (infractionSelect) {
                    Array.from(infractionSelect.options).forEach(option => {
                        if (option.value === '') return;
                        const optionUserId = option.dataset.userId;
                        if (userId === '' || optionUserId === userId) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                    infractionSelect.value = '';
                }
            });
        }
    });
    </script>
</x-app-layout>