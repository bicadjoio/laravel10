<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Novo Usuário') }}
        </h2>
    </x-slot>


<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- i_cod_cnes_fonte -->
        <div class="mt-4">
            <x-input-label for="i_cod_cnes_fonte" :value="__('Codigo CNES')" />
            <x-text-input id="i_cod_cnes_fonte" class="block mt-1 w-full" type="text" name="i_cod_cnes_fonte" :value="old('i_cod_cnes_fonte')" required autofocus autocomplete="i_cod_cnes_fonte" />
            <x-input-error :messages="$errors->get('i_cod_cnes_fonte')" class="mt-2" />
        </div>
        
        <!-- i_nome_estab_fonte -->
        <div class="mt-4">
            <x-input-label for="i_nome_estab_fonte" :value="__('Nome do estabelecimento')" />
            <x-text-input id="i_nome_estab_fonte" class="block mt-1 w-full" type="text" name="i_nome_estab_fonte" :value="old('i_nome_estab_fonte')" required autofocus autocomplete="i_nome_estab_fonte" />
            <x-input-error :messages="$errors->get('i_nome_estab_fonte')" class="mt-2" />
        </div>        
        

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar senha')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
             <x-primary-button class="ms-4">
                {{ __('Registrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
</x-app-layout>