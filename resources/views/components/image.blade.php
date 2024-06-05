@props(['src', 'class' => ''])
<div>
    <img src="{{ $src }}" alt="{{ $src }}"
    class="w-20 h-20 rounded-full object-cover {{ $class }}">
</div>
