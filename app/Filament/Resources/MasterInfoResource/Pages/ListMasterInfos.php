<?php

namespace App\Filament\Resources\MasterInfoResource\Pages;

use App\Filament\Resources\MasterInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterInfos extends ListRecords
{
    protected static string $resource = MasterInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
