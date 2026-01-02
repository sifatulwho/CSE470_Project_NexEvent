<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Comment;
use App\Models\Review;
use App\Models\Certificate;
use App\Models\Wishlist;
use App\Policies\EventPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\CertificatePolicy;
use App\Policies\WishlistPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Comment::class => CommentPolicy::class,
        Review::class => ReviewPolicy::class,
        Certificate::class => CertificatePolicy::class,
        Wishlist::class => WishlistPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }

    /**
     * Register authorization policies.
     */
    protected function registerPolicies(): void
    {
        \Illuminate\Support\Facades\Gate::policy(Event::class, EventPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(Comment::class, CommentPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(Review::class, ReviewPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(Certificate::class, CertificatePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(Wishlist::class, WishlistPolicy::class);
    }
}
