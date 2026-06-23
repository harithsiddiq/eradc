<?php

namespace App\Filament\Resources\PostResource\Pages;

use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\PostResource;
use App\Models\Media;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditPost extends EditRecord
{
    use Translatable {
        Translatable::updatedActiveLocale as traitUpdatedActiveLocale;
    }

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }

    public function updatedActiveLocale(): void
    {
        $this->traitUpdatedActiveLocale();

        $locale = $this->activeLocale;
        $this->data['meta_items'] = $this->record->meta()->get()->map(function ($m) use ($locale) {
            return [
                'meta_key' => $m->getTranslation('meta_key', $locale, false) ?? '',
                'meta_value' => $m->getTranslation('meta_value', $locale, false) ?? '',
            ];
        })->toArray();
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
            $locale = $this->activeLocale ?? app()->getLocale();
            $existingMetas = $this->record->meta()->get();
            foreach ($metaItems as $i => $item) {
                $key = $item['meta_key'] ?? null;
                $val = $item['meta_value'] ?? null;
                if (! $key) {
                    continue;
                }
                $meta = $existingMetas->get($i) ?? $this->record->meta()->make();
                $meta->post_id = $this->record->getKey();
                $meta->setTranslation('meta_key', $locale, $item['meta_key']);
                $meta->setTranslation('meta_value', $locale, $item['meta_value'] ?? null);
                $meta->save();
            }
            // Remove extra metas beyond what's in the repeater
            $existingMetas->slice(count($metaItems))->each->delete();
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
        $locale = $this->activeLocale ?? app()->getLocale();
        $data['meta_items'] = $this->record->meta()->get()->map(function ($m) use ($locale) {
            return [
                'meta_key' => $m->getTranslation('meta_key', $locale, false) ?? '',
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
        if ($path === '') {
            return $path;
        }
        if (str_starts_with($path, '/storage/')) {
            return substr($path, 9);
        }
        if (str_starts_with($path, 'storage/')) {
            return substr($path, 8);
        }

        return $path;
    }
}
