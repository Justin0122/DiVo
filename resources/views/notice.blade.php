<x-app-layout>
    <div class="rounded bg-gray-700 text-white p-2">
        <h1 class="text-2xl">{{ $title ?? 'Notice' }}</h1>
        <hr>
        <span>{{ $body ?? 'Something happened.' }}</span>
        @if(isset($actions))
            <br>
            {{ $actions }}
        @endif
    </div>
</x-app-layout>
