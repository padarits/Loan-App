<div
x-data="{ value: @entangle($attributes->wire('model')), picker: undefined }"
x-init="new Pikaday({ field: $refs.input, format: 'DD/MM/YYYY', defaultDate: $refs.input.value })"
x-on:change="value = $event.target.value"
class="input-group pe-2"
>
<span class="input-group-text">
    <i class="fa-solid fa-calendar-days"></i>
</span>

<input
    {{ $attributes->whereDoesntStartWith('wire:model') }}
    x-ref="input"
    x-bind:value="value"
    class="form-control"
/>
</div>