<x-app-layout>
    @php
        $pages = ['party', 'candidate', 'votingPeriod'];
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="mx-auto py-10 sm:px-6 lg:px-8">

        @if (in_array(request()->type, $pages))
            @livewire(request()->type)
        @else
            @livewire('voting-period')
        @endif
    </div>



</x-app-layout>
