<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Informacion extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?string $navigationLabel = 'Información';

    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?int $navigationSort = 100;

    protected static ?string $slug = 'informacion';

    protected static ?string $title = 'Información';

    protected string $view = 'filament.pages.informacion';

    public string $autor = 'David Rodriguez';

    public string $correo = 'dvirdr7@gmail.com';
}
