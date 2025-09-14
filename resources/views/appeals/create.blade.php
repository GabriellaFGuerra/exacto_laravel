<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Novo Recurso') }}
            </h2>
            <a href="{{ route('appeals.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('appeals.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Infração -->
                            <div class="md:col-span-2">
                                <label for="infraction_id"
                                    class="block text-sm font-medium text-gray-700">Infração</label>
                                <select name="infraction_id" id="infraction_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('infraction_id') border-red-500 @enderror">
                                    <option value="">Selecione uma infração</option>
                                    @foreach($infractions as $infraction)
                                        <option value="{{ $infraction->id }}" {{ old('infraction_id') == $infraction->id ? 'selected' : '' }}>
                                            #{{ $infraction->id }} - {{ $infraction->type ?? 'N/A' }}
                                            ({{ $infraction->user->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('infraction_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cliente -->
                            <div class="md:col-span-2">
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                <select name="user_id" id="user_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('user_id') border-red-500 @enderror">
                                    <option value="">Selecione um cliente</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Motivo -->
                            <div class="md:col-span-2">
                                <label for="reason" class="block text-sm font-medium text-gray-700">Motivo do
                                    Recurso</label>
                                <textarea name="reason" id="reason" rows="4" required
                                    placeholder="Descreva detalhadamente o motivo do recurso..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
                                @error('reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Documentos de Apoio -->
                            <div class="md:col-span-2">
                                <label for="supporting_documents"
                                    class="block text-sm font-medium text-gray-700">Documentos de Apoio</label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="supporting_documents"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Clique para fazer upload</span>
                                                <input id="supporting_documents" name="supporting_documents[]"
                                                    type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                    class="sr-only">
                                            </label>
                                            <p class="pl-1">ou arraste os arquivos</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, PNG, JPG até 10MB cada</p>
                                    </div>
                                </div>
                                @error('supporting_documents')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('supporting_documents.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                        Pendente</option>
                                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Aprovado
                                    </option>
                                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejeitado
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Data de Análise -->
                            <div>
                                <label for="decision_date" class="block text-sm font-medium text-gray-700">Data de
                                    Análise</label>
                                <input type="datetime-local" name="decision_date" id="decision_date"
                                    value="{{ old('decision_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('decision_date') border-red-500 @enderror">
                                @error('decision_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Comentários da Decisão -->
                            <div class="md:col-span-2">
                                <label for="decision_comments"
                                    class="block text-sm font-medium text-gray-700">Comentários da Decisão</label>
                                <textarea name="decision_comments" id="decision_comments" rows="3"
                                    placeholder="Comentários sobre a decisão tomada..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('decision_comments') border-red-500 @enderror">{{ old('decision_comments') }}</textarea>
                                @error('decision_comments')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('appeals.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Criar Recurso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Preview dos arquivos selecionados
    document.getElementById('supporting_documents').addEventListener('change', function (e) {
        const files = e.target.files;
        const fileList = document.createElement('div');
        fileList.className = 'mt-2';

        // Remove preview anterior se existir
        const existingPreview = document.querySelector('.file-preview');
        if (existingPreview) {
            existingPreview.remove();
        }

        if (files.length > 0) {
            fileList.className += ' file-preview';
            fileList.innerHTML = '<p class="text-sm text-gray-600 mb-2">Arquivos selecionados:</p>';

            Array.from(files).forEach(file => {
                const fileItem = document.createElement('div');
                fileItem.className = 'text-sm text-gray-500';
                fileItem.textContent = `• ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                fileList.appendChild(fileItem);
            });

            e.target.parentNode.parentNode.parentNode.parentNode.appendChild(fileList);
        }
    });
</script>