<?php

namespace App\Filament\Resources\SistemaModulos\Pages;

use App\Filament\Resources\SistemaModulos\SistemaModuloResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSistemaModulo extends ViewRecord
{
    protected static string $resource = SistemaModuloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
