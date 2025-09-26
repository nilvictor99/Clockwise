<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers\DepartamentUsersRelationManager;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('departament.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('departament.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('departament.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Request Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('manager_id')
                            ->translateLabel()
                            ->relationship('manager', 'name')
                            ->disabledOn('edit')
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->translateLabel()
                            ->columnSpanFull(),

                    ])->columns(2),

                Section::make(__('Staff'))
                    ->hiddenOn('edit')
                    ->schema([
                        Forms\Components\Repeater::make('departamentUsers')
                            ->translateLabel()
                            ->relationship('departamentUsers')
                            ->collapsible()
                            ->maxItems(10)
                            ->addActionLabel(__('Add member'))
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->translateLabel()
                                    ->relationship('user', 'name')
                                    ->options(function (callable $get) {
                                        $managerId = $get('manager_id');

                                        return \App\Models\User::where('id', '!=', $managerId)->pluck('name', 'id');
                                    })
                                    ->preload()
                                    ->required()
                                    ->searchable(),
                                Forms\Components\Select::make('type')
                                    ->translateLabel()
                                    ->default('staff')
                                    ->options([
                                        'staff' => __('Staff'),
                                    ])
                                    ->required(),
                            ])->columns(2),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DepartamentUsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
