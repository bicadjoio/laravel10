<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                @auth
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <a href="{{ url('/upload') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Enviar arquivos CSV</a>
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <a href="{{ url('/upload_history') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Historico de envio dos arquivos CSV</a>
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if ($user->is_admin == 1 && Route::has('register'))
                            <a href="{{ route('register') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Registrar Novo Usuário</a>
                        @endif
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if ($user->is_admin == 1 && Route::has('users.index'))
                            <a href="{{ route('users.index') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Remover Usuário</a>
                        @endif
                    </div>





                @endauth

            </div>
        </div>
    </div>
</x-app-layout>
