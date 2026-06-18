<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CourseSettings extends Settings
{
    /** Maximum video upload size in MB */
    public int $max_video_upload_mb = 2048; // 2 GB default

    /** Allowed video MIME types (comma separated, shown to admin) */
    public string $allowed_video_types = 'video/mp4,video/webm,video/ogg,video/quicktime';

    public static function group(): string
    {
        return 'course';
    }
}
