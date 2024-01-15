@extends("template")
@section("content")
    <x-guest-layout>
        <x-auth-card>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 alert alert-danger" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4 alert alert-danger" :errors="$errors" />

            <form id='loginForm' method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div id='licenceDiv'>
                    <label for="MEM_NUM_LICENCE">Licence</label>

                    <x-input id="MEM_NUM_LICENCE" class="block mt-1 w-full" type="text" name="MEM_NUM_LICENCE" :value="old('MEM_NUM_LICENCE')" required autofocus />
                </div>

                <!-- Password -->
                <div id='passwordDiv'>
                    <label for="password">Mot de passe</label>

                    <x-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div id='rememberMeDiv'">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                    </label>
                </div>

                <button id='buttonLogIn' class="btn btn-primary ml-3">
                    Se connecter
                </button>
            </form>
        </x-auth-card>
    </x-guest-layout>
@endsection
