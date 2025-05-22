<?php

namespace App\Filament\Resources\Surat2Resource\Pages;

use App\Filament\Resources\Surat2Resource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurat2s extends ListRecords
{
    protected static string $resource = Surat2Resource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
