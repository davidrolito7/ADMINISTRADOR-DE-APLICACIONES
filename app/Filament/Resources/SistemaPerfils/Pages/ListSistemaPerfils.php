<?php

namespace App\Filament\Resources\SistemaPerfils\Pages;

use App\Filament\Resources\SistemaPerfils\SistemaPerfilResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSistemaPerfils extends ListRecords
{
    protected static string $resource = SistemaPerfilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
