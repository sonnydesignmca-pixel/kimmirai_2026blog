@props(['label', 'name'])

<div class="pt-4">
    @if ($label)
        <x-forms.label :$name :$label />
    @endif

    <div class="mt-1">
        {{ $slot }}

        <x-forms.error :error="$errors->first($name)" />

</div>
