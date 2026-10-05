<?php

namespace App\Filament\Resources\AreaSistemas;

use App\Filament\Resources\AreaSistemas\Pages\CreateAreaSistema;
use App\Filament\Resources\AreaSistemas\Pages\EditAreaSistema;
use App\Filament\Resources\AreaSistemas\Pages\ListAreaSistemas;
use App\Filament\Resources\AreaSistemas\Pages\ViewAreaSistema;
use App\Filament\Resources\AreaSistemas\RelationManagers\UsuariosRelationManager;
use App\Filament\Resources\AreaSistemas\Schemas\AreaSistemaForm;
use App\Filament\Resources\AreaSistemas\Tables\AreaSistemasTable;
use App\Models\ADM\AreaSistema;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AreaSistemaResource extends Resource
{
    protected static ?string $model = AreaSistema::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $recordTitleAttribute = 'idAreaSistema';
    protected static ?string $modelLabel = 'Area-Sistemas';
    protected static ?string $pluralModelLabel = 'Area-Sistemas';
    protected static string|\UnitEnum|null $navigationGroup = 'Permisos';

    public static function form(Schema $schema): Schema
    {
        return AreaSistemaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AreaSistemasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            UsuariosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAreaSistemas::route('/'),
            'create' => CreateAreaSistema::route('/create'),
            'view'   => ViewAreaSistema::route('/{record}'),
            'edit' => EditAreaSistema::route('/{record}/edit'),
        ];
    }
}
