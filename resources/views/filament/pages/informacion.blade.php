<x-filament-panels::page>
    <div class="max-w-2xl space-y-6">
        <x-filament::section>
            <x-slot name="heading">Administrador de Permisos</x-slot>

            <x-slot name="description">
                Panel para la gestión de sistemas, módulos, perfiles, áreas, usuarios y permisos.
            </x-slot>

            <dl class="grid gap-4 md:grid-cols-2">
                <div>
                    <dt class="text-sm text-gray-600 dark:text-gray-400">Desarrollado por</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-950 dark:text-white">{{ $autor }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-gray-600 dark:text-gray-400">Contacto</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-950 dark:text-white">
                        <a href="mailto:{{ $correo }}" class="text-primary-600 hover:underline dark:text-primary-400">{{ $correo }}</a>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-gray-600 dark:text-gray-400">Tecnologías</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-950 dark:text-white">
                        Laravel {{ app()->version() }} · F4
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-gray-600 dark:text-gray-400">PHP</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-950 dark:text-white">{{ PHP_VERSION }}</dd>
                </div>
            </dl>
        </x-filament::section>

        <x-filament::section compact>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Este proyecto y su código fueron diseñados y desarrollados por
                <span class="font-semibold text-gray-950 dark:text-white">{{ $autor }}</span>.
                © 2026 Todos los derechos reservados.
            </p>
        </x-filament::section>
    </div>
</x-filament-panels::page>
