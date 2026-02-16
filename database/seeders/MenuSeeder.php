<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Seed the application's menu items based on config/sections.php
     */
    public function run(): void
    {
        $sections = config('sections.menu_sections', []);

        if (empty($sections)) {
            return;
        }

        $order = 1;

        foreach ($sections as $slug => $label) {
            $targetType = $slug === 'posts' ? 'external' : 'section';

            $attributes = [
                'section_slug' => $slug,
                'target_type' => $targetType,
            ];

            $data = [
                'title' => [
                    'ar' => $label,
                    'en' => Str::title(str_replace(['-', '_'], ' ', $slug)),
                ],
                'section_slug' => $slug,
                'target_type' => $targetType,
                'external_url' => $targetType === 'external' ? route('posts.index') : null,
                'order' => $order++,
                'is_active' => true,
            ];

            Menu::updateOrCreate($attributes, $data);
        }
    }
}
