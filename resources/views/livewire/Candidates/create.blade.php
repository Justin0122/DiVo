<div class="edit flex flex-col p-4 bg-white shadow-md rounded-lg dark:bg-gray-800">
    <form wire:submit.prevent="createAndAssignCandidate" class="space-y-2">
        <x-input type="text"
            class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
            placeholder="Candidate name" wire:model="form.candidate.name" />
        <x-error :fillable="'candidate.name'" />

        <textarea wire:model="form.candidate.bio"
            class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
            placeholder="Candidate bio"></textarea>
        <x-error :fillable="'candidate.bio'" />

        <x-input type="date"
            class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
            placeholder="Candidate date of birth" wire:model="form.candidate.birthdate" />
        <x-error :fillable="'candidate.birthdate'" />

        <!-- show temporary image -->
        @if (isset($form['candidate']['image']) && is_object($form['candidate']['image']))
            <label for="{{ $form['candidate']['image']->temporaryUrl() }}"
                class="text-sm font-semibold text-gray-600">
                Candidate Image Preview
            </label>
            <x-image :src="$form['candidate']['image']->temporaryUrl()" />
        @endif

        <x-input type="file"
            class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
            placeholder="Candidate image" wire:model="form.candidate.image" />
        <x-error :fillable="'candidate.image'" />

        <x-section-border />

        <x-button>Add</x-button>
    </form>
</div>
