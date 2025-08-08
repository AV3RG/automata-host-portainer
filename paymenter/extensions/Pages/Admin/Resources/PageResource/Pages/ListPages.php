<?php

namespace Paymenter\Extensions\Others\Pages\Admin\Resources\PageResource\Pages;

use Paymenter\Extensions\Others\Pages\Admin\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
