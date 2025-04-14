<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokResource\Pages;
use App\Models\Stok;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class StokResource extends Resource
{
    protected static ?string $model = Stok::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('keterangan')
                    ->options([
                        'tu masuk' => 'TU Masuk',
                        'tu keluar' => 'TU Keluar',
                        'bidang masuk' => 'Bidang Masuk',
                        'bidang keluar' => 'Bidang Keluar',
                    ])
                    ->required()
                    ->label('Keterangan'),

                TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->label('Jumlah'),
                               
                FileUpload::make('dokumen')
                    ->disk('public')  
                    ->directory('uploads')
                    ->preserveFilenames()
                    ->nullable()
                    ->label('Upload Dokumen'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('keterangan'),
                TextColumn::make('jumlah'),
                               
                TextColumn::make('dokumen')
                    ->label('Dokumen')
                    ->getStateUsing(fn ($record) => basename($record->dokumen)),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-download')
                    ->url(fn ($record) => asset('storage/' . $record->dokumen   ))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStoks::route('/'),
            'create' => Pages\CreateStok::route('/create'),
            'edit' => Pages\EditStok::route('/{record}/edit'),
        ];
    }    
}
