<?php

namespace App\Filament\Resources\AreaSistemas\Pages;

use App\Filament\Resources\AreaSistemas\AreaSistemaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAreaSistema extends ViewRecord
{
    protected static string $resource = AreaSistemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
