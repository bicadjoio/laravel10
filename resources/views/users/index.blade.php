<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciar Usuários') }}
        </h2>
    </x-slot>

  




    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table">
                    <thead>
                        <tr>
                            <th style="padding-right: 200px;">Nome</th>
                            <th style="padding-right: 10px;">CNES</th>
                            <th style="padding-right: 200px;">Fonte</th>
                            <th style="padding-right: 50px;">Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td style="padding-right: 200px;">{{ $user->name }}</td>
                            <td style="padding-right: 10px;">{{ $user->i_cod_cnes_fonte }}</td>
                            <td style="padding-right: 200px;">{{ $user->i_nome_estab_fonte }}</td>
                            <td style="padding-right: 50px;">{{ $user->email }}</td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir o usuário {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                

            </div>
        </div>
    </div>
</x-app-layout>