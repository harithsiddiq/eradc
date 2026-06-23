<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use SpykApp\UppyUpload\Forms\Components\UppyUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Models\Course;
use App\Settings\CourseSettings;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    TextInput::make('title.en')
                        ->label('Title (English)')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('title.ar')
                        ->label('Title (Arabic)')
                        ->required()
                        ->maxLength(255),
                ]),

                Grid::make(2)->schema([
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(table: 'lessons', ignoreRecord: true),
                    TextInput::make('order')
                        ->numeric()
                        ->required()
                        ->default(0),
                ]),

                Textarea::make('description.en')
                    ->label('Description (English)')
                    ->columnSpanFull(),
                Textarea::make('description.ar')
                    ->label('Description (Arabic)')
                    ->columnSpanFull(),

                // ── Video section ──────────────────────────────────
                Select::make('video_provider')
                    ->label('Video Source')
                    ->options([
                        'upload'   => '📁 Upload Video File',
                        'youtube'  => '▶ YouTube URL',
                        'vimeo'    => '🎬 Vimeo URL',
                        'onedrive' => '☁️ OneDrive URL',
                        'hls'      => '📡 HLS Stream URL',
                        'direct'   => '🔗 Direct MP4 URL',
                    ])
                    ->required()
                    ->live() // re-render when changed
                    ->columnSpanFull(),

                // Show current video placeholder if already uploaded to avoid loading the entire video into Uppy
                \Filament\Forms\Components\Placeholder::make('current_video')
                    ->label('Current Video File')
                    ->content(fn (?\Illuminate\Database\Eloquent\Model $record) => $record?->video_file_path ? basename($record->video_file_path) : 'None')
                    ->visible(fn (Get $get, string $operation, ?\Illuminate\Database\Eloquent\Model $record) => 
                        $operation === 'edit' && 
                        $get('video_provider') === 'upload' && 
                        !$get('replace_video') && 
                        $record?->video_file_path
                    ),

                // Toggle to replace the existing video
                Toggle::make('replace_video')
                    ->label('Upload New Video (Replace existing)')
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function (Set $set, $state, ?\Illuminate\Database\Eloquent\Model $record) {
                        if ($state) {
                            $set('video_file_path', null);
                        } else {
                            $set('video_file_path', $record?->video_file_path);
                        }
                    })
                    ->visible(fn (Get $get, string $operation, ?\Illuminate\Database\Eloquent\Model $record) => 
                        $operation === 'edit' && 
                        $get('video_provider') === 'upload' && 
                        $record?->video_file_path
                    ),

                // Show UppyUpload only on create, or if replace_video is toggled, or if no video exists yet
                UppyUpload::make('video_file_path')
                    ->label('Video File')
                    ->acceptedFileTypes(
                        array_map('trim', explode(',', app(CourseSettings::class)->allowed_video_types))
                    )
                    ->maxFileSize(app(CourseSettings::class)->max_video_upload_mb * 1024 * 1024) // MB → Bytes for Uppy
                    ->disk('local')
                    ->directory('lessons/videos')
                    ->helperText(fn () => 'Max size: ' . app(CourseSettings::class)->max_video_upload_mb . ' MB. Stored privately — users cannot download it directly.')
                    ->hintAction(
                        Action::make('chooseExisting')
                            ->label('📂 Choose Existing Server File')
                            ->schema([
                                Select::make('existing_video')
                                    ->label('Select from storage/app/lessons/videos')
                                    ->options(function () {
                                        $files = Storage::disk('local')->files('lessons/videos');
                                        return collect($files)
                                            ->filter(fn ($file) => !str_starts_with(basename($file), '.')) // Ignore hidden files
                                            ->mapWithKeys(fn ($file) => [$file => basename($file)])
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->required(),
                            ])
                            ->action(function (Set $set, array $data) {
                                $set('video_file_path', $data['existing_video']);
                            })
                    )
                    ->columnSpanFull()
                    ->visible(fn (Get $get, string $operation, ?\Illuminate\Database\Eloquent\Model $record): bool => 
                        $get('video_provider') === 'upload' && 
                        ($operation === 'create' || empty($record?->video_file_path) || $get('replace_video'))
                    ),

                // Show URL input for all other providers
                TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->maxLength(1000)
                    ->helperText('Paste the full URL (e.g. https://youtube.com/watch?v=...)')
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('video_provider') !== 'upload' && $get('video_provider') !== null),

                TextInput::make('duration_seconds')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->helperText('e.g. 623 for 10 min 23 sec'),

                Grid::make(2)->schema([
                    Toggle::make('is_published')
                        ->label('Published')
                        ->default(false),
                    Toggle::make('is_preview')
                        ->label('Free Preview (no login needed)')
                        ->default(false),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->reorderable('order')
            ->columns([
                TextColumn::make('order')->sortable()->width(60),
                TextColumn::make('title.en')->label('Title')->searchable(),
                BadgeColumn::make('video_provider')
                    ->label('Source')
                    ->colors([
                        'success' => 'upload',
                        'warning' => 'youtube',
                        'info'    => 'vimeo',
                        'gray'    => fn ($state) => in_array($state, ['hls', 'direct']),
                    ]),
                IconColumn::make('is_published')->boolean(),
                IconColumn::make('is_preview')->boolean()->label('Free'),
            ])
            ->filters([])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
