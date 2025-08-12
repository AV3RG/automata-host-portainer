<?php

namespace Paymenter\Extensions\Others\FAQ\Admin\Resources\FAQQuestionResource\Pages;

use Paymenter\Extensions\Others\FAQ\Admin\Resources\FAQQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFAQQuestions extends ListRecords
{
    protected static string $resource = FAQQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

