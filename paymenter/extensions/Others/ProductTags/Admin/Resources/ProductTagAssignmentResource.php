<?php

namespace Paymenter\Extensions\Others\ProductTags\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Paymenter\Extensions\Others\ProductTags\Models\Tag;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

class ProductTagAssignmentResource extends Resource
{
    protected static ?string $model = null; // We'll handle this manually
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Assign Product Tags';
    protected static ?string $pluralModelLabel = 'Product Tag Assignments';
    protected static ?string $navigationGroup = 'Products';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Tag Assignment')
                    ->description('Assign tags to products for better organization and filtering')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Select Product')
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                $productModel = config('paymenter.models.product', 'App\Models\Product');
                                return $productModel::all()->pluck('name', 'id');
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state) {
                                    $tags = TagService::getProductTags($state);
                                    $set('current_tags', $tags->pluck('id')->toArray());
                                }
                            }),
                        
                        Forms\Components\Placeholder::make('current_tags_display')
                            ->label('Current Tags')
                            ->content(function (Forms\Get $get) {
                                $productId = $get('product_id');
                                if (!$productId) {
                                    return 'Select a product to see current tags';
                                }
                                
                                $tags = TagService::getProductTags($productId);
                                if ($tags->isEmpty()) {
                                    return new \Illuminate\Support\HtmlString('<span class="text-gray-500 italic">No tags assigned</span>');
                                }
                                
                                $html = '<div class="flex flex-wrap gap-2">';
                                foreach ($tags as $tag) {
                                    $html .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: ' . $tag->display_color . '; color: ' . $tag->text_color . ';">';
                                    $html .= e($tag->name);
                                    $html .= '</span>';
                                }
                                $html .= '</div>';
                                
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->visible(fn (Forms\Get $get) => $get('product_id')),
                        
                        Forms\Components\Select::make('tag_ids')
                            ->label('Assign Tags')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return Tag::active()->orderBy('name')->pluck('name', 'id');
                            })
                            ->helperText('Select tags to assign to this product')
                            ->afterStateHydrated(function (Forms\Components\Select $component, Forms\Get $get) {
                                $productId = $get('product_id');
                                if ($productId) {
                                    $currentTags = TagService::getProductTags($productId)->pluck('id')->toArray();
                                    $component->state($currentTags);
                                }
                            }),
                        
                        Forms\Components\Fieldset::make('Quick Tag Creation')
                            ->schema([
                                Forms\Components\TextInput::make('new_tag_name')
                                    ->label('Create New Tag')
                                    ->placeholder('Enter tag name')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            // Generate a color based on the tag name
                                            $set('new_tag_color', '#' . substr(md5($state), 0, 6));
                                        }
                                    }),
                                
                                Forms\Components\ColorPicker::make('new_tag_color')
                                    ->label('Tag Color')
                                    ->default('#3b82f6')
                                    ->hex()
                                    ->visible(fn (Forms\Get $get) => filled($get('new_tag_name'))),
                            ])
                            ->columns(2),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        $productModel = config('paymenter.models.product', 'App\Models\Product');
        
        return $table
            ->query($productModel::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                Tables\Columns\TextColumn::make('tags')
                    ->label('Assigned Tags')
                    ->formatStateUsing(function ($record) {
                        $tags = TagService::getProductTags($record->id);
                        
                        if ($tags->isEmpty()) {
                            return new \Illuminate\Support\HtmlString('<span class="text-gray-400 italic">No tags</span>');
                        }
                        
                        $html = '<div class="flex flex-wrap gap-1">';
                        foreach ($tags->take(4) as $tag) {
                            $html .= '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background-color: ' . $tag->display_color . '; color: ' . $tag->text_color . ';">';
                            $html .= e($tag->name);
                            $html .= '</span>';
                        }
                        
                        if ($tags->count() > 4) {
                            $html .= '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">+' . ($tags->count() - 4) . ' more</span>';
                        }
                        
                        $html .= '</div>';
                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->html()
                    ->searchable(false)
                    ->sortable(false),
                
                Tables\Columns\TextColumn::make('tag_count')
                    ->label('Tag Count')
                    ->formatStateUsing(function ($record) {
                        $count = TagService::getProductTags($record->id)->count();
                        return $count;
                    })
                    ->badge()
                    ->color(fn ($state) => $state > 3 ? 'success' : ($state > 0 ? 'warning' : 'gray'))
                    ->sortable(false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tag_filter')
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
                
                Tables\Filters\Filter::make('untagged')
                    ->label('Products without tags')
                    ->query(function ($query) {
                        $query->whereNotIn('id', function ($subQuery) {
                            $subQuery->select('product_id')
                                ->from('ext_product_tag_assignments');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('manageTags')
                    ->label('Manage Tags')
                    ->icon('heroicon-o-tag')
                    ->form([
                        Forms\Components\Select::make('tag_ids')
                            ->label('Product Tags')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return Tag::active()->orderBy('name')->pluck('name', 'id');
                            })
                            ->default(function ($record) {
                                return TagService::getProductTags($record->id)->pluck('id')->toArray();
                            }),
                    ])
                    ->action(function ($record, array $data) {
                        TagService::assignTagsToProduct($record->id, $data['tag_ids'] ?? []);
                        
                        Notification::make()
                            ->title('Tags Updated')
                            ->body("Tags for '{$record->name}' have been updated successfully.")
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('clearTags')
                    ->label('Clear All Tags')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        TagService::assignTagsToProduct($record->id, []);
                        
                        Notification::make()
                            ->title('Tags Cleared')
                            ->body("All tags have been removed from '{$record->name}'.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
                            
                            Forms\Components\Radio::make('action_type')
                                ->label('Action')
                                ->options([
                                    'add' => 'Add to existing tags',
                                    'replace' => 'Replace all existing tags',
                                ])
                                ->default('add')
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($data['action_type'] === 'add') {
                                    $existingTags = TagService::getProductTags($record->id)->pluck('id')->toArray();
                                    $newTags = array_unique(array_merge($existingTags, $data['tag_ids']));
                                } else {
                                    $newTags = $data['tag_ids'];
                                }
                                
                                TagService::assignTagsToProduct($record->id, $newTags);
                                $count++;
                            }
                            
                            Notification::make()
                                ->title('Bulk Tag Assignment Complete')
                                ->body("Tags have been assigned to {$count} products.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    Tables\Actions\BulkAction::make('removeTags')
                        ->label('Remove Tags')
                        ->icon('heroicon-o-minus-circle')
                        ->color('warning')
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
                            $count = 0;
                            foreach ($records as $record) {
                                $existingTags = TagService::getProductTags($record->id)->pluck('id')->toArray();
                                $remainingTags = array_diff($existingTags, $data['tag_ids']);
                                TagService::assignTagsToProduct($record->id, $remainingTags);
                                $count++;
                            }
                            
                            Notification::make()
                                ->title('Bulk Tag Removal Complete')
                                ->body("Tags have been removed from {$count} products.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    Tables\Actions\BulkAction::make('clearAllTags')
                        ->label('Clear All Tags')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                TagService::assignTagsToProduct($record->id, []);
                                $count++;
                            }
                            
                            Notification::make()
                                ->title('Bulk Tag Clearing Complete')
                                ->body("All tags have been cleared from {$count} products.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => \Paymenter\Extensions\Others\ProductTags\Admin\Resources\ProductTagAssignmentResource\Pages\ListProductTagAssignments::route('/'),
            'create' => \Paymenter\Extensions\Others\ProductTags\Admin\Resources\ProductTagAssignmentResource\Pages\AssignProductTags::route('/assign'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $productModel = config('paymenter.models.product', 'App\Models\Product');
        $totalProducts = $productModel::count();
        $taggedProducts = \DB::table('ext_product_tag_assignments')
            ->distinct('product_id')
            ->count();
        
        return "{$taggedProducts}/{$totalProducts}";
    }

    public static function canCreate(): bool
    {
        return true;
    }
}
