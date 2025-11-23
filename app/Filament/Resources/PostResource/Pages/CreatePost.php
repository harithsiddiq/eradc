<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable as CreateTranslatable;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;

class CreatePost extends CreateRecord
{
    use CreateTranslatable;
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = Filament::auth()->id() ?? auth()->id();
        // featured_image_path is directly bound to the FileUpload field
        $gallery = $data['gallery'] ?? [];
        if (is_string($gallery)) { $gallery = [$gallery]; }
        if (is_array($gallery) && count($gallery)) {
            $ids = [];
            foreach ($gallery as $path) {
                $ids[] = $this->findOrCreateMediaIdByPath($this->normalizePublicPath($path));
            }
            $data['additional_images'] = $ids;
        }

        unset($data['gallery']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $state = $this->form->getState();
        Log::info('CreatePost afterCreate state', ['featured_image' => $state['featured_image'] ?? null, 'gallery' => $state['gallery'] ?? null]);
        // featured_image_path is persisted automatically now
        $gallery = $state['gallery'] ?? [];

        if (is_array($gallery) && count($gallery)) {
            $ids = [];
            foreach ($gallery as $path) {
                $ids[] = $this->findOrCreateMediaIdByPath($this->normalizePublicPath($path));
            }
            $this->record->additional_images = $ids;
            $this->record->save();
            Log::info('CreatePost saved additional_images', ['post_id' => $this->record->getKey(), 'additional_images' => $ids]);
        }

        // Save meta items
        $metaItems = $state['meta_items'] ?? [];
        if (is_array($metaItems)) {
            $locale = app()->getLocale();
            foreach ($metaItems as $item) {
                $key = $item['meta_key'] ?? null;
                $val = $item['meta_value'] ?? null;
                if (!$key) continue;
                $meta = $this->record->meta()->firstOrNew(['meta_key' => $key]);
                $meta->post_id = $this->record->getKey();
                $meta->setTranslation('meta_value', $locale, $val ?? '');
                $meta->save();
            }
        }
    }

    protected function createMediaFromPath(string $path): int
    {
        $disk = 'public';
        $size = Storage::disk($disk)->size($path);
        $mime = Storage::disk($disk)->mimeType($path);

        $fileName = Str::of($path)->afterLast('/')->toString();

        $media = Media::create([
            'file_name' => $fileName,
            'file_path' => $path,
            'file_type' => $mime ?? 'image',
            'file_size' => $size ?? 0,
            'alt_text' => null,
        ]);

        return (int) $media->getKey();
    }

    protected function findOrCreateMediaIdByPath(string $path): int
    {
        $existing = Media::where('file_path', $path)->first();
        return $existing ? (int) $existing->getKey() : $this->createMediaFromPath($path);
    }

    protected function normalizePublicPath(string $path): string
    {
        $path = trim($path ?? '');
        if ($path === '') return $path;
        if (str_starts_with($path, '/storage/')) return substr($path, 9);
        if (str_starts_with($path, 'storage/')) return substr($path, 8);
        return $path;
    }
}
        // Persist meta repeater
        $metaItems = $data['meta_items'] ?? [];
        if (is_array($metaItems)) {
            $data['meta_items'] = array_values(array_filter($metaItems, fn($m) => !empty($m['meta_key'])));
        }
