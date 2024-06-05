<x-app-layout>
    <div class="container mx-auto px-8 py-4 bg-gray-100 rounded-lg shadow-md dark:bg-gray-800">
        <div class="flex items-center space-x-4 mb-4">
            <img src="{{$candidate->image}}" alt="{{$candidate->name}} Image" class="w-16 h-16 rounded-full">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                {{$candidate->name}}
            </h1>
        </div>
        <x-link-border/>
        <h2 class="mt-4 text-xl font-semibold mb-2 text-gray-800 dark:text-gray-100">More about <span
                class="text-gray-500 dark:text-gray-50">{{$candidate->name}}</span></h2>
        <p class="text-gray-700 dark:text-gray-100 mb-4">{{$candidate->bio}}</p>

        @if($candidate->parties->count() != 0)
            <x-link-border/>
            <h2 class="text-xl font-semibold mb-2 text-gray-700 dark:text-gray-100">More
                about {{$candidate->parties->first()->name}}</h2>
            <p class="text-gray-700 dark:text-gray-100 mb-4">{{$candidate->parties->first()->bio}}</p>
            <hr class="my-4 border-t-2 border-gray-300">
            <a href="/candidate/{{$candidate->id}}/vote">
                <x-button class="mt-5 bg-blue-500 text-white">Vote for {{$candidate->name}}</x-button>
            </a>
        @endif
        <x-section-border/>
        <div class="flex items-center space-x-4 mb-4 justify-between">
            <div class="flex items-center">
                @if($previousCandidate)
                    <a href="/candidate/{{$previousCandidate->id}}">
                        <x-button class="bg-blue-500 text-white">Previous</x-button>
                    </a>
                @endif

            </div>
            @if($nextCandidate)
                <a href="/candidate/{{$nextCandidate->id}}">
                    <x-button class="bg-blue-500 text-white">Next</x-button>
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
