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

                <label for="{{ $results->$fillable }}" class="text-sm font-semibold text-gray`-600">
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
    <x-danger-button wire:click="delete({{ $results->id }})"
                     class="mt-4 py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300"
                     wire:confirm="Are you sure you want to delete this voting period?">
        Delete
    </x-danger-button>
</div>
