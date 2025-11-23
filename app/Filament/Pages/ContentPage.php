<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Post;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ContentPage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'المحتوى';
    protected static ?string $title = 'المحتوى';
    protected static ?string $slug = 'content';
    protected static string $view = 'filament.pages.content-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'status' => 'draft',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                ToggleButtons::make('section')
                    ->label('القسم')
                    ->options($this->getSectionOptions())
                    ->inline()
                    ->required()
                    ->reactive()
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->prefillSection($state);
                    }),
                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    })
                    ->hidden(fn ($get) => in_array($get('section'), [
                        'logo_marquee','infra_projects','projects','trainers','services','levels','hero','contact'
                    ], true)),
                TextInput::make('slug')
                    ->label('الرابط')
                    ->required()
                    ->hidden(fn ($get) => in_array($get('section'), [
                        'logo_marquee','infra_projects','projects','trainers','services','levels','hero','contact'
                    ], true)),
                Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'draft' => 'مسودة',
                        'published' => 'منشور',
                        'archived' => 'مؤرشف',
                    ])
                    ->required(),
                RichEditor::make('content')
                    ->label('المحتوى')
                    ->columnSpanFull()
                    ->hidden(fn ($get) => in_array($get('section'), [
                        'logo_marquee','infra_projects','projects','trainers','services','levels','hero','contact'
                    ], true)),
                RichEditor::make('excerpt')
                    ->label('الملخص')
                    ->columnSpanFull()
                    ->hidden(fn ($get) => in_array($get('section'), [
                        'logo_marquee','infra_projects','projects','trainers','services','levels','hero','contact'
                    ], true)),

                Group::make()
                    ->schema([
                        TextInput::make('lm_title')->label('عنوان الشعار المتحرك'),
                        RichEditor::make('lm_description')->label('وصف الشعار المتحرك'),
                        Repeater::make('lm_logos')
                            ->label('شعارات')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('صورة الشعار')
                                    ->acceptedFileTypes(['image/*'])
                                    ->disk('public')
                                    ->directory('content/logo_marquee')
                                    ->visibility('public')
                                    ->preserveFilenames(),
                                TextInput::make('title')->label('عنوان')->nullable(),
                                TextInput::make('link')->label('رابط')->url()->nullable(),
                            ])
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('section') === 'logo_marquee'),

                Group::make()
                    ->schema([
                        Repeater::make('projects_items')
                            ->label('عناصر المشاريع')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('صورة الخلفية')
                                    ->acceptedFileTypes(['image/*'])
                                    ->disk('public')
                                    ->directory('content/projects')
                                    ->visibility('public')
                                    ->preserveFilenames(),
                                TextInput::make('title_line1')->label('العنوان 1')->required(),
                                TextInput::make('title_line2')->label('العنوان 2'),
                                TextInput::make('link')->label('رابط المزيد')->url()->nullable(),
                            ])
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => $get('section') === 'projects'),

                Group::make()
                    ->schema([
                        Repeater::make('trainers')
                            ->label('المدربون')
                            ->schema([
                                FileUpload::make('photo')
                                    ->label('الصورة')
                                    ->acceptedFileTypes(['image/*'])
                                    ->disk('public')
                                    ->directory('content/trainers')
                                    ->visibility('public')
                                    ->preserveFilenames(),
                                TextInput::make('name')->label('الاسم')->required(),
                                RichEditor::make('bio')->label('نبذة'),
                                TextInput::make('years')->label('سنوات الخبرة')->numeric()->nullable(),
                                TextInput::make('projects')->label('المشاريع المكتملة')->numeric()->nullable(),
                                TextInput::make('trainees')->label('عدد المتدربين')->nullable(),
                                TextInput::make('link')->label('رابط المزيد')->url()->nullable(),
                            ])
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => $get('section') === 'trainers'),

                Group::make()
                    ->schema([
                        Repeater::make('services_items')
                            ->label('عناصر الخدمات')
                            ->schema([
                                TextInput::make('title')->label('العنوان')->required(),
                                RichEditor::make('content')->label('المحتوى'),
                                TextInput::make('link')->label('رابط المزيد')->url()->nullable(),
                            ])
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => $get('section') === 'services'),

                Group::make()
                    ->schema([
                        Repeater::make('levels_items')
                            ->label('عناصر المستويات')
                            ->schema([
                                TextInput::make('title')->label('العنوان')->required(),
                                RichEditor::make('content')->label('المحتوى'),
                                TextInput::make('link')->label('رابط المزيد')->url()->nullable(),
                            ])
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => $get('section') === 'levels'),

                Group::make()
                    ->schema([
                        TextInput::make('ip_title')->label('عنوان البنية التحتية'),
                        Repeater::make('ip_logos')
                            ->label('شعارات')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('صورة الشعار')
                                    ->acceptedFileTypes(['image/*'])
                                    ->disk('public')
                                    ->directory('content/infra_projects')
                                    ->visibility('public')
                                    ->preserveFilenames(),
                                TextInput::make('title')->label('عنوان')->nullable(),
                                TextInput::make('link')->label('رابط')->url()->nullable(),
                            ])
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('section') === 'infra_projects'),

                Group::make()
                    ->schema([
                        TextInput::make('badge_text')->label('نص الشارة'),
                        TextInput::make('headline')->label('العنوان الرئيسي')->required(),
                        RichEditor::make('hero_description')->label('وصف'),
                        TextInput::make('primary_cta_label')->label('زر أساسي - نص'),
                        TextInput::make('primary_cta_link')->label('زر أساسي - رابط')->url()->nullable(),
                        TextInput::make('secondary_cta_label')->label('زر ثانوي - نص'),
                        TextInput::make('secondary_cta_link')->label('زر ثانوي - رابط')->url()->nullable(),
                        Repeater::make('metrics')
                            ->label('مؤشرات')
                            ->schema([
                                FileUpload::make('icon')
                                    ->acceptedFileTypes(['image/*'])
                                    ->disk('public')
                                    ->directory('content/hero')
                                    ->visibility('public')
                                    ->preserveFilenames(),
                                TextInput::make('text')->label('نص المؤشر')->required(),
                            ])
                            ->reorderable(),
                        FileUpload::make('hero_image')
                            ->label('صورة البطل')
                            ->acceptedFileTypes(['image/*'])
                            ->disk('public')
                            ->directory('content/hero')
                            ->visibility('public')
                            ->preserveFilenames(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('section') === 'hero'),

                Group::make()
                    ->schema([
                        TextInput::make('contact_title')->label('عنوان الاتصال'),
                        RichEditor::make('contact_description')->label('وصف الاتصال'),
                        TextInput::make('org_name')->label('اسم الجهة'),
                        TextInput::make('email')->label('البريد الإلكتروني')->email()->nullable(),
                        TextInput::make('location')->label('الموقع')->nullable(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('section') === 'contact'),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('حفظ')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $section = $state['section'];
        $category = Category::firstOrCreate(
            ['slug' => Str::slug($section)],
            ['name' => $section]
        );

        $content = $this->buildContentPayload($section, $state);

        $post = Post::create([
            'title' => $state['title'] ?? $section,
            'slug' => $state['slug'] ?? Str::slug($state['title'] ?? $section),
            'content' => $content,
            'excerpt' => $state['excerpt'] ?? null,
            'status' => $state['status'] ?? 'draft',
            'author_id' => Filament::auth()->id() ?? auth()->id(),
            'category_id' => $category->id,
            'published_at' => ($state['status'] ?? 'draft') === 'published' ? now() : null,
        ]);

        $this->attachSectionMedia($post, $section, $state);

        Notification::make()
            ->title('تم حفظ المحتوى كمنشور')
            ->success()
            ->send();
        $this->form->fill([ 'status' => 'draft' ]);
    }

    protected function prefillSection(?string $section): void
    {
        if (!$section) {
            return;
        }
        $slug = Str::slug($section);
        $category = Category::where('slug', $slug)->first();
        $post = $category ? Post::where('category_id', $category->id)->latest('id')->first() : null;

        $fill = ['section' => $section];
        if ($post) {
            $content = $post->content ?? null;
            if (is_string($content)) {
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $content = $decoded;
                }
            }

            switch ($section) {
                case 'logo_marquee':
                    $fill['lm_title'] = $content['title'] ?? null;
                    $fill['lm_description'] = $content['description'] ?? null;
                    if (!empty($content['logos'])) {
                        $fill['lm_logos'] = $content['logos'];
                    } else {
                        $fill['lm_logos'] = collect($post->media()->wherePivot('relation_type', 'logo_marquee')->get())
                            ->map(fn ($m) => ['image' => $m->file_path, 'title' => null, 'link' => null])
                            ->values()
                            ->toArray();
                    }
                    break;
                case 'infra_projects':
                    $fill['ip_title'] = $content['title'] ?? null;
                    if (!empty($content['logos'])) {
                        $fill['ip_logos'] = $content['logos'];
                    } else {
                        $fill['ip_logos'] = collect($post->media()->wherePivot('relation_type', 'infra_projects')->get())
                            ->map(fn ($m) => ['image' => $m->file_path, 'title' => null, 'link' => null])
                            ->values()
                            ->toArray();
                    }
                    break;
                case 'projects':
                    $fill['projects_items'] = $content['items'] ?? [];
                    break;
                case 'trainers':
                    $fill['trainers'] = $content['trainers'] ?? [];
                    break;
                case 'services':
                    $fill['services_items'] = $content['items'] ?? [];
                    break;
                case 'levels':
                    $fill['levels_items'] = $content['items'] ?? [];
                    break;
                case 'hero':
                    $fill['badge_text'] = $content['badge_text'] ?? null;
                    $fill['headline'] = $content['headline'] ?? null;
                    $fill['hero_description'] = $content['description'] ?? null;
                    $fill['primary_cta_label'] = $content['primary_cta']['label'] ?? null;
                    $fill['primary_cta_link'] = $content['primary_cta']['link'] ?? null;
                    $fill['secondary_cta_label'] = $content['secondary_cta']['label'] ?? null;
                    $fill['secondary_cta_link'] = $content['secondary_cta']['link'] ?? null;
                    $fill['metrics'] = $content['metrics'] ?? [];
                    $fill['hero_image'] = $post->featuredMedia->file_path ?? ($content['hero_image'] ?? null);
                    break;
                case 'contact':
                    $fill['contact_title'] = $content['title'] ?? null;
                    $fill['contact_description'] = $content['description'] ?? null;
                    $fill['org_name'] = $content['org_name'] ?? null;
                    $fill['email'] = $content['email'] ?? null;
                    $fill['location'] = $content['location'] ?? null;
                    break;
            }
        }

        $this->form->fill(array_merge($this->form->getState() ?? [], $fill));
    }

    protected function buildContentPayload(string $section, array $state): array|string|null
    {
        switch ($section) {
            case 'logo_marquee':
                return [
                    'title' => $state['lm_title'] ?? null,
                    'description' => $state['lm_description'] ?? null,
                    'logos' => $state['lm_logos'] ?? [],
                ];
            case 'infra_projects':
                return [
                    'title' => $state['ip_title'] ?? null,
                    'logos' => $state['ip_logos'] ?? [],
                ];
            case 'projects':
                return [
                    'items' => $state['projects_items'] ?? [],
                ];
            case 'trainers':
                return [
                    'trainers' => $state['trainers'] ?? [],
                ];
            case 'services':
                return [
                    'items' => $state['services_items'] ?? [],
                ];
            case 'levels':
                return [
                    'items' => $state['levels_items'] ?? [],
                ];
            case 'hero':
                return [
                    'badge_text' => $state['badge_text'] ?? null,
                    'headline' => $state['headline'] ?? null,
                    'description' => $state['hero_description'] ?? null,
                    'primary_cta' => [
                        'label' => $state['primary_cta_label'] ?? null,
                        'link' => $state['primary_cta_link'] ?? null,
                    ],
                    'secondary_cta' => [
                        'label' => $state['secondary_cta_label'] ?? null,
                        'link' => $state['secondary_cta_link'] ?? null,
                    ],
                    'metrics' => $state['metrics'] ?? [],
                    'hero_image' => $state['hero_image'] ?? null,
                ];
            case 'contact':
                return [
                    'title' => $state['contact_title'] ?? null,
                    'description' => $state['contact_description'] ?? null,
                    'org_name' => $state['org_name'] ?? null,
                    'email' => $state['email'] ?? null,
                    'location' => $state['location'] ?? null,
                ];
            default:
                return $state['content'] ?? null;
        }
    }

    protected function attachSectionMedia(Post $post, string $section, array $state): void
    {
        $position = ($post->mediaRelations()->max('position') ?? 0);
        $attach = function (array $paths, string $type) use ($post, &$position) {
            foreach ($paths as $path) {
                $mediaId = $this->createMediaFromPath($path);
                $post->media()->attach($mediaId, [
                    'relation_type' => $type,
                    'position' => $position++,
                ]);
            }
        };

        if ($section === 'logo_marquee' && !empty($state['lm_logos'])) {
            $paths = collect($state['lm_logos'])->pluck('image')->filter()->values()->all();
            $attach($paths, 'logo_marquee');
        }
        if ($section === 'infra_projects' && !empty($state['ip_logos'])) {
            $paths = collect($state['ip_logos'])->pluck('image')->filter()->values()->all();
            $attach($paths, 'infra_projects');
        }
        if ($section === 'projects' && !empty($state['projects_items'])) {
            $paths = collect($state['projects_items'])->pluck('image')->filter()->values()->all();
            $attach($paths, 'projects');
        }
        if ($section === 'trainers' && !empty($state['trainers'])) {
            $paths = collect($state['trainers'])->pluck('photo')->filter()->values()->all();
            $attach($paths, 'trainers');
        }
        if ($section === 'hero' && !empty($state['hero_image'])) {
            $mediaId = $this->createMediaFromPath($state['hero_image']);
            $post->forceFill(['featured_media_id' => $mediaId])->save();
        }
    }

    protected function createMediaFromPath(string $path): int
    {
        $disk = 'public';
        $size = \Storage::disk($disk)->size($path);
        $mime = \Storage::disk($disk)->mimeType($path);

        $fileName = Str::of($path)->afterLast('/')->toString();

        $media = \App\Models\Media::create([
            'file_name' => $fileName,
            'file_path' => $path,
            'file_type' => $mime ?? 'image',
            'file_size' => $size ?? 0,
            'alt_text' => null,
        ]);

        return (int) $media->getKey();
    }

    protected function getSectionOptions(): array
    {
        $dir = resource_path('views/components');
        $files = glob($dir . '/*.blade.php');
        $options = [];
        foreach ($files as $file) {
            $name = basename($file, '.blade.php');
            $label = Str::replace('_', ' ', $name);
            $options[$name] = Str::title($label);
        }
        return $options;
    }
}
