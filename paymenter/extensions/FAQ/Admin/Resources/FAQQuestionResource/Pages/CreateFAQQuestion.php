<?php

namespace Paymenter\Extensions\Others\FAQ\Admin\Resources\FAQQuestionResource\Pages;

use Paymenter\Extensions\Others\FAQ\Admin\Resources\FAQQuestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFAQQuestion extends CreateRecord
{
    protected static string $resource = FAQQuestionResource::class;
}

