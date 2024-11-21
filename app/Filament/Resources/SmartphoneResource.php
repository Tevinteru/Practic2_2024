<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmartphoneResource\Pages;
use App\Filament\Resources\SmartphoneResource\RelationManagers;
use App\Models\Smartphone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Actions\ExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\SmartphoneExporter;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SmartphoneResource extends Resource
{
    protected static ?string $model = Smartphone::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('brand_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('release_year')
                    ->required(),
                Forms\Components\TextInput::make('sim_count')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('memory_options')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('color_options')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('image_url')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('release_year'),
                Tables\Columns\TextColumn::make('sim_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('memory_options')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color_options')
                    ->searchable(),
                Tables\Columns\TextColumn::make('image_url'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            ->headerActions([
                ExportAction::make()
                    ->exporter(SmartphoneExporter::class),
                    Tables\Actions\Action::make('export_txt')
                        ->label('Экспорт в TXT')
                        ->action(function () {
                         
                            return SmartphoneExporter::exportToTxt();
                        })
                        ,
                    Tables\Actions\Action::make('export_xml')
                        ->label('Экспорт в XML')
                        ->action(function () {
                         
                            return SmartphoneExporter::exportToXML();
                        })
                        ,
                    Tables\Actions\Action::make('export_yaml')
                        ->label('Экспорт в YAML')
                        ->action(function () {
                         
                            return SmartphoneExporter::exportToYaml();
                        })
                        ,
                    Tables\Actions\Action::make('import')
                        ->label('Внести в БД')
                        ->action(function () {
                         
                            return SmartphoneExporter::import();
                        })
                        
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(SmartphoneExporter::class),
                ]),
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
            'index' => Pages\ListSmartphones::route('/'),
            'create' => Pages\CreateSmartphone::route('/create'),
            'edit' => Pages\EditSmartphone::route('/{record}/edit'),
        ];
    }
}
