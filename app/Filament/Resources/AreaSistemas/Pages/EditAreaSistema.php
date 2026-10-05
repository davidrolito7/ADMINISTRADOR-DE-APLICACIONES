<?php

namespace App\Filament\Resources\AreaSistemas\Pages;

use App\Filament\Resources\AreaSistemas\AreaSistemaResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAreaSistema extends EditRecord
{
    protected static string $resource = AreaSistemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            Action::make('save')
                ->label('Guardar')
                ->action('save')
                ->color('primary'),
            Action::make('cancel')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    // ← Quita los del footer
    protected function getFormActions(): array
    {
        return [];
    }
}