<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <br>
        <x-filament::button type="submit" form="submit">
            Gerar Modelo
        </x-filament::button>
    </form>
</x-filament::page>
