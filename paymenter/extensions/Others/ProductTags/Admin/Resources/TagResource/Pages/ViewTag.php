<?php

namespace Paymenter\Extensions\Others\ProductTags\Admin\Resources\TagResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;
use Paymenter\Extensions\Others\ProductTags\Admin\Resources\TagResource;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

class ViewTag extends ViewRecord
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            
            Actions\Action::make('viewProducts')
                ->label('View Products')
                ->icon('heroicon-o-cube')
                ->color('info')
                ->action(function () {
                    $products = TagService::getTagProducts($this->record->id);
                    
                    Notification::make()
                        ->title('Tag Usage Information')
                        ->body("This tag is used by {$products->count()} products.")
                        ->info()
                        ->send();
                }),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Widgets can be added here later if needed
        ];
    }
}
