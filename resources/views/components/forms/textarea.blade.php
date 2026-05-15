@props(['label', 'name','post'=>null])

@php
    $defaults = [
        'type' => 'text',
        'id' => $name,
        'name' => $name,
        'class' => 'rounded-xl border w-full p-4',
    ];
@endphp

<x-forms.field :$label :$name>
    <textarea {{ $attributes($defaults) }}>{{ old($name,$post->body ??'') }}</textarea>
</x-forms.field>

