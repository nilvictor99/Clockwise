<?php

namespace App\Filament\Resources;

use App\Enums\PermissionsPasswordEnum;
use App\Filament\Exports\PasswordShareExporter;
use App\Filament\Imports\PasswordShareImporter;
use App\Filament\Resources\PasswordShareResource\Pages;
use App\Models\PasswordShare;
use App\Services\Auth\AuthService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PasswordShareResource extends Resource
{
    protected static ?string $model = PasswordShare::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Logistics');
    }

    public static function getNavigationLabel(): string
    {
        return __('password_share.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('password_share.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('password_share.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected $authUserId;

    public function __construct()
    {
        $this->authUserId = (new AuthService)->getAuthUser()->id;
    }

    protected static function getAuthUserId()
    {
        return app(AuthService::class)->getAuthUser()->id;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Request Information'))
                    ->schema([
                        Forms\Components\Select::make('password_id')
                            ->translateLabel()
                            ->relationship('password', 'name', function (Builder $query) {
                                $query->where('user_id', static::getAuthUserId());
                            })
                            ->required()
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('shared_with')
                            ->translateLabel()
                            ->relationship('sharedWith', 'name', function (Builder $query) {
                                $query->where('id', '!=', static::getAuthUserId());
                            })
                            ->required()
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('permissions')
                            ->translateLabel()
                            ->options(PermissionsPasswordEnum::Options())
                            ->required()
                            ->native(false),
                    ])->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sharedWith.name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sharedBy.name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('permissions')
                    ->translateLabel()
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __($state))
                    ->color(fn (string $state): string => match ($state) {
                        'view' => 'success',
                        'edit' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(PasswordShareExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(PasswordShareImporter::class)
                    ->icon('heroicon-o-arrow-up-tray'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPasswordShares::route('/'),
            // 'create' => Pages\CreatePasswordShare::route('/create'),
            // 'edit' => Pages\EditPasswordShare::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (! app(AuthService::class)->IsSuperUser()) {
            $query->visibleToUser(static::getAuthUserId());
        }

        return $query;
    }
}
