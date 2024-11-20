<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmartphoneCharacteristicResource\Pages;
use App\Filament\Resources\SmartphoneCharacteristicResource\RelationManagers;
use App\Models\SmartphoneCharacteristic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SmartphoneCharacteristicResource extends Resource
{
    protected static ?string $model = SmartphoneCharacteristic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('smartphone_id')
                    ->tel()
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('characteristic')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('smartphone_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('characteristic')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->searchable(),
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSmartphoneCharacteristics::route('/'),
            'create' => Pages\CreateSmartphoneCharacteristic::route('/create'),
            'edit' => Pages\EditSmartphoneCharacteristic::route('/{record}/edit'),
        ];
    }
}
