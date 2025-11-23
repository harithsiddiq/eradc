<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\Media;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'admin', 'password' => bcrypt('password')]
        );

        $categories = @require base_path('database/data/category.php');
        if (is_array($categories)) {
            foreach ($categories as $c) {
                $cat = Category::firstOrNew(['slug' => $c['slug']]);
                $cat->name = $c['name'] ?? [];
                $cat->description = $c['description'] ?? [];
                $cat->slug = $c['slug'];
                $cat->link = $c['link'] ?? null;
                $cat->show_in_menu = (bool) ($c['show_in_menu'] ?? true);
                $cat->order = (int) ($c['order'] ?? 0);
                $cat->parent_id = $c['parent_id'] ?? null;
                $cat->save();
            }
        }

        $posts = @require base_path('database/data/posts.php');
        if (is_array($posts)) {
            foreach ($posts as $p) {
                $author = isset($p['author_email']) ? User::where('email', $p['author_email'])->first() : $admin;
                $category = isset($p['category_slug']) ? Category::where('slug', $p['category_slug'])->first() : null;
                $post = Post::firstOrNew(['slug' => $p['slug']]);
                $post->title = $p['title'] ?? [];
                $post->content = $p['content'] ?? [];
                $post->excerpt = $p['excerpt'] ?? [];
                $post->status = $p['status'] ?? 'draft';
                $post->author_id = $author?->getKey();
                $post->featured_image_path = $p['featured_image_path'] ?? null;
                $post->additional_images = $p['additional_images'] ?? [];
                $post->published_at = $p['published_at'] ?? null;
                $post->category_id = $category?->getKey();
                $post->save();

                // Normalize additional_images to media IDs if strings provided
                if (!empty($p['additional_images']) && is_array($p['additional_images'])) {
                    $ids = [];
                    foreach ($p['additional_images'] as $item) {
                        if (is_numeric($item)) {
                            $ids[] = (int) $item;
                        } elseif (is_string($item)) {
                            $m = Media::where('file_path', $item)->orWhere('file_name', $item)->first();
                            if ($m) { $ids[] = $m->getKey(); }
                        }
                    }
                    if (!empty($ids)) {
                        $post->additional_images = $ids;
                        $post->save();
                    }
                }
            }
        }

        // Media
        $mediaItems = @require base_path('database/data/media.php');
        if (is_array($mediaItems)) {
            foreach ($mediaItems as $m) {
                $media = Media::firstOrNew(['file_path' => $m['file_path'] ?? null]);
                $media->file_name = $m['file_name'] ?? basename((string) ($m['file_path'] ?? ''));
                $media->file_type = $m['file_type'] ?? null;
                $media->file_size = $m['file_size'] ?? 0;
                $media->alt_text = $m['alt_text'] ?? [];
                $media->save();
            }
        }

        // Post meta
        $metaItems = @require base_path('database/data/post_meta.php');
        if (is_array($metaItems)) {
            foreach ($metaItems as $item) {
                $post = isset($item['post_slug']) ? Post::where('slug', $item['post_slug'])->first() : null;
                if (!$post) { continue; }
                $meta = PostMeta::firstOrNew([
                    'post_id' => $post->getKey(),
                    'meta_key' => $item['meta_key'] ?? null,
                ]);
                $meta->meta_value = $item['meta_value'] ?? [];
                $meta->save();
            }
        }
    }
}
