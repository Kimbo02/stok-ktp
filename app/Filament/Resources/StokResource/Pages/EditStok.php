<?php

namespace App\Filament\Resources\StokResource\Pages;

use App\Filament\Resources\StokResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStok extends EditRecord
{
    protected static string $resource = StokResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
