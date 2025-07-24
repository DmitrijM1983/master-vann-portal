<?php

namespace App\Filament\Resources\MasterInfoResource\Pages;

use App\Filament\Resources\MasterInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterInfo extends EditRecord
{
    protected static string $resource = MasterInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
