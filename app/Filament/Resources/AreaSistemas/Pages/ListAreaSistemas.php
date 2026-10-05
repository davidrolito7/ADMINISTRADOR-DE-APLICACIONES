<?php

namespace App\Filament\Resources\AreaSistemas\Pages;

use App\Filament\Resources\AreaSistemas\AreaSistemaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAreaSistemas extends ListRecords
{
    protected static string $resource = AreaSistemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
