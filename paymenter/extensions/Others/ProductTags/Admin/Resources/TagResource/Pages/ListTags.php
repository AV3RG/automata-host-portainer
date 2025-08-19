<?php

namespace Paymenter\Extensions\Others\ProductTags\Admin\Resources\TagResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Paymenter\Extensions\Others\ProductTags\Admin\Resources\TagResource;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            Actions\Action::make('cleanup')
                ->label('Cleanup Unused Tags')
                ->icon('heroicon-o-trash')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Cleanup Unused Tags')
                ->modalDescription('This will permanently delete all tags that are not assigned to any products. This action cannot be undone.')
                ->action(function () {
                    $result = TagService::cleanupUnusedTags();
                    
                    if ($result['count'] > 0) {
                        Notification::make()
                            ->title('Cleanup Complete')
                            ->body("Cleaned up {$result['count']} unused tags.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('No Action Needed')
                            ->body('No unused tags found.')
                            ->info()
                            ->send();
                    }
                }),
            
            Actions\Action::make('stats')
                ->label('Tag Statistics')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->modalContent(function () {
                    $stats = TagService::getTagStats();
                    
                    $content = '<div class="space-y-4">';
                    $content .= '<div class="grid grid-cols-2 gap-4">';
                    $content .= '<div class="text-center"><div class="text-2xl font-bold text-blue-600">' . $stats['total_tags'] . '</div><div class="text-sm text-gray-600">Total Tags</div></div>';
                    $content .= '<div class="text-center"><div class="text-2xl font-bold text-green-600">' . $stats['active_tags'] . '</div><div class="text-sm text-gray-600">Active Tags</div></div>';
                    $content .= '<div class="text-center"><div class="text-2xl font-bold text-yellow-600">' . $stats['featured_tags'] . '</div><div class="text-sm text-gray-600">Featured Tags</div></div>';
                    $content .= '<div class="text-center"><div class="text-2xl font-bold text-red-600">' . $stats['unused_tags'] . '</div><div class="text-sm text-gray-600">Unused Tags</div></div>';
                    $content .= '</div></div>';
                    
                    return new \Illuminate\Support\HtmlString($content);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Widgets can be added here later if needed
        ];
    }
}
