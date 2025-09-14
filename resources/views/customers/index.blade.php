<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Clientes') }}
            </h2>
            <x-exacto-button href="{{ route('customers.create') }}">
                {{ __('Novo Cliente') }}
            </x-exacto-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-exacto-table>
                <x-slot name="header">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>CNPJ</th>
                        <th>Telefone</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </x-slot>
                
                <x-slot name="body">
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->cnpj ?? 'N/A' }}</td>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge {{ $customer->status ? 'status-active' : 'status-inactive' }}">
                                    {{ $customer->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('customers.show', $customer) }}" class="action-link view">Ver</a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="action-link edit">Editar</a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link delete" 
                                                onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhum cliente encontrado.</td>
                        </tr>
                    @endforelse
                </x-slot>
                
                <x-slot name="pagination">
                    {{ $customers->appends(request()->query())->links('pagination.exacto') }}
                </x-slot>
            </x-exacto-table>
        </div>
    </div>
</x-app-layout>
