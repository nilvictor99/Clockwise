<?php

namespace App\Filament\Resources;

use App\Enums\HolidayTypeEnum;
use App\Filament\Exports\HolidayExporter;
use App\Filament\Imports\HolidayImporter;
use App\Filament\Resources\HolidayResource\Pages;
use App\Models\Holiday;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Logistics');
    }

    public static function getNavigationLabel(): string
    {
        return __('holiday.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('holiday.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('holiday.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Request Information'))
                    ->schema([
                        Forms\Components\Select::make('staff_id')
                            ->translateLabel()
                            ->searchable()
                            ->preload()
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->required(),
                        Forms\Components\TextInput::make('Calendar')
                            ->required()
                            ->default(date('Y')),
                        Forms\Components\Select::make('type')
                            ->translateLabel()
                            ->native(false)
                            ->options(HolidayTypeEnum::options())
                            ->default(HolidayTypeEnum::PERSONAL_LEAVE)
                            ->required(),
                    ])->columns(3),

                Section::make(__('Request Information'))
                    ->schema([
                        Forms\Components\DatePicker::make('start_day')
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->locale('es')
                            ->required(),
                        Forms\Components\DatePicker::make('end_day')
                            ->label('Fin')
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->locale('es')
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Descripción')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('holidays/rich-editor'),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calendar')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('staff.name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('start_day')
                    ->date('d/m/Y')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('end_day')
                    ->date('d/m/Y')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('type')
                    ->translateLabel()
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __($state))
                    ->color(fn (string $state): string => match ($state) {
                        'vacation' => 'success',
                        'sick_leave' => 'warning',
                        'personal_leave' => 'info',
                        'maternity_leave' => 'primary',
                        'paternity_leave' => 'secondary',
                        default => 'gray',
                    }),
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
                        ->exporter(HolidayExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(HolidayImporter::class)
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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }
}
