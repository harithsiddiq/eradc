<?php

namespace App\Filament\Pages;

use App\Settings\CourseSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageCourseSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static string $settings = CourseSettings::class;

    protected static ?string $navigationGroup = 'Settings';

    public static function getNavigationLabel(): string
    {
        return 'Course Settings';
    }

    public function getTitle(): string
    {
        return 'Course & Video Settings';
    }

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('📹 Video Upload Limits')
                ->description('Control how large uploaded video files can be.')
                ->schema([
                    TextInput::make('max_video_upload_mb')
                        ->label('Maximum Upload Size (MB)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(20480) // 20 GB hard cap
                        ->required()
                        ->suffix('MB')
                        ->helperText('Enter the maximum allowed video file size in megabytes. Example: 500 = 500 MB, 2048 = 2 GB.')
                        ->hint('Current server PHP limit: ' . ini_get('upload_max_filesize'))
                        ->hintColor('warning'),

                    Placeholder::make('size_guide')
                        ->label('Quick Reference')
                        ->content(
                            "100 MB  ≈ 10 min video (low quality)\n" .
                            "500 MB  ≈ 20 min video (HD 720p)\n" .
                            "1024 MB ≈ 30–40 min video (HD 1080p)\n" .
                            "2048 MB ≈ 60–80 min video (HD 1080p)"
                        ),
                ]),

            Section::make('🗂 Allowed File Types')
                ->description('Comma-separated MIME types accepted for upload.')
                ->schema([
                    TextInput::make('allowed_video_types')
                        ->label('Allowed MIME Types')
                        ->required()
                        ->helperText('Common values: video/mp4, video/webm, video/ogg, video/quicktime')
                        ->placeholder('video/mp4,video/webm,video/ogg,video/quicktime'),
                ]),

        ]);
    }
}
