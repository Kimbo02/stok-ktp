<?php

namespace App\Filament\Resources;

use App\Models\Surat2;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Surat2Resource\Pages;
use Filament\Tables\Actions\Action;

class Surat2Resource extends Resource
{
    protected static ?string $model = Surat2::class;

    protected static ?string $navigationLabel = 'Surat 2';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Dokumen';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor_kelahiran')
                ->required()
                ->label('Nomor Kelahiran')
                ->maxLength(255),

            TextInput::make('nama_bersangkutan')
                ->required()
                ->label('Nama Bersangkutan')
                ->maxLength(255),

            TextInput::make('nama_pemohon')
                ->required()
                ->label('Nama Pemohon')
                ->maxLength(255),

            Textarea::make('alamat_tinggal')
                ->required()
                ->label('Alamat Tinggal')
                ->maxLength(1000),

            DatePicker::make('tanggal_lapor')
                ->required()
                ->label('Tanggal Lapor'),

            DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal Surat'),

            FileUpload::make('file_pdf')
                ->label('File PDF Surat (Otomatis)')
                ->directory('surat2s')
                ->acceptedFileTypes(['application/pdf'])
                ->disabled()
                ->hint('Akan dibuat otomatis setelah surat disimpan atau diperbarui.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_kelahiran')
                    ->label('Nomor Kelahiran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_bersangkutan')
                    ->label('Nama Bersangkutan')
                    ->searchable(),

                TextColumn::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->searchable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal Surat')
                    ->date('d F Y')
                    ->sortable(),

                TextColumn::make('tanggal_lapor')
                    ->label('Tanggal Lapor')
                    ->date('d F Y')
                    ->sortable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->actions([
                Action::make('Download PDF')
                    ->label('Download PDF')
                    ->url(fn ($record) => $record->file_pdf ? asset('storage/' . $record->file_pdf) : '#')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-download')
                    ->visible(fn ($record) => !empty($record->file_pdf)),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurat2s::route('/'),
            'create' => Pages\CreateSurat2::route('/create'),
            'edit' => Pages\EditSurat2::route('/{record}/edit'),
        ];
    }
}
