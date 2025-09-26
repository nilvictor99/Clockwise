<?php

namespace App\Filament\Resources;

use App\Enums\PasswordTypeEnum;
use App\Filament\Exports\PasswordVaultExporter;
use App\Filament\Imports\PasswordVaultImporter;
use App\Filament\Resources\PasswordVaultResource\Pages;
use App\Models\PasswordVault;
use App\Services\Auth\AuthService;
use App\Services\Utils\PasswordGenerator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PasswordVaultResource extends Resource
{
    protected static ?string $model = PasswordVault::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Logistics');
    }

    public static function getNavigationLabel(): string
    {
        return __('password_vault.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('password_vault.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('password_vault.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-key';

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
                Forms\Components\Section::make(__('Basic Info'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->translateLabel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->translateLabel()
                            ->password()
                            ->required()
                            ->prefixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-m-key')
                                    ->action(function ($set) {
                                        $set('password', app(PasswordGenerator::class)->generate());
                                    })
                            )
                            ->revealable(),
                    ])->columns(3),
                Forms\Components\Section::make(__('Details'))
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->translateLabel()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->translateLabel()
                            ->options(PasswordTypeEnum::Options())
                            ->required()
                            ->native(false),
                        Forms\Components\MarkdownEditor::make('notes')
                            ->translateLabel()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->translateLabel()
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('password')
                    ->translateLabel()
                    ->copyable()
                    ->limit(15)
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('url')
                    ->translateLabel()
                    ->limit(15)
                    ->url(fn ($record) => $record->url, shouldOpenInNewTab: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->translateLabel()
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __($state))
                    ->color(fn (string $state): string => match ($state) {
                        'private' => 'success',
                        'public' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->translateLabel()
                    ->boolean(),
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn ($record) => $record->canView(static::getAuthUserId())),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->canEdit(static::getAuthUserId())),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(PasswordVaultExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(PasswordVaultImporter::class)
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
            'index' => Pages\ListPasswordVaults::route('/'),
            'create' => Pages\CreatePasswordVault::route('/create'),
            'view' => Pages\ViewPasswordVault::route('/{record}'),
            'edit' => Pages\EditPasswordVault::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
        if (! app(AuthService::class)->IsSuperUser()) {
            $query->visibleToUser(static::getAuthUserId());
        }

        return $query;
    }
}
