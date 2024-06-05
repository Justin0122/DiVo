<div>
    @error('form.' . $fillable) <span class="text-red-500">{{ str_replace('form.', '', $message) }}</span> @enderror
</div>
