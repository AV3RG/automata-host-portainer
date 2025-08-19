<?php

namespace Paymenter\Extensions\Others\ProductTags\Admin\Resources\ProductTagAssignmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Paymenter\Extensions\Others\ProductTags\Admin\Resources\ProductTagAssignmentResource;
use Paymenter\Extensions\Others\ProductTags\Services\TagService;

class AssignProductTags extends CreateRecord
{
    protected static string $resource = ProductTagAssignmentResource::class;
    
    protected static ?string $title = 'Assign Product Tags';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Create new tag if specified
        if (!empty($data['new_tag_name'])) {
            $newTag = TagService::createTag([
                'name' => $data['new_tag_name'],
                'color' => $data['new_tag_color'] ?? '#3b82f6',
            ]);
            
            // Add the new tag to the selection
            $data['tag_ids'] = array_merge($data['tag_ids'] ?? [], [$newTag->id]);
            
            Notification::make()
                ->title('New Tag Created')
                ->body("Tag '{$newTag->name}' has been created and assigned.")
                ->success()
                ->send();
        }
        
        // Assign tags to product
        TagService::assignTagsToProduct($data['product_id'], $data['tag_ids'] ?? []);
        
        // Get the product for the success message
        $productModel = config('paymenter.models.product', 'App\Models\Product');
        $product = $productModel::find($data['product_id']);
        
        Notification::make()
            ->title('Tags Assigned Successfully')
            ->body("Tags have been assigned to '{$product->name}'.")
            ->success()
            ->send();
        
        // Return a dummy model since we're not actually creating a record
        return new class extends \Illuminate\Database\Eloquent\Model {
            protected $fillable = ['*'];
        };
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // We don't actually create a model record, just handle the tag assignment
        return $data;
    }
}
