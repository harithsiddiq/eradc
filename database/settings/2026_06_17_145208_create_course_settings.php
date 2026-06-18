<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('course.max_video_upload_mb', 2048);
        $this->migrator->add('course.allowed_video_types', 'video/mp4,video/webm,video/ogg,video/quicktime');
    }
};
