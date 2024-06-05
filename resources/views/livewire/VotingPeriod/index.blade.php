<div class="container mx-auto px-4">
    <x-session-message/>
    @can('crudParty')
        @if($this->id)
            @include('livewire.crud.edit')
        @else
            <div class="flex row-auto gap-2">
                <label class="w-full block text-sm font-medium text-gray-900 dark:text-gray-400"
                       for="search">
                    <input wire:model.live="search" type="text"
                           class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                           placeholder="Search...">
                </label>
                <label class="text-sm font-medium text-gray-900 dark:text-gray-400 flex items-center">
                    @can('crudParty')
                        <input wire:model.live="showDeleted" type="checkbox"
                               class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 mr-2">
                        Show Deleted
                    @endcan
                </label>
                <x-select-per-page/>
                <x-button wire:click="clearFilters">

                    Clear Filters
                </x-button>
            </div>
            @if(isset($results->links))
                {{ $results->links }}
            @endif
            <div class="mt-4">
                <x-table :results="$results" :type="'votingPeriod'" :create="true" :fillables="$fillables"/>
            </div>
        @endif
    @endcan
    @cannot('crudParty')
        <div class="flex justify-between gap-2">
            <x-card :title="'Voting Period'" :description="'Voting Period'" :current="$current">
                <a href="{{ route('dashboard', ['type' => 'party    ']) }}"
                   class="block w-full px-4 py-2 text-center text-white bg-green-500 rounded hover:bg-green-600 focus:outline-none focus:bg-green-600 transition duration-150 ease-in-out">
                    Vote
                </a>
            </x-card>
        </div>
    @endcannot
</div>
