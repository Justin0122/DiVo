<div class="edit flex flex-col p-4 bg-white shadow-md rounded-lg dark:bg-gray-800">
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
                @if (strpos($fillable, 'date') !== false)
                    <x-input type="date"
                             class="py-2 px-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                             placeholder="{{ $results->$fillable }}" wire:model="form.{{ $fillable }}"/>
                    <x-error :fillable="$fillable"/>
                @elseif (strpos($fillable, 'image') !== false || strpos($fillable, 'logo') !== false)
                    <x-image :src="$results->$fillable"/>
                    <x-input type="file"
                             class="py-2 px-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                             placeholder="{{ $results->$fillable }}" wire:model="form.{{ $fillable }}"/>
                    <x-error :fillable="$fillable"/>
                @else
                    <x-input type="text"
                             class="py-2 px-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                             placeholder="{{ $results->$fillable }}" wire:model="form.{{ $fillable }}"/>
                    <x-error :fillable="$fillable"/>
                @endif
            </div>
        @endforeach
        <button type="submit"
                class="py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
            Submit
        </button>
    </form>
    <x-danger-button wire:click="delete({{ $results->id }})"
                     class="mt-4 py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300">
        Delete
    </x-danger-button>

    <x-section-border/>

    @isset($results->parties)
    @foreach ($results->parties as $party)
        <div class="flex flex-row">
            <div class="flex items-center">
                <img src="{{ $party->logo }}" alt="{{ $party->name }} Logo" class="w-16 h-16 rounded-full">
                <div class="ml-4">
                    <div class="text-sm font-semibold text-gray-600">
                        <span>
                            <a href="{{ route('dashboard', ['type' => 'party', 'id' => $party->id]) }}"
                               class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                                {{ $party->name }}
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @endisset

</div>
