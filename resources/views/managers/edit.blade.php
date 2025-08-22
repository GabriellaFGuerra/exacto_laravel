<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Gerente: ') }}{{ $manager->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('managers.show', $manager) }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Ver
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('managers.update', $manager) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informações Básicas -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Pessoais</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nome -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nome
                                            Completo</label>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name') ?? $manager->name }}" required
                                            placeholder="Nome completo do gerente"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700">E-mail</label>
                                        <input type="email" name="email" id="email"
                                            value="{{ old('email') ?? $manager->email }}" required
                                            placeholder="email@exemplo.com"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Telefone -->
                                    <div>
                                        <label for="phone"
                                            class="block text-sm font-medium text-gray-700">Telefone</label>
                                        <input type="text" name="phone" id="phone"
                                            value="{{ old('phone') ?? $manager->phone }}" placeholder="(11) 99999-9999"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('phone') border-red-500 @enderror">
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- CPF -->
                                    <div>
                                        <label for="document"
                                            class="block text-sm font-medium text-gray-700">CPF</label>
                                        <input type="text" name="document" id="document"
                                            value="{{ old('document') ?? $manager->document }}"
                                            placeholder="000.000.000-00"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('document') border-red-500 @enderror">
                                        @error('document')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informações Profissionais -->
                            <div class="md:col-span-2 border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Profissionais</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Departamento -->
                                    <div>
                                        <label for="department"
                                            class="block text-sm font-medium text-gray-700">Departamento</label>
                                        <input type="text" name="department" id="department"
                                            value="{{ old('department') ?? $manager->department }}"
                                            placeholder="Ex: Financeiro, Jurídico, Administrativo"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('department') border-red-500 @enderror">
                                        @error('department')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Cargo -->
                                    <div>
                                        <label for="position"
                                            class="block text-sm font-medium text-gray-700">Cargo</label>
                                        <input type="text" name="position" id="position"
                                            value="{{ old('position') ?? $manager->position }}"
                                            placeholder="Ex: Gerente, Coordenador, Supervisor"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('position') border-red-500 @enderror">
                                        @error('position')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Data de Admissão -->
                                    <div>
                                        <label for="hire_date" class="block text-sm font-medium text-gray-700">Data de
                                            Admissão</label>
                                        <input type="date" name="hire_date" id="hire_date"
                                            value="{{ old('hire_date') ?? ($manager->hire_date ? $manager->hire_date->format('Y-m-d') : '') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('hire_date') border-red-500 @enderror">
                                        @error('hire_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label for="status"
                                            class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="status" id="status"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                                            <option value="1" {{ (old('status') ?? $manager->status) == 1 ? 'selected' : '' }}>Ativo</option>
                                            <option value="0" {{ (old('status') ?? $manager->status) == 0 ? 'selected' : '' }}>Inativo</option>
                                        </select>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informações Adicionais -->
                            <div class="md:col-span-2 border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Adicionais</h3>

                                <!-- Bio/Descrição -->
                                <div class="mb-6">
                                    <label for="bio"
                                        class="block text-sm font-medium text-gray-700">Biografia/Descrição</label>
                                    <textarea name="bio" id="bio" rows="4"
                                        placeholder="Informações sobre experiência, especialidades, responsabilidades..."
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('bio') border-red-500 @enderror">{{ old('bio') ?? $manager->bio }}</textarea>
                                    @error('bio')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Foto Atual -->
                                @if($manager->photo)
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Atual</label>
                                        <div class="flex items-center space-x-4">
                                            <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-200"
                                                src="{{ Storage::url($manager->photo) }}" alt="{{ $manager->name }}">
                                            <div>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="remove_photo" value="1"
                                                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-sm text-red-600">Remover foto atual</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Nova Foto do Perfil -->
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-700">
                                        {{ $manager->photo ? 'Alterar Foto do Perfil' : 'Foto do Perfil' }}
                                    </label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="photo"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                    <span>Clique para fazer upload</span>
                                                    <input id="photo" name="photo" type="file"
                                                        accept=".jpg,.jpeg,.png,.gif" class="sr-only">
                                                </label>
                                                <p class="pl-1">ou arraste a imagem</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF até 2MB</p>
                                        </div>
                                    </div>
                                    @error('photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Estatísticas de Uso -->
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Estatísticas</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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

                            @if($manager->budgets->count() > 0)
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <strong>Atenção:</strong> Este gerente está responsável por
                                                {{ $manager->budgets->count() }} orçamento(s).
                                                Alterações no status podem afetar orçamentos ativos.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('managers.show', $manager) }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Atualizar Gerente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Máscaras para formatação de campos
    document.addEventListener('DOMContentLoaded', function () {
        // Máscara para telefone
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '');
                if (x.length >= 11) {
                    x = x.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (x.length >= 7) {
                    x = x.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                } else if (x.length >= 3) {
                    x = x.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                }
                e.target.value = x;
            });
        }

        // Máscara para CPF
        const documentInput = document.getElementById('document');
        if (documentInput) {
            documentInput.addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '');
                if (x.length <= 11) {
                    x = x.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                    x = x.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
                    x = x.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
                    x = x.replace(/(\d{3})(\d{0,3})/, '$1.$2');
                }
                e.target.value = x;
            });
        }

        // Preview da foto
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Remove preview anterior
                        const existingPreview = document.querySelector('.photo-preview');
                        if (existingPreview) {
                            existingPreview.remove();
                        }

                        // Cria novo preview
                        const preview = document.createElement('div');
                        preview.className = 'photo-preview mt-4';
                        preview.innerHTML = `
                        <p class="text-sm text-gray-600 mb-2">Prévia da nova foto:</p>
                        <img src="${e.target.result}" alt="Preview" class="w-32 h-32 object-cover rounded-lg border">
                    `;
                        photoInput.parentNode.parentNode.parentNode.parentNode.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>