<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Models\Media;
use App\Filament\Resources\PostResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput as FormsTextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\Concerns\Translatable as RelationTranslatable;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Storage;
use App\Models\PostMeta;

class PostsRelationManager extends RelationManager
{
    use RelationTranslatable;

    protected static string $relationship = 'posts';

    protected static ?string $title = 'المنشورات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('التفاصيل')
                        ->schema([
                            TextInput::make('title')
                                ->label('العنوان')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (filled($state)) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),
                            TextInput::make('slug')
                                ->label('الرابط')
                                ->required()
                                ->unique(ignoreRecord: true),
                            Select::make('status')
                                ->label('الحالة')
                                ->required()
                                ->options([
                                    'draft' => 'مسودة',
                                    'published' => 'منشور',
                                    'archived' => 'مؤرشف',
                                ]),
                            DateTimePicker::make('published_at')
                                ->label('وقت النشر')
                                ->nullable(),
                        ])->columns(2),
                    Step::make('المحتوى')
                        ->schema([
                            RichEditor::make('content')
                                ->label('المحتوى')
                                ->columnSpanFull(),
                            RichEditor::make('excerpt')
                                ->label('الملخص')
                                ->columnSpanFull(),
                        ]),
                    Step::make('الوسائط')
                        ->schema([
                            FileUpload::make('featured_image_path')
                                ->label('صورة رئيسية')
                                ->acceptedFileTypes(['image/*'])
                                ->rules(['mimes:jpeg,jpg,png,webp,gif,bmp,svg,ico,avif,heic,tif,tiff'])
                                ->disk('public')
                                ->directory('posts/featured')
                                ->visibility('public')
                                ->getUploadedFileNameForStorageUsing(fn ($file) => (string) \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension())
                                ->default(fn ($record) => $record?->featured_image_path),
                            FileUpload::make('gallery')
                                ->label('صور إضافية')
                                ->acceptedFileTypes(['image/*'])
                                ->rules(['mimes:jpeg,jpg,png,webp,gif,bmp,svg,ico,avif,heic,tif,tiff'])
                                ->multiple()
                                ->reorderable()
                                ->disk('public')
                                ->directory('posts/gallery')
                                ->visibility('public')
                                ->getUploadedFileNameForStorageUsing(fn ($file) => (string) \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension())
                                ->default(function ($record) {
                                    if (!$record || !is_array($record->additional_images))
                                        return [];
                                    return Media::whereIn('id', $record->additional_images)->pluck('file_path')->toArray();
                                })
                                ->columnSpanFull(),
                        ])->columns(2),
                    Step::make('البيانات الإضافية')
                        ->schema([
                            Repeater::make('meta_items')
                                ->label('بيانات إضافية')
                                ->columns(2)
                                ->schema([
                                    FormsTextInput::make('meta_key')
                                        ->label('المفتاح')
                                        ->required(),
                                    FormsTextInput::make('meta_value')
                                        ->label('القيمة')
                                        ->required(),
                                ])
                                ->columnSpanFull(),
                        ])->columns(2),
                ])->skippable()->columnSpanFull(),
                Hidden::make('author_id')
                    ->default(fn () => Filament::auth()->id() ?? auth()->id()),
                Hidden::make('category_id')
                    ->default(fn () => $this->getOwnerRecord()->getKey()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('الرابط')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('وقت النشر')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label('إنشاء')
                    ->icon('heroicon-o-plus')
                    ->url(function (): string {
                        $categoryId = $this->getOwnerRecord()->getKey();
                        $base = PostResource::getUrl('create');
                        return $categoryId ? ($base . '?category_id=' . $categoryId) : $base;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('تحرير')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn ($record) => PostResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
