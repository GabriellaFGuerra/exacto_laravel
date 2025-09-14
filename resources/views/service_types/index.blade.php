<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Tipos de Serviço') }}
            </h2>
            <x-exacto-button href="{{ route('service_types.create') }}">
                {{ __('Novo Tipo de Serviço') }}
            </x-exacto-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-exacto-table>
                <x-slot name="header">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </x-slot>
                
                <x-slot name="body">
                    @forelse ($service_types as $service_type)
                        <tr>
                            <td>{{ $service_type->id }}</td>
                            <td>{{ $service_type->name }}</td>
                            <td class="text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('service_types.edit', $service_type) }}" class="action-link">Editar</a>
                                    <form action="{{ route('service_types.destroy', $service_type) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-link" onclick="return confirm('Tem certeza que deseja excluir este tipo de serviço?')">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhum tipo de serviço encontrado</td>
                        </tr>
                    @endforelse
                </x-slot>
                
                <x-slot name="pagination">
                    {{ $service_types->links('pagination.exacto') }}
                </x-slot>
            </x-exacto-table>
        </div>
    </div>
</x-app-layout>