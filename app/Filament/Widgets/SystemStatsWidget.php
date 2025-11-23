<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Category;
use App\Models\Post;
use App\Models\Media;
use App\Models\User;
use App\Models\Visit;

class SystemStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $categories = Category::query()->where('show_in_menu', true)->count();
        $publishedPosts = Post::query()->where('status', 'published')->count();
        $mediaCount = Media::query()->count();
        $usersCount = User::query()->count();
        $visitsTotal = Visit::query()->count();
        $visitsToday = Visit::query()->where('date', now()->toDateString())->count();

        return [
            Stat::make('أقسام الموقع', $categories)
                ->icon('heroicon-o-list-bullet'),
            Stat::make('المحتوى المنشور', $publishedPosts)
                ->icon('heroicon-o-document-text'),
            Stat::make('الوسائط', $mediaCount)
                ->icon('heroicon-o-photo'),
            Stat::make('المستخدمون', $usersCount)
                ->icon('heroicon-o-user'),
            Stat::make('زوار اليوم', $visitsToday)
                ->icon('heroicon-o-user-group'),
            Stat::make('إجمالي الزيارات', $visitsTotal)
                ->icon('heroicon-o-chart-bar'),
        ];
    }
}
