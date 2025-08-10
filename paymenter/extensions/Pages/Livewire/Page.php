<?php

namespace Paymenter\Extensions\Others\Pages\Livewire;

use App\Livewire\Component;
use Paymenter\Extensions\Others\Pages\Models\Page as ModelsPage;

class Page extends Component
{
    public ModelsPage $page;

    public function mount($fallbackPlaceholder)
    {
        // Validate if page exists
        $this->page = ModelsPage::where('slug', $fallbackPlaceholder)->firstOrFail();
        if (!$this->page->visible) {
            abort(404);
        }
        // Validate if user is logged in
        if ($this->page->visibility == 'client' && !auth()->check()) {
            abort(404);
        }
        if ($this->page->visibility == 'admin' && (!auth()->check() || is_null(auth()->user()->role))) {
            abort(404);
        }
    }

    /**
     * Get the effective route for this page
     */
    public function getEffectiveRouteAttribute()
    {
        return $this->page->custom_route ?: '/' . $this->page->slug;
    }

    public function render()
    {
        return view('others.pages::page', [
            'page' => $this->page,
        ])->layout('layouts.app', [
            'title' => $this->page->title,
            'description' => $this->page->description,
            'sidebar' => $this->page->navigation == 'dashboard' ? true : false,
        ]);
    }
}
