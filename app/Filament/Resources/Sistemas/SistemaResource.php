<?php

namespace App\Filament\Resources\Sistemas;

use App\Filament\Resources\Sistemas\Pages\CreateSistema;
use App\Filament\Resources\Sistemas\Pages\EditSistema;
use App\Filament\Resources\Sistemas\Pages\ListSistemas;
use App\Filament\Resources\Sistemas\Pages\ViewSistema;
use App\Filament\Resources\Sistemas\Schemas\SistemaForm;
use App\Filament\Resources\Sistemas\Tables\SistemasTable;
use App\Models\ADM\Sistema;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SistemaResource extends Resource
{
    protected static ?string $model = Sistema::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static ?string $navigationLabel = 'Sistemas';

    protected static ?string $modelLabel = 'Sistema';

    protected static ?string $pluralModelLabel = 'Sistemas';

    protected static ?string $recordTitleAttribute = 'NombreSis';
    protected static string|\UnitEnum|null $navigationGroup = 'Permisos';

    public static function form(Schema $schema): Schema
    {
        return SistemaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SistemasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSistemas::route('/'),
            'create' => CreateSistema::route('/create'),
            'view'   => ViewSistema::route('/{record}'),
            'edit'   => EditSistema::route('/{record}/edit'),
        ];
    }
}
