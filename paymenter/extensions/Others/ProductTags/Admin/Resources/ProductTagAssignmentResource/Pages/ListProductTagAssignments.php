<?php

namespace Paymenter\Extensions\Others\ProductTags\Admin\Resources\ProductTagAssignmentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Paymenter\Extensions\Others\ProductTags\Admin\Resources\ProductTagAssignmentResource;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;
use Filament\Notifications\Notification;

class ListProductTagAssignments extends ListRecords
{
    protected static string $resource = ProductTagAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Assign Tags'),
            
            Actions\Action::make('quickStats')
                ->label('Tag Statistics')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->modalContent(function () {
                    $stats = TagService::getTagStats();
                    $productModel = config('paymenter.models.product', 'App\Models\Product');
                    $totalProducts = $productModel::count();
                    $taggedProducts = \DB::table('ext_product_tag_assignments')
                        ->distinct('product_id')
                        ->count();
                    
                    $content = '<div class="space-y-4">';
                    $content .= '<div class="grid grid-cols-2 gap-4">';
                    $content .= '<div class="text-center"><div class="text-2xl font-bold text-blue-600">' . $totalProducts . '</div><div class="text-sm text-gray-600">Total Products</div></div>';
                    $content .= '<div class="text-center"><div class="text-2xl font-bold text-green-600">' . $taggedProducts . '</div><div class="text-sm text-gray-600">Tagged Products</div></div>';
                    $content .= '<div class="text-center"><div class="text-2xl font-bold text-purple-600">' . $stats['total_tags'] . '</div><div class="text-sm text-gray-600">Total Tags</div></div>';
                    $content .= '<div class="text-center"><div class="text-2xl font-bold text-yellow-600">' . $stats['active_tags'] . '</div><div class="text-sm text-gray-600">Active Tags</div></div>';
                    $content .= '</div>';
                    
                    if ($stats['most_used_tag']) {
                        $content .= '<div class="mt-4 p-3 bg-gray-50 rounded">';
                        $content .= '<div class="text-sm font-semibold text-gray-700">Most Popular Tag:</div>';
                        $content .= '<div class="mt-1"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium" style="background-color: ' . $stats['most_used_tag']->display_color . '; color: ' . $stats['most_used_tag']->text_color . ';">';
                        $content .= $stats['most_used_tag']->name . ' (' . $stats['most_used_tag']->usage_count . ' products)';
                        $content .= '</span></div>';
                        $content .= '</div>';
                    }
                    
                    $content .= '</div>';
                    
                    return new \Illuminate\Support\HtmlString($content);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),
        ];
    }
}
