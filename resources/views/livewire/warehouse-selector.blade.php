<!-- Noliktavas koda un nosaukuma izvēlne -->
<div class="mb-4" style="margin: 0px !important;">
    <!--<label for="warehouseCode" class="block text-sm font-medium text-gray-700">Noliktava:</label>-->
    <select id="{{ $selectId }}" wire:model.live="warehouseCode" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
        <option value="" disabled selected>Izvēlieties noliktavu</option>
        @foreach ($warehouses as $warehouse)
            <option value="{{ $warehouse['warehouse_code'] }}">
                {{ $warehouse['name'] }}
            </option>
        @endforeach
    </select>
    @error('warehouseCode') 
        <span class="text-red-500">{{ $message }}</span>
        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
    @enderror
</div>

