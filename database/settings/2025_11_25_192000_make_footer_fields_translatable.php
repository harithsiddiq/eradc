<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->update('footer.brand_name', fn ($value) => ['ar' => 'أكاديمية EradcHub', 'en' => 'EradcHub Academy']);
        $this->migrator->update('footer.about_text', fn ($value) => ['ar' => 'تمكين المهنيين بشهادات إدارة المشاريع المعترف بها عالمياً والتدريب المتخصص.', 'en' => 'Empowering professionals with globally recognized project management certifications.']);
        $this->migrator->update('footer.address_line1', fn ($value) => ['ar' => 'شارع الأعمال 123', 'en' => '123 Business Street']);
        $this->migrator->update('footer.address_line2', fn ($value) => ['ar' => 'مكتب 100، الرياض، المملكة العربية السعودية', 'en' => 'Office 100, Riyadh, Saudi Arabia']);
        $this->migrator->update('footer.copyright_text', fn ($value) => ['ar' => '© أكاديمية EradcHub. جميع الحقوق محفوظة.', 'en' => '© EradcHub Academy. All rights reserved.']);
        $this->migrator->update('footer.contact_title', fn ($value) => ['ar' => 'اتصل بنا', 'en' => 'Contact Us']);
        $this->migrator->update('footer.contact_description', fn ($value) => ['ar' => 'نحن نحب أن نسمع منك. أرسل رسالة وسنعود إليك قريباً.', 'en' => 'We love to hear from you. Send a message and we’ll reply soon.']);
        $this->migrator->update('footer.contact_location', fn ($value) => ['ar' => 'الرياض، المملكة العربية السعودية', 'en' => 'Riyadh, Saudi Arabia']);
        $this->migrator->update('footer.contact_cta_label', fn ($value) => ['ar' => 'اتصل بنا', 'en' => 'Call Us']);
    }

    public function down(): void
    {
        // No safe down migration since we would have to choose a single locale.
    }
};
