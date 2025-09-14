<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Editar Cliente') }} - {{ $customer->name }}
            </h2>
            <a href="{{ route('customers.show', $customer) }}"
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
                    <form method="POST" action="{{ route('customers.update', $customer) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Informações Básicas -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Nome -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nome/Razão
                                        Social</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $customer->email) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Login -->
                                <div>
                                    <label for="login" class="block text-sm font-medium text-gray-700">Login</label>
                                    <input type="text" name="login" id="login"
                                        value="{{ old('login', $customer->login) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('login')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Telefone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                                    <input type="text" name="phone" id="phone"
                                        value="{{ old('phone', $customer->phone) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="(11) 99999-9999">
                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CNPJ -->
                                <div>
                                    <label for="cnpj" class="block text-sm font-medium text-gray-700">CNPJ</label>
                                    <input type="text" name="cnpj" id="cnpj" value="{{ old('cnpj', $customer->cnpj) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="00.000.000/0000-00">
                                    @error('cnpj')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CPF -->
                                <div>
                                    <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                                    <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $customer->cpf) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="000.000.000-00">
                                    @error('cpf')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status e Notificações -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="1" {{ old('status', $customer->status) == 1 ? 'selected' : '' }}>
                                            Ativo</option>
                                        <option value="0" {{ old('status', $customer->status) == 0 ? 'selected' : '' }}>
                                            Inativo</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notification"
                                        class="block text-sm font-medium text-gray-700">Notificações</label>
                                    <select name="notification" id="notification"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="1" {{ old('notification', $customer->notification) == 1 ? 'selected' : '' }}>Habilitadas</option>
                                        <option value="0" {{ old('notification', $customer->notification) == 0 ? 'selected' : '' }}>Desabilitadas</option>
                                    </select>
                                    @error('notification')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-6">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Nova Senha <span class="text-gray-500">(deixe em branco para manter a atual)</span>
                                </label>
                                <input type="password" name="password" id="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700">Confirmar Nova Senha</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Endereço</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Endereço -->
                                <div>
                                    <label for="address"
                                        class="block text-sm font-medium text-gray-700">Endereço</label>
                                    <input type="text" name="address" id="address"
                                        value="{{ old('address', $customer->address) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Número -->
                                <div>
                                    <label for="number" class="block text-sm font-medium text-gray-700">Número</label>
                                    <input type="text" name="number" id="number"
                                        value="{{ old('number', $customer->number) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Complemento -->
                                <div>
                                    <label for="complement"
                                        class="block text-sm font-medium text-gray-700">Complemento</label>
                                    <input type="text" name="complement" id="complement"
                                        value="{{ old('complement', $customer->complement) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('complement')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bairro -->
                                <div>
                                    <label for="neighborhood"
                                        class="block text-sm font-medium text-gray-700">Bairro</label>
                                    <input type="text" name="neighborhood" id="neighborhood"
                                        value="{{ old('neighborhood', $customer->neighborhood) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('neighborhood')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CEP -->
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium text-gray-700">CEP</label>
                                    <input type="text" name="zip_code" id="zip_code"
                                        value="{{ old('zip_code', $customer->zip_code) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="00000-000">
                                    @error('zip_code')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Município -->
                                <div>
                                    <label for="municipality_id"
                                        class="block text-sm font-medium text-gray-700">Município</label>
                                    <select name="municipality_id" id="municipality_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Selecione um município</option>
                                        @foreach($municipalities as $municipality)
                                            <option value="{{ $municipality->id }}" {{ old('municipality_id', $customer->municipality_id) == $municipality->id ? 'selected' : '' }}>
                                                {{ $municipality->name }} -
                                                {{ $municipality->federativeUnit->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('municipality_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Foto -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Foto</h3>

                            @if($customer->photo)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="Foto Atual"
                                        class="h-32 w-32 object-cover rounded-lg">
                                    <p class="text-sm text-gray-600 mt-2">Foto atual</p>
                                </div>
                            @endif

                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700">
                                    Nova Foto <span class="text-gray-500">(deixe em branco para manter a atual)</span>
                                </label>
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('photo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('customers.show', $customer) }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Atualizar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para máscaras -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Máscara para CPF
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    e.target.value = value;
                });
            }

            // Máscara para CNPJ
            const cnpjInput = document.getElementById('cnpj');
            if (cnpjInput) {
                cnpjInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                    e.target.value = value;
                });
            }

            // Máscara para CEP
            const cepInput = document.getElementById('zip_code');
            if (cepInput) {
                cepInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/^(\d{5})(\d)/, '$1-$2');
                    e.target.value = value;
                });
            }

            // Máscara para telefone
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                    value = value.replace(/(\d{4})-(\d)(\d{4})/, '$1$2-$3');
                    e.target.value = value;
                });
            }
        });
    </script>
</x-app-layout>