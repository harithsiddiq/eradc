<?php

namespace App\Settings;
use Spatie\LaravelSettings\Settings;

class FooterSettings extends Settings
{
    public array $brand_name = ['ar' => 'أكاديمية EradcHub', 'en' => 'EradcHub Academy'];
    public array $about_text = ['ar' => 'تمكين المهنيين بشهادات إدارة المشاريع المعترف بها عالمياً والتدريب المتخصص.', 'en' => 'Empowering professionals with globally recognized project management certifications.'];
    public array $social_links = [];
    public string $support_email = 'support@eradchub.com';
    public string $admissions_email = 'admissions@eradchub.com';
    public string $phone = '+966 50 123 4567';
    public string $hours = 'الأحد-الخميس: 9ص-6م';
    public array $address_line1 = ['ar' => 'شارع الأعمال 123', 'en' => '123 Business Street'];
    public array $address_line2 = ['ar' => 'مكتب 100، الرياض، المملكة العربية السعودية', 'en' => 'Office 100, Riyadh, Saudi Arabia'];
    public array $copyright_text = ['ar' => '© أكاديمية EradcHub. جميع الحقوق محفوظة.', 'en' => '© EradcHub Academy. All rights reserved.'];
    public array $policies = [];

    // Contact section settings
    public array $contact_title = ['ar' => 'اتصل بنا', 'en' => 'Contact Us'];
    public array $contact_description = ['ar' => 'نحن نحب أن نسمع منك. أرسل رسالة وسنعود إليك قريباً.', 'en' => 'We love to hear from you. Send a message and we’ll reply soon.'];
    public string $contact_email = 'info@example.com';
    public array $contact_location = ['ar' => 'الرياض، المملكة العربية السعودية', 'en' => 'Riyadh, Saudi Arabia'];
    public array $contact_cta_label = ['ar' => 'اتصل بنا', 'en' => 'Call Us'];
    public string $contact_cta_tel = '+966000000000';

    public static function group(): string
    {
        return 'footer';
    }
}
