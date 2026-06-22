<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\Course;
use App\Settings\CourseSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('title.en')
                        ->label('Title (English)')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('title.ar')
                        ->label('Title (Arabic)')
                        ->required()
                        ->maxLength(255),
                ]),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(table: 'lessons', ignoreRecord: true),
                    Forms\Components\TextInput::make('order')
                        ->numeric()
                        ->required()
                        ->default(0),
                ]),

                Forms\Components\Textarea::make('description.en')
                    ->label('Description (English)')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description.ar')
                    ->label('Description (Arabic)')
                    ->columnSpanFull(),

                // ── Video section ──────────────────────────────────
                Forms\Components\Select::make('video_provider')
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

                // Show file upload only when provider = 'upload'
                Forms\Components\FileUpload::make('video_file_path')
                    ->label('Video File')
                    ->acceptedFileTypes(
                        array_map('trim', explode(',', app(CourseSettings::class)->allowed_video_types))
                    )
                    ->maxSize(app(CourseSettings::class)->max_video_upload_mb * 1024) // MB → KB for Filament
                    ->disk('local')
                    ->directory('lessons/videos')
                    ->visibility('private')
                    ->helperText(fn () => 'Max size: ' . app(CourseSettings::class)->max_video_upload_mb . ' MB. Stored privately — users cannot download it directly.')
                    ->hintAction(
                        Forms\Components\Actions\Action::make('chooseExisting')
                            ->label('📂 Choose Existing Server File')
                            ->form([
                                Forms\Components\Select::make('existing_video')
                                    ->label('Select from storage/app/lessons/videos')
                                    ->options(function () {
                                        $files = \Illuminate\Support\Facades\Storage::disk('local')->files('lessons/videos');
                                        return collect($files)
                                            ->filter(fn ($file) => !str_starts_with(basename($file), '.')) // Ignore hidden files
                                            ->mapWithKeys(fn ($file) => [$file => basename($file)])
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->required(),
                            ])
                            ->action(function (\Filament\Forms\Set $set, array $data) {
                                $set('video_file_path', $data['existing_video']);
                            })
                    )
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('video_provider') === 'upload'),

                // Show URL input for all other providers
                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->maxLength(1000)
                    ->helperText('Paste the full URL (e.g. https://youtube.com/watch?v=...)')
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('video_provider') !== 'upload' && $get('video_provider') !== null),

                Forms\Components\TextInput::make('duration_seconds')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->helperText('e.g. 623 for 10 min 23 sec'),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Published')
                        ->default(false),
                    Forms\Components\Toggle::make('is_preview')
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
                Tables\Columns\TextColumn::make('order')->sortable()->width(60),
                Tables\Columns\TextColumn::make('title.en')->label('Title')->searchable(),
                Tables\Columns\BadgeColumn::make('video_provider')
                    ->label('Source')
                    ->colors([
                        'success' => 'upload',
                        'warning' => 'youtube',
                        'info'    => 'vimeo',
                        'gray'    => fn ($state) => in_array($state, ['hls', 'direct']),
                    ]),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\IconColumn::make('is_preview')->boolean()->label('Free'),
            ])
            ->filters([])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
