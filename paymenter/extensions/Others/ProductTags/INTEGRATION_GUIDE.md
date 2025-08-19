# Product Tags UI Integration Guide

This guide shows you how to add tag management UI to your existing Product admin interface.

## Method 1: Extend Existing Product Resource (Recommended)

### Step 1: Modify Your Product Resource

Add tag management to your existing Product Filament resource by integrating the ProductTagsExtension:

```php
<?php
// In your existing ProductResource.php (usually app/Filament/Resources/ProductResource.php)

use Paymenter\Extensions\Others\ProductTags\Admin\Resources\ProductTagsExtension;

class ProductResource extends Resource
{
    // ... existing code ...

    public static function form(Form $form): Form
    {
        return $form->schema([
            // ... your existing form fields ...
            
            // Add tag management fields
            ...ProductTagsExtension::getFormFields(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ... your existing columns ...
                
                // Add tag display column
                ...ProductTagsExtension::getTableColumns(),
            ])
            ->filters([
                // ... your existing filters ...
                
                // Add tag filters
                ...ProductTagsExtension::getTableFilters(),
            ])
            ->bulkActions([
                // ... your existing bulk actions ...
                
                // Add tag bulk actions
                ...ProductTagsExtension::getBulkActions(),
            ]);
    }

    // Add this method to handle saving tags
    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);
        ProductTagsExtension::handleSave($record, $data);
        return $record;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);
        ProductTagsExtension::handleSave($record, $data);
        return $record;
    }
}
```

## Method 2: Create Standalone Tag Assignment Page

### Step 1: Create Tag Assignment Resource

```php
<?php
// Create app/Filament/Resources/ProductTagAssignmentResource.php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Paymenter\Extensions\Others\ProductTags\Models\Tag;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;
use App\Models\Product;

class ProductTagAssignmentResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Product Tags Assignment';
    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('id')
                ->label('Product')
                ->searchable()
                ->options(Product::all()->pluck('name', 'id'))
                ->required()
                ->reactive(),
                
            Forms\Components\Select::make('tag_ids')
                ->label('Tags')
                ->multiple()
                ->searchable()
                ->options(Tag::active()->orderBy('name')->pluck('name', 'id'))
                ->afterStateHydrated(function (Forms\Components\Select $component, $state, $record) {
                    if ($record) {
                        $tagIds = TagService::getProductTags($record->id)->pluck('id')->toArray();
                        $component->state($tagIds);
                    }
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tags')
                    ->formatStateUsing(function ($record) {
                        $tags = TagService::getProductTags($record->id);
                        return $tags->pluck('name')->join(', ') ?: 'No tags';
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        TagService::assignTagsToProduct($record->id, $data['tag_ids'] ?? []);
        return $record;
    }
}
```

## Method 3: Quick Tag Assignment Modal

### Add to Any Resource

```php
// Add this action to any resource's table actions
Tables\Actions\Action::make('manageTags')
    ->label('Manage Tags')
    ->icon('heroicon-o-tag')
    ->form([
        Forms\Components\Select::make('tag_ids')
            ->label('Tags')
            ->multiple()
            ->searchable()
            ->options(Tag::active()->orderBy('name')->pluck('name', 'id'))
            ->default(function ($record) {
                return TagService::getProductTags($record->id)->pluck('id')->toArray();
            }),
    ])
    ->action(function ($record, array $data) {
        TagService::assignTagsToProduct($record->id, $data['tag_ids'] ?? []);
        
        Notification::make()
            ->title('Tags Updated')
            ->body('Product tags have been updated successfully.')
            ->success()
            ->send();
    }),
```

## Method 4: Frontend Tag Assignment (For Customers/Users)

### Create a Livewire Component

```php
<?php
// Create app/Livewire/ProductTagManager.php

namespace App\Livewire;

use Livewire\Component;
use Paymenter\Extensions\Others\ProductTags\Models\Tag;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

class ProductTagManager extends Component
{
    public $product;
    public $selectedTags = [];
    public $availableTags;

    public function mount($product)
    {
        $this->product = $product;
        $this->availableTags = Tag::active()->orderBy('name')->get();
        $this->selectedTags = TagService::getProductTags($product->id)->pluck('id')->toArray();
    }

    public function updateTags()
    {
        TagService::assignTagsToProduct($this->product->id, $this->selectedTags);
        session()->flash('message', 'Tags updated successfully!');
    }

    public function render()
    {
        return view('livewire.product-tag-manager');
    }
}
```

### Create the Blade View

```blade
{{-- resources/views/livewire/product-tag-manager.blade.php --}}
<div class="p-6 bg-white rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4">Manage Product Tags</h3>
    
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="space-y-3">
        @foreach($availableTags as $tag)
            <label class="flex items-center">
                <input type="checkbox" 
                       wire:model="selectedTags" 
                       value="{{ $tag->id }}"
                       class="mr-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                      style="background-color: {{ $tag->display_color }}; color: {{ $tag->text_color }};">
                    {{ $tag->name }}
                </span>
            </label>
        @endforeach
    </div>

    <button wire:click="updateTags" 
            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
        Update Tags
    </button>
</div>
```

### Use in Templates

```blade
{{-- In any blade template --}}
@livewire('product-tag-manager', ['product' => $product])
```

## Method 5: API-Based Tag Assignment

### JavaScript Integration

```javascript
// Include this in your frontend JavaScript
class ProductTagManager {
    constructor(productId) {
        this.productId = productId;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    async assignTags(tagIds) {
        try {
            const response = await fetch('/api/tags/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    product_id: this.productId,
                    tag_ids: tagIds
                })
            });

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error assigning tags:', error);
            return { success: false, error: 'Network error' };
        }
    }
}

// Usage
const tagManager = new ProductTagManager(123);
tagManager.assignTags([1, 2, 3]).then(result => {
    if (result.success) {
        alert('Tags assigned successfully!');
    }
});
```

## Recommendations

1. **For Admin Users**: Use Method 1 (extend existing Product resource)
2. **For Standalone Management**: Use Method 2 (separate resource)
3. **For Quick Actions**: Use Method 3 (modal actions)
4. **For Frontend Users**: Use Method 4 (Livewire component)
5. **For Custom Integrations**: Use Method 5 (API)

Choose the method that best fits your application's architecture and user needs!
