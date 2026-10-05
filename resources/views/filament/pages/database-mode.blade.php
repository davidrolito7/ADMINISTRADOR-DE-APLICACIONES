<x-filament-panels::page>
    <div class="max-w-2xl space-y-6">
        <x-filament::section>
            <x-slot name="heading">Selecciona el origen de datos</x-slot>

            <x-slot name="description">
                El modo se guarda únicamente para tu sesión. Los demás usuarios no cambian de conexión.
            </x-slot>

            <div class="grid gap-4 md:grid-cols-2">
                <button
                    type="button"
                    wire:click="selectMode('production')"
                    @class([
                        'rounded-xl border p-5 text-left transition',
                        'border-success-600 bg-success-50 dark:bg-success-950/30' => $mode === 'production',
                        'border-gray-200 hover:border-success-400 dark:border-white/10' => $mode !== 'production',
                    ])
                >
                    <span class="block text-base font-semibold">Producción</span>
                    <span class="mt-1 block text-sm text-gray-600 dark:text-gray-400">Datos reales</span>
                </button>

                <button
                    type="button"
                    wire:click="selectMode('testing')"
                    @class([
                        'rounded-xl border p-5 text-left transition',
                        'border-blue-600 bg-blue-50 dark:bg-blue-950/30' => $mode === 'testing',
                        'border-gray-200 hover:border-blue-400 dark:border-white/10' => $mode !== 'testing',
                    ])
                >
                    <span class="block text-base font-semibold">Pruebas</span>
                    <span class="mt-1 block text-sm text-gray-600 dark:text-gray-400">Ambiente de validación</span>
                </button>
            </div>
        </x-filament::section>

        <x-filament::section compact>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Modo actual:
                <span class="font-semibold text-gray-950 dark:text-white">
                    {{ $mode === 'production' ? 'Producción' : 'Pruebas' }}
                </span>
            </p>
        </x-filament::section>
    </div>
</x-filament-panels::page>
