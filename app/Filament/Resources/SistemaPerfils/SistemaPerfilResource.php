<?php

namespace App\Filament\Resources\SistemaPerfils;

use App\Filament\Resources\SistemaPerfils\Pages\CreateSistemaPerfil;
use App\Filament\Resources\SistemaPerfils\Pages\EditSistemaPerfil;
use App\Filament\Resources\SistemaPerfils\Pages\ListSistemaPerfils;
use App\Filament\Resources\SistemaPerfils\Schemas\RelationManagers\PermisosRelationManager;
use App\Filament\Resources\SistemaPerfils\Schemas\SistemaPerfilForm;
use App\Filament\Resources\SistemaPerfils\Tables\SistemaPerfilsTable;
use App\Models\ADM\SistemaPerfil as ADMSistemaPerfil;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SistemaPerfilResource extends Resource
{
    protected static ?string $model = ADMSistemaPerfil::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserCircle;
    protected static ?string $pluralModelLabel = 'Perfiles-Sistemas';
    protected static ?string $recordTitleAttribute = 'Descripcion';
    protected static string|\UnitEnum|null $navigationGroup = 'Permisos';

    public static function form(Schema $schema): Schema
    {
        return SistemaPerfilForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SistemaPerfilsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PermisosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSistemaPerfils::route('/'),
            'create' => CreateSistemaPerfil::route('/create'),
            'edit' => EditSistemaPerfil::route('/{record}/edit'),
        ];
    }
}
