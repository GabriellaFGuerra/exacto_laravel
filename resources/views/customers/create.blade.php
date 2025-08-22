<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Novo Cliente') }}
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
                    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome/Razão Social
                                    *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Senha -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Senha *</label>
                                <input type="password" name="password" id="password" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Confirmação da Senha -->
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700">Confirmar Senha *</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- CNPJ -->
                            <div>
                                <label for="cnpj" class="block text-sm font-medium text-gray-700">CNPJ *</label>
                                <input type="text" name="cnpj" id="cnpj" value="{{ old('cnpj') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    maxlength="18">
                            </div>

                            <!-- CPF -->
                            <div>
                                <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                                <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    maxlength="14">
                            </div>

                            <!-- Telefone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telefone *</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Login -->
                            <div>
                                <label for="login" class="block text-sm font-medium text-gray-700">Login</label>
                                <input type="text" name="login" id="login" value="{{ old('login') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Endereço -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Endereço *</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Número -->
                            <div>
                                <label for="number" class="block text-sm font-medium text-gray-700">Número *</label>
                                <input type="text" name="number" id="number" value="{{ old('number') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Complemento -->
                            <div>
                                <label for="complement"
                                    class="block text-sm font-medium text-gray-700">Complemento</label>
                                <input type="text" name="complement" id="complement" value="{{ old('complement') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Bairro -->
                            <div>
                                <label for="neighborhood" class="block text-sm font-medium text-gray-700">Bairro
                                    *</label>
                                <input type="text" name="neighborhood" id="neighborhood"
                                    value="{{ old('neighborhood') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- CEP -->
                            <div>
                                <label for="zip_code" class="block text-sm font-medium text-gray-700">CEP *</label>
                                <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    maxlength="10">
                            </div>

                            <!-- Município -->
                            <div>
                                <label for="municipality" class="block text-sm font-medium text-gray-700">Município
                                    *</label>
                                <select name="municipality" id="municipality" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Selecione um município</option>
                                    <!-- Note: You'll need to pass municipalities from the controller -->
                                </select>
                            </div>

                            <!-- Notificação -->
                            <div>
                                <label for="notification"
                                    class="block text-sm font-medium text-gray-700">Notificações</label>
                                <select name="notification" id="notification"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="0" {{ old('notification') == '0' ? 'selected' : '' }}>Desabilitadas
                                    </option>
                                    <option value="1" {{ old('notification') == '1' ? 'selected' : '' }}>Habilitadas
                                    </option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                            </div>
                        </div>

                        <!-- Foto -->
                        <div class="mt-6">
                            <label for="photo" class="block text-sm font-medium text-gray-700">Foto</label>
                            <input type="file" name="photo" id="photo" accept="image/*" class="mt-1 block w-full">
                            <p class="mt-1 text-sm text-gray-500">Selecione uma imagem (JPEG, PNG, JPG, GIF)</p>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-4">
                            <a href="{{ route('customers.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Criar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CNPJ mask
        document.getElementById('cnpj').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
            e.target.value = value;
        });

        // CPF mask
        document.getElementById('cpf').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})/, '$1-$2');
            e.target.value = value;
        });

        // CEP mask
        document.getElementById('zip_code').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });

        // Phone mask
        document.getElementById('phone').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            e.target.value = value;
        });
    </script>
</x-app-layout>