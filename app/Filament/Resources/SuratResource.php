<?php

namespace App\Filament\Resources;

use App\Models\Surat;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use App\Filament\Resources\SuratResource\Pages;

class SuratResource extends Resource
<<<<<<< HEAD
{
    protected static ?string $model = Surat::class;

=======

{
    protected static ?string $model = Surat::class;

    protected static ?string $navigationLabel = 'Surat';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Dokumen';

>>>>>>> a0f3416 (first commit)
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor_surat')
                ->required()
                ->label('Nomor Surat')
                ->maxLength(255),

            Textarea::make('keterangan')
                ->required()
                ->label('Keterangan')
                ->maxLength(1000),

            DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal Surat'),

            FileUpload::make('template_surat')
                ->label('Template Surat (Word)')
                ->directory('templates')
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->preserveFilenames()
                ->hint('Kosongkan jika upload PDF secara manual'),

            FileUpload::make('file_pdf')
                ->label('Upload Manual PDF (Opsional)')
                ->directory('pdf')
                ->acceptedFileTypes(['application/pdf'])
                ->preserveFilenames()
                ->hint('Kosongkan jika ingin digenerate otomatis'),
            
            TextInput::make('link_ttd')
                ->label('Link TTD (untuk QR Code)')
                ->required()
                ->url()
                ->maxLength(255),
            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal Surat')
                    ->date('d F Y'),

                Tables\Columns\TextColumn::make('file_pdf')
                    ->label('Download PDF')
                    ->formatStateUsing(fn ($record) => $record->file_pdf ? 'Download' : '-')
                    ->url(fn ($record) => $record->file_pdf ? asset('storage/' . $record->file_pdf) : null, true)
                    ->openUrlInNewTab()
                    ->color(fn ($record) => $record->file_pdf ? 'primary' : 'secondary'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurats::route('/'),
            'create' => Pages\CreateSurat::route('/create'),
            'edit' => Pages\EditSurat::route('/{record}/edit'),
        ];
    }
}
