<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Infração') }} #{{ $infraction->id }}
            </h2>
            <a href="{{ route('infractions.show', $infraction) }}"
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
                    <form method="POST" action="{{ route('infractions.update', $infraction) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Cliente -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                <select name="user_id" id="user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                    <option value="">Selecione um cliente</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('user_id', $infraction->user_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->email }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Tipo de
                                    Infração</label>
                                <select name="type" id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="velocidade"
                                        {{ old('type', $infraction->type) == 'velocidade' ? 'selected' : '' }}>Excesso
                                        de Velocidade</option>
                                    <option value="semaforo"
                                        {{ old('type', $infraction->type) == 'semaforo' ? 'selected' : '' }}>Avanço de
                                        Semáforo</option>
                                    <option value="estacionamento"
                                        {{ old('type', $infraction->type) == 'estacionamento' ? 'selected' : '' }}>
                                        Estacionamento Irregular</option>
                                    <option value="documento"
                                        {{ old('type', $infraction->type) == 'documento' ? 'selected' : '' }}>Documento
                                        Irregular</option>
                                    <option value="outros"
                                        {{ old('type', $infraction->type) == 'outros' ? 'selected' : '' }}>Outros
                                    </option>
                                </select>
                                @error('type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Data da Infração -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Data da
                                    Infração</label>
                                <input type="date" name="date" id="date"
                                    value="{{ old('date', $infraction->date ? $infraction->date->format('Y-m-d') : '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                @error('date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hora -->
                            <div>
                                <label for="time" class="block text-sm font-medium text-gray-700">Hora</label>
                                <input type="time" name="time" id="time" value="{{ old('time', $infraction->time) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cidade -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">Cidade</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $infraction->city) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                @error('city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Local -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Local</label>
                                <input type="text" name="location" id="location"
                                    value="{{ old('location', $infraction->location) }}"
                                    placeholder="Rua, avenida, rodovia..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('location')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Placa do Veículo -->
                            <div>
                                <label for="license_plate" class="block text-sm font-medium text-gray-700">Placa do
                                    Veículo</label>
                                <input type="text" name="license_plate" id="license_plate"
                                    value="{{ old('license_plate', $infraction->license_plate) }}"
                                    placeholder="ABC-1234"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                @error('license_plate')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Valor da Multa -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Valor da Multa
                                    (R$)</label>
                                <input type="number" name="amount" id="amount"
                                    value="{{ old('amount', $infraction->amount) }}" step="0.01" min="0"
                                    placeholder="0,00"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                @error('amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="active"
                                        {{ old('status', $infraction->status) == 'active' ? 'selected' : '' }}>Ativa
                                    </option>
                                    <option value="paid"
                                        {{ old('status', $infraction->status) == 'paid' ? 'selected' : '' }}>Paga
                                    </option>
                                    <option value="appealed"
                                        {{ old('status', $infraction->status) == 'appealed' ? 'selected' : '' }}>
                                        Recorrida</option>
                                    <option value="cancelled"
                                        {{ old('status', $infraction->status) == 'cancelled' ? 'selected' : '' }}>
                                        Cancelada</option>
                                </select>
                                @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Auto de Infração -->
                            <div>
                                <label for="auto_number" class="block text-sm font-medium text-gray-700">Número do
                                    Auto</label>
                                <input type="text" name="auto_number" id="auto_number"
                                    value="{{ old('auto_number', $infraction->auto_number) }}"
                                    placeholder="Número do auto de infração"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('auto_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="mb-6">
                            <label for="observation" class="block text-sm font-medium text-gray-700">Observações</label>
                            <textarea name="observation" id="observation" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Observações adicionais sobre a infração...">{{ old('observation', $infraction->observation) }}</textarea>
                            @error('observation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('infractions.show', $infraction) }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Atualizar Infração
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para máscara de placa -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const licensePlateInput = document.getElementById('license_plate');
        if (licensePlateInput) {
            licensePlateInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();

                // Formato antigo (ABC-1234) ou novo (ABC1D23)
                if (value.length > 3 && value.length <= 7) {
                    if (/^[A-Z]{3}[0-9]/.test(value)) {
                        // Formato novo: ABC1D23
                        value = value.replace(/^([A-Z]{3})([0-9]{1})([A-Z]{1})([0-9]{2})$/, '$1$2$3$4');
                    } else {
                        // Formato antigo: ABC-1234
                        value = value.replace(/^([A-Z]{3})([0-9]{1,4})$/, '$1-$2');
                    }
                }

                e.target.value = value;
            });
        }
    });
    </script>
</x-app-layout>