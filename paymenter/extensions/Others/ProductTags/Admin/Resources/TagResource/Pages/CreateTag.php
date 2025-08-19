<?php

namespace Paymenter\Extensions\Others\ProductTags\Admin\Resources\TagResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Paymenter\Extensions\Others\ProductTags\Admin\Resources\TagResource;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tag created successfully';
    }
}
