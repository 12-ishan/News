<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $table = 'news_category';

    public function news()
    {
        return $this->hasMany(News::class, 'category_id');
    }

    // protected static function booted()
    // {
    //     static::retrieved(function ($page) {
    //         $currentRoute = request()->route()->getName(); // Get the route name
    //         $currentUrl = request()->fullUrl(); // Get the full URL
        
    //         // Check if the current route is 'admin.news.index' or other relevant route
    //         activity()
    //             ->event('view') // Custom event for viewing a page
    //             ->causedBy(auth()->user())
    //             ->performedOn($page)
    //             ->withProperties([
    //                 'route' => $currentRoute,
    //                 'url' => $currentUrl,
    //             ])
    //             ->log("Page '{$currentUrl}' was viewed.");
    //     });
    // }
 
}