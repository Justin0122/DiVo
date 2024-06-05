@props([ 'title', 'description', 'current' => null ])
<div
    class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700">
    <div
        class="border-b-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
        <h3 class="text-lg font-medium leading-6 text-neutral-900 dark:text-neutral-50">
            {{ $title }}
        </h3>
    </div>
    <div class="p-6">
        <div class="mb-4">
            <span class="text-sm font-medium text-gray-900 dark:text-gray-400">
                Start Time: {{ $current ? $current->start_time : 'N/A' }}
            </span>
        </div>
        <div class="mb-4">
            <span class="text-sm font-medium text-gray-900 dark:text-gray-400">
                End Time: {{ $current ? $current->end_time : 'N/A' }}
            </span>
        </div>
        {{ $slot }}
    </div>
</div>
