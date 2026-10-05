<?php

namespace App\Filament\Resources\SistemaModulos\Pages;

use App\Filament\Resources\SistemaModulos\SistemaModuloResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSistemaModulo extends EditRecord
{
    protected static string $resource = SistemaModuloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
