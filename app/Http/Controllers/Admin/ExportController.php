<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\Media;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->type !== 'admin') {
            abort(403);
        }

        $base = database_path('data');

        $toPhp = function ($data) {
            $encode = function ($value) use (&$encode) {
                if (is_array($value)) {
                    $items = [];
                    $isAssoc = array_keys($value) !== range(0, count($value) - 1);
                    foreach ($value as $k => $v) {
                        $valStr = $encode($v);
                        if ($isAssoc) {
                            $items[] = var_export($k, true) . ' => ' . $valStr;
                        } else {
                            $items[] = $valStr;
                        }
                    }
                    return '[' . implode(', ', $items) . ']';
                }
                if (is_string($value)) {
                    return var_export($value, true);
                }
                if (is_bool($value) || is_int($value) || is_float($value) || is_null($value)) {
                    return var_export($value, true);
                }
                return var_export((string) $value, true);
            };
            return "<?php\n\nreturn " . $encode($data) . ";\n";
        };

        // Categories
        $categories = Category::with('parent')->orderBy('order')->get()->map(function ($c) {
            return [
                'name' => $c->getTranslations('name'),
                'slug' => $c->slug,
                'link' => $c->link,
                'show_in_menu' => (bool) $c->show_in_menu,
                'order' => (int) $c->order,
                'parent_id' => $c->parent_id,
                'description' => $c->getTranslations('description'),
            ];
        })->toArray();
        file_put_contents($base . '/category.php', $toPhp($categories));

        // Posts
        $posts = Post::with(['author', 'category'])->orderBy('created_at')->get()->map(function ($p) {
            return [
                'title' => $p->getTranslations('title'),
                'slug' => $p->slug,
                'content' => $p->getTranslations('content'),
                'excerpt' => $p->getTranslations('excerpt'),
                'status' => $p->status,
                'author_email' => optional($p->author)->email,
                'featured_image_path' => $p->featured_image_path,
                'additional_images' => $p->additional_images ?? [],
                'category_slug' => optional($p->category)->slug,
            ];
        })->toArray();
        file_put_contents($base . '/posts.php', $toPhp($posts));

        // Post meta
        $metas = PostMeta::with('post')->orderBy('post_id')->get()->map(function ($m) {
            return [
                'post_slug' => optional($m->post)->slug,
                'meta_key' => $m->meta_key,
                'meta_value' => $m->getTranslations('meta_value'),
            ];
        })->toArray();
        file_put_contents($base . '/post_meta.php', $toPhp($metas));

        // Media
        $media = Media::orderBy('created_at')->get()->map(function ($m) {
            return [
                'file_name' => $m->file_name,
                'file_path' => $m->file_path,
                'file_type' => $m->file_type,
                'file_size' => $m->file_size,
                'alt_text' => $m->getTranslations('alt_text'),
            ];
        })->toArray();
        file_put_contents($base . '/media.php', $toPhp($media));

        return response()->json(['status' => 'ok']);
    }
}
