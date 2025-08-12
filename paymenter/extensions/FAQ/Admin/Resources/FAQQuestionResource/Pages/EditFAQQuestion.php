<?php

namespace Paymenter\Extensions\Others\FAQ\Admin\Resources\FAQQuestionResource\Pages;

use Paymenter\Extensions\Others\FAQ\Admin\Resources\FAQQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFAQQuestion extends EditRecord
{
    protected static string $resource = FAQQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

