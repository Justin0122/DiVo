<div class="edit flex flex-col p-4 bg-white shadow-md rounded-lg dark:bg-gray-800">
    <x-session-message/>
    <form wire:submit.prevent="update" class="space-y-4">
        @foreach ($fillables as $fillable)
            <div class="flex flex-col">
                @if (isset($form[$fillable]) && is_object($form[$fillable]))
                    <label for="{{ $form[$fillable]->temporaryUrl() }}" class="text-sm font-semibold text-gray-600">
                        {{ ucfirst($fillable) }} Preview
                    </label>
                    <x-image :src="$form[$fillable]->temporaryUrl()"/>
                @endif

                <label for="{{ $results->$fillable }}" class="text-sm font-semibold text-gray-600">
                    {{ ucfirst($fillable) }}
                </label>
                @if (str_contains($fillable, 'image') || str_contains($fillable, 'logo') !== false)
                    <x-image :src="$results->$fillable"/>
                    <x-input type="file"
                             class="py-2 px-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                             placeholder="{{ $results->$fillable }}" wire:model="form.{{ $fillable }}"/>
                @elseif (str_contains($fillable, 'bio') !== false)
                    <textarea
                        class="py-2 px-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-700"
                        placeholder="{{ $results->$fillable }}" wire:model="form.{{ $fillable }}"></textarea>
                @elseif (str_contains($fillable, 'date') !== false)
                    <x-input type="date"
                             class="py-2 px-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                             placeholder="{{ $results->$fillable }}" wire:model="form.{{ $fillable }}"/>
                @else
                    <x-input type="text"
                             class="py-2 px-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                             placeholder="{{ $results->$fillable }}" wire:model="form.{{ $fillable }}"/>
                @endif
                <x-error :fillable="$fillable"/>
            </div>
        @endforeach
        <button type="submit"
                class="py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
            Submit
        </button>
    </form>
    @if ($results->deleted_at)
        <x-button wire:click="restore({{ $results->id }})"
                  class="mt-4 py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300  dark:text-gray-300 justify-center">
            Restore
        </x-button>
        <x-danger-button wire:click="forceDelete({{ $results->id }})"
                         class="mt-4 py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-700 transition duration-300"
                         wire:confirm="Are you sure you want to delete this party?">
            Force Delete
        </x-danger-button>
    @else
        <x-danger-button wire:click="delete({{ $results->id }})"
                         class="mt-4 py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300"
                         wire:confirm="Are you sure you want to delete this party?">
            Delete
        </x-danger-button>
    @endif
    <x-section-border/>

    <p class="text-sm font-semibold text-gray-600">
        Create and assign a candidate
    </p>
    @include('livewire.Candidates.create')
    <x-section-border/>

    @if ($results->candidates->count() == 0)
        <p class="text-sm font-semibold text-gray-600">
            No candidates assigned to this group
        </p>
    @else
        <p class="text-sm font-semibold text-gray-600">
            candidates assigned to this group
        </p>

        <ul class="text-sm font-semibold text-gray-600">
            @foreach ($results->candidates as $candidate)
                <li
                    class="flex justify-between items-center w-full px-4 py-2 border-b border-gray-200 hover:bg-gray-100 transition duration-300 ease-in-out dark:hover:bg-gray-700 dark:text-gray-300 dark:border-gray-700">
                    <span class="flex items-center">
                        <x-image :src="$candidate->image" class="w-8 h-8 rounded-full"/>
                        <a href="{{ '/dashboard?type=candidate&id=' . $candidate->id }}" class="ml-2">
                            {{ $candidate->name }}
                        </a>
                    </span>
                    @can('crudParty')
                        <x-danger-button wire:click="removeCandidate({{ $candidate->id }})">Unassign</x-danger-button>
                    @endcan
                </li>
            @endforeach
        </ul>
    @endif
    <input type="text" wire:model.live="searchUnassignedCandidates" placeholder="Search candidates"
           class="py-2 px-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-700">
    @if ($candidates->count() == 0)
        <p class="text-sm font-semibold text-gray-600">
            No candidates available to assign
        </p>
    @else
        <ul class="text-sm font-semibold text-gray-600">
            @foreach ($candidates as $candidate)
                @unless ($results->candidates->contains($candidate->id))
                    <li class="flex justify-between items-center w-full px-4 py-2 border-b border-gray-200 hover:bg-gray-100 transition duration-300 ease-in-out dark:hover:bg-gray-700 dark:text-gray-300 dark:border-gray-700">
                        <span class="flex items-center">
                            <x-image :src="$candidate->image" class="w-8 h-8 rounded-full"/>
                            <span class="ml-2">
                                {{ $candidate->name }}
                            </span>
                        </span>
                        <x-secondary-button wire:click="assignCandidate({{ $candidate->id }})">Assign
                        </x-secondary-button>
                    </li>
                @endunless
            @endforeach
        </ul>
    @endif
</div>
