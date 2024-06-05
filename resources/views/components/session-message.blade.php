@if (session()->has('message') || session()->has('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        class="{{ session()->has('message') ? 'bg-green-500' : 'bg-red-500'}}
        text-white p-4 rounded-lg mb-6 fixed top-3 left-1/2 transform -translate-x-1/2 text-xl"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform"
        x-transition:enter-end="opacity-100 transform"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform"
        x-transition:leave-end="opacity-0 transform"
        style="width: fit-content;"
    >
        {{ session()->get('message') ?? session()->get('error') }}

        <button type="button" @click="show = false" class="text-white ml-4">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
    @php session()->forget('message'); session()->forget('error') @endphp
@endif
