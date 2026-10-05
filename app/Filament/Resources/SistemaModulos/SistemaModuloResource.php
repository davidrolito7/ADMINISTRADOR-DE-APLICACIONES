<?php

namespace App\Filament\Resources\SistemaModulos;

use App\Filament\Resources\SistemaModulos\Pages\CreateSistemaModulo;
use App\Filament\Resources\SistemaModulos\Pages\EditSistemaModulo;
use App\Filament\Resources\SistemaModulos\Pages\ListSistemaModulos;
use App\Filament\Resources\SistemaModulos\Pages\ViewSistemaModulo;
use App\Filament\Resources\SistemaModulos\RelationManagers\PantallasRelationManager;
use App\Filament\Resources\SistemaModulos\Schemas\SistemaModuloForm;
use App\Filament\Resources\SistemaModulos\Schemas\SistemaModuloInfolist;
use App\Filament\Resources\SistemaModulos\Tables\SistemaModulosTable;
use App\Models\ADM\SistemaModulo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


class SistemaModuloResource extends Resource
{
    protected static ?string $model = SistemaModulo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static string|\UnitEnum|null $navigationGroup = 'Permisos';

    protected static ?string $recordTitleAttribute = 'Nombre';
    protected static ?string $modelLabel = 'Módulo';
    protected static ?string $pluralModelLabel = 'Módulos - Pantallas - Secciones';

    public static function form(Schema $schema): Schema
    {
        return SistemaModuloForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SistemaModulosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PantallasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSistemaModulos::route('/'),
            'create' => CreateSistemaModulo::route('/create'),
            'view'   => ViewSistemaModulo::route('/{record}'),
            'edit'   => EditSistemaModulo::route('/{record}/edit'),
        ];
    }
}
