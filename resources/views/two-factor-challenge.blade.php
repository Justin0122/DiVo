<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo/>
        </x-slot>

        <x-validation-errors/>

        <form method="POST" class="w-full bg-red flex flex-col" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="grid grid-cols-3">
                <x-input class="col-span-2" id="code" type="text" name="code" required autofocus
                         placeholder="{{ __('One Time Password')  }}"
                         autocomplete="one-time-code"/>
                <x-button class="col-span-1 ml-2 grid items-center">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>

</x-guest-layout>
