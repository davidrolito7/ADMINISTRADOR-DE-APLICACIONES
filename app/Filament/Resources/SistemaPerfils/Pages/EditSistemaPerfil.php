<?php

namespace App\Filament\Resources\SistemaPerfils\Pages;

use App\Filament\Resources\SistemaPerfils\SistemaPerfilResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSistemaPerfil extends EditRecord
{
    protected static string $resource = SistemaPerfilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
