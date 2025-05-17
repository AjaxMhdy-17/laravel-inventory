<?php

namespace App\View\Components;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class BlogSidebar extends Component
{

    public $recentPosts;
    public $mostCommented;
    public $categories;
    public $tags;
    public $activeUsers;

    public function __construct()
    {
        $this->recentPosts = Cache::remember('recentPosts', now()->addSeconds(120), function () {
            return  BlogPost::latest()->take(3)->get();
        });
        $this->mostCommented = Cache::remember('mostCommented', now()->addSeconds(120), function () {
            return BlogPost::mostCommented()
                ->take(3)
                ->get(['title', 'slug', 'photo']);
        });
        $this->categories = Cache::remember('categories', now()->addSeconds(120), function () {
            return Category::all();
        });
        $this->tags = Cache::remember('tags', now()->addSeconds(120), function () {
            return Tag::all();
        });
        $this->activeUsers =  Cache::remember('activeUsers', now()->addSeconds(120), function () {
            return User::activeUser()->take(3)->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.blog-sidebar');
    }
}
