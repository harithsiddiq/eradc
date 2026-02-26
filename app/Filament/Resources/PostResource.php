<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput as FormsTextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Resources\Concerns\Translatable;
use App\Models\Media;

class PostResource extends Resource
{
    use Translatable;
    protected static ?string $model = Post::class;
    protected static array $mediaGuidelineCache = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationLabel(): string
    {
        return 'محتوى الأقسام';
    }

    public static function form(Form $form): Form
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
                            Select::make('category_id')
                                ->label('الفئة')
                                ->relationship('category', 'name')
                                ->preload()
                                ->searchable()
                                ->live(),
                            Toggle::make('show_on_landing')
                                ->label('عرض في الصفحة الرئيسية')
                                ->default(false),
                        ])->columns(2),
                    Step::make('المحتوى')
                        ->schema([
                            RichEditor::make('content')
                                ->label('المحتوى')
                                ->toolbarButtons([
                                    'attachFiles',
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'h1',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'undo',
                                ])
                                ->columnSpanFull(),
                            RichEditor::make('excerpt')
                                ->label('الملخص')
                                ->toolbarButtons([
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'h1',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'undo',
                                ])
                                ->columnSpanFull(),
                        ]),
                    Step::make('محركات البحث')
                        ->schema([
                            TextInput::make('meta_title')
                                ->label('عنوان الميتا')
                                ->maxLength(255),
                            Textarea::make('meta_description')
                                ->label('وصف الميتا')
                                ->rows(3)
                                ->maxLength(500),
                        ]),
                    Step::make('الوسائط')
                        ->schema([
                            Placeholder::make('media_guidelines')
                                ->label('تعليمات الوسائط')
                                ->content(fn (Get $get) => static::resolveMediaGuidelines($get('category_id')))
                                ->hidden(fn (Get $get) => ! static::resolveMediaGuidelines($get('category_id')))
                                ->columnSpanFull(),
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
                                    if (!$record || !is_array($record->additional_images)) return [];
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image_path')
                    ->label('الصورة')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('الرابط')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('الكاتب')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('الفئة')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('وقت النشر')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('الإنشاء')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gallery_count')
                    ->label('عدد الصور')
                    ->getStateUsing(fn ($record) => is_array($record->additional_images) ? count($record->additional_images) : 0)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('show_on_landing')
                    ->label('في الصفحة الرئيسية'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'draft' => 'مسودة',
                        'published' => 'منشور',
                        'archived' => 'مؤرشف',
                    ]),
                Tables\Filters\Filter::make('published')
                    ->label('منشور')
                    ->query(fn ($query) => $query->whereNotNull('published_at')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    protected static function resolveMediaGuidelines(?int $categoryId): ?\Illuminate\Support\HtmlString
    {
        if (!$categoryId) {
            return null;
        }

        if (array_key_exists($categoryId, static::$mediaGuidelineCache)) {
            return static::$mediaGuidelineCache[$categoryId];
        }

        $category = Category::query()->select(['id', 'layout_style', 'name'])->find($categoryId);
        if (!$category) {
            return static::$mediaGuidelineCache[$categoryId] = null;
        }

        $messages = [
            'logo-marquee-partner' => [
                'استخدم شعارات بصيغة PNG أو SVG بخلفية شفافة.',
                'الحجم المثالي 160×80 بكسل (نسبة 2:1) ليتناسب مع الإطار الجديد.',
                'تجنّب ترك مساحات بيضاء كبيرة حول الشعار حتى لا يبدو أصغر من المطلوب.',
            ],
            'logo-marquee-standards' => [
                'ارفع الشعارات بنفس أبعاد 160×80 بكسل لتحافظ على محاذاة السلايدر.',
                'صيغة PNG أو SVG بخلفية شفافة تضمن وضوح الشعار على الخلفية الفاتحة.',
                'يمكن رفع أكثر من صورة داخل المعرض وسيتم تدويرها في السلايدر.',
            ],
            'projects' => [
                'استخدم صوراً أفقية عالية الجودة (يفضل 1280×720 بكسل أو نسبة 16:9).',
                'سيتم قص الصورة في واجهة الموقع إلى ارتفاع 280px مع تغطية كاملة، فاحرص على بقاء العناصر المهمة في المنتصف.',
                'يفضل ألا يتجاوز حجم الملف 1MB للحفاظ على سرعة التحميل.',
            ],
        ];

        $layout = static::normalizeLayoutStyle($category->layout_style);
        if (! isset($messages[$layout])) {
            return static::$mediaGuidelineCache[$categoryId] = null;
        }

        $listItems = '';
        foreach ($messages[$layout] as $line) {
            $listItems .= '<li>' . e($line) . '</li>';
        }

        $title = 'تعليمات الوسائط لقسم ' . e($category->name);
        $content = <<<HTML
<div class="space-y-2">
    <p class="text-sm font-semibold text-gray-700">{$title}</p>
    <ul class="list-disc ps-5 text-sm text-gray-600 space-y-1">{$listItems}</ul>
</div>
HTML;

        return static::$mediaGuidelineCache[$categoryId] = new \Illuminate\Support\HtmlString($content);
    }

    protected static function normalizeLayoutStyle(?string $layoutStyle): ?string
    {
        if (!$layoutStyle) {
            return null;
        }

        return match ($layoutStyle) {
            'strategic-partner' => 'logo-marquee-partner',
            'standards' => 'logo-marquee-standards',
            'engineering-projects' => 'projects',
            default => $layoutStyle,
        };
    }
}
