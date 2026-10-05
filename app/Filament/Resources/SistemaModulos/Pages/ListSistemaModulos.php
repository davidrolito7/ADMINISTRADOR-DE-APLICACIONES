<?php

namespace App\Filament\Resources\SistemaModulos\Pages;

use App\Filament\Resources\SistemaModulos\SistemaModuloResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSistemaModulos extends ListRecords
{
    protected static string $resource = SistemaModuloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
