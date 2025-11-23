<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable as EditTranslatable;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class EditPost extends EditRecord
{
    use EditTranslatable;
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // featured_image_path is directly bound to the FileUpload field

        unset($data['gallery']);
        return $data;
    }

    protected function afterSave(): void
    {
        $state = $this->form->getState();
        Log::info('EditPost afterSave state', ['featured_image' => $state['featured_image'] ?? null, 'gallery' => $state['gallery'] ?? null]);
        // featured_image_path is persisted automatically now
        $gallery = $state['gallery'] ?? [];

        if (is_array($gallery)) {
            $ids = [];
            foreach ($gallery as $path) {
                $ids[] = $this->findOrCreateMediaIdByPath($this->normalizePublicPath($path));
            }
            $this->record->additional_images = $ids;
            $this->record->save();
            Log::info('EditPost saved additional_images', ['post_id' => $this->record->getKey(), 'additional_images' => $ids]);
        }

        // Save meta items
        $metaItems = $state['meta_items'] ?? [];
        if (is_array($metaItems)) {
            $locale = app()->getLocale();
            // Sync: update/create items present; remove ones not present
            $keys = [];
            foreach ($metaItems as $item) {
                $key = $item['meta_key'] ?? null;
                $val = $item['meta_value'] ?? null;
                if (!$key) continue;
                $keys[] = $key;
                $meta = $this->record->meta()->firstOrNew(['meta_key' => $key]);
                $meta->post_id = $this->record->getKey();
                $meta->setTranslation('meta_value', $locale, $val ?? '');
                $meta->save();
            }
            if (count($keys)) {
                $this->record->meta()->whereNotIn('meta_key', $keys)->delete();
            }
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $ids = $this->record->additional_images;
        if (is_array($ids) && count($ids)) {
            $data['gallery'] = Media::whereIn('id', $ids)->pluck('file_path')->toArray();
        } else {
            $data['gallery'] = [];
        }
        // Pre-fill meta repeater
        $locale = app()->getLocale();
        $data['meta_items'] = $this->record->meta()->get()->map(function ($m) use ($locale) {
            return [
                'meta_key' => $m->meta_key,
                'meta_value' => $m->getTranslation('meta_value', $locale, false) ?? '',
            ];
        })->toArray();
        return $data;
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
