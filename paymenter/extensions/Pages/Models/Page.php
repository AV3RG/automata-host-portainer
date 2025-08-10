<?php

namespace Paymenter\Extensions\Others\Pages\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'ext_pages';
    protected $fillable = [
        'slug',
        'custom_route',
        'title',
        'description',
        'content',
        'visible',
        'as_html',
        'visibility',
        'navigation',
        'sort',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'html' => 'boolean',
        'visibility' => 'string',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the effective route for this page
     */
    public function getEffectiveRouteAttribute()
    {
        return $this->custom_route ?: '/' . $this->slug;
    }

    /**
     * Get the route name for this page
     */
    public function getRouteNameAttribute()
    {
        if ($this->custom_route) {
            return 'extensions.others.pages.custom.' . $this->id;
        }
        return 'extensions.others.pages';
    }

    /**
     * Get the route parameters for this page
     */
    public function getRouteParamsAttribute()
    {
        if ($this->custom_route) {
            return [];
        }
        return ['fallbackPlaceholder' => $this->slug];
    }

    /**
     * Check if the custom route conflicts with system routes
     */
    public function hasRouteConflict()
    {
        if (!$this->custom_route) {
            return false;
        }

        // List of common system routes that should not be overridden
        $systemRoutes = [
            '/',
            '/login',
            '/register',
            '/dashboard',
            '/admin',
            '/api',
            '/telescope',
            '/horizon',
            '/nova',
            '/filament',
        ];

        return in_array($this->custom_route, $systemRoutes);
    }

    /**
     * Get a warning message if there's a route conflict
     */
    public function getRouteConflictWarningAttribute()
    {
        if ($this->hasRouteConflict()) {
            return 'This route conflicts with a system route and may not work as expected.';
        }
        return null;
    }
}
