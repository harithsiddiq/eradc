<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::create([
            'slug'         => 'project-management-fundamentals',
            'title'        => [
                'en' => 'Project Management Fundamentals',
                'ar' => 'أساسيات إدارة المشاريع',
            ],
            'description'  => [
                'en' => 'Learn the core principles of project management including planning, execution, monitoring, and closing projects successfully.',
                'ar' => 'تعلم المبادئ الأساسية لإدارة المشاريع بما في ذلك التخطيط والتنفيذ والمراقبة وإغلاق المشاريع بنجاح.',
            ],
            'is_published' => true,
            'order'        => 1,
        ]);

        $lessons = [
            [
                'order'            => 1,
                'slug'             => 'introduction-to-project-management',
                'title'            => ['en' => 'Introduction to Project Management', 'ar' => 'مقدمة في إدارة المشاريع'],
                'description'      => ['en' => 'Overview of project management concepts and methodologies.', 'ar' => 'نظرة عامة على مفاهيم إدارة المشاريع والمنهجيات.'],
                'video_url'        => 'https://www.youtube.com/watch?v=_TZR29gB1dc',
                'video_provider'   => 'youtube',
                'duration_seconds' => 623,
                'is_preview'       => true,
                'is_published'     => true,
            ],
            [
                'order'            => 2,
                'slug'             => 'project-lifecycle',
                'title'            => ['en' => 'Project Lifecycle', 'ar' => 'دورة حياة المشروع'],
                'description'      => ['en' => 'Understanding the five phases of a project lifecycle.', 'ar' => 'فهم المراحل الخمس لدورة حياة المشروع.'],
                'video_url'        => 'https://www.youtube.com/watch?v=X5_FOrjFAkI',
                'video_provider'   => 'youtube',
                'duration_seconds' => 541,
                'is_preview'       => false,
                'is_published'     => true,
            ],
            [
                'order'            => 3,
                'slug'             => 'stakeholder-management',
                'title'            => ['en' => 'Stakeholder Management', 'ar' => 'إدارة أصحاب المصلحة'],
                'description'      => ['en' => 'How to identify, analyse and engage project stakeholders.', 'ar' => 'كيفية تحديد وتحليل وإشراك أصحاب المصلحة في المشروع.'],
                'video_url'        => 'https://www.youtube.com/watch?v=rvVArnWUYLE',
                'video_provider'   => 'youtube',
                'duration_seconds' => 487,
                'is_preview'       => false,
                'is_published'     => true,
            ],
            [
                'order'            => 4,
                'slug'             => 'scope-management',
                'title'            => ['en' => 'Scope Management', 'ar' => 'إدارة النطاق'],
                'description'      => ['en' => 'Defining and controlling what is and is not included in the project.', 'ar' => 'تحديد والتحكم فيما هو مدرج وما هو غير مدرج في المشروع.'],
                'video_url'        => 'https://www.youtube.com/watch?v=j4VZET0NQFA',
                'video_provider'   => 'youtube',
                'duration_seconds' => 512,
                'is_preview'       => false,
                'is_published'     => true,
            ],
            [
                'order'            => 5,
                'slug'             => 'time-management',
                'title'            => ['en' => 'Time Management & Scheduling', 'ar' => 'إدارة الوقت والجدولة'],
                'description'      => ['en' => 'Creating schedules, Gantt charts, and managing project timelines.', 'ar' => 'إنشاء الجداول الزمنية ومخططات جانت وإدارة الجداول الزمنية للمشاريع.'],
                'video_url'        => 'https://www.youtube.com/watch?v=MgM3rOC8hQE',
                'video_provider'   => 'youtube',
                'duration_seconds' => 630,
                'is_preview'       => false,
                'is_published'     => true,
            ],
            [
                'order'            => 6,
                'slug'             => 'cost-management',
                'title'            => ['en' => 'Cost Management & Budgeting', 'ar' => 'إدارة التكاليف والميزانية'],
                'description'      => ['en' => 'Estimating, budgeting and controlling project costs.', 'ar' => 'تقدير وإعداد الميزانية والتحكم في تكاليف المشروع.'],
                'video_url'        => 'https://www.youtube.com/watch?v=HhEKA9uKAGE',
                'video_provider'   => 'youtube',
                'duration_seconds' => 578,
                'is_preview'       => false,
                'is_published'     => true,
            ],
            [
                'order'            => 7,
                'slug'             => 'risk-management',
                'title'            => ['en' => 'Risk Management', 'ar' => 'إدارة المخاطر'],
                'description'      => ['en' => 'Identifying, assessing and responding to project risks.', 'ar' => 'تحديد وتقييم والاستجابة لمخاطر المشروع.'],
                'video_url'        => 'https://www.youtube.com/watch?v=rzfvBNF0GcQ',
                'video_provider'   => 'youtube',
                'duration_seconds' => 602,
                'is_preview'       => false,
                'is_published'     => true,
            ],
            [
                'order'            => 8,
                'slug'             => 'quality-management',
                'title'            => ['en' => 'Quality Management', 'ar' => 'إدارة الجودة'],
                'description'      => ['en' => 'Ensuring project deliverables meet quality standards.', 'ar' => 'ضمان أن مخرجات المشروع تلبي معايير الجودة.'],
                'video_url'        => 'https://www.youtube.com/watch?v=qyGjgJkUHO0',
                'video_provider'   => 'youtube',
                'duration_seconds' => 545,
                'is_preview'       => false,
                'is_published'     => true,
            ],
            [
                'order'            => 9,
                'slug'             => 'team-leadership',
                'title'            => ['en' => 'Team Leadership & Communication', 'ar' => 'قيادة الفريق والتواصل'],
                'description'      => ['en' => 'Leading teams, resolving conflicts, and effective communication.', 'ar' => 'قيادة الفرق وحل النزاعات والتواصل الفعال.'],
                'video_url'        => 'https://www.youtube.com/watch?v=2Vd5Z0p8WKM',
                'video_provider'   => 'youtube',
                'duration_seconds' => 689,
                'is_preview'       => false,
                'is_published'     => true,
            ],
            [
                'order'            => 10,
                'slug'             => 'project-closure',
                'title'            => ['en' => 'Project Closure & Lessons Learned', 'ar' => 'إغلاق المشروع والدروس المستفادة'],
                'description'      => ['en' => 'How to properly close a project and document lessons learned.', 'ar' => 'كيفية إغلاق المشروع بشكل صحيح وتوثيق الدروس المستفادة.'],
                'video_url'        => 'https://www.youtube.com/watch?v=vkSlJEwkrYI',
                'video_provider'   => 'youtube',
                'duration_seconds' => 558,
                'is_preview'       => false,
                'is_published'     => true,
            ],
        ];

        foreach ($lessons as $lessonData) {
            Lesson::create(array_merge($lessonData, ['course_id' => $course->id]));
        }

        $this->command->info('✅ Course "Project Management Fundamentals" seeded with 10 lessons.');
    }
}
