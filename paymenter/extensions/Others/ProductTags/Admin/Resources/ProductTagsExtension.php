<?php

namespace Paymenter\Extensions\Others\ProductTags\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Paymenter\Extensions\Others\ProductTags\Models\Tag;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

class ProductTagsExtension
{
    /**
     * Add tag management fields to Product forms
     */
    public static function getFormFields(): array
    {
        return [
            Forms\Components\Section::make('Product Tags')
                ->schema([
                    Forms\Components\Select::make('tag_ids')
                        ->label('Tags')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->options(function () {
                            return Tag::active()->orderBy('name')->pluck('name', 'id');
                        })
                        ->helperText('Select tags to categorize this product')
                        ->afterStateHydrated(function (Forms\Components\Select $component, $state, $record) {
                            if ($record) {
                                $tagIds = TagService::getProductTags($record->id)->pluck('id')->toArray();
                                $component->state($tagIds);
                            }
                        }),
                    
                    Forms\Components\TextInput::make('new_tag_name')
                        ->label('Create New Tag')
                        ->placeholder('Enter tag name to create')
                        ->helperText('Type a new tag name and it will be created when you save')
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $set('new_tag_color', '#' . substr(md5($state), 0, 6));
                            }
                        }),
                    
                    Forms\Components\ColorPicker::make('new_tag_color')
                        ->label('New Tag Color')
                        ->default('#3b82f6')
                        ->hex()
                        ->visible(fn (callable $get) => filled($get('new_tag_name'))),
                ])
                ->columns(2)
                ->collapsible(),
        ];
    }

    /**
     * Add tag display to Product tables
     */
    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('tags')
                ->label('Tags')
                ->formatStateUsing(function ($record) {
                    $tags = TagService::getProductTags($record->id);
                    if ($tags->isEmpty()) {
                        return new \Illuminate\Support\HtmlString('<span class="text-gray-400 italic">No tags</span>');
                    }
                    
                    $html = '';
                    foreach ($tags->take(3) as $tag) {
                        $html .= '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mr-1 mb-1" style="background-color: ' . $tag->display_color . '; color: ' . $tag->text_color . ';">';
                        $html .= e($tag->name);
                        $html .= '</span>';
                    }
                    
                    if ($tags->count() > 3) {
                        $html .= '<span class="text-xs text-gray-500">+' . ($tags->count() - 3) . ' more</span>';
                    }
                    
                    return new \Illuminate\Support\HtmlString($html);
                })
                ->html()
                ->searchable(false)
                ->sortable(false),
        ];
    }

    /**
     * Handle saving tags when product is saved
     */
    public static function handleSave($record, array $data): void
    {
        $tagIds = $data['tag_ids'] ?? [];
        
        // Create new tag if specified
        if (!empty($data['new_tag_name'])) {
            $newTag = TagService::createTag([
                'name' => $data['new_tag_name'],
                'color' => $data['new_tag_color'] ?? '#3b82f6',
            ]);
            $tagIds[] = $newTag->id;
        }
        
        // Assign tags to product
        if (!empty($tagIds)) {
            TagService::assignTagsToProduct($record->id, $tagIds);
        } else {
            TagService::assignTagsToProduct($record->id, []);
        }
    }

    /**
     * Add tag filters to Product tables
     */
    public static function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('tags')
                ->label('Filter by Tag')
                ->multiple()
                ->options(function () {
                    return Tag::active()->orderBy('name')->pluck('name', 'id');
                })
                ->query(function ($query, array $data) {
                    if (!empty($data['values'])) {
                        $query->whereIn('id', function ($subQuery) use ($data) {
                            $subQuery->select('product_id')
                                ->from('ext_product_tag_assignments')
                                ->whereIn('tag_id', $data['values']);
                        });
                    }
                }),
        ];
    }

    /**
     * Add bulk actions for tag management
     */
    public static function getBulkActions(): array
    {
        return [
            Tables\Actions\BulkAction::make('assignTags')
                ->label('Assign Tags')
                ->icon('heroicon-o-tag')
                ->form([
                    Forms\Components\Select::make('tag_ids')
                        ->label('Tags to Assign')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->options(function () {
                            return Tag::active()->orderBy('name')->pluck('name', 'id');
                        })
                        ->required(),
                ])
                ->action(function ($records, array $data) {
                    foreach ($records as $record) {
                        $existingTags = TagService::getProductTags($record->id)->pluck('id')->toArray();
                        $newTags = array_unique(array_merge($existingTags, $data['tag_ids']));
                        TagService::assignTagsToProduct($record->id, $newTags);
                    }
                })
                ->deselectRecordsAfterCompletion(),
            
            Tables\Actions\BulkAction::make('removeTags')
                ->label('Remove Tags')
                ->icon('heroicon-o-minus-circle')
                ->color('danger')
                ->form([
                    Forms\Components\Select::make('tag_ids')
                        ->label('Tags to Remove')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->options(function () {
                            return Tag::active()->orderBy('name')->pluck('name', 'id');
                        })
                        ->required(),
                ])
                ->action(function ($records, array $data) {
                    foreach ($records as $record) {
                        $existingTags = TagService::getProductTags($record->id)->pluck('id')->toArray();
                        $remainingTags = array_diff($existingTags, $data['tag_ids']);
                        TagService::assignTagsToProduct($record->id, $remainingTags);
                    }
                })
                ->deselectRecordsAfterCompletion(),
        ];
    }
}
